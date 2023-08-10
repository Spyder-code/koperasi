<?php

use App\Http\Controllers\AnggotaController;
use App\Http\Controllers\ApprovalController;
use App\Http\Controllers\BiayaController;
use App\Http\Controllers\CopySaldoController;
use App\Http\Controllers\DivisiController;
use App\Http\Controllers\DivisiDebetController;
use App\Http\Controllers\DivisiKreditController;
use App\Http\Controllers\HelpController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LaporanAllController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\ModuleController;
use App\Http\Controllers\OptionController;
use App\Http\Controllers\PeriodeController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\PinjamanDebetController;
use App\Http\Controllers\PinjamanKreditController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SimpananDebetController;
use App\Http\Controllers\SimpananKreditController;
use App\Http\Controllers\TransaksiHarianController;
use App\Http\Controllers\TransaksiSimpananController;
use App\Http\Controllers\UserAnggotaController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Auth::routes();
Route::get('/', function () {
    return redirect()->route('login');
});
Route::group(['middleware' => 'web'], function () {
    Route::group(['middleware' => ['auth']], function () {
        Route::get('aproval-simpanan', [ApprovalController::class,'simpanan'])->name('approval.simpanan');
        Route::get('aproval-pinjaman', [ApprovalController::class,'pinjaman'])->name('approval.pinjaman');
        Route::put('aproval/{trx}', [ApprovalController::class,'update'])->name('approval.update');
        Route::get('help', [HelpController::class,'index'])->name('help.index');
        Route::post('help', [HelpController::class,'store'])->name('help.store');
        Route::get('laporan-simpanan', [LaporanController::class,'simpanan'])->name('laporan.simpanan');
        Route::get('laporan-pinjaman', [LaporanController::class,'pinjaman'])->name('laporan.pinjaman');
        Route::get('laporan-simpanan-all', [LaporanAllController::class,'simpananAll'])->name('laporan.simpanan-all');
        Route::post('laporan-simpanan-validation', [LaporanAllController::class,'validationSimpanan'])->name('laporan-simpanan-all.validation');
        Route::post('laporan-simpanan-export', [LaporanAllController::class,'exportSimpanan'])->name('laporan-simpanan-all.export');
        Route::get('laporan-pinjaman-all', [LaporanAllController::class,'pinjamanAll'])->name('laporan.pinjaman-all');
        Route::post('laporan-pinjaman-validation', [LaporanAllController::class,'validationPinjaman'])->name('laporan-pinjaman-all.validation');
        Route::post('laporan-pinjaman-export', [LaporanAllController::class,'exportPinjaman'])->name('laporan-pinjaman-all.export');
        Route::get('/user/{user}/profile-user/', [UserController::class,'editPasswordMember'])->name('user.user-profile');
        Route::put('/user/profile/{user}', [UserController::class,'putEditPassword'])->name('user.edit-password');
        Route::get('/home', [HomeController::class,'index'])->name('home');
        Route::resource('anggota', AnggotaController::class);
        Route::get('/anggota-export', [AnggotaController::class,'export'])->name('anggota.export');
        Route::resource('divisi', DivisiController::class);
        Route::resource('transaksi-harian', TransaksiHarianController::class);
        Route::resource('periode', PeriodeController::class);
        Route::resource('role', RoleController::class);
        Route::resource('module', ModuleController::class);
        Route::get('copy-saldo-copy/{copy_saldo}', [CopySaldoController::class,'copySaldo'])->name('copy-saldo.copy');
        Route::resource('copy-saldo', CopySaldoController::class);
        Route::post('detach-permission/{role_id}', [PermissionController::class,'detachPermission'])->name('permission.detach');
        Route::post('attach-permission/{role_id}', [PermissionController::class,'attachPermission'])->name('permission.attach');
        Route::resource('permission', PermissionController::class);
        Route::get('laporan-kas-bank', [LaporanController::class,'cashBank'])->name('laporan.cash-bank');
        Route::get('laporan-angusran', [LaporanController::class,'angsuran'])->name('laporan.angsuran');
        Route::get('laporan-perdivisi', [LaporanController::class,'perDivisi'])->name('laporan.per-divisi');
        Route::get('transaksi-harian.chek-anggota', [TransaksiHarianController::class,'cekAnggota'])->name('transaksi-harian.chek-anggota');
        Route::resource('user', UserController::class);
        Route::resource('option', OptionController::class);
        Route::post('company-options', [OptionController::class,'saveCompany'])->name('company.option');
        Route::post('email-options', [OptionController::class,'saveEmail'])->name('email.option');
        Route::post('sosmed', [OptionController::class,'saveSosmed'])->name('social-media');
        Route::resource('transaksi-simpanan', TransaksiSimpananController::class);
        // Route::resource('transaksi-pinjaman', TransaksiPinjamanController::class);
        // Route::resource('transaksi-divisi', TransaksiDivisiController::class);
        Route::get('/simpanan-debet/upload', [SimpananDebetController::class,'upload'])->name('simpanan-debet.upload');
        Route::post('/simpanan-debet/doupload', [SimpananDebetController::class,'doUpload'])->name('simpanan-debet.doupload');
        Route::get('/simpanan-kredit/upload', [SimpananKreditController::class,'upload'])->name('simpanan-kredit.upload');
        Route::post('/simpanan-kredit/doupload', [SimpananKreditController::class,'doUpload'])->name('simpanan-kredit.doupload');
        Route::get('/pinjaman-kredit/upload', [PinjamanKreditController::class,'upload'])->name('pinjaman-kredit.upload');
        Route::post('/pinjaman-kredit/doupload', [PinjamanKreditController::class,'doUpload'])->name('pinjaman-kredit.doupload');
        Route::get('/pinjaman-debet/upload', [PinjamanDebetController::class,'upload'])->name('pinjaman-debet.upload');
        Route::post('/pinjaman-debet/doupload', [PinjamanDebetController::class,'doUpload'])->name('pinjaman-debet.doupload');
        Route::get('/simpanan-debet/close-book', [SimpananDebetController::class,'closeBook'])->name('simpanan-debet.close-book');
        Route::get('/simpanan-kredit/close-book', [SimpananKreditController::class,'closeBook'])->name('simpanan-kredit.close-book');
        Route::get('/pinjaman-debet/close-book', [PinjamanDebetController::class,'closeBook'])->name('pinjaman-debet.close-book');
        Route::get('/pinjaman-kredit/close-book', [PinjamanKreditController::class,'closeBook'])->name('pinjaman-kredit.close-book');
        Route::get('/divisi-debet/close-book', [DivisiDebetController::class,'closeBook'])->name('divisi-debet.close-book');
        Route::get('/divisi-kredit/close-book', [DivisiKreditController::class,'closeBook'])->name('divisi-kredit.close-book');

        Route::resource('simpanan-debet', SimpananDebetController::class);
        Route::resource('simpanan-kredit', SimpananKreditController::class);
        Route::resource('pinjaman-debet', PinjamanDebetController::class);
        Route::resource('pinjaman-kredit', PinjamanKreditController::class);
        Route::resource('divisi-debet', DivisiDebetController::class);
        Route::resource('divisi-kredit', DivisiKreditController::class);
        Route::get('/check-biaya-debet/{divisi}', [BiayaController::class,'checkBiayaDebet'])->name('check-biaya-debet.get');
        Route::get('/check-biaya-kredit/{divisi}', [BiayaController::class,'checkBiayaKredit'])->name('check-biaya-kredit.get');
        Route::resource('biaya', BiayaController::class);
        Route::get('/user/reset-password/{user}', [UserController::class,'resetPassword'])->name('user.reset-password');
        Route::put('/user/reset-pass/{user}', [UserController::class,'putResetPass'])->name('user.reset-pass');
        Route::resource('/user-anggota', UserAnggotaController::class);
        Route::post('/periode-close-book/{periode}', [PeriodeController::class,'closeBook'])->name('periode.close-book');
        Route::post('/simpanan-anggota/cari', [LaporanController::class,'cariSimpanan'])->name('simpanan-anggota.cari');
        Route::post('/simpanan-anggota/excel', [LaporanController::class,'simpananExcel'])->name('simpanan-anggota.excel');
        Route::post('/pinjaman-anggota/cari', [LaporanController::class,'cariPinjaman'])->name('pinjaman-anggota.cari');
        Route::post('/pinjaman-anggota/excel', [LaporanController::class,'pinjamanExcel'])->name('pinjaman-anggota.excel');
    });
});
