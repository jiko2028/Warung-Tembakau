@extends('layouts.admin')

@section('header', 'Laporan Penjualan')

@section('content')
<div class="mb-6">
    <h2 class="text-2xl font-bold text-gray-900">Laporan Penjualan</h2>
    <p class="text-sm text-gray-600 mt-1">Analisis produk terlaris dan pola penjualan harian.</p>
</div>

{{-- Filter --}}
<form method="GET" class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 flex flex-wrap gap-3 items-end mb-6">
    <div>
        <label class="block text-xs font-semibold text-gray-600 mb-1">Tahun</label>
        <select name="tahun" class="rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 text-sm">
            @foreach($availableYears->isEmpty() ? [now()->year] : $availableYears as $yr)
                <option value="{{ $yr }}" {{ $year == $yr ? 'selected' : '' }}>{{ $yr }}</option>
            @endforeach
        </select>
    </div>
    <div>
        <label class="block text-xs font-semibold text-gray-600 mb-1">Bulan (opsional)</label>
        <select name="bulan" class="rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 text-sm">
            <option value="">Semua Bulan</option>
            @foreach(['01'=>'Januari','02'=>'Februari','03'=>'Maret','04'=>'April','05'=>'Mei','06'=>'Juni','07'=>'Juli','08'=>'Agustus','09'=>'September','10'=>'Oktober','11'=>'November','12'=>'Desember'] as $num => $nama)
                <option value="{{ (int)$num }}" {{ $month == (int)$num ? 'selected' : '' }}>{{ $nama }}</option>
            @endforeach
        </select>
    </div>
    <button type="submit" class="px-4 py-2 bg-primary-600 hover:bg-primary-700 text-white text-sm font-semibold rounded-md transition-colors shadow-sm">Tampilkan</button>
    @if($month)
        <a href="{{ route('admin.laporan.penjualan', ['tahun' => $year]) }}" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-semibold rounded-md transition-colors">Reset</a>
    @endif
</form>

{{-- Summary Stats --}}
<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex items-center">
        <div class="p-3 rounded-lg bg-blue-100 text-blue-600 mr-4">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
        </div>
        <div>
            <p class="text-sm text-gray-500">Total Pesanan Sukses</p>
            <p class="text-2xl font-bold text-gray-900">{{ number_format($totalPesanan) }}</p>
        </div>
    </div>
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex items-center">
        <div class="p-3 rounded-lg bg-orange-100 text-orange-600 mr-4">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
        </div>
        <div>
            <p class="text-sm text-gray-500">Total Produk Terjual</p>
            <p class="text-2xl font-bold text-gray-900">{{ number_format($totalProdukTerjual) }} pcs</p>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
    {{-- Daily Sales Chart --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <h3 class="font-semibold text-gray-900 mb-1">Penjualan Harian</h3>
        <p class="text-xs text-gray-500 mb-4">
            {{ \Carbon\Carbon::create($year, $displayMonth)->translatedFormat('F Y') }}
        </p>
        <div style="position:relative; height:260px;">
            <canvas id="chartHarian"></canvas>
        </div>
    </div>

    {{-- Top Products --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
            <h3 class="font-semibold text-gray-900">🏆 Produk Terlaris</h3>
            <p class="text-xs text-gray-500 mt-0.5">Top 10 berdasarkan jumlah terjual</p>
        </div>
        <ul class="divide-y divide-gray-100">
            @forelse($topProduk as $i => $produk)
            <li class="px-6 py-3 flex items-center justify-between">
                <div class="flex items-center">
                    <span class="w-6 text-xs font-bold {{ $i < 3 ? 'text-yellow-500' : 'text-gray-400' }}">{{ $i + 1 }}</span>
                    <div class="ml-2">
                        <p class="text-sm font-medium text-gray-900 truncate max-w-xs">{{ $produk->nama_produk }}</p>
                        <p class="text-xs text-gray-400">Rp {{ number_format($produk->total_pendapatan, 0, ',', '.') }}</p>
                    </div>
                </div>
                <span class="text-sm font-bold text-primary-600 bg-primary-50 px-2 py-1 rounded-md">
                    {{ number_format($produk->total_terjual) }} pcs
                </span>
            </li>
            @empty
            <li class="px-6 py-8 text-center text-gray-400 text-sm">Belum ada data penjualan.</li>
            @endforelse
        </ul>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4/dist/chart.umd.min.js"></script>
<script>
    new Chart(document.getElementById('chartHarian').getContext('2d'), {
        type: 'line',
        data: {
            labels: {!! json_encode($dailyLabels) !!},
            datasets: [{
                label: 'Jumlah Pesanan',
                data: {!! json_encode($dailySales) !!},
                borderColor: 'rgba(99, 102, 241, 1)',
                backgroundColor: 'rgba(99, 102, 241, 0.1)',
                borderWidth: 2,
                tension: 0.4,
                fill: true,
                pointBackgroundColor: 'rgba(99, 102, 241, 1)',
                pointRadius: 3,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: { stepSize: 1, precision: 0 }
                }
            }
        }
    });
</script>
@endpush
@endsection
