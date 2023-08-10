<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransaksiSimpanan extends Model
{
    use HasFactory;
    protected $table = 'transaksi_simpanan';
    protected $fillable = [
        'anggota_id',
        'transaksi_harian_biaya_id',
        'tanggal_awal',
        'tanggal_akhir',
        'lama_simpanan',
        'simpanan_pinjaman',
        'simpanan_bulanan',
        'status',
    ];

    public function transaksi_harian_biaya()
    {
        return $this->belongsTo(TransaksiHarianBiaya::class);
    }
}
