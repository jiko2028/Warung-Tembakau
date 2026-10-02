<?php

namespace App\Services;

use App\Models\Keranjang;
use App\Models\Produk;
use Illuminate\Support\Facades\Auth;

class KeranjangService
{
    /**
     * Tambah produk ke keranjang
     *
     * @param int $produkId
     * @param int $jumlah
     * @return Keranjang
     */
    public function tambahProduk($produkId, $jumlah = 1)
    {
        // Validasi produk exists dan tersedia
        $produk = Produk::findOrFail($produkId);

        if (!$produk->isTersedia($jumlah)) {
            throw new \Exception("Produk tidak tersedia atau stok tidak mencukupi");
        }

        // Get user ID atau session ID
        $userId = Auth::id();
        $sessionId = $userId ? null : session()->getId();

        // Check apakah produk sudah ada di keranjang
        $keranjang = Keranjang::where('produk_id', $produkId)
            ->where(function ($query) use ($userId, $sessionId) {
                if ($userId) {
                    $query->where('user_id', $userId);
                } else {
                    $query->where('session_id', $sessionId);
                }
            })
            ->first();

        if ($keranjang) {
            // Update jumlah jika sudah ada
            $jumlahBaru = $keranjang->jumlah + $jumlah;

            if (!$produk->isTersedia($jumlahBaru)) {
                throw new \Exception("Stok tidak mencukupi untuk jumlah yang diminta");
            }

            $keranjang->update(['jumlah' => $jumlahBaru]);
        } else {
            // Tambah item baru
            $keranjang = Keranjang::create([
                'user_id' => $userId,
                'session_id' => $sessionId,
                'produk_id' => $produkId,
                'jumlah' => $jumlah,
            ]);
        }

        return $keranjang;
    }

    /**
     * Update jumlah item di keranjang
     *
     * @param int $keranjangId
     * @param int $jumlah
     * @return Keranjang
     */
    public function updateJumlah($keranjangId, $jumlah)
    {
        $keranjang = Keranjang::findOrFail($keranjangId);

        // Validasi stok
        if (!$keranjang->produk->isTersedia($jumlah)) {
            throw new \Exception("Stok tidak mencukupi untuk jumlah yang diminta");
        }

        $keranjang->update(['jumlah' => $jumlah]);

        return $keranjang;
    }

    /**
     * Hapus item dari keranjang
     *
     * @param int $keranjangId
     * @return void
     */
    public function hapusProduk($keranjangId)
    {
        $keranjang = Keranjang::findOrFail($keranjangId);
        $keranjang->delete();
    }

    /**
     * Kosongkan semua item di keranjang user/session
     *
     * @return void
     */
    public function kosongkanKeranjang()
    {
        $userId = Auth::id();
        $sessionId = $userId ? null : session()->getId();

        Keranjang::where(function ($query) use ($userId, $sessionId) {
            if ($userId) {
                $query->where('user_id', $userId);
            } else {
                $query->where('session_id', $sessionId);
            }
        })->delete();
    }

    /**
     * Get semua item di keranjang dengan detail produk
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getIsiKeranjang()
    {
        $userId = Auth::id();
        $sessionId = $userId ? null : session()->getId();

        return Keranjang::with('produk.kategori')
            ->where(function ($query) use ($userId, $sessionId) {
                if ($userId) {
                    $query->where('user_id', $userId);
                } else {
                    $query->where('session_id', $sessionId);
                }
            })
            ->get();
    }

    /**
     * Hitung total harga dan berat keranjang
     *
     * @return array ['subtotal' => float, 'total_berat' => int, 'jumlah_item' => int]
     */
    public function hitungTotalKeranjang()
    {
        $items = $this->getIsiKeranjang();

        $subtotal = 0;
        $totalBerat = 0;
        $jumlahItem = 0;

        foreach ($items as $item) {
            $subtotal += $item->subtotal;
            $totalBerat += $item->total_berat;
            $jumlahItem += $item->jumlah;
        }

        return [
            'subtotal' => $subtotal,
            'total_berat' => $totalBerat,
            'jumlah_item' => $jumlahItem,
        ];
    }

    /**
     * Transfer keranjang session ke user setelah login
     *
     * @param int $userId
     * @return void
     */
    public function transferSessionToUser($userId)
    {
        $sessionId = session()->getId();

        // Update semua keranjang dengan session_id ke user_id
        Keranjang::where('session_id', $sessionId)->update([
            'user_id' => $userId,
            'session_id' => null,
        ]);
    }
}
