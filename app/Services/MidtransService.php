<?php

namespace App\Services;

use App\Models\Pesanan;
use App\Models\Pembayaran;
use Midtrans\Config;
use Midtrans\Snap;
use Midtrans\Notification;
use Midtrans\Transaction;

class MidtransService
{
    public function __construct()
    {
        // Set konfigurasi Midtrans
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = config('midtrans.is_sanitized');
        Config::$is3ds = config('midtrans.is_3ds');
    }

    /**
     * Buat Snap Token untuk pembayaran
     *
     * @param Pesanan $pesanan
     * @return string Snap token
     */
    public function buatSnapToken(Pesanan $pesanan)
    {
        // Generate order ID untuk Midtrans
        $orderId = 'ORDER-' . $pesanan->nomor_pesanan;

        // Buat data pembayaran jika belum ada atau update ID jika berubah
        $pembayaran = Pembayaran::updateOrCreate(
            ['pesanan_id' => $pesanan->id],
            [
                'midtrans_order_id' => $orderId,
                'gross_amount' => $pesanan->total_pembayaran,
                'transaction_status' => 'pending',
            ]
        );

        // Prepare transaction details
        $transactionDetails = [
            'order_id' => $orderId,
            'gross_amount' => (int) $pesanan->total_pembayaran,
        ];

        // Prepare item details
        $itemDetails = [];
        foreach ($pesanan->detailPesanan as $detail) {
            $itemDetails[] = [
                'id' => $detail->produk_id,
                'price' => (int) $detail->harga_satuan,
                'quantity' => $detail->jumlah,
                'name' => $detail->nama_produk,
            ];
        }

        // Add shipping cost as item
        $itemDetails[] = [
            'id' => 'ONGKIR',
            'price' => (int) $pesanan->total_ongkir,
            'quantity' => 1,
            'name' => 'Ongkos Kirim',
        ];

        // Prepare customer details
        $customerDetails = [
            'first_name' => $pesanan->user->name,
            'email' => $pesanan->user->email,
            'phone' => $pesanan->alamatPengguna->telepon,
            'shipping_address' => [
                'first_name' => $pesanan->alamatPengguna->nama_penerima,
                'phone' => $pesanan->alamatPengguna->telepon,
                'address' => $pesanan->alamatPengguna->alamat_lengkap,
                'city' => $pesanan->alamatPengguna->kota,
                'postal_code' => $pesanan->alamatPengguna->kode_pos,
            ],
        ];

        // Prepare transaction array
        $transaction = [
            'transaction_details' => $transactionDetails,
            'item_details' => $itemDetails,
            'customer_details' => $customerDetails,
        ];

        try {
            // Debug Log
            \Illuminate\Support\Facades\Log::info('Midtrans Config:', [
                'isProduction' => Config::$isProduction,
                'serverKeyPrefix' => substr(Config::$serverKey, 0, 5),
                'serverKeyLength' => strlen(Config::$serverKey),
                'using_production_url' => Config::$isProduction ? 'YES' : 'NO'
            ]);

            // Get Snap token
            $snapToken = Snap::getSnapToken($transaction);

            return $snapToken;
        } catch (\Exception $e) {
            $debugInfo = " [Config: Prod=" . (Config::$isProduction ? 'true' : 'false') . 
                         ", Key=" . substr(Config::$serverKey, 0, 5) . "..." . 
                         ", Env=" . config('midtrans.is_production') . "]";
            throw new \Exception("Gagal membuat Snap token: " . $e->getMessage() . $debugInfo);
        }
    }

    /**
     * Proses callback notification dari Midtrans
     *
     * @param array $notificationData
     * @return Pembayaran
     */
    public function prosesCallback($notificationData)
    {
        try {
            // Create notification object (reads from php://input)
            $notification = new Notification();
            
            // Process the notification object
            return $this->processTransactionUpdate($notification);

        } catch (\Exception $e) {
            throw new \Exception("Gagal memproses callback: " . $e->getMessage());
        }
    }

    /**
     * Update status transaksi secara manual (cek ke API Midtrans)
     */
    public function updateTransactionStatus($midtransOrderId)
    {
        try {
            // Get status from Midtrans API
            $status = \Midtrans\Transaction::status($midtransOrderId);
            
            // Process the status object
            return $this->processTransactionUpdate($status);

        } catch (\Exception $e) {
            throw new \Exception("Gagal cek status transaksi: " . $e->getMessage());
        }
    }

    /**
     * Common logic to update transaction status
     */
    private function processTransactionUpdate($data)
    {
        // Convert array to object if needed
        $data = (object) $data;

        $transactionStatus = $data->transaction_status;
        $fraudStatus = $data->fraud_status ?? null;
        $orderId = $data->order_id;
        $transactionId = $data->transaction_id;
        $paymentType = $data->payment_type;
        
        // Find pembayaran by order ID
        $pembayaran = Pembayaran::where('midtrans_order_id', $orderId)->first();

        // Fallback: If not found, try to find by parsing ORDER- prefix
        if (!$pembayaran && str_starts_with($orderId, 'ORDER-')) {
            $nomorPesanan = substr($orderId, 6);
            $pesanan = Pesanan::where('nomor_pesanan', $nomorPesanan)->first();
            
            if ($pesanan && $pesanan->pembayaran) {
                $pembayaran = $pesanan->pembayaran;
                // Update midtrans_order_id in DB to match actual transaction
                $pembayaran->update(['midtrans_order_id' => $orderId]);
            }
        }

        if (!$pembayaran) {
            throw new \Exception("Pembayaran not found for Order ID: $orderId");
        }

        // Update pembayaran data
        $pembayaran->update([
            'midtrans_transaction_id' => $transactionId,
            'payment_type' => $paymentType,
            'transaction_status' => $transactionStatus,
            'fraud_status' => $fraudStatus,
            'metadata' => (array) $data,
        ]);

        // Get pesanan
        $pesanan = $pembayaran->pesanan;

        // Update status berdasarkan transaction status
        if ($transactionStatus == 'capture') {
            if ($fraudStatus == 'accept') {
                $pembayaran->update(['paid_at' => now()]);
                $pesanan->updateStatus('dibayar');
            }
        } elseif ($transactionStatus == 'settlement') {
            $pembayaran->update(['paid_at' => now()]);
            $pesanan->updateStatus('dibayar');
        } elseif ($transactionStatus == 'pending') {
            $pesanan->updateStatus('pending');
        } elseif (in_array($transactionStatus, ['deny', 'expire', 'cancel'])) {
            $this->restoreStok($pesanan);
            $pesanan->updateStatus('dibatalkan');
        }

        return $pembayaran;
    }

    /**
     * Restore stok produk jika pembayaran gagal
     *
     * @param Pesanan $pesanan
     * @return void
     */
    private function restoreStok(Pesanan $pesanan)
    {
        foreach ($pesanan->detailPesanan as $detail) {
            $detail->produk->tambahStok($detail->jumlah);
        }
    }

    /**
     * Verifikasi signature notification
     *
     * @param array $notification
     * @return bool
     */
    public function verifikasiSignature($notification)
    {
        $serverKey = config('midtrans.server_key');
        $orderId = $notification['order_id'];
        $statusCode = $notification['status_code'];
        $grossAmount = $notification['gross_amount'];
        $signatureKey = $notification['signature_key'];

        $mySignature = hash('sha512', $orderId . $statusCode . $grossAmount . $serverKey);

        return $mySignature === $signatureKey;
    }
}
