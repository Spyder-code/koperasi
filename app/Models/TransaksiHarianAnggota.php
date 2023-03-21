<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransaksiHarianAnggota extends Model
{
    //

    protected $fillable = [
        'transaksi_harian_id',
        'anggota_id'
    ];

	public function anggota()
	{
		return $this->belongsTo(Anggota::class, 'anggota_id');
	}


	/*public function transaksi_harian()
	{
		return $this->hasOne('App\TransaksiHarian');
	}
	*/
}
