<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Produk extends Model
{
    use SoftDeletes;

    protected $table = 'produk';

    protected $fillable = [
        'kategori_id',
        'nama',
        'slug',
        'deskripsi',
        'harga',
        'berat',
        'stok',
        'foto',
        'is_active',
    ];

    protected $casts = [
        'harga' => 'decimal:2',
        'berat' => 'integer',
        'stok' => 'integer',
        'is_active' => 'boolean',
    ];

    /**
     * Boot function untuk auto-generate slug
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($produk) {
            if (empty($produk->slug)) {
                $produk->slug = Str::slug($produk->nama);
            }
        });

        static::updating(function ($produk) {
            if ($produk->isDirty('nama')) {
                $produk->slug = Str::slug($produk->nama);
            }
        });
    }

    /**
     * Relationship: Produk belongs to Kategori
     */
    public function kategori()
    {
        return $this->belongsTo(Kategori::class, 'kategori_id');
    }

    /**
     * Relationship: Produk has many DetailPesanan
     */
    public function detailPesanan()
    {
        return $this->hasMany(DetailPesanan::class, 'produk_id');
    }

    /**
     * Scope: Query hanya produk aktif
     */
    public function scopeAktif($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope: Query produk yang tersedia (stok > 0 dan aktif)
     */
    public function scopeTersedia($query)
    {
        return $query->where('is_active', true)->where('stok', '>', 0);
    }

    /**
     * Accessor: Format harga dengan Rupiah
     */
    public function getHargaFormatAttribute()
    {
        return 'Rp ' . number_format($this->harga, 0, ',', '.');
    }

    /**
     * Accessor: Format berat dalam kg
     */
    public function getBeratKgAttribute()
    {
        return $this->berat / 1000;
    }

    /**
     * Method: Kurangi stok produk
     */
    public function kurangiStok($jumlah)
    {
        if ($this->stok < $jumlah) {
            throw new \Exception("Stok tidak mencukupi untuk produk {$this->nama}");
        }

        $this->decrement('stok', $jumlah);
    }

    /**
     * Method: Tambah stok produk
     */
    public function tambahStok($jumlah)
    {
        $this->increment('stok', $jumlah);
    }

    /**
     * Method: Check apakah produk tersedia
     */
    public function isTersedia($jumlah = 1)
    {
        return $this->is_active && $this->stok >= $jumlah;
    }
}
