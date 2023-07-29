<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Anggota;
use App\Models\Divisi;
use Illuminate\Support\Facades\DB;
use Options;
use App\Models\Periode;
use App\Models\TransaksiHarian;
use App\Models\ActivityLog;
use App\Models\TransaksiPinjaman;
use App\Models\UserAnggota;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $user = Auth::user();
        $anggota = UserAnggota::where('user_id',Auth::id())->first();
        $periode = Periode::select()->where('status', 1)->first();
        $anggota_aktif = Anggota::select()->where('status', 1)->count();
        $anggota_disable = Anggota::select()->where('status', 0)->count();
        $status_anggota = [$anggota_aktif, $anggota_disable];
        if($user->roles->pluck( 'name' )->contains( 'admin' ) || $user->roles->pluck( 'name' )->contains( 'ketua') || $user->roles->pluck( 'name' )->contains( 'operator' ))
        {
            $transaksi_pinjam = TransaksiPinjaman::select('id','status')->get();
            $transaksi_harian = TransaksiHarian::with(['sumKreditAll', 'sumDebitAll'])
                ->select(['id', 'tgl', 'keterangan', 'jenis_transaksi'])
                ->orderBy('tgl', 'DESC')
                ->limit(10)
                ->get();
            $sum_pokok = DB::table('transaksi_harians')
                        ->join('transaksi_harian_biayas', 'transaksi_harians.id', '=', 'transaksi_harian_biayas.transaksi_harian_id')
                        ->join('transaksi_harian_anggotas', 'transaksi_harians.id', '=', 'transaksi_harian_anggotas.transaksi_harian_id')
                        // ->whereBetween('transaksi_harians.tgl', [$periode->open_date, $periode->close_date])
                        ->whereMonth('transaksi_harians.tgl',date('m'))
                        ->whereYear('transaksi_harians.tgl',date('Y'))
                        ->where('transaksi_harian_biayas.biaya_id', '1')
                        ->where('divisi_id', '1')
                        ->sum('transaksi_harian_biayas.nominal');
            $sum_wajib = DB::table('transaksi_harians')
                        ->join('transaksi_harian_biayas', 'transaksi_harians.id', '=', 'transaksi_harian_biayas.transaksi_harian_id')
                        ->join('transaksi_harian_anggotas', 'transaksi_harians.id', '=', 'transaksi_harian_anggotas.transaksi_harian_id')
                        // ->whereBetween('transaksi_harians.tgl', [$periode->open_date, $periode->close_date])
                        ->whereMonth('transaksi_harians.tgl',date('m'))
                        ->whereYear('transaksi_harians.tgl',date('Y'))
                        ->where('transaksi_harian_biayas.biaya_id', '2')
                        ->where('divisi_id', '1')
                        ->sum('transaksi_harian_biayas.nominal');
            $sum_sukarela = DB::table('transaksi_harians')
                        ->join('transaksi_harian_biayas', 'transaksi_harians.id', '=', 'transaksi_harian_biayas.transaksi_harian_id')
                        ->join('transaksi_harian_anggotas', 'transaksi_harians.id', '=', 'transaksi_harian_anggotas.transaksi_harian_id')
                        // ->whereBetween('transaksi_harians.tgl', [$periode->open_date, $periode->close_date])
                        ->whereMonth('transaksi_harians.tgl',date('m'))
                        ->whereYear('transaksi_harians.tgl',date('Y'))
                        ->where('transaksi_harian_biayas.biaya_id', '3')
                        ->where('divisi_id', '1')
                        ->sum('transaksi_harian_biayas.nominal');
            $kredit_simpanan = DB::table('transaksi_harians')
                        ->join('transaksi_harian_biayas', 'transaksi_harians.id', '=', 'transaksi_harian_biayas.transaksi_harian_id')
                        ->join('transaksi_harian_anggotas', 'transaksi_harians.id', '=', 'transaksi_harian_anggotas.transaksi_harian_id')
                        ->whereBetween('transaksi_harians.tgl', [$periode->open_date, $periode->close_date])
                        ->where('transaksi_harian_biayas.biaya_id', '4')
                        ->where('divisi_id', '1')
                        ->sum('transaksi_harian_biayas.nominal');
            $debet_pinjaman = DB::table('transaksi_harians')
                        ->join('transaksi_harian_biayas', 'transaksi_harians.id', '=', 'transaksi_harian_biayas.transaksi_harian_id')
                        ->join('transaksi_harian_anggotas', 'transaksi_harians.id', '=', 'transaksi_harian_anggotas.transaksi_harian_id')
                        // ->whereBetween('transaksi_harians.tgl', [$periode->open_date, $periode->close_date])
                        ->whereMonth('transaksi_harians.tgl',date('m'))
                        ->whereYear('transaksi_harians.tgl',date('Y'))
                        ->where('transaksi_harian_biayas.biaya_id', '8')
                        ->where('divisi_id', '2')
                        ->sum('transaksi_harian_biayas.nominal');
            $jt_pelunasan = TransaksiPinjaman::whereMonth('periode',date('m'))
                        ->whereYear('periode',date('Y'))
                        ->where('status',0  )
                        ->sum('angsuran_bulanan');
            $jt_pelunasan_dibayar = DB::table('transaksi_harians')
                        ->join('transaksi_harian_biayas', 'transaksi_harians.id', '=', 'transaksi_harian_biayas.transaksi_harian_id')
                        ->join('transaksi_harian_anggotas', 'transaksi_harians.id', '=', 'transaksi_harian_anggotas.transaksi_harian_id')
                        // ->whereBetween('transaksi_harians.tgl', [$periode->open_date, $periode->close_date])
                        ->whereMonth('transaksi_harians.tgl',date('m'))
                        ->whereYear('transaksi_harians.tgl',date('Y'))
                        ->where('transaksi_harian_biayas.biaya_id', '6')
                        ->where('divisi_id', '2')
                        ->sum('transaksi_harian_biayas.nominal');

            $bunga_pinjaman = DB::table('transaksi_harians')
                        ->join('transaksi_harian_biayas', 'transaksi_harians.id', '=', 'transaksi_harian_biayas.transaksi_harian_id')
                        ->join('transaksi_harian_anggotas', 'transaksi_harians.id', '=', 'transaksi_harian_anggotas.transaksi_harian_id')
                        ->whereBetween('transaksi_harians.tgl', [$periode->open_date, $periode->close_date])
                        ->where('transaksi_harian_biayas.biaya_id', '7')
                        ->where('divisi_id', '2')
                        ->sum('transaksi_harian_biayas.nominal');
            $kredit_pinjaman = DB::table('transaksi_harians')
                        ->join('transaksi_harian_biayas', 'transaksi_harians.id', '=', 'transaksi_harian_biayas.transaksi_harian_id')
                        ->join('transaksi_harian_anggotas', 'transaksi_harians.id', '=', 'transaksi_harian_anggotas.transaksi_harian_id')
                        ->whereBetween('transaksi_harians.tgl', [$periode->open_date, $periode->close_date])
                        ->where('transaksi_harian_biayas.biaya_id', '8')
                        ->where('divisi_id', '2')
                        ->sum('transaksi_harian_biayas.nominal');
            $kas_kredit = DB::table('transaksi_harians')
                        ->join('transaksi_harian_biayas', 'transaksi_harians.id', '=', 'transaksi_harian_biayas.transaksi_harian_id')
                        // ->whereBetween('transaksi_harians.tgl', [$periode->open_date, $periode->close_date])
                        ->whereMonth('transaksi_harians.tgl',date('m'))
                        ->whereYear('transaksi_harians.tgl',date('Y'))
                        ->where('transaksi_harians.jenis_transaksi', 2)
                        ->where('transaksi_harians.jenis_pembayaran', 1)
                        ->sum('transaksi_harian_biayas.nominal');
            $kas_debit = DB::table('transaksi_harians')
                        ->join('transaksi_harian_biayas', 'transaksi_harians.id', '=', 'transaksi_harian_biayas.transaksi_harian_id')
                        // ->whereBetween('transaksi_harians.tgl', [$periode->open_date, $periode->close_date])
                        ->whereMonth('transaksi_harians.tgl',date('m'))
                        ->whereYear('transaksi_harians.tgl',date('Y'))
                        ->where('transaksi_harians.jenis_transaksi', 1)
                        ->where('transaksi_harians.jenis_pembayaran', 1)
                        ->sum('transaksi_harian_biayas.nominal');
            $bank_kredit = DB::table('transaksi_harians')
                        ->join('transaksi_harian_biayas', 'transaksi_harians.id', '=', 'transaksi_harian_biayas.transaksi_harian_id')
                        // ->whereBetween('transaksi_harians.tgl', [$periode->open_date, $periode->close_date])
                        ->whereMonth('transaksi_harians.tgl',date('m'))
                        ->whereYear('transaksi_harians.tgl',date('Y'))
                        ->where('transaksi_harians.jenis_transaksi', 2)
                        ->where('transaksi_harians.jenis_pembayaran', 2)
                        ->sum('transaksi_harian_biayas.nominal');
            $bank_debit = DB::table('transaksi_harians')
                        ->join('transaksi_harian_biayas', 'transaksi_harians.id', '=', 'transaksi_harian_biayas.transaksi_harian_id')
                        // ->whereBetween('transaksi_harians.tgl', [$periode->open_date, $periode->close_date])
                        ->whereMonth('transaksi_harians.tgl',date('m'))
                        ->whereYear('transaksi_harians.tgl',date('Y'))
                        ->where('transaksi_harians.jenis_pembayaran', 2)
                        ->where('transaksi_harians.jenis_transaksi', 1)
                        ->sum('transaksi_harian_biayas.nominal');
            $countAnggota = Anggota::select()->count();
            $countDivisi = Divisi::select()->count();
            $activity_log =  ActivityLog::with('user')->limit(10)->get();
            $result = TransaksiHarian::where('periode_id', $periode->id)
                        ->selectRaw('year(tgl) year, monthname(tgl) month, count(*) data, month(tgl) mon')
                        ->groupBy('year', 'month', 'mon')
                        ->orderBy('year', 'Asc')
                        ->orderBy('mon', 'Asc')
                        ->get();

            $tr_debits = DB::table('transaksi_harians')
                            ->join('transaksi_harian_biayas', 'transaksi_harians.id', '=', 'transaksi_harian_biayas.transaksi_harian_id')
                            ->where('transaksi_harians.jenis_transaksi', 1)
                            ->where('periode_id', $periode->id)
                            ->select(
                                DB::raw('sum(transaksi_harian_biayas.nominal) as sums'),
                                DB::raw("month(transaksi_harians.tgl) as mon"),
                                DB::raw("year(transaksi_harians.tgl) year")
                            )
                            ->groupBy('year')
                            ->groupBy('mon')
                            ->orderBy('year', 'Asc')
                            ->orderBy('mon', 'Asc')
                            ->get();
            $tr_kredits = DB::table('transaksi_harians')
                            ->join('transaksi_harian_biayas', 'transaksi_harians.id', '=', 'transaksi_harian_biayas.transaksi_harian_id')
                            ->where('transaksi_harians.jenis_transaksi', 2)
                            ->where('periode_id', $periode->id)
                            ->select(
                                DB::raw('sum(transaksi_harian_biayas.nominal) as sums'),
                                DB::raw("month(transaksi_harians.tgl) as mon"),
                                DB::raw("year(transaksi_harians.tgl) year")
                            )
                            ->groupBy('year')
                            ->groupBy('mon')
                            ->orderBy('year', 'Asc')
                            ->orderBy('mon', 'Asc')
                            ->get();
            $debitAll = [];
            foreach($tr_debits as $tr_debit)
            {
                array_push($debitAll, $tr_debit->sums);
            }
            $months = [];
            $kreditAll = [];
            foreach($result as $item){
                array_push($months, $item->month.' '.$item->year);
            }
            foreach($tr_kredits as $tr_kredit)
            {
                array_push($kreditAll, $tr_kredit->sums);
            }
            return view('dashboard.admin')->with(compact('countAnggota', 'countDivisi', 'periode', 'sum_pokok', 'sum_wajib', 'sum_sukarela', 'kredit_simpanan', 'debet_pinjaman', 'bunga_pinjaman', 'kredit_pinjaman', 'transaksi_harian', 'activity_log','status_anggota', 'months', 'debitAll', 'kreditAll','jt_pelunasan','jt_pelunasan_dibayar','anggota_aktif','anggota_disable','transaksi_pinjam','kas_debit','kas_kredit','bank_debit','bank_kredit'));
        }
        elseif($user->roles->pluck( 'name' )->contains( 'member' ))
        {
            $sum_pokok = DB::table('transaksi_harians')
                        ->join('transaksi_harian_biayas', 'transaksi_harians.id', '=', 'transaksi_harian_biayas.transaksi_harian_id')
                        ->join('transaksi_harian_anggotas', 'transaksi_harians.id', '=', 'transaksi_harian_anggotas.transaksi_harian_id')
                        ->whereBetween('transaksi_harians.tgl', [$periode->open_date, $periode->close_date])
                        ->where('transaksi_harian_biayas.biaya_id', '1')
                        ->where('transaksi_harian_anggotas.anggota_id',$anggota->anggota_id)
                        ->where('divisi_id', '1')
                        ->sum('transaksi_harian_biayas.nominal');
            $sum_wajib = DB::table('transaksi_harians')
                        ->join('transaksi_harian_biayas', 'transaksi_harians.id', '=', 'transaksi_harian_biayas.transaksi_harian_id')
                        ->join('transaksi_harian_anggotas', 'transaksi_harians.id', '=', 'transaksi_harian_anggotas.transaksi_harian_id')
                        ->whereBetween('transaksi_harians.tgl', [$periode->open_date, $periode->close_date])
                        ->where('transaksi_harian_biayas.biaya_id', '2')
                        ->where('transaksi_harian_anggotas.anggota_id',$anggota->anggota_id)
                        ->where('divisi_id', '1')
                        ->sum('transaksi_harian_biayas.nominal');
            $sum_sukarela = DB::table('transaksi_harians')
                        ->join('transaksi_harian_biayas', 'transaksi_harians.id', '=', 'transaksi_harian_biayas.transaksi_harian_id')
                        ->join('transaksi_harian_anggotas', 'transaksi_harians.id', '=', 'transaksi_harian_anggotas.transaksi_harian_id')
                        ->whereBetween('transaksi_harians.tgl', [$periode->open_date, $periode->close_date])
                        ->where('transaksi_harian_biayas.biaya_id', '3')
                        ->where('transaksi_harian_anggotas.anggota_id',$anggota->anggota_id)
                        ->where('divisi_id', '1')
                        ->sum('transaksi_harian_biayas.nominal');
            $kredit_simpanan = DB::table('transaksi_harians')
                        ->join('transaksi_harian_biayas', 'transaksi_harians.id', '=', 'transaksi_harian_biayas.transaksi_harian_id')
                        ->join('transaksi_harian_anggotas', 'transaksi_harians.id', '=', 'transaksi_harian_anggotas.transaksi_harian_id')
                        ->whereBetween('transaksi_harians.tgl', [$periode->open_date, $periode->close_date])
                        ->where('transaksi_harian_biayas.biaya_id', '4')
                        ->where('transaksi_harian_anggotas.anggota_id',$anggota->anggota_id)
                        ->where('divisi_id', '1')
                        ->sum('transaksi_harian_biayas.nominal');
            $debet_pinjaman = DB::table('transaksi_harians')
                        ->join('transaksi_harian_biayas', 'transaksi_harians.id', '=', 'transaksi_harian_biayas.transaksi_harian_id')
                        ->join('transaksi_harian_anggotas', 'transaksi_harians.id', '=', 'transaksi_harian_anggotas.transaksi_harian_id')
                        ->whereBetween('transaksi_harians.tgl', [$periode->open_date, $periode->close_date])
                        ->where('transaksi_harian_biayas.biaya_id', '6')
                        ->where('transaksi_harian_anggotas.anggota_id',$anggota->anggota_id)
                        ->where('divisi_id', '2')
                        ->sum('transaksi_harian_biayas.nominal');
            $bunga_pinjaman = DB::table('transaksi_harians')
                        ->join('transaksi_harian_biayas', 'transaksi_harians.id', '=', 'transaksi_harian_biayas.transaksi_harian_id')
                        ->join('transaksi_harian_anggotas', 'transaksi_harians.id', '=', 'transaksi_harian_anggotas.transaksi_harian_id')
                        ->whereBetween('transaksi_harians.tgl', [$periode->open_date, $periode->close_date])
                        ->where('transaksi_harian_biayas.biaya_id', '7')
                        ->where('transaksi_harian_anggotas.anggota_id',$anggota->anggota_id)
                        ->where('divisi_id', '2')
                        ->sum('transaksi_harian_biayas.nominal');
            $kredit_pinjaman = DB::table('transaksi_harians')
                        ->join('transaksi_harian_biayas', 'transaksi_harians.id', '=', 'transaksi_harian_biayas.transaksi_harian_id')
                        ->join('transaksi_harian_anggotas', 'transaksi_harians.id', '=', 'transaksi_harian_anggotas.transaksi_harian_id')
                        ->whereBetween('transaksi_harians.tgl', [$periode->open_date, $periode->close_date])
                        ->where('transaksi_harian_biayas.biaya_id', '8')
                        ->where('transaksi_harian_anggotas.anggota_id',$anggota->anggota_id)
                        ->where('divisi_id', '2')
                        ->sum('transaksi_harian_biayas.nominal');
            return view('dashboard.member', compact('sum_pokok','sum_wajib','sum_sukarela','kredit_simpanan','debet_pinjaman','bunga_pinjaman','kredit_pinjaman'));
        }else {
            return view('dashboard.member');
        }
    }
}
