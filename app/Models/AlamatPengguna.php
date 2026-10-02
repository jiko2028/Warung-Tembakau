<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AlamatPengguna extends Model
{
    protected $table = 'alamat_pengguna';

    protected $fillable = [
        'user_id',
        'label',
        'nama_penerima',
        'nomor_telepon',
        'provinsi',
        'kota',
        'kecamatan',
        'kode_pos',
        'alamat_lengkap',
        'is_default',
    ];

    protected $casts = [
        'is_default' => 'boolean',
    ];

    /**
     * Relationship: AlamatPengguna belongs to User
     */
    public function pengguna()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Relationship: AlamatPengguna has many Pesanan
     */
    public function pesanan()
    {
        return $this->hasMany(Pesanan::class, 'alamat_pengguna_id');
    }

    /**
     * Method: Set alamat ini sebagai alamat utama
     */
    public function setAsUtama()
    {
        // Set semua alamat user ini menjadi bukan utama
        self::where('user_id', $this->user_id)->update(['is_default' => false]);

        // Set alamat ini sebagai utama
        $this->update(['is_default' => true]);
    }

    /**
     * Accessor: Format lengkap alamat
     */
    public function getAlamatLengkapFormatAttribute()
    {
        return "{$this->alamat_lengkap}, {$this->kota}, {$this->provinsi} {$this->kode_pos}";
    }
}
