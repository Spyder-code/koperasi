<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransaksiPinjaman extends Model
{
    //

    protected $fillable = [
        'transaksi_harian_biaya_id',
        'lama_cicilan',
        'status',
        'angsuran_pinjaman',
        'angsuran_bulanan',
        'denda',
    ];

    public function transaksi_harian_biaya()
    {
        return $this->belongsTo(TransaksiHarianBiaya::class);
    }
}
