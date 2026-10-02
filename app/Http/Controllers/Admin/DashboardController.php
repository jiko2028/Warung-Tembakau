<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Produk;
use App\Models\Pesanan;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Pastikan hanya admin yang bisa akses (bisa dipindah ke middleware nanti)
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Akses ditolak.');
        }

        $totalPendapatan = Pesanan::whereIn('status_pesanan', ['dibayar', 'diproses', 'dikirim', 'selesai'])
            ->sum('total_pembayaran');
            
        $pesananBaru = Pesanan::where('status_pesanan', 'dibayar')->count();
        $totalProduk = Produk::count();
        $totalPelanggan = User::where('role', 'pelanggan')->count();

        $pesananTerbaru = Pesanan::with('user')
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalPendapatan', 'pesananBaru', 'totalProduk', 'totalPelanggan', 'pesananTerbaru'
        ));
    }
}
