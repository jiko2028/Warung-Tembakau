<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pesanan;
use App\Models\DetailPesanan;
use App\Models\Produk;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LaporanController extends Controller
{
    /**
     * Laporan Pendapatan (Revenue Report)
     */
    public function pendapatan(Request $request)
    {
        $this->checkAdmin();

        $year  = $request->input('tahun', now()->year);
        $month = $request->input('bulan');

        // Pendapatan per bulan untuk tahun yang dipilih
        $pendapatanBulanan = Pesanan::selectRaw('MONTH(created_at) as bulan, SUM(total_pembayaran) as total')
            ->whereIn('status_pesanan', ['dibayar', 'diproses', 'dikirim', 'selesai'])
            ->whereYear('created_at', $year)
            ->groupByRaw('MONTH(created_at)')
            ->orderBy('bulan')
            ->get()
            ->keyBy('bulan');

        // Build full 12-month data with zeros for missing months
        $chartLabels = [];
        $chartData   = [];
        for ($m = 1; $m <= 12; $m++) {
            $chartLabels[] = Carbon::create()->month($m)->format('M');
            $chartData[]   = isset($pendapatanBulanan[$m]) ? (float) $pendapatanBulanan[$m]->total : 0;
        }

        // Total stats
        $totalTahun = array_sum($chartData);

        // Transaksi detail berdasarkan filter bulan (opsional)
        $query = Pesanan::with('user')
            ->whereIn('status_pesanan', ['dibayar', 'diproses', 'dikirim', 'selesai'])
            ->whereYear('created_at', $year);

        if ($month) {
            $query->whereMonth('created_at', $month);
        }

        $pesanans = $query->latest()->paginate(10)->withQueryString();

        $availableYears = Pesanan::selectRaw('YEAR(created_at) as year')
            ->groupBy('year')
            ->orderBy('year', 'desc')
            ->pluck('year');

        return view('admin.laporan.pendapatan', compact(
            'chartLabels', 'chartData', 'totalTahun',
            'pesanans', 'year', 'month', 'availableYears'
        ));
    }

    /**
     * Laporan Penjualan (Sales Report - by product)
     */
    public function penjualan(Request $request)
    {
        $this->checkAdmin();

        $year  = $request->input('tahun', now()->year);
        $month = $request->input('bulan');

        // Top Products
        $topProduk = DetailPesanan::selectRaw('produk_id, nama_produk, SUM(jumlah) as total_terjual, SUM(subtotal) as total_pendapatan')
            ->whereHas('pesanan', function ($q) use ($year, $month) {
                $q->whereIn('status_pesanan', ['dibayar', 'diproses', 'dikirim', 'selesai'])
                  ->whereYear('created_at', $year);
                if ($month) {
                    $q->whereMonth('created_at', $month);
                }
            })
            ->groupBy('produk_id', 'nama_produk')
            ->orderBy('total_terjual', 'desc')
            ->take(10)
            ->get();

        // Penjualan harian untuk bulan yang dipilih (atau bulan ini)
        $displayMonth = $month ?? now()->month;
        $penjualanHarian = Pesanan::selectRaw('DAY(created_at) as hari, COUNT(*) as jumlah_pesanan, SUM(total_pembayaran) as total')
            ->whereIn('status_pesanan', ['dibayar', 'diproses', 'dikirim', 'selesai'])
            ->whereYear('created_at', $year)
            ->whereMonth('created_at', $displayMonth)
            ->groupByRaw('DAY(created_at)')
            ->orderBy('hari')
            ->get()
            ->keyBy('hari');

        $daysInMonth = Carbon::create($year, $displayMonth)->daysInMonth;
        $dailyLabels  = [];
        $dailySales   = [];
        for ($d = 1; $d <= $daysInMonth; $d++) {
            $dailyLabels[] = $d;
            $dailySales[]  = isset($penjualanHarian[$d]) ? (int) $penjualanHarian[$d]->jumlah_pesanan : 0;
        }

        // Summary Stats
        $totalPesananQuery = Pesanan::whereIn('status_pesanan', ['dibayar', 'diproses', 'dikirim', 'selesai'])
            ->whereYear('created_at', $year);
        if ($month) {
            $totalPesananQuery->whereMonth('created_at', $month);
        }
        $totalPesanan  = (clone $totalPesananQuery)->count();
        $totalProdukTerjual = DetailPesanan::whereHas('pesanan', function ($q) use ($year, $month) {
                $q->whereIn('status_pesanan', ['dibayar', 'diproses', 'dikirim', 'selesai'])
                  ->whereYear('created_at', $year);
                if ($month) $q->whereMonth('created_at', $month);
            })->sum('jumlah');

        $availableYears = Pesanan::selectRaw('YEAR(created_at) as year')
            ->groupBy('year')
            ->orderBy('year', 'desc')
            ->pluck('year');

        return view('admin.laporan.penjualan', compact(
            'topProduk', 'dailyLabels', 'dailySales', 'displayMonth',
            'totalPesanan', 'totalProdukTerjual',
            'year', 'month', 'availableYears'
        ));
    }

    private function checkAdmin()
    {
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Akses ditolak.');
        }
    }
}
