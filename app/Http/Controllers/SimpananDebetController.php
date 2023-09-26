<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DataTables;
use App\Models\TransaksiHarian;
use App\Helpers\Tanggal;
use App\Models\Periode;
use App\Models\TransaksiHarianAnggota;
use App\Models\TransaksiHarianBiaya;
use App\Helpers\Money;
use Maatwebsite\Excel\Facades\Excel;
use File;
use App\Imports\SimpananDebet;
use Session;
use Illuminate\Support\Facades\DB;
use App\Helpers\Pembukuan;
use App\Models\Anggota;
use App\Models\TransaksiSimpanan;
use Illuminate\Support\Carbon;

class SimpananDebetController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
        if (\Auth::user()->isAbleTo('manage-debet-simpanan')) {
            # code...
            if ($request->ajax()) {
                $transaksiHarian = TransaksiHarian::with([
                    'divisi', 'nama_anggota' => function ($sql) {
                        $sql->with('anggota');
                    }, 'sumPokok', 'sumWajib', 'sumSukarela'
                ])
                    ->where('divisi_id', '1')
                    ->where('jenis_transaksi', '1');
                return DataTables::of($transaksiHarian)
                    ->editColumn('tgl', function ($transaksiHarian) {
                        return Tanggal::tanggal_id($transaksiHarian->tgl);
                    })
                    ->editColumn('jenis_pembayaran', function ($transaksiHarian) {
                        if ($transaksiHarian->jenis_pembayaran == '1') {
                            return '<span class="badge badge-success badge-pill">Cash</span>';
                        } else {
                            return '<span class="badge badge-danger badge-pill">Bank</span>';
                        }
                    })
                    ->editColumn('jenis_transaksi', function ($transaksiHarian) {
                        return '<span class="badge badge-primary badge-pill">Tambah Simpanan</span>';
                    })
                    ->editColumn('file', function ($transaksiHarian) {
                        return '<a href="'.asset($transaksiHarian->file).'" target="d_blank">
                            <img src="'.asset($transaksiHarian->file).'" style="height:50px; width:50px;"/>
                        </a>';
                    })
                    ->addColumn('action', function ($transaksiHarian) {
                        return view('datatable._action-transaction', [
                            'model' => $transaksiHarian,
                            'form_url' => route('simpanan-debet.destroy', $transaksiHarian->id),
                            'edit_url' => route('simpanan-debet.edit', $transaksiHarian->id),
                            'can_edit' => 'edit-debet-simpanan',
                            'can_delete' => 'delete-debet-simpanan',
                            'confirm_message' => 'Apakah anda yakin mau Transaksi'
                        ]);
                    })
                    ->editColumn('sumPokok', function ($transaksiHarian) {
                        return Money::stringToRupiah($transaksiHarian->sumPokok->sum('nominal'));
                    })
                    ->editColumn('sumWajib', function ($transaksiHarian) {
                        return Money::stringToRupiah($transaksiHarian->sumWajib->sum('nominal'));
                    })
                    ->editColumn('sumSukarela', function ($transaksiHarian) {
                        return Money::stringToRupiah($transaksiHarian->sumSukarela->sum('nominal'));
                    })
                    ->editColumn('is_close', function ($transaksiHarian) {
                        if ($transaksiHarian->is_close == '0') {
                            return '<p class="text-primary">Aktif</p>';
                        } else {
                            return '<p class="text-danger">None Aktif</p>';
                        }
                    })
                    ->rawColumns(['jenis_pembayaran', 'jenis_transaksi', 'action', 'is_close','file'])
                    ->make(true);
            }
            return view('simpanan-debet.index');
        } else {
            # code...
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        if (\Auth::user()->isAbleTo('create-debet-simpanan')) {
            # code...
            return view('simpanan-debet.create');
        } else {
            # code...
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        if (\Auth::user()->isAbleTo('create-debet-simpanan')) {
            # code...
            $this->validate($request, [
                'anggota_id' => 'required',
                'file' => 'required',
                'tgl' => 'required',
                'divisi_id' => 'required',
                'jenis_transaksi' => 'required'
            ]);

            $file_name = date('ymdhis').'.jpg';
            if($request->file('file')){
                $request->file('file')->storeAs('public/transaksi/', $file_name);
            }
            //Save Transaction Kopkar
            $periode = Periode::where('status', '1')->first();
            $transaksiHarian = new TransaksiHarian();
            $transaksiHarian->tgl = Tanggal::convert_tanggal($request->tgl);
            $transaksiHarian->divisi_id = $request->divisi_id;
            $transaksiHarian->jenis_pembayaran = $request->jenis_pembayaran;
            $transaksiHarian->jenis_transaksi = $request->jenis_transaksi;
            $transaksiHarian->keterangan = $request->keterangan;
            $transaksiHarian->periode_id = $periode->id;
            if($request->file('file')){
                $transaksiHarian->file = 'storage/transaksi/'.$file_name;
            }
            $transaksiHarian->save();

            //Save Transation Member Kopkar
            $transaksi_harian_anggota = new TransaksiHarianAnggota();
            $transaksi_harian_anggota->transaksi_harian_id = $transaksiHarian->id;
            $transaksi_harian_anggota->anggota_id = $request->anggota_id;
            $transaksi_harian_anggota->save();
            $biaya = $request->biaya;

            //Store Biaya Pokok
            $transaksi_biaya = new TransaksiHarianBiaya();
            $transaksi_biaya->biaya_id = $request->id_biaya_pokok;
            $transaksi_biaya->transaksi_harian_id = $transaksiHarian->id;
            $transaksi_biaya->nominal = Money::rupiahToString($request->nominal_biaya_pokok);
            $transaksi_biaya->save();

            //Store Biaya Wajib
            $transaksi_biaya = new TransaksiHarianBiaya();
            $transaksi_biaya->biaya_id = $request->id_biaya_wajib;
            $transaksi_biaya->transaksi_harian_id = $transaksiHarian->id;
            $transaksi_biaya->nominal = Money::rupiahToString($request->nominal_biaya_wajib);
            $transaksi_biaya->save();

            //Store Biaya Sukarela
            $sukarela_price = Money::rupiahToString($request->nominal_biaya_sukarela);
            $transaksi_biaya = new TransaksiHarianBiaya();
            $transaksi_biaya->biaya_id = $request->id_biaya_sukarela;
            $transaksi_biaya->transaksi_harian_id = $transaksiHarian->id;
            $transaksi_biaya->nominal = $sukarela_price;
            $transaksi_biaya->save();

            if($sukarela_price>0 && $request->lama_simpanan>0){
                $count = (int)$request->lama_simpanan;
                $tgl_awal = Tanggal::convert_tanggal($request->tgl);
                $c = new Carbon($tgl_awal);
                $tgl_akhir = $c->addMonths($request->lama_simpanan)->format('Y-m-d');
                $fee = 0;
                if($count==3){
                    $fee = 0.01;
                }else if($count==6){
                    $fee = 0.02;
                }else if($count==9){
                    $fee = 0.03;
                }else if($count==12){
                    $fee = 0.04;
                }
                $total = ($sukarela_price * $fee) + $sukarela_price;
                $price = $total / $count;
                TransaksiSimpanan::create([
                    'anggota_id' => $request->anggota_id,
                    'transaksi_harian_biaya_id' => $transaksi_biaya->id,
                    'tanggal_awal' => $tgl_awal,
                    'tanggal_akhir' => $tgl_akhir,
                    'lama_simpanan' => $request->lama_simpanan,
                    'simpanan_pinjaman' => $fee,
                    'simpanan_bulanan' => $price,
                    'status' => 0,
                ]);
            }
            //Sent Session To VIEW
            Session::flash("flash_notification", [
                "level" => "success",
                "message" => "Berhasil Merubah Data Transaksi !!!"
            ]);
            activity()->log('Menambahkan Data Simpanan');

            return redirect()->route('simpanan-debet.index');
        } else {
            # code...
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
        if (\Auth::user()->isAbleTo('edit-debet-simpanan')) {
            # code...
            $transaksiHarian = DB::table('transaksi_harians')
                ->join('transaksi_harian_biayas', 'transaksi_harian_biayas.transaksi_harian_id', '=', 'transaksi_harians.id')
                ->join('transaksi_harian_anggotas', 'transaksi_harian_anggotas.transaksi_harian_id', '=', 'transaksi_harians.id')
                ->where('transaksi_harians.id', $id)
                ->first();

            $nominal_biaya_pokok = DB::table('transaksi_harian_biayas')
                ->where('transaksi_harian_id', $id)
                ->where('biaya_id', '1')
                ->sum('nominal');
            $nominal_biaya_wajib = DB::table('transaksi_harian_biayas')
                ->where('transaksi_harian_id', $id)
                ->where('biaya_id', '2')
                ->sum('nominal');
            $nominal_biaya_sukarela = DB::table('transaksi_harian_biayas')
                ->where('transaksi_harian_id', $id)
                ->where('biaya_id', '3')
                ->sum('nominal');
            $nominal_biaya_pokok = Money::stringToRupiah($nominal_biaya_pokok ?? 0);
            $nominal_biaya_wajib = Money::stringToRupiah($nominal_biaya_wajib ?? 0);
            $nominal_biaya_sukarela = Money::stringToRupiah($nominal_biaya_sukarela ?? 0);
            $transaksiHarian->nominal = Money::stringToRupiah($transaksiHarian->nominal ?? 0);
            $transaksiHarian->tgl = date('d-m-Y', strtotime($transaksiHarian->tgl));
            return view('simpanan-debet.edit')->with(compact('transaksiHarian', 'nominal_biaya_pokok', 'nominal_biaya_wajib', 'nominal_biaya_sukarela'));
        } else {
            # code...
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
        if (\Auth::user()->isAbleTo('edit-debet-simpanan')) {
            # code...
            $this->validate($request, [
                'tgl' => 'required',
                'divisi_id' => 'required',
                'jenis_transaksi' => 'required'
            ]);

            //Save Transaction Kopkar
            $transaksiHarian = TransaksiHarian::find($id);
            $transaksiHarian->tgl = Tanggal::convert_tanggal($request->tgl);
            $transaksiHarian->jenis_pembayaran = $request->jenis_pembayaran;
            $transaksiHarian->jenis_transaksi = $request->jenis_transaksi;
            $transaksiHarian->keterangan = $request->keterangan;
            $transaksiHarian->update();

            //Save Transation Member Kopkar
            $transaksi_harian_anggota = TransaksiHarianAnggota::where('transaksi_harian_id', $id)->first();
            $transaksi_harian_anggota->anggota_id = $request->anggota_id;
            $transaksi_harian_anggota->update();

            $transaksi_biaya = TransaksiHarianBiaya::where('transaksi_harian_id', $id)->where('biaya_id', '1')->first();
            $transaksi_biaya->nominal = Money::rupiahToString($request->nominal_biaya_pokok);
            $transaksi_biaya->update();

            $transaksi_biaya = TransaksiHarianBiaya::where('transaksi_harian_id', $id)->where('biaya_id', '2')->first();
            $transaksi_biaya->nominal = Money::rupiahToString($request->nominal_biaya_wajib);
            $transaksi_biaya->update();

            $transaksi_biaya = TransaksiHarianBiaya::where('transaksi_harian_id', $id)->where('biaya_id', '3')->first();
            $transaksi_biaya->nominal = Money::rupiahToString($request->nominal_biaya_sukarela);
            $transaksi_biaya->update();

            Session::flash("flash_notification", [
                "level" => "success",
                "message" => "Berhasil Merubah Data Transaksi !!!"
            ]);
            activity()->log('Merubah Data Simpanan');

            return redirect()->route('simpanan-debet.index');
        } else {
            # code...
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        if(\Auth::user()->isAbleTo('delete-debet-simpanan'))
        {
            TransaksiHarianAnggota::where('transaksi_harian_id', $id)->delete();
            TransaksiHarianBiaya::where('transaksi_harian_id', $id)->delete();
            TransaksiHarian::find($id)->delete();
            Session::flash("flash_notification", [
                "level" => "success",
                "message" => "Berhasil Menghapus Transaksi !!!"
            ]);
            activity()->log('Menghapus Data Simpanan');
            return redirect()->route('simpanan-debet.index');
        }else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function upload()
    {
        if (\Auth::user()->isAbleTo('upload-debet-simpanan')) {
            # code...
            return view('simpanan-debet.upload');
        } else {
            # code...
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function doUpload(Request $request)
    {
        $this->validate($request, [
            'file' => 'required'
        ]);
        $periode = Periode::where('status', '1')->first();
        // import data
        try {
            Excel::import(new SimpananDebet($periode), request()->file('file'));
            activity()->log('Upload Data Simpanan Debet');
            return redirect()->route('simpanan-debet.index')->with('success', __('Data Simpanan Debet Telah Sukses Di Tambahkan'));
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            $failures = $e->failures();
            foreach ($failures as $failure) {
                $failure->row(); // row that went wrong
                $failure->attribute(); // either heading key (if using heading row concern) or column index
                $failure->errors(); // Actual error messages from Laravel validator
                foreach($failure->errors() as $key)
                {
                    return redirect()->route('simpanan-debet.index')->with('error', $key);
                }
                $failure->values(); // The values of the row that has failed.
            }
        }
    }

    public function closeBook()
    {
        $periode = Periode::where('status', '1')->first();
        $want_close = Periode::where('status', 2)->first();
        $anggota = Anggota::select()->where('status', 1)->get();
        foreach ($anggota as $row) {
            $transaksiHarian = new TransaksiHarian();
            $transaksiHarian->tgl = Tanggal::convert_tanggal($periode->open_date);
            $transaksiHarian->divisi_id = '1';
            $transaksiHarian->jenis_pembayaran = '1';
            $transaksiHarian->jenis_transaksi = '1';
            $transaksiHarian->keterangan = 'Saldo Awal Periode ' . $periode->name;
            $transaksiHarian->periode_id = $periode->id;
            $transaksiHarian->save();
            //SUMP SIMPANAN POKOK
            $sum_pokok = DB::table('transaksi_harians')
                ->join('transaksi_harian_biayas', 'transaksi_harians.id', '=', 'transaksi_harian_biayas.transaksi_harian_id')
                ->join('transaksi_harian_anggotas', 'transaksi_harians.id', '=', 'transaksi_harian_anggotas.transaksi_harian_id')
                ->whereBetween('transaksi_harians.tgl', [$want_close->open_date, $want_close->close_date])
                ->where('transaksi_harian_anggotas.anggota_id', $row->id)
                ->where('transaksi_harian_biayas.biaya_id', '1')
                ->where('divisi_id', '1')
                ->sum('transaksi_harian_biayas.nominal');
            //Store Simpanan Pokok
            $transaksi_biaya = new TransaksiHarianBiaya();
            $transaksi_biaya->biaya_id = '1';
            $transaksi_biaya->transaksi_harian_id = $transaksiHarian->id;
            $transaksi_biaya->nominal = $sum_pokok;
            $transaksi_biaya->save();
            //SUM SIMPANAN WAJIB
            $sum_wajib = DB::table('transaksi_harians')
                ->join('transaksi_harian_biayas', 'transaksi_harians.id', '=', 'transaksi_harian_biayas.transaksi_harian_id')
                ->join('transaksi_harian_anggotas', 'transaksi_harians.id', '=', 'transaksi_harian_anggotas.transaksi_harian_id')
                ->whereBetween('transaksi_harians.tgl', [$want_close->open_date, $want_close->close_date])
                ->where('transaksi_harian_anggotas.anggota_id', $row->id)
                ->where('transaksi_harian_biayas.biaya_id', '2')
                ->where('divisi_id', '1')
                ->sum('transaksi_harian_biayas.nominal');
            //SIMPANAN Biaya Wajib
            $transaksi_biaya = new TransaksiHarianBiaya();
            $transaksi_biaya->biaya_id = '2';
            $transaksi_biaya->transaksi_harian_id = $transaksiHarian->id;
            $transaksi_biaya->nominal = $sum_wajib;
            $transaksi_biaya->save();
            //SUM SIMPANAN SUKARELA
            $sum_sukarela = DB::table('transaksi_harians')
                ->join('transaksi_harian_biayas', 'transaksi_harians.id', '=', 'transaksi_harian_biayas.transaksi_harian_id')
                ->join('transaksi_harian_anggotas', 'transaksi_harians.id', '=', 'transaksi_harian_anggotas.transaksi_harian_id')
                ->whereBetween('transaksi_harians.tgl', [$want_close->open_date, $want_close->close_date])
                ->where('transaksi_harian_anggotas.anggota_id', $row->id)
                ->where('transaksi_harian_biayas.biaya_id', '3')
                ->where('divisi_id', '1')
                ->sum('transaksi_harian_biayas.nominal');

            //SIMPANAN Sukarela
            $transaksi_biaya = new TransaksiHarianBiaya();
            $transaksi_biaya->biaya_id = '3';
            $transaksi_biaya->transaksi_harian_id = $transaksiHarian->id;
            $transaksi_biaya->nominal = $sum_sukarela;
            $transaksi_biaya->save();

            //Save Transation Member Kopkar
            $transaksi_harian_anggota = new TransaksiHarianAnggota();
            $transaksi_harian_anggota->transaksi_harian_id = $transaksiHarian->id;
            $transaksi_harian_anggota->anggota_id = $row->id;
            $transaksi_harian_anggota->save();

            //Update Simpanan All
            $transaksi_harian = DB::table('transaksi_harians')
                ->join('transaksi_harian_biayas', 'transaksi_harians.id', '=', 'transaksi_harian_biayas.transaksi_harian_id')
                ->join('transaksi_harian_anggotas', 'transaksi_harians.id', '=', 'transaksi_harian_anggotas.transaksi_harian_id')
                ->whereBetween('transaksi_harians.tgl', [$want_close->open_date, $want_close->close_date])
                ->where('transaksi_harian_anggotas.anggota_id', $row->id)
                ->where('divisi_id', '1')
                ->whereIn('transaksi_harian_biayas.biaya_id', ['1', '2', '3'])
                ->select('transaksi_harians.id as id')
                ->get();

            foreach ($transaksi_harian as $item) {
                $transaksi_harian = TransaksiHarian::find($item->id);
                $transaksi_harian->is_close = '1';
                $transaksi_harian->update();
            }
        }
        activity()->log('Tutup Buku Data Simpanan');
        return redirect()->route('simpanan-debet.index');
    }
}
