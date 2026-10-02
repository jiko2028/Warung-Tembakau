<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Produk;
use App\Models\Kategori;

class ProdukSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get kategori IDs
        $tembakauIris = Kategori::where('slug', 'tembakau-iris')->first();
        $tembakauKrosok = Kategori::where('slug', 'tembakau-krosok')->first();
        $tembakauPremium = Kategori::where('slug', 'tembakau-premium')->first();
        $rokokLinting = Kategori::where('slug', 'rokok-linting')->first();

        $produks = [
            // Tembakau Iris
            [
                'kategori_id' => $tembakauIris->id,
                'nama' => 'Tembakau Iris Super',
                'slug' => 'tembakau-iris-super',
                'deskripsi' => 'Tembakau iris berkualitas tinggi dengan aroma khas. Cocok untuk rokok linting.',
                'harga' => 50000,
                'berat' => 250, // gram
                'stok' => 100,
                'is_active' => true,
            ],
            [
                'kategori_id' => $tembakauIris->id,
                'nama' => 'Tembakau Iris Reguler',
                'slug' => 'tembakau-iris-reguler',
                'deskripsi' => 'Tembakau iris kualitas reguler dengan harga terjangkau.',
                'harga' => 35000,
                'berat' => 250,
                'stok' => 150,
                'is_active' => true,
            ],
            // Tembakau Krosok
            [
                'kategori_id' => $tembakauKrosok->id,
                'nama' => 'Tembakau Krosok Pilihan',
                'slug' => 'tembakau-krosok-pilihan',
                'deskripsi' => 'Tembakau krosok pilihan langsung dari petani. Belum diiris.',
                'harga' => 80000,
                'berat' => 500,
                'stok' => 50,
                'is_active' => true,
            ],
            [
                'kategori_id' => $tembakauKrosok->id,
                'nama' => 'Tembakau Krosok Grade A',
                'slug' => 'tembakau-krosok-grade-a',
                'deskripsi' => 'Tembakau krosok grade A dengan daun berkualitas premium.',
                'harga' => 120000,
                'berat' => 500,
                'stok' => 30,
                'is_active' => true,
            ],
            // Tembakau Premium
            [
                'kategori_id' => $tembakauPremium->id,
                'nama' => 'Tembakau Premium Virginia',
                'slug' => 'tembakau-premium-virginia',
                'deskripsi' => 'Tembakau Virginia premium import dengan aroma manis khas.',
                'harga' => 150000,
                'berat' => 200,
                'stok' => 25,
                'is_active' => true,
            ],
            [
                'kategori_id' => $tembakauPremium->id,
                'nama' => 'Tembakau Premium Blend',
                'slug' => 'tembakau-premium-blend',
                'deskripsi' => 'Campuran tembakau premium dari berbagai jenis untuk pengalaman unik.',
                'harga' => 180000,
                'berat' => 200,
                'stok' => 20,
                'is_active' => true,
            ],
            // Rokok Linting
            [
                'kategori_id' => $rokokLinting->id,
                'nama' => 'Kertas Linting Premium',
                'slug' => 'kertas-linting-premium',
                'deskripsi' => 'Kertas linting berkualitas tinggi, tidak mudah sobek.',
                'harga' => 15000,
                'berat' => 50,
                'stok' => 200,
                'is_active' => true,
            ],
            [
                'kategori_id' => $rokokLinting->id,
                'nama' => 'Filter Rokok',
                'slug' => 'filter-rokok',
                'deskripsi' => 'Filter rokok untuk hasil lintingan yang lebih halus.',
                'harga' => 20000,
                'berat' => 100,
                'stok' => 150,
                'is_active' => true,
            ],
            [
                'kategori_id' => $rokokLinting->id,
                'nama' => 'Mesin Linting Manual',
                'slug' => 'mesin-linting-manual',
                'deskripsi' => 'Mesin linting manual untuk membuat rokok dengan mudah dan rapi.',
                'harga' => 75000,
                'berat' => 300,
                'stok' => 40,
                'is_active' => true,
            ],
        ];

        foreach ($produks as $produk) {
            Produk::create($produk);
        }
    }
}
