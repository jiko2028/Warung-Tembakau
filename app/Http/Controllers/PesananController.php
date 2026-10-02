<?php

namespace App\Http\Controllers;

use App\Models\Pesanan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PesananController extends Controller
{
    protected $midtransService;

    public function __construct(\App\Services\MidtransService $midtransService)
    {
        $this->midtransService = $midtransService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pesanans = Pesanan::where('user_id', Auth::id())
            ->latest()
            ->paginate(10);

        return view('pesanan.index', compact('pesanans'));
    }

    /**
     * Display the specified resource.
     */
    public function show($nomor_pesanan)
    {
        $pesanan = Pesanan::with(['detailPesanan.produk', 'pembayaran', 'alamatPengguna'])
            ->where('nomor_pesanan', $nomor_pesanan)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        // Check status if pending
        if ($pesanan->status_pesanan == 'pending') {
            try {
                // Gunakan format ID yang seharusnya (ORDER-...) agar fallback di service bisa bekerja
                // jika ID di database belum update.
                $this->midtransService->updateTransactionStatus('ORDER-' . $pesanan->nomor_pesanan);
                $pesanan->refresh();
            } catch (\Exception $e) {
                // Log but continue
                \Illuminate\Support\Facades\Log::error('Gagal auto-update status di detail pesanan: ' . $e->getMessage());
            }
        }

        return view('pesanan.show', compact('pesanan'));
    }

    public function updateRefund(Request $request, $nomor_pesanan)
    {
        $pesanan = Pesanan::where('nomor_pesanan', $nomor_pesanan)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        if ($pesanan->status_pesanan !== 'dibatalkan') {
            return back()->with('error', 'Pesanan tidak dalam status dibatalkan.');
        }

        $request->validate([
            'info_refund' => 'required|string|max:1000',
        ]);

        $pesanan->update([
            'info_refund' => $request->info_refund,
        ]);

        return back()->with('success', 'Informasi refund berhasil dikirim. Admin akan segera memproses.');
    }

    public function terima($nomor_pesanan)
    {
        $pesanan = Pesanan::where('nomor_pesanan', $nomor_pesanan)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        if ($pesanan->status_pesanan == 'dikirim') {
            $pesanan->update(['status_pesanan' => 'selesai']);
            return back()->with('success', 'Terima kasih! Pesanan telah diselesaikan.');
        }

        return back()->with('error', 'Pesanan tidak dapat diselesaikan saat ini.');
    }
}
