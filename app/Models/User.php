<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Relationship: User has many AlamatPengguna
     */
    public function alamatPengguna()
    {
        return $this->hasMany(AlamatPengguna::class, 'user_id');
    }

    /**
     * Relationship: User has many Pesanan
     */
    public function pesanan()
    {
        return $this->hasMany(Pesanan::class, 'user_id');
    }

    /**
     * Relationship: User has many Keranjang
     */
    public function keranjang()
    {
        return $this->hasMany(Keranjang::class, 'user_id');
    }

    /**
     * Method: Get alamat utama user
     */
    public function alamatUtama()
    {
        return $this->alamatPengguna()->where('is_alamat_utama', true)->first();
    }

    /**
     * Method: Check apakah user adalah admin
     */
    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    /**
     * Method: Check apakah user adalah pelanggan
     */
    public function isPelanggan()
    {
        return $this->role === 'pelanggan';
    }
}
