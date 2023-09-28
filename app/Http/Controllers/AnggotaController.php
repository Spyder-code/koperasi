<?php

namespace App\Http\Controllers;

use App\Models\Anggota;
use Illuminate\Http\Request;
use DataTables;
use App\Helpers\Tanggal;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use App\Models\UserAnggota;
use App\Models\Periode;
use App\Exports\AnggotaExport;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use Session;


class AnggotaController extends Controller
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

    public function index(Request $request)
    {
        //
        if (Auth::user()->isAbleTo('manage-anggota')) {
            if ($request->ajax()) {
                $periode = Periode::select()->where('status', 1)->first();
                $anggota = Anggota::select();
                return DataTables::of($anggota)
                    ->editColumn('status', function ($anggota) {
                        if ($anggota->status == '0') {
                            return '<span class="badge badge-danger badge-pill">Non Aktif</span>';
                        } else {
                            return '<span class="badge badge-gradient badge-pill">Aktif</span>';
                        }
                    })
                    ->editColumn('tgl_daftar', function ($anggota) {
                        return Tanggal::tanggal_id($anggota->tgl_daftar);
                    })
                    ->editColumn('username', function ($anggota) {
                        return $anggota->user_anggota->user->username ?? '-';
                    })
                    ->addColumn('action', function ($anggota) {
                        return view('datatable._nodelete', [
                            'edit_url' => route('anggota.edit', $anggota->id),
                            'can_edit' => 'edit-anggota',
                            'id' => $anggota->id
                        ]);
                    })
                    ->editColumn('is_close', function ($anggota) {
                        if ($anggota->status == '0') {
                            return 'Status Tidak Aktif';
                        }
                    })
                    ->rawColumns(['status', 'action'])
                    ->make(true);
            }
            return view('anggota.index');
        } else {
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
        if (Auth::user()->isAbleTo('create-anggota')) {

            return view('anggota.create');
        } else {
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
        //
        if (Auth::user()->isAbleTo('create-anggota')) {
            $this->validate($request, [
                'nik' => 'required|unique:anggotas',
                'nama' => 'required',
                'inisial' => 'required',
                'tgl_lahir' => 'required',
                'tempat_lahir' => 'required',
                'jabatan' => 'required',
                'username' => 'required',
                'status' => 'required',
                'tgl_daftar' => 'required',
                'homebase' => 'required'
            ]);
            $anggota = new Anggota();
            $anggota->nik = $request->nik;
            $anggota->nama = $request->nama;
            $anggota->tempat_lahir = $request->tempat_lahir;
            $anggota->tgl_lahir = Tanggal::convert_tanggal($request->tgl_lahir);
            $anggota->jabatan = $request->jabatan;
            $anggota->inisial = $request->inisial;
            $anggota->tgl_daftar = Tanggal::convert_tanggal($request->tgl_daftar);
            $anggota->status = $request->status;
            $anggota->homebase = $request->homebase;
            $anggota->bank = $request->bank;
            $anggota->no_rek = $request->no_rek;
            $anggota->save();

            $array_name = explode(' ',$request->nama);
            $user = new User();
            $user->email = $request->nik;
            $user->password = bcrypt(strtolower($array_name[0]).date('dmY',strtotime($anggota->tgl_lahir)));
            $user->name = $request->nama;
            $user->username = $request->username;
            $user->save();

            DB::table('role_user')->insert([
                'user_id' => $user->id,
                'role_id' => '2',
                'user_type' => 'App\User'
            ]);

            $userAnggota = new UserAnggota();
            $userAnggota->anggota_id = $anggota->id;
            $userAnggota->user_id = $user->id;
            $userAnggota->save();
            Session::flash("flash_notification", [
                "level" => "success",
                "message" => "Data Berhasil ditambah !!!"
            ]);
            return redirect()->route('anggota.index');
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Anggota  $anggota
     * @return \Illuminate\Http\Response
     */
    public function show(Anggota $anggota)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Anggota  $anggota
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (Auth::user()->isAbleTo('edit-anggota')) {
            $anggota = Anggota::find($id);
            $anggota->tgl_daftar = date('d-m-Y', strtotime($anggota->tgl_daftar));
            return view('anggota.edit')->with(compact('anggota'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Anggota  $anggota
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if (Auth::user()->isAbleTo('edit-anggota')) {
            $this->validate($request, [
                'nik' => 'required|unique:anggotas,nik,' . $id,
                'nama' => 'required',
                'inisial' => 'required',
                'status' => 'required',
                'tgl_daftar' => 'required',
                'homebase' => 'required',
                'tgl_lahir' => 'required',
                'tempat_lahir' => 'required',
                'jabatan' => 'required',
            ]);
            $anggota = Anggota::find($id);
            $anggota->nik = $request->nik;
            $anggota->nama = $request->nama;
            $anggota->inisial = $request->inisial;
            $anggota->status = $request->status;
            $anggota->tempat_lahir = $request->tempat_lahir;
            $anggota->tgl_lahir = Tanggal::convert_tanggal($request->tgl_lahir);
            $anggota->jabatan = $request->jabatan;
            $anggota->tgl_daftar = Tanggal::convert_tanggal($request->tgl_daftar);
            $anggota->homebase = $request->homebase;
            $anggota->update();

            $userAnggota = UserAnggota::where('anggota_id', $id)->first();

            if($userAnggota){
                $user = User::find($userAnggota->user_id);
                $user->email = $request->nik;
                $user->name = $request->nama;
                if($request->username){
                    $user->username = $request->username;
                }
                if($request->password){
                    $user->password = bcrypt($request->password);
                }
                $user->save();
            }
            Session::flash("flash_notification", [
                "level" => "success",
                "message" => "Data Berhasil diubah !!!"
            ]);
            return redirect()->route('anggota.index');
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Anggota  $anggota
     * @return \Illuminate\Http\Response
     */
    public function destroy(Anggota $anggota)
    {
        //
    }

    public function export()
    {
        $anggota = Anggota::select()->get();
        activity()->log('Mendowload Data Anggota');
        return Excel::download(new AnggotaExport($anggota), 'Anggota-Koperasi.xlsx');
    }
}
