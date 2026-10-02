<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Wilayah pengiriman dihitung dari base location toko di Jogja:
     * - dalam_kota: Jogja/Yogyakarta
     * - luar_kota: Luar Jogja, dalam Pulau Jawa
     * - luar_pulau: Luar Pulau Jawa
     */
    public function up(): void
    {
        Schema::create('alamat_pengguna', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('label'); // contoh: "Rumah", "Kantor"
            $table->string('nama_penerima');
            $table->string('telepon');
            $table->text('alamat_lengkap');
            $table->string('kota');
            $table->string('provinsi');
            $table->string('kode_pos');
            $table->enum('wilayah_pengiriman', ['dalam_kota', 'luar_kota', 'luar_pulau']);
            $table->boolean('is_alamat_utama')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('alamat_pengguna');
    }
};
