<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Keranjang extends Model
{
    protected $table = 'keranjang';

    protected $fillable = [
        'user_id',
        'session_id',
        'produk_id',
        'jumlah',
    ];

    protected $casts = [
        'jumlah' => 'integer',
    ];

    /**
     * Relationship: Keranjang belongs to User
     */
    public function pengguna()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Relationship: Keranjang belongs to Produk
     */
    public function produk()
    {
        return $this->belongsTo(Produk::class, 'produk_id');
    }

    /**
     * Accessor: Subtotal untuk item ini
     */
    public function getSubtotalAttribute()
    {
        return $this->produk->harga * $this->jumlah;
    }

    /**
     * Accessor: Total berat untuk item ini
     */
    public function getTotalBeratAttribute()
    {
        return $this->produk->berat * $this->jumlah;
    }
}
