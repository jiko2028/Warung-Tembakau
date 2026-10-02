<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Kategori extends Model
{
    protected $table = 'kategori';

    protected $fillable = [
        'nama',
        'slug',
        'deskripsi',
        'foto',
    ];

    /**
     * Boot function untuk auto-generate slug
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($kategori) {
            if (empty($kategori->slug)) {
                $kategori->slug = Str::slug($kategori->nama);
            }
        });

        static::updating(function ($kategori) {
            if ($kategori->isDirty('nama')) {
                $kategori->slug = Str::slug($kategori->nama);
            }
        });
    }

    /**
     * Relationship: Kategori has many Produk
     */
    public function produk()
    {
        return $this->hasMany(Produk::class, 'kategori_id');
    }
}
