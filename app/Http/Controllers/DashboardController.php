<?php

namespace App\Http\Controllers;

use App\Models\Pesanan;
use App\Models\AlamatPengguna;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Redirect admin to admin dashboard
        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }
        
        // Statistik Pesanan
        // Statistik Pesanan
        $stats = [
            'pending' => Pesanan::where('user_id', $user->id)->whereIn('status_pesanan', ['pending', 'dibayar'])->count(),
            'processing' => Pesanan::where('user_id', $user->id)->where('status_pesanan', 'diproses')->count(),
            'shipping' => Pesanan::where('user_id', $user->id)->where('status_pesanan', 'dikirim')->count(),
            'completed' => Pesanan::where('user_id', $user->id)->where('status_pesanan', 'selesai')->count(),
        ];

        // Riwayat Pesanan Terbaru
        $orders = Pesanan::with('detailPesanan.produk')
            ->where('user_id', $user->id)
            ->latest()
            ->paginate(5);

        // Alamat Pengguna
        $addresses = AlamatPengguna::where('user_id', $user->id)->get();

        return view('dashboard', compact('stats', 'orders', 'addresses'));
    }
}
