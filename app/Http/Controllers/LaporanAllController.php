<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\Tanggal;
use App\Models\TransaksiHarian;
use Illuminate\Support\Facades\DB;
use App\Models\Anggota;
use App\Exports\LaporanKasBank;
use App\Exports\LaporanSimpanan;
use App\Exports\LaporanPinjaman;
use App\Exports\LaporanPerDivisi;
use Maatwebsite\Excel\Facades\Excel;
use App\Divisi;
use App\Exports\ExportSimpananAll;
use App\Exports\ExportPinjamanAll;
use App\Exports\LaporanSimpananAll;
use App\Exports\LaporanPinjamanAll;
use Auth;
use App\Models\Periode;

class LaporanAllController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function simpananAll(Request $request)
    {
        if (\Auth::user()->isAbleTo('manage-laporan-simpanan-all')) {
            $anggota = Anggota::where('status', 1)->get();
            $sum_pokok = 0;
            $transaksi_harian = 0;
            $sum_wajib = 0;
            $sum_sukarela = 0;
            $sum_kredit_simpanan = 0;
            if (request('search')) {
                $tgl_awal = Tanggal::convert_tanggal($request->start_date);
                $tgl_akhir = Tanggal::convert_tanggal($request->end_date);
                $date_before = date('Y-m-d', strtotime($tgl_awal . ' -1 day'));
                $periode_aktif = Periode::where('status', 1)->first();
                $sum_pokok = DB::table('transaksi_harians')
                        ->join('transaksi_harian_biayas', 'transaksi_harians.id', '=', 'transaksi_harian_biayas.transaksi_harian_id')
                        ->join('transaksi_harian_anggotas', 'transaksi_harians.id', '=', 'transaksi_harian_anggotas.transaksi_harian_id')
                        ->whereBetween('transaksi_harians.tgl', [$periode_aktif->open_date, $date_before])
                        ->where('transaksi_harian_anggotas.anggota_id', request('id_anggota'))
                        ->where('transaksi_harian_biayas.biaya_id', '1')
                        ->where('divisi_id', '1')
                        ->sum('transaksi_harian_biayas.nominal');
                $transaksi_harian = TransaksiHarian::with('transaksi_harian_biaya', 'transaksi_harian_anggota',
                            'sumPokok', 'sumWajib', 'sumSukarela', 'sumKredit')
                                ->whereHas('transaksi_harian_anggota', function($q){
                                    $q->where('anggota_id', request('id_anggota'));
                                })
                                ->whereBetween('tgl', [$tgl_awal, $tgl_akhir])
                                ->where('divisi_id', '1')
                                ->orderBy('tgl', 'ASC')
                                ->get();
                $sum_wajib = DB::table('transaksi_harians')
                                ->join('transaksi_harian_biayas', 'transaksi_harians.id', '=', 'transaksi_harian_biayas.transaksi_harian_id')
                                ->join('transaksi_harian_anggotas', 'transaksi_harians.id', '=', 'transaksi_harian_anggotas.transaksi_harian_id')
                                ->whereBetween('transaksi_harians.tgl', [$periode_aktif->open_date, $date_before])
                                ->where('transaksi_harian_anggotas.anggota_id', request('id_anggota'))
                                ->where('transaksi_harian_biayas.biaya_id', '2')
                                ->where('divisi_id', '1')
                                ->sum('transaksi_harian_biayas.nominal');
                $sum_sukarela = DB::table('transaksi_harians')
                                ->join('transaksi_harian_biayas', 'transaksi_harians.id', '=', 'transaksi_harian_biayas.transaksi_harian_id')
                                ->join('transaksi_harian_anggotas', 'transaksi_harians.id', '=', 'transaksi_harian_anggotas.transaksi_harian_id')
                                ->whereBetween('transaksi_harians.tgl', [$periode_aktif->open_date, $date_before])
                                ->where('transaksi_harian_anggotas.anggota_id', request('id_anggota'))
                                ->where('transaksi_harian_biayas.biaya_id', '3')
                                ->where('divisi_id', '1')
                                ->sum('transaksi_harian_biayas.nominal');
                $sum_kredit_simpanan = DB::table('transaksi_harians')
                                ->join('transaksi_harian_biayas', 'transaksi_harians.id', '=', 'transaksi_harian_biayas.transaksi_harian_id')
                                ->join('transaksi_harian_anggotas', 'transaksi_harians.id', '=', 'transaksi_harian_anggotas.transaksi_harian_id')
                                ->whereBetween('transaksi_harians.tgl', [$periode_aktif->open_date, $date_before])
                                ->where('transaksi_harian_anggotas.anggota_id', request('id_anggota'))
                                ->where('transaksi_harian_biayas.biaya_id', '4')
                                ->where('divisi_id', '1')
                                ->sum('transaksi_harian_biayas.nominal');
            }
            return view('laporan.simpanan-all', compact('anggota', 'sum_pokok', 'transaksi_harian', 'sum_wajib', 'sum_sukarela', 'sum_kredit_simpanan'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function validationSimpanan(Request $request)
    {
        if ($request->ajax()) {
            $validator = \Validator::make(
                $request->all(),
                [
                    'start_date' => 'required',
                    'end_date' => 'required',
                    "anggota"    => "required|array|min:1",
                    "anggota.*"  => "required",
                ]
            );

            if ($validator->fails()) {
                $messages = $validator->getMessageBag();
                return response()->json([
                    'error' => $messages->first()
                ]);
            }
        }
    }

    public function exportSimpanan(Request $request)
    {
        if ($request->ajax()) {
            $tgl_awal = Tanggal::convert_tanggal($request->start_date);
            $tgl_akhir = Tanggal::convert_tanggal($request->end_date);
            $date_before = date('Y-m-d', strtotime($tgl_awal . ' -1 day'));
            $periode_aktif = Periode::where('status', 1)->first();
            $anggota = $request->anggota;
            $anggotas = Anggota::whereIn('id', $anggota)->get();

            return Excel::download(new ExportSimpananAll($anggotas, $tgl_awal, $tgl_akhir, $date_before, $periode_aktif), Tanggal::tanggal_id($tgl_awal) . ' sampai ' . Tanggal::tanggal_id($tgl_akhir) . '-simpanan.xlsx');
        }
    }

    public function pinjamanAll(Request $request)
    {
        if (\Auth::user()->isAbleTo('manage-laporan-pinjaman-all')) {
            $anggota = Anggota::where('status', 1)->get();
            $sum_cicilan = 0;
            $transaksi_harian = [];
            $sum_kredit_pinjaman = 0;
            $sum_bunga = 0;
            if(request('search')){
                $tgl_awal = Tanggal::convert_tanggal($request->start_date);
                $tgl_akhir = Tanggal::convert_tanggal($request->end_date);
                $date_before = date('Y-m-d', strtotime($tgl_awal . ' -1 day'));
                $periode_aktif = Periode::where('status', 1)->first();
                $sum_cicilan = DB::table('transaksi_harians')
                    ->join('transaksi_harian_biayas', 'transaksi_harians.id', '=', 'transaksi_harian_biayas.transaksi_harian_id')
                    ->join('transaksi_harian_anggotas', 'transaksi_harians.id', '=', 'transaksi_harian_anggotas.transaksi_harian_id')
                    ->whereBetween('transaksi_harians.tgl', [$periode_aktif->open_date, $date_before])
                    ->where('transaksi_harian_anggotas.anggota_id', $request->id_anggota)
                    ->where('transaksi_harian_biayas.biaya_id', '6')
                    ->where('divisi_id', '2')
                    ->sum('transaksi_harian_biayas.nominal');
                $transaksi_harian = TransaksiHarian::with('transaksi_harian_biaya', 'transaksi_harian_anggota', 'sumCicilan', 'sumBunga', 'sumKreditPinjaman')
                    ->whereHas('transaksi_harian_anggota', function ($q) {
                        $q->where('anggota_id', request('id_anggota'));
                    })
                    ->whereBetween('tgl', [$tgl_awal, $tgl_akhir])
                    ->where('divisi_id', '2')
                    ->orderBy('tgl', 'ASC')
                    ->get();
                $sum_kredit_pinjaman = DB::table('transaksi_harians')
                    ->join('transaksi_harian_biayas', 'transaksi_harians.id', '=', 'transaksi_harian_biayas.transaksi_harian_id')
                    ->join('transaksi_harian_anggotas', 'transaksi_harians.id', '=', 'transaksi_harian_anggotas.transaksi_harian_id')
                    ->whereBetween('transaksi_harians.tgl', [$periode_aktif->open_date, $date_before])
                    ->where('transaksi_harian_anggotas.anggota_id', $request->id_anggota)
                    ->where('transaksi_harian_biayas.biaya_id', '8')
                    ->where('divisi_id', '2')
                    ->sum('transaksi_harian_biayas.nominal');
                $sum_bunga = DB::table('transaksi_harians')
                    ->join('transaksi_harian_biayas', 'transaksi_harians.id', '=', 'transaksi_harian_biayas.transaksi_harian_id')
                    ->join('transaksi_harian_anggotas', 'transaksi_harians.id', '=', 'transaksi_harian_anggotas.transaksi_harian_id')
                    ->whereBetween('transaksi_harians.tgl', [$periode_aktif->open_date, $date_before])
                    ->where('transaksi_harian_anggotas.anggota_id', $request->id_anggota)
                    ->where('transaksi_harian_biayas.biaya_id', '7')
                    ->where('divisi_id', '2')
                    ->sum('transaksi_harian_biayas.nominal');
            }
            return view('laporan.pinjaman-all', compact('anggota','sum_cicilan','transaksi_harian','sum_bunga','sum_kredit_pinjaman'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function validationPinjaman(Request $request)
    {
        if ($request->ajax()) {
            $validator = \Validator::make(
                $request->all(),
                [
                    'start_date' => 'required',
                    'end_date' => 'required',
                    "anggota"    => "required|array|min:1",
                    "anggota.*"  => "required",
                ]
            );

            if ($validator->fails()) {
                $messages = $validator->getMessageBag();
                return response()->json([
                    'error' => $messages->first()
                ]);
            }
        }
    }

    public function exportPinjaman(Request $request)
    {
        if ($request->ajax()) {
            $tgl_awal = Tanggal::convert_tanggal($request->start_date);
            $tgl_akhir = Tanggal::convert_tanggal($request->end_date);
            $date_before = date('Y-m-d', strtotime($tgl_awal . ' -1 day'));
            $periode_aktif = Periode::where('status', 1)->first();
            $anggota = $request->anggota;
            $anggotas = Anggota::whereIn('id', $anggota)->get();

            return Excel::download(new ExportPinjamanAll($anggotas, $tgl_awal, $tgl_akhir, $date_before, $periode_aktif), Tanggal::tanggal_id($tgl_awal) . ' sampai ' . Tanggal::tanggal_id($tgl_akhir) . '-Pinjaman.xlsx');
        }
    }
}
