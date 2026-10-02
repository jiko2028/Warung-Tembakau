<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Kategori;

class KategoriSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $kategories = [
            [
                'nama' => 'Tembakau Iris',
                'slug' => 'tembakau-iris',
                'deskripsi' => 'Tembakau yang sudah diiris halus, siap untuk dilinting',
            ],
            [
                'nama' => 'Tembakau Krosok',
                'slug' => 'tembakau-krosok',
                'deskripsi' => 'Tembakau dalam bentuk utuh (belum diiris)',
            ],
            [
                'nama' => 'Tembakau Premium',
                'slug' => 'tembakau-premium',
                'deskripsi' => 'Tembakau kualitas terbaik dengan aroma khas',
            ],
            [
                'nama' => 'Rokok Linting',
                'slug' => 'rokok-linting',
                'deskripsi' => 'Perlengkapan untuk melilit rokok',
            ],
        ];

        foreach ($kategories as $kategori) {
            Kategori::create($kategori);
        }
    }
}
