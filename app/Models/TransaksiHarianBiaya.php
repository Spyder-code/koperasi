<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransaksiHarianBiaya extends Model
{
    //
    protected $fillable = [
        'transaksi_harian_id',
        'biaya_id',
        'nominal'
    ];

    public function biaya()
    {
        return $this->belongsTo(Biaya::class, 'biaya_id');
    }

    public function transaksi_harian()
    {
        return $this->belongsTo(TransaksiHarian::class, 'transaksi_harian_id');
    }

    public function transaksi_pinjaman()
    {
        return $this->hasOne(TransaksiPinjaman::class, 'transaksi_harian_biaya_id');
    }
}
