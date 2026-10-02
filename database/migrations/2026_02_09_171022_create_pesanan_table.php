<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pesanan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('nomor_pesanan')->unique();
            $table->foreignId('alamat_pengguna_id')->constrained('alamat_pengguna')->onDelete('cascade');
            $table->decimal('subtotal', 12, 2); // total harga produk
            $table->integer('total_berat'); // dalam gram
            $table->decimal('ongkir_wilayah', 10, 2);
            $table->decimal('ongkir_berat', 10, 2);
            $table->decimal('total_ongkir', 10, 2);
            $table->decimal('total_pembayaran', 12, 2); // subtotal + total_ongkir
            $table->enum('status_pesanan', ['pending', 'dibayar', 'diproses', 'dikirim', 'selesai', 'dibatalkan'])->default('pending');
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pesanan');
    }
};
