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
        Schema::table('alamat_pengguna', function (Blueprint $table) {
            // Rename columns
            $table->renameColumn('telepon', 'nomor_telepon');
            $table->renameColumn('is_alamat_utama', 'is_default');
            
            // Add kecamatan column (after kota)
            $table->string('kecamatan')->after('kota')->nullable();
            
            // Drop wilayah_pengiriman (not used anymore)
            $table->dropColumn('wilayah_pengiriman');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('alamat_pengguna', function (Blueprint $table) {
            // Reverse the changes
            $table->renameColumn('nomor_telepon', 'telepon');
            $table->renameColumn('is_default', 'is_alamat_utama');
            $table->dropColumn('kecamatan');
            $table->enum('wilayah_pengiriman', ['dalam_kota', 'luar_kota', 'luar_pulau'])->after('kode_pos');
        });
    }
};
