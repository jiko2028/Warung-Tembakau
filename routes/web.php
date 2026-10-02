<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    $featuredProducts = \App\Models\Produk::with('kategori')
        ->where('is_active', true)
        ->latest()
        ->take(8)
        ->get();
        
    return view('welcome', compact('featuredProducts'));
})->name('home');

Route::get('/tentang-kami', [\App\Http\Controllers\PageController::class, 'about'])->name('about');
Route::get('/kontak', [\App\Http\Controllers\PageController::class, 'contact'])->name('contact');
Route::post('/kontak', [\App\Http\Controllers\PageController::class, 'storeContact'])->name('contact.store');

Route::controller(\App\Http\Controllers\ProdukController::class)->group(function () {
    Route::get('/produk', 'index')->name('produk.index');
    Route::get('/produk/{slug}', 'show')->name('produk.show');
});

Route::middleware(['auth'])->group(function () {
    // Dashboard & Profile
    Route::get('/dashboard', [\App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Address Management
    Route::post('/alamat', [\App\Http\Controllers\AlamatPenggunaController::class, 'store'])->name('alamat.store');
    Route::patch('/alamat/{id}', [\App\Http\Controllers\AlamatPenggunaController::class, 'update'])->name('alamat.update');
    Route::delete('/alamat/{id}', [\App\Http\Controllers\AlamatPenggunaController::class, 'destroy'])->name('alamat.destroy');
    Route::post('/alamat/{id}/set-default', [\App\Http\Controllers\AlamatPenggunaController::class, 'setDefault'])->name('alamat.setDefault');
    
    // Cart & Checkout
    Route::resource('keranjang', \App\Http\Controllers\KeranjangController::class);
    Route::post('/keranjang/update', [\App\Http\Controllers\KeranjangController::class, 'updateQty'])->name('keranjang.updateQty');
    
    Route::get('/checkout', [\App\Http\Controllers\CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout/calculate-shipping', [\App\Http\Controllers\CheckoutController::class, 'calculateShipping'])->name('checkout.calculateShipping');
    Route::post('/checkout', [\App\Http\Controllers\CheckoutController::class, 'store'])->name('checkout.store');
    
    // Order History
    // Order History
    Route::post('/pesanan/{nomor_pesanan}/terima', [\App\Http\Controllers\PesananController::class, 'terima'])->name('pesanan.terima');
    Route::post('/pesanan/{nomor_pesanan}/refund', [\App\Http\Controllers\PesananController::class, 'updateRefund'])->name('pesanan.refund');
    Route::resource('pesanan', \App\Http\Controllers\PesananController::class)->only(['index', 'show']);

    // Payment & Midtrans
    Route::controller(\App\Http\Controllers\PembayaranController::class)->group(function () {
        Route::get('/pembayaran/finish', 'finish')->name('pembayaran.finish');
        Route::get('/pembayaran/{pesanan}', 'index')->name('pembayaran.index');
        Route::get('/pembayaran/{pesanan}/sukses', 'sukses')->name('pembayaran.sukses');
        Route::get('/pembayaran/{pesanan}/gagal', 'gagal')->name('pembayaran.gagal');
    });
});

// Callback (No Auth, No CSRF)
Route::post('/midtrans/callback', [\App\Http\Controllers\PembayaranController::class, 'callback'])->name('midtrans.callback');

// Admin Area
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');
    Route::resource('kategori', \App\Http\Controllers\Admin\KategoriController::class);
    Route::resource('produk', \App\Http\Controllers\Admin\ProdukController::class);
    Route::post('/produk/{produk}/toggle', [\App\Http\Controllers\Admin\ProdukController::class, 'toggleStatus'])->name('produk.toggle');
    Route::resource('pesanan', \App\Http\Controllers\Admin\PesananController::class);
    Route::get('/laporan/pendapatan', [\App\Http\Controllers\Admin\LaporanController::class, 'pendapatan'])->name('laporan.pendapatan');
    Route::get('/laporan/penjualan', [\App\Http\Controllers\Admin\LaporanController::class, 'penjualan'])->name('laporan.penjualan');
    Route::get('/pengaturan', [\App\Http\Controllers\Admin\SettingController::class, 'index'])->name('pengaturan.index');
    Route::put('/pengaturan', [\App\Http\Controllers\Admin\SettingController::class, 'update'])->name('pengaturan.update');
});

require __DIR__.'/auth.php';
