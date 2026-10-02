<?php

namespace App\Http\Controllers\Pelanggan;

use App\Http\Controllers\Controller;
use App\Services\KeranjangService;
use Illuminate\Http\Request;

class KeranjangController extends Controller
{
    protected $keranjangService;

    public function __construct(KeranjangService $keranjangService)
    {
        $this->keranjangService = $keranjangService;
    }

    /**
     * Tampilkan halaman keranjang
     */
    public function index()
    {
        $items = $this->keranjangService->getIsiKeranjang();
        $total = $this->keranjangService->hitungTotalKeranjang();

        return view('pelanggan.keranjang.index', compact('items', 'total'));
    }

    /**
     * Tambah produk ke keranjang
     */
    public function tambah(Request $request)
    {
        $request->validate([
            'produk_id' => 'required|exists:produk,id',
            'jumlah' => 'required|integer|min:1',
        ]);

        try {
            $this->keranjangService->tambahProduk($request->produk_id, $request->jumlah);

            return redirect()->back()->with('success', 'Produk berhasil ditambahkan ke keranjang');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Update jumlah item di keranjang
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'jumlah' => 'required|integer|min:1',
        ]);

        try {
            $this->keranjangService->updateJumlah($id, $request->jumlah);

            return redirect()->back()->with('success', 'Jumlah produk berhasil diupdate');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Hapus item dari keranjang
     */
    public function hapus($id)
    {
        try {
            $this->keranjangService->hapusProduk($id);

            return redirect()->back()->with('success', 'Produk berhasil dihapus dari keranjang');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Kosongkan semua item di keranjang
     */
    public function kosongkan()
    {
        $this->keranjangService->kosongkanKeranjang();

        return redirect()->back()->with('success', 'Keranjang berhasil dikosongkan');
    }
}
