<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Produk;
use App\Models\Pesanan;
use App\Models\DetailPesanan;
use App\Models\AlamatPengguna;

class DummyOrderSeeder extends Seeder
{
    public function run()
    {
        $user = User::where('email', 'admin@warko.com')->first();
        if (!$user) {
            // Fallback if seeded user not found
            $user = User::factory()->create(['email' => 'customer@test.com', 'role' => 'pelanggan']);
        }
        
        $product = Produk::first();
        if (!$product) {
            // Fallback product
             $product = Produk::create([
                'nama' => 'Tembakau Test',
                'slug' => 'tembakau-test',
                'harga' => 50000,
                'stok' => 10,
                'berat' => 100,
                'deskripsi' => 'Test',
                'kategori_id' => 1
             ]);
        }

        // Create Address
        $address = AlamatPengguna::create([
            'user_id' => $user->id,
            'label' => 'Rumah',
            'nama_penerima' => 'Budi Test',
            'nomor_telepon' => '08123456789',
            'provinsi' => 'Jawa Timur',
            'kota' => 'Surabaya',
            'kecamatan' => 'Sukolilo',
            'kode_pos' => '60111',
            'alamat_lengkap' => 'Jl. Test No. 123',
            'is_default' => true,
        ]);

        // Create Cancelled Order
        $order = Pesanan::create([
            'user_id' => $user->id,
            'nomor_pesanan' => 'WRK-' . date('YmdHis') . '-CANCEL',
            'alamat_pengguna_id' => $address->id,
            'status_pesanan' => 'dibatalkan',
            'subtotal' => $product->harga,
            'total_berat' => $product->berat,
            'ongkir_wilayah' => 10000,
            'ongkir_berat' => 0,
            'total_ongkir' => 10000,
            'total_pembayaran' => $product->harga + 10000,
            'catatan' => "Dibatalkan: Stok ternyata habis saat packing.\n\nInfo Refund: Dana akan dikembalikan ke saldo OVO Anda dalam 1x24 jam.",
        ]);

        DetailPesanan::create([
            'pesanan_id' => $order->id,
            'produk_id' => $product->id,
            'jumlah' => 1,
            'harga_satuan' => $product->harga,
            'berat_satuan' => $product->berat,
            'subtotal' => $product->harga,
            'nama_produk' => $product->nama,
        ]);
        
        $this->command->info('Dummy cancelled order created for ' . $user->email);
    }
}
