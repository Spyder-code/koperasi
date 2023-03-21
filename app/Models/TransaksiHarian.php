<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransaksiHarian extends Model
{
    //
    protected $fillable = [
        'divisi_id',
        'tgl',
        'jenis_pembayaran',
        'jenis_transaksi',
        'keterangan',
        'periode_id',
        'is_close'
    ];

    public function divisi()
    {
        return $this->belongsTo(Divisi::class);
    }

    public function transaksi_harian_biaya()
    {
        return $this->hasMany(TransaksiHarianBiaya::class);
    }

    public function transaksi_harian_anggota()
    {
        return $this->hasOne(TransaksiHarianAnggota::class);
    }

    public function sumPokok()
    {
        return $this->hasMany(TransaksiHarianBiaya::class)->with('biaya')
            ->whereHas('biaya', function($query){
                $query->where('biaya_id', '1');
            });
    }

    public function sumWajib()
    {
        return $this->hasMany(TransaksiHarianBiaya::class)->with('biaya')
            ->whereHas('biaya', function($query){
                $query->where('biaya_id', '2');
            });
    }

    public function sumSukarela()
    {
        return $this->hasMany(TransaksiHarianBiaya::class)->with('biaya')
            ->whereHas('biaya', function($query){
                $query->where('biaya_id', '3');
            });
    }

    public function sumKredit()
    {
        return $this->hasMany(TransaksiHarianBiaya::class)->with('biaya')
            ->whereHas('biaya', function($query){
                $query->where('biaya_id', '4');
            });
    }

    public function sumKreditAll()
    {
        return $this->hasMany(TransaksiHarianBiaya::class)->with('biaya')
            ->whereHas('biaya', function($query){
                $query->where('jenis_biaya', '2');
            });
    }

    public function sumDebitAll()
    {
        return $this->hasMany(TransaksiHarianBiaya::class)->with('biaya')
            ->whereHas('biaya', function($query){
                $query->where('jenis_biaya', '1');
            });
    }

    public function sumCicilan()
    {
        return $this->hasMany(TransaksiHarianBiaya::class)->with('biaya')
            ->whereHas('biaya', function($query){
                $query->where('biaya_id', '6');
            });
    }

    public function sumBunga()
    {
        return $this->hasMany(TransaksiHarianBiaya::class)->with('biaya')
            ->whereHas('biaya', function($query){
                $query->where('biaya_id', '7');
            });
    }

    public function sumKreditPinjaman()
    {
        return $this->hasMany(TransaksiHarianBiaya::class)->with('biaya')
            ->whereHas('biaya', function($query){
                $query->where('biaya_id', '8');
            });
    }

    public function nama_anggota()
    {
        return $this->hasOne(TransaksiHarianAnggota::class, 'transaksi_harian_id');
    }

}
