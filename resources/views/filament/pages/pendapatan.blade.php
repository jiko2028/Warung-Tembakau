<x-filament-panels::page>
    <div class="space-y-6">
        <!-- Stats Overview -->
        <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">
            <!-- Pendapatan Hari Ini -->
            <x-filament::card>
                <div class="space-y-2">
                    <div class="text-sm font-medium text-gray-500 dark:text-gray-400">
                        Pendapatan Hari Ini
                    </div>
                    <div class="text-3xl font-bold text-primary-600">
                        Rp {{ number_format($totalPendapatanHariIni, 0, ',', '.') }}
                    </div>
                </div>
            </x-filament::card>
            
            <!-- Pendapatan Bulan Ini -->
            <x-filament::card>
                <div class="space-y-2">
                    <div class="text-sm font-medium text-gray-500 dark:text-gray-400">
                        Pendapatan Bulan Ini
                    </div>
                    <div class="text-3xl font-bold text-success-600">
                        Rp {{ number_format($totalPendapatanBulanIni, 0, ',', '.') }}
                    </div>
                    <div class="text-xs text-gray-500">
                        {{ $jumlahPesananBulanIni }} pesanan
                    </div>
                </div>
            </x-filament::card>
            
            <!-- Pendapatan Tahun Ini -->
            <x-filament::card>
                <div class="space-y-2">
                    <div class="text-sm font-medium text-gray-500 dark:text-gray-400">
                        Pendapatan Tahun Ini
                    </div>
                    <div class="text-3xl font-bold text-info-600">
                        Rp {{ number_format($totalPendapatanTahunIni, 0, ',', '.') }}
                    </div>
                </div>
            </x-filament::card>
            
            <!-- Total Keseluruhan -->
            <x-filament::card>
                <div class="space-y-2">
                    <div class="text-sm font-medium text-gray-500 dark:text-gray-400">
                        Total Keseluruhan
                    </div>
                    <div class="text-3xl font-bold text-warning-600">
                        Rp {{ number_format($totalPendapatanKeseluruhan, 0, ',', '.') }}
                    </div>
                </div>
            </x-filament::card>
            
            <!-- Rata-rata Per Pesanan -->
            <x-filament::card>
                <div class="space-y-2">
                    <div class="text-sm font-medium text-gray-500 dark:text-gray-400">
                        Rata-rata Per Pesanan
                    </div>
                    <div class="text-3xl font-bold">
                        Rp {{ number_format($rataRataPendapatanPerPesanan, 0, ',', '.') }}
                    </div>
                    <div class="text-xs text-gray-500">
                        Bulan ini
                    </div>
                </div>
            </x-filament::card>
        </div>
        
        <!-- Pendapatan Per Bulan -->
        <x-filament::section>
            <x-slot name="heading">
                Pendapatan Per Bulan ({{ now()->year }})
            </x-slot>
            
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b dark:border-gray-700">
                            <th class="px-4 py-2 text-left">Bulan</th>
                            <th class="px-4 py-2 text-right">Total Pendapatan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($this->getPendapatanPerBulan() as $bulan => $total)
                            <tr class="border-b dark:border-gray-700">
                                <td class="px-4 py-2">{{ $bulan }}</td>
                                <td class="px-4 py-2 text-right font-medium">
                                    Rp {{ number_format($total, 0, ',', '.') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="2" class="px-4 py-8 text-center text-gray-500">
                                    Belum ada data pendapatan untuk tahun ini
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </x-filament::section>
        
        <!-- Info -->
        <x-filament::section>
            <x-slot name="heading">
                Informasi
            </x-slot>
            
            <div class="text-sm text-gray-600 dark:text-gray-400">
                <p>Data pendapatan dihitung dari pesanan dengan status: <strong>Dibayar, Diproses, Dikirim, dan Selesai</strong>.</p>
                <p class="mt-2">Pesanan dengan status <strong>Pending</strong> atau <strong>Dibatalkan</strong> tidak dihitung dalam laporan pendapatan.</p>
            </div>
        </x-filament::section>
    </div>
</x-filament-panels::page>
