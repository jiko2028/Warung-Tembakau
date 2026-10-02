<x-filament-panels::page>
    <div class="space-y-6">
        <!-- Info Alert -->
        <x-filament::section>
            <x-slot name="heading">
                Informasi
            </x-slot>
            
            <div class="text-sm text-gray-600 dark:text-gray-400">
                <p>Halaman ini menampilkan data produk yang sudah terjual berdasarkan pesanan dengan status: <strong>Dibayar, Diproses, Dikirim, atau Selesai</strong>.</p>
                <p class="mt-2">Gunakan filter <strong>"Produk Terlaris"</strong> untuk melihat 10 produk dengan penjualan tertinggi, atau filter <strong>"Pendapatan Tertinggi"</strong> untuk melihat produk dengan total pendapatan terbesar.</p>
            </div>
        </x-filament::section>
        
        <!-- Table -->
        {{ $this->table }}
    </div>
</x-filament-panels::page>
