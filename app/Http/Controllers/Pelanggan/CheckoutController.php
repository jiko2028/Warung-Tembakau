<?php

namespace App\Http\Controllers\Pelanggan;

use App\Http\Controllers\Controller;
use App\Services\KeranjangService;
use App\Services\OngkirService;
use App\Services\PesananService;
use App\Models\AlamatPengguna;
use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    protected $keranjangService;
    protected $ongkirService;
    protected $pesananService;

    public function __construct(
        KeranjangService $keranjangService,
        OngkirService $ongkirService,
        PesananService $pesananService
    ) {
        $this->keranjangService = $keranjangService;
        $this->ongkirService = $ongkirService;
        $this->pesananService = $pesananService;
    }

    /**
     * Tampilkan halaman checkout
     */
   public function index()
    {
        $items = $this->keranjangService->getIsiKeranjang();
        $total = $this->keranjangService->hitungTotalKeranjang();
        $alamatList = auth()->user()->alamatPengguna;

        if ($items->isEmpty()) {
            return redirect()->route('keranjang.index')->with('error', 'Keranjang kosong');
        }

        return view('pelanggan.checkout.index', compact('items', 'total', 'alamatList'));
    }

    /**
     * Hitung ongkir via AJAX
     */
    public function hitungOngkir(Request $request)
    {
        $request->validate([
            'alamat_id' => 'required|exists:alamat_pengguna,id',
        ]);

        $alamat = AlamatPengguna::findOrFail($request->alamat_id);
        $total = $this->keranjangService->hitungTotalKeranjang();
        
        $ongkir = $this->ongkirService->hitungOngkir(
            $alamat->wilayah_pengiriman,
            $total['total_berat']
        );

        return response()->json([
            'success' => true,
            'ongkir' => $ongkir,
            'total_pembayaran' => $total['subtotal'] + $ongkir['total_ongkir'],
        ]);
    }

    /**
     * Proses checkout dan buat pesanan
     */
    public function proses(Request $request)
    {
        $request->validate([
            'alamat_pengguna_id' => 'required|exists:alamat_pengguna,id',
            'catatan' => 'nullable|string|max:500',
        ]);

        try {
            $pesanan = $this->pesananService->buatPesanan([
                'alamat_pengguna_id' => $request->alamat_pengguna_id,
                'catatan' => $request->catatan,
            ]);

            return redirect()->route('pembayaran.index', $pesanan->id)
                ->with('success', 'Pesanan berhasil dibuat. Silakan lakukan pembayaran.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
