<?php

use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Produk;
use App\Models\Pesanan;
use App\Models\DetailPesanan;
use App\Models\AlamatPengguna;
use App\Models\Pembayaran;

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== GENERATING DUMMY ORDERS ===\n\n";

// 1. Get Customer
$user = User::where('email', 'customer@test.com')->first();
if (!$user) {
    echo "Creating dummy customer...\n";
    $user = User::factory()->create([
        'name' => 'Customer Test',
        'email' => 'customer@test.com',
        'password' => bcrypt('password'),
        'role' => 'customer',
        'telpon' => '081234567890',
    ]);
}
echo "User: {$user->name} ({$user->email})\n";

// 2. Create Address if not exists
$alamat = AlamatPengguna::where('user_id', $user->id)->first();
if (!$alamat) {
    echo "Creating dummy address...\n";
    $alamat = AlamatPengguna::create([
        'user_id' => $user->id,
        'label' => 'Rumah',
        'nama_penerima' => $user->name,
        'telepon' => '081234567890',
        'alamat_lengkap' => 'Jl. Kertajaya No. 123',
        'kota' => 'Surabaya',
        'provinsi' => 'Jawa Timur',
        'kode_pos' => '60282',
        'wilayah_pengiriman' => 'luar_kota', // Surabaya != Jogja (assuming base is Jogja)
        'is_alamat_utama' => true,
    ]);
}
echo "Alamat: {$alamat->alamat_lengkap}\n";

// 3. Get Products
$products = Produk::where('is_active', true)->where('stok', '>', 0)->get();
if ($products->count() < 3) {
    die("Error: Need at least 3 active products to generate dummy data. Run seeders first.\n");
}

// 4. Generate Orders with different statuses
$statuses = [
    'pending' => ['payment' => 'pending', 'resi' => null],
    'dibayar' => ['payment' => 'success', 'resi' => null],
    'diproses' => ['payment' => 'success', 'resi' => null],
    'dikirim' => ['payment' => 'success', 'resi' => 'generate'],
    'selesai' => ['payment' => 'success', 'resi' => 'generate'],
    'dibatalkan' => ['payment' => 'failed', 'resi' => null],
];

foreach ($statuses as $statusPesanan => $config) {
    echo "Generating order with status: {$statusPesanan}...\n";
    
    // Pick 1-3 random products
    $orderProducts = $products->random(rand(1, 3));
    $subtotal = 0;
    $totalBerat = 0;
    
    // Calculate totals
    foreach ($orderProducts as $product) {
        $qty = rand(1, 2);
        $subtotal += $product->harga * $qty;
        $totalBerat += $product->berat * $qty;
    }
    
    $ongkir = 15000; // Flat rate for dummy
    $total = $subtotal + $ongkir;
    
    // Create Order
    $pesanan = Pesanan::create([
        'user_id' => $user->id,
        'nomor_pesanan' => Pesanan::generateNomorPesanan(),
        'nomor_resi' => $config['resi'] === 'generate' ? Pesanan::generateNomorResi() : null,
        'alamat_pengguna_id' => $alamat->id,
        'subtotal' => $subtotal,
        'total_berat' => $totalBerat,
        'ongkir_wilayah' => $ongkir,
        'ongkir_berat' => 0,
        'total_ongkir' => $ongkir,
        'total_pembayaran' => $total,
        'status_pesanan' => $statusPesanan,
        'tanggal_dikirim' => $config['resi'] ? now()->subDays(rand(1, 5)) : null,
        'catatan' => 'Dummy order generated for testing.',
        'created_at' => now()->subDays(rand(0, 30)), // Spread over last 30 days
    ]);
    
    // Create Order Details
    foreach ($orderProducts as $product) {
        $qty = rand(1, 2);
        DetailPesanan::create([
            'pesanan_id' => $pesanan->id,
            'produk_id' => $product->id,
            'nama_produk' => $product->nama,
            'harga_satuan' => $product->harga,
            'jumlah' => $qty,
            'berat_satuan' => $product->berat,
            'subtotal' => $product->harga * $qty,
        ]);
    }
    
    // Create Payment
    if ($config['payment'] !== 'pending' || $statusPesanan === 'pending') {
        // Map simplified status to Midtrans status
        $midtransStatus = match($config['payment']) {
            'success' => 'settlement',
            'pending' => 'pending',
            'failed' => 'expire',
            default => 'pending'
        };

        Pembayaran::create([
            'pesanan_id' => $pesanan->id,
            'midtrans_order_id' => 'MIDTRANS-' . uniqid(),
            'midtrans_transaction_id' => uniqid('TRX-'),
            'payment_type' => 'bank_transfer',
            'gross_amount' => $total,
            'transaction_status' => $midtransStatus,
            'fraud_status' => 'accept',
            'metadata' => json_encode(['bank' => 'bca', 'va_number' => '1234567890']),
            'paid_at' => $config['payment'] === 'success' ? $pesanan->created_at->addMinutes(rand(10, 60)) : null,
        ]);
    }
    
    echo "  -> Order {$pesanan->nomor_pesanan} created.\n";
}

echo "\n✅ Created " . count($statuses) . " dummy orders successfully!\n";
