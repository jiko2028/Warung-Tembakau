<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailPesanan extends Model
{
    protected $table = 'detail_pesanan';

    protected $fillable = [
        'pesanan_id',
        'produk_id',
        'nama_produk',
        'harga_satuan',
        'berat_satuan',
        'jumlah',
        'subtotal',
    ];

    protected $casts = [
        'harga_satuan' => 'decimal:2',
        'berat_satuan' => 'integer',
        'jumlah' => 'integer',
        'subtotal' => 'decimal:2',
    ];

    /**
     * Relationship: DetailPesanan belongs to Pesanan
     */
    public function pesanan()
    {
        return $this->belongsTo(Pesanan::class, 'pesanan_id');
    }

    /**
     * Relationship: DetailPesanan belongs to Produk
     */
    public function produk()
    {
        return $this->belongsTo(Produk::class, 'produk_id');
    }

    /**
     * Accessor: Format harga satuan
     */
    public function getHargaSatuanFormatAttribute()
    {
        return 'Rp ' . number_format($this->harga_satuan, 0, ',', '.');
    }

    /**
     * Accessor: Format subtotal
     */
    public function getSubtotalFormatAttribute()
    {
        return 'Rp ' . number_format($this->subtotal, 0, ',', '.');
    }

    /**
     * Accessor: Total berat untuk item ini
     */
    public function getTotalBeratAttribute()
    {
        return $this->berat_satuan * $this->jumlah;
    }
}
