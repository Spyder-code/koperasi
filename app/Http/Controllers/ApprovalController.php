<?php

namespace App\Http\Controllers;

use App\Models\TransaksiHarian;
use Illuminate\Http\Request;
use Session;

class ApprovalController extends Controller
{
    public function simpanan()
    {
        // Ambil (debit)
        $data1 = TransaksiHarian::where('status',0)->where('jenis_transaksi',2)->where('divisi_id',1)->get();
        // Simpan (credit)
        $data2 = TransaksiHarian::where('status',0)->where('jenis_transaksi',1)->where('divisi_id',1)->get();
        return view('approval.simpanan', compact('data1','data2'));
    }

    public function pinjaman()
    {
        // Ambil
        $data1 = TransaksiHarian::where('status',0)->where('jenis_transaksi',2)->where('divisi_id',2)->get();
        // Bayar
        $data2 = TransaksiHarian::where('status',0)->where('jenis_transaksi',1)->where('divisi_id',2)->get();
        return view('approval.pinjaman', compact('data1','data2'));
    }

    public function update(Request $request, TransaksiHarian $trx)
    {
        $trx->update($request->all());
        Session::flash("flash_notification", [
            "level" => "success",
            "message" => "Approve Berhasil !!!"
        ]);
        return back();
    }
}
