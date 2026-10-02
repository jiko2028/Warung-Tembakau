<?php

use Illuminate\Support\Facades\DB;

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== CHECKING FOTO IN DATABASE ===\n\n";

$produkWithFoto = DB::table('produk')->whereNotNull('foto')->get(['id', 'nama', 'foto']);
$produkWithoutFoto = DB::table('produk')->whereNull('foto')->count();

echo "Produk DENGAN foto: " . $produkWithFoto->count() . "\n";
echo "Produk TANPA foto: " . $produkWithoutFoto . "\n\n";

if ($produkWithFoto->count() > 0) {
    echo "Detail produk yang punya foto:\n";
    foreach ($produkWithFoto as $p) {
        echo sprintf("%d. %s -> %s\n", $p->id, $p->nama, $p->foto);
    }
} else {
    echo "❌ TIDAK ADA produk yang punya foto di database!\n";
    echo "Ini sebabnya foto tidak muncul.\n\n";
    echo "File ada di storage tapi path belum tersimpan di database.\n";
}
