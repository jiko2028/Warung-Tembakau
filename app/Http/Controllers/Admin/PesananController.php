<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pesanan;
use Illuminate\Http\Request;

class PesananController extends Controller
{
    public function index()
    {
        // Fitur Filter sederhana jika diperlukan bisa ditambahkan lewat request()
        $pesanans = Pesanan::with(['user', 'pembayaran'])
            ->latest()
            ->paginate(10);
            
        return view('admin.pesanan.index', compact('pesanans'));
    }

    public function show(Pesanan $pesanan)
    {
        $pesanan->load(['user', 'alamatPengguna', 'detailPesanan.produk', 'pembayaran']);
        return view('admin.pesanan.show', compact('pesanan'));
    }

    public function update(Request $request, Pesanan $pesanan)
    {
        $request->validate([
            'status_pesanan' => 'required|in:pending,dibayar,diproses,dikirim,selesai,dibatalkan',
            'nomor_resi' => 'nullable|string|max:100',
        ]);

        $data = ['status_pesanan' => $request->status_pesanan];

        // Auto-save nomor_resi & tanggal saat status dikirim
        if ($request->status_pesanan === 'dikirim') {
            if ($request->filled('nomor_resi')) {
                $data['nomor_resi'] = $request->nomor_resi;
            }
            if (!$pesanan->tanggal_dikirim) {
                $data['tanggal_dikirim'] = now();
            }
        }

        $pesanan->update($data);

        return back()->with('success', 'Status pesanan ' . $pesanan->nomor_pesanan . ' berhasil diubah menjadi ' . strtoupper($request->status_pesanan));
    }
}
