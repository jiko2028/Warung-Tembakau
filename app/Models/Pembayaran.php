<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pembayaran extends Model
{
    protected $table = 'pembayaran';

    protected $fillable = [
        'pesanan_id',
        'midtrans_order_id',
        'midtrans_transaction_id',
        'payment_type',
        'gross_amount',
        'transaction_status',
        'fraud_status',
        'metadata',
        'paid_at',
    ];

    protected $casts = [
        'gross_amount' => 'decimal:2',
        'metadata' => 'array',
        'paid_at' => 'datetime',
    ];

    /**
     * Relationship: Pembayaran belongs to Pesanan
     */
    public function pesanan()
    {
        return $this->belongsTo(Pesanan::class, 'pesanan_id');
    }

    /**
     * Method: Check apakah pembayaran sudah lunas
     */
    public function isPaid()
    {
        return in_array($this->transaction_status, ['capture', 'settlement']);
    }

    /**
     * Method: Check apakah pembayaran pending
     */
    public function isPending()
    {
        return $this->transaction_status === 'pending';
    }

    /**
     * Method: Check apakah pembayaran gagal
     */
    public function isFailed()
    {
        return in_array($this->transaction_status, ['deny', 'cancel', 'expire']);
    }

    /**
     * Accessor: Format gross amount
     */
    public function getGrossAmountFormatAttribute()
    {
        return 'Rp ' . number_format($this->gross_amount, 0, ',', '.');
    }
}
