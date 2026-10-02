<?php

use Illuminate\Support\Facades\DB;

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== DATABASE CHECK ===\n\n";

// Count tables
$counts = [
    'Users' => DB::table('users')->count(),
    'Kategori' => DB::table('kategori')->count(),
    'Produk' => DB::table('produk')->count(),
    'Alamat Pengguna' => DB::table('alamat_pengguna')->count(),
    'Pesanan' => DB::table('pesanan')->count(),
];

foreach ($counts as $table => $count) {
    echo "$table: $count records\n";
}

echo "\n=== PRODUK LIST ===\n\n";

$produk = DB::table('produk')
    ->join('kategori', 'produk.kategori_id', '=', 'kategori.id')
    ->select('produk.id', 'produk.nama', 'kategori.nama as kategori', 'produk.harga', 'produk.stok')
    ->get();

foreach ($produk as $p) {
    echo sprintf(
        "%d. %s (%s) - Rp%s - Stok: %d\n",
        $p->id,
        $p->nama,
        $p->kategori,
        number_format($p->harga, 0, ',', '.'),
        $p->stok
    );
}

echo "\n✅ Data seeding berhasil!\n";
