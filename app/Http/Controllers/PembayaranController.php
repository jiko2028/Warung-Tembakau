<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\MidtransService;
use App\Models\Pesanan;
use Illuminate\Http\Request;

class PembayaranController extends Controller
{
    protected $midtransService;

    public function __construct(MidtransService $midtransService)
    {
        $this->midtransService = $midtransService;
    }

    /**
     * Tampilkan halaman pembayaran dengan Midtrans Snap
     */
    public function index($pesananId)
    {
        $pesanan = Pesanan::with(['detailPesanan.produk', 'alamatPengguna'])
            ->where('user_id', auth()->id())
            ->findOrFail($pesananId);

        // Generate Snap Token
        try {
            $snapToken = $this->midtransService->buatSnapToken($pesanan);

            return view('pembayaran.index', compact('pesanan', 'snapToken'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal membuat token pembayaran: ' . $e->getMessage());
        }
    }

    /**
     * Handle callback dari Midtrans
     */
    public function callback(Request $request)
    {
        try {
            $notification = $request->all();

            // Verifikasi signature
            if (!$this->midtransService->verifikasiSignature($notification)) {
                return response()->json(['message' => 'Invalid signature'], 403);
            }

            // Proses callback
            $pembayaran = $this->midtransService->prosesCallback($notification);

            return response()->json([
                'success' => true,
                'message' => 'Callback processed successfully',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Halaman setelah pembayaran selesai (redirect dari Midtrans)
     */
    /**
     * Halaman setelah pembayaran selesai (redirect dari Midtrans)
     */
    public function finish(Request $request)
    {
        $orderId = $request->order_id;
        $statusCode = $request->status_code;
        $transactionStatus = $request->transaction_status;

        // Cek status terbaru ke Midtrans (untuk update DB)
        try {
            if ($orderId) {
                $this->midtransService->updateTransactionStatus($orderId);
            }
        } catch (\Exception $e) {
            // Log error tapi jangan block user view
            \Illuminate\Support\Facades\Log::error('Gagal update status di finish: ' . $e->getMessage());
        }

        return view('pembayaran.finish', compact('orderId', 'statusCode', 'transactionStatus'));
    }

    /**
     * Halaman sukses pembayaran
     */
    public function sukses($pesananId)
    {
        $pesanan = Pesanan::with(['pembayaran', 'detailPesanan.produk'])
            ->where('user_id', auth()->id())
            ->findOrFail($pesananId);

        // Cek status terbaru ke Midtrans
        try {
            $this->midtransService->updateTransactionStatus('ORDER-' . $pesanan->nomor_pesanan);
            // Refresh model setelah update
            $pesanan->refresh();
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Gagal update status di sukses: ' . $e->getMessage());
        }

        return view('pembayaran.sukses', compact('pesanan'));
    }

    /**
     * Halaman gagal pembayaran
     */
    public function gagal($pesananId)
    {
        $pesanan = Pesanan::where('user_id', auth()->id())->findOrFail($pesananId);
        
        // Cek status terbaru juga untuk memastikan
        try {
            $this->midtransService->updateTransactionStatus('ORDER-' . $pesanan->nomor_pesanan);
            $pesanan->refresh();
        } catch (\Exception $e) {
             \Illuminate\Support\Facades\Log::error('Gagal update status di gagal: ' . $e->getMessage());
        }

        return view('pembayaran.gagal', compact('pesanan'));
    }
}
