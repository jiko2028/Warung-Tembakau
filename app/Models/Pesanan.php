<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pesanan extends Model
{
    protected $table = 'pesanan';

    protected $fillable = [
        'user_id',
        'nomor_pesanan',
        'nomor_resi',
        'alamat_pengguna_id',
        'subtotal',
        'total_berat',
        'ongkir_wilayah',
        'ongkir_berat',
        'total_ongkir',
        'total_pembayaran',
        'kurir',
        'layanan',
        'status_pesanan',
        'tanggal_dikirim',
        'catatan',
        'alasan_batal',
        'info_refund',
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'total_berat' => 'integer',
        'ongkir_wilayah' => 'decimal:2',
        'ongkir_berat' => 'decimal:2',
        'total_ongkir' => 'decimal:2',
        'total_pembayaran' => 'decimal:2',
        'tanggal_dikirim' => 'datetime',
    ];

    /**
     * Boot function untuk auto-generate nomor pesanan
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($pesanan) {
            if (empty($pesanan->nomor_pesanan)) {
                $pesanan->nomor_pesanan = self::generateNomorPesanan();
            }
        });

        static::updated(function ($pesanan) {
            // Restore stock if status changes to 'dibatalkan'
            if ($pesanan->isDirty('status_pesanan') && $pesanan->status_pesanan === 'dibatalkan') {
                foreach ($pesanan->detailPesanan as $detail) {
                    if ($detail->produk) {
                        $detail->produk->increment('stok', $detail->jumlah);
                    }
                }

                // Send Notification on Cancellation
                try {
                    if ($pesanan->user) {
                        $pesanan->user->notify(new \App\Notifications\PesananDibatalkan(
                            $pesanan, 
                            $pesanan->alasan_batal ?? 'Pesanan dibatalkan oleh admin', 
                            $pesanan->info_refund
                        ));
                    }
                } catch (\Throwable $e) {
                    \Illuminate\Support\Facades\Log::error('Gagal kirim notif pembatalan: ' . $e->getMessage());
                }
            }
        });
    }

    /**
     * Generate nomor pesanan unik
     */
    public static function generateNomorPesanan()
    {
        $prefix = 'WRK';
        $date = date('Ymd');
        $random = strtoupper(substr(md5(uniqid(rand(), true)), 0, 6));

        return "{$prefix}{$date}{$random}";
    }

    /**
     * Generate nomor resi otomatis
     */
    public static function generateNomorResi()
    {
        $prefix = 'WARKO';
        $date = date('Ymd');
        
        // Get count of shipments today
        $count = self::whereDate('tanggal_dikirim', today())
            ->whereNotNull('nomor_resi')
            ->count() + 1;
        
        $sequential = str_pad($count, 5, '0', STR_PAD_LEFT);
        
        return "{$prefix}-{$date}-{$sequential}";
    }

    /**
     * Relationship: Pesanan belongs to User
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Relationship: Pesanan belongs to AlamatPengguna
     */
    public function alamatPengguna()
    {
        return $this->belongsTo(AlamatPengguna::class, 'alamat_pengguna_id');
    }

    /**
     * Relationship: Pesanan has many DetailPesanan
     */
    public function detailPesanan()
    {
        return $this->hasMany(DetailPesanan::class, 'pesanan_id');
    }

    /**
     * Relationship: Pesanan has one Pembayaran
     */
    public function pembayaran()
    {
        return $this->hasOne(Pembayaran::class, 'pesanan_id');
    }

    /**
     * Method: Update status pesanan
     */
    public function updateStatus($status)
    {
        $this->update(['status_pesanan' => $status]);
    }

    /**
     * Accessor: Format total pembayaran
     */
    public function getTotalPembayaranFormatAttribute()
    {
        return 'Rp ' . number_format($this->total_pembayaran, 0, ',', '.');
    }

    /**
     * Accessor: Format subtotal
     */
    public function getSubtotalFormatAttribute()
    {
        return 'Rp ' . number_format($this->subtotal, 0, ',', '.');
    }

    /**
     * Accessor: Format total ongkir
     */
    public function getTotalOngkirFormatAttribute()
    {
        return 'Rp ' . number_format($this->total_ongkir, 0, ',', '.');
    }

    /**
     * Accessor: Format berat dalam kg
     */
    public function getTotalBeratKgAttribute()
    {
        return $this->total_berat / 1000;
    }

    /**
     * Scope: Filter by status
     */
    public function scopeStatus($query, $status)
    {
        return $query->where('status_pesanan', $status);
    }
}
