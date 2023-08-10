<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class TransaksiPinjaman extends Model
{
    //
    protected $table = 'transaksi_pinjamen';
    protected $fillable = [
        'transaksi_harian_biaya_id',
        'lama_cicilan',
        'status',
        'angsuran_pinjaman',
        'angsuran_bulanan',
        'denda',
        'persetujuan',
    ];

    public function anggota()
    {
        return $this->belongsTo(Anggota::class,'anggota_id');
    }

    public function jatuh_tempo()
    {
        $count = ($this->angsuran_pinjaman / $this->angsuran_bulanan);
        $c = new Carbon($this->periode);
        $jt = $c->addMonths($count)->format('d/m/Y');
        return $jt;
    }

    public function transaksi_harian_biaya()
    {
        return $this->belongsTo(TransaksiHarianBiaya::class);
    }
}
