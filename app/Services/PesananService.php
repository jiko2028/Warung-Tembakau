<?php

namespace App\Services;

use App\Models\Pesanan;
use App\Models\DetailPesanan;
use App\Models\AlamatPengguna;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class PesananService
{
    protected $keranjangService;
    protected $ongkirService;

    public function __construct(KeranjangService $keranjangService, OngkirService $ongkirService)
    {
        $this->keranjangService = $keranjangService;
        $this->ongkirService = $ongkirService;
    }

    /**
     * Buat pesanan dari keranjang
     *
     * @param array $data ['alamat_pengguna_id' => int, 'catatan' => string|null]
     * @return Pesanan
     */
    public function buatPesanan($data)
    {
        // Validasi alamat pengguna
        $alamat = AlamatPengguna::where('id', $data['alamat_pengguna_id'])
            ->where('user_id', Auth::id())
            ->firstOrFail();

        // Get isi keranjang
        $items = $this->keranjangService->getIsiKeranjang();

        if ($items->isEmpty()) {
            throw new \Exception("Keranjang kosong. Tambahkan produk terlebih dahulu.");
        }

        // Hitung total keranjang
        $totalKeranjang = $this->keranjangService->hitungTotalKeranjang();

        // Hitung ongkir
        $ongkir = $this->ongkirService->hitungOngkir(
            $alamat->wilayah_pengiriman,
            $totalKeranjang['total_berat']
        );

        // Hitung total pembayaran
        $totalPembayaran = $totalKeranjang['subtotal'] + $ongkir['total_ongkir'];

        DB::beginTransaction();

        try {
            // Buat pesanan
            $pesanan = Pesanan::create([
                'user_id' => Auth::id(),
                'alamat_pengguna_id' => $alamat->id,
                'subtotal' => $totalKeranjang['subtotal'],
                'total_berat' => $totalKeranjang['total_berat'],
                'ongkir_wilayah' => $ongkir['ongkir_wilayah'],
                'ongkir_berat' => $ongkir['ongkir_berat'],
                'total_ongkir' => $ongkir['total_ongkir'],
                'total_pembayaran' => $totalPembayaran,
                'status_pesanan' => 'pending',
                'catatan' => $data['catatan'] ?? null,
            ]);

            // Buat detail pesanan dan kurangi stok
            foreach ($items as $item) {
                // Validasi stok sekali lagi (avoid race condition)
                if (!$item->produk->isTersedia($item->jumlah)) {
                    throw new \Exception("Stok tidak mencukupi untuk produk: {$item->produk->nama}");
                }

                // Buat detail pesanan dengan snapshot data produk
                DetailPesanan::create([
                    'pesanan_id' => $pesanan->id,
                    'produk_id' => $item->produk->id,
                    'nama_produk' => $item->produk->nama,
                    'harga_satuan' => $item->produk->harga,
                    'berat_satuan' => $item->produk->berat,
                    'jumlah' => $item->jumlah,
                    'subtotal' => $item->subtotal,
                ]);

                // Kurangi stok produk
                $item->produk->kurangiStok($item->jumlah);
            }

            // Kosongkan keranjang
            $this->keranjangService->kosongkanKeranjang();

            DB::commit();

            return $pesanan;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Update status pesanan
     *
     * @param int $pesananId
     * @param string $status
     * @return Pesanan
     */
    public function updateStatusPesanan($pesananId, $status)
    {
        $pesanan = Pesanan::findOrFail($pesananId);
        $pesanan->updateStatus($status);

        return $pesanan;
    }

    /**
     * Batalkan pesanan dan kembalikan stok
     *
     * @param int $pesananId
     * @return Pesanan
     */
    public function batalkanPesanan($pesananId)
    {
        $pesanan = Pesanan::findOrFail($pesananId);

        // Hanya bisa dibatalkan jika status pending atau dibayar
        if (!in_array($pesanan->status_pesanan, ['pending', 'dibayar'])) {
            throw new \Exception("Pesanan dengan status {$pesanan->status_pesanan} tidak dapat dibatalkan");
        }

        DB::beginTransaction();

        try {
            // Kembalikan stok produk
            foreach ($pesanan->detailPesanan as $detail) {
                $detail->produk->tambahStok($detail->jumlah);
            }

            // Update status pesanan
            $pesanan->updateStatus('dibatalkan');

            DB::commit();

            return $pesanan;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
