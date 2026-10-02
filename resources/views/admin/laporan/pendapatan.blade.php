@extends('layouts.admin')

@section('header', 'Laporan Pendapatan')

@section('content')
<div class="mb-6">
    <h2 class="text-2xl font-bold text-gray-900">Laporan Pendapatan</h2>
    <p class="text-sm text-gray-600 mt-1">Ringkasan pendapatan toko berdasarkan periode waktu.</p>
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
        <a href="{{ route('admin.laporan.pendapatan', ['tahun' => $year]) }}" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-semibold rounded-md transition-colors">Reset</a>
    @endif
</form>

{{-- Summary Card --}}
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex items-center">
        <div class="p-3 rounded-lg bg-green-100 text-green-600 mr-4">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        </div>
        <div>
            <p class="text-sm text-gray-500">Total Pendapatan {{ $month ? '(Bulan ini)' : "(Tahun {$year})" }}</p>
            <p class="text-2xl font-bold text-gray-900">Rp {{ number_format($totalTahun, 0, ',', '.') }}</p>
        </div>
    </div>
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex items-center">
        <div class="p-3 rounded-lg bg-blue-100 text-blue-600 mr-4">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
        </div>
        <div>
            <p class="text-sm text-gray-500">Jumlah Transaksi</p>
            <p class="text-2xl font-bold text-gray-900">{{ $pesanans->total() }}</p>
        </div>
    </div>
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex items-center">
        <div class="p-3 rounded-lg bg-purple-100 text-purple-600 mr-4">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
        </div>
        <div>
            <p class="text-sm text-gray-500">Rata-rata per Transaksi</p>
            <p class="text-2xl font-bold text-gray-900">
                Rp {{ $pesanans->total() > 0 ? number_format($totalTahun / $pesanans->total(), 0, ',', '.') : '0' }}
            </p>
        </div>
    </div>
</div>

{{-- Bar Chart --}}
@if(!$month)
<div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-6">
    <h3 class="font-semibold text-gray-900 mb-4">Grafik Pendapatan Bulanan {{ $year }}</h3>
    <div style="position:relative; height:280px;">
        <canvas id="chartPendapatan"></canvas>
    </div>
</div>
@endif

{{-- Transactions Table --}}
<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-100 bg-gray-50 flex justify-between items-center">
        <h3 class="font-semibold text-gray-900">Daftar Transaksi</h3>
        <span class="text-sm text-gray-500">{{ $pesanans->total() }} transaksi ditemukan</span>
    </div>
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-white">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Order ID</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Pelanggan</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Tanggal</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Total</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($pesanans as $p)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4">
                        <a href="{{ route('admin.pesanan.show', $p->id) }}" class="font-mono text-sm text-primary-600 hover:underline">{{ $p->nomor_pesanan }}</a>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-700">{{ $p->user->name ?? '-' }}</td>
                    <td class="px-6 py-4 text-sm text-gray-500">{{ $p->created_at->format('d M Y, H:i') }}</td>
                    <td class="px-6 py-4 text-sm font-bold text-gray-900">Rp {{ number_format($p->total_pembayaran, 0, ',', '.') }}</td>
                    <td class="px-6 py-4">
                        @php $statusColors = ['dibayar'=>'blue','diproses'=>'purple','dikirim'=>'indigo','selesai'=>'green']; $c = $statusColors[$p->status_pesanan] ?? 'gray'; @endphp
                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-{{ $c }}-100 text-{{ $c }}-800">{{ ucfirst($p->status_pesanan) }}</span>
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="px-6 py-8 text-center text-gray-400">Belum ada transaksi pada periode ini.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($pesanans->hasPages())
    <div class="px-6 py-4 border-t border-gray-100">{{ $pesanans->links() }}</div>
    @endif
</div>

@push('scripts')
@if(!$month)
<script src="https://cdn.jsdelivr.net/npm/chart.js@4/dist/chart.umd.min.js"></script>
<script>
    const ctx = document.getElementById('chartPendapatan').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: {!! json_encode($chartLabels) !!},
            datasets: [{
                label: 'Pendapatan (Rp)',
                data: {!! json_encode($chartData) !!},
                backgroundColor: 'rgba(101, 163, 13, 0.7)',
                borderColor: 'rgba(101, 163, 13, 1)',
                borderWidth: 1,
                borderRadius: 6,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        label: ctx => 'Rp ' + new Intl.NumberFormat('id-ID').format(ctx.raw)
                    }
                }
            },
            scales: {
                y: {
                    ticks: {
                        callback: val => 'Rp ' + new Intl.NumberFormat('id-ID').format(val)
                    }
                }
            }
        }
    });
</script>
@endif
@endpush
@endsection
