<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PagesController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Middleware\IsAdmin;
use App\Http\Middleware\IsGuest;
use App\Http\Middleware\IsUser;

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

Route::middleware(IsGuest::class)->group(function(){
    Route::get('/', [PagesController::class, 'index'])->name('Home');
    Route::get('/login', [AuthController::class, 'index'])->name('login');
    Route::get('/lupa-password', [AuthController::class, 'lupa_password'])->name('lupa_pass');
});

Route::middleware(IsAdmin::class)->group(function(){
    Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::prefix('dashboard')->group(function () {
        Route::get('/', [AdminController::class, 'index'])->name('dashboard');
        Route::get('/profile-saya', [AdminController::class, 'profile_saya'])->name('profilesaya');
        Route::get('/data-unit', [AdminController::class, 'data_unit'])->name('dataunit');

        //pegawai
        Route::get('/data-pegawai', [AdminController::class, 'data_pegawai'])->name('datapegawai');
        Route::get('/tambah-pegawai', [AdminController::class, 'add_pegawai'])->name('addpegawai');
        Route::get('/edit-pegawai', [AdminController::class, 'edit_pegawai'])->name('editpegawai');

        //cuti
        Route::get('/data-cuti', [AdminController::class, 'data_cuti'])->name('cuti');
        Route::get('/tambah-cuti', [AdminController::class, 'tambah_cuti'])->name('addcuti');
        Route::get('/edit-cuti', [AdminController::class, 'edit_cuti'])->name('addcuti');
        Route::get('/data-jenis-cuti', [AdminController::class, 'data_jenis_cuti'])->name('jeniscuti');

        //presensi
        Route::get('/data-presensi', [AdminController::class, 'data_presensi'])->name('datapresensi');
        Route::get('/detail-presensi', [AdminController::class, 'detail_presensi'])->name('detailpresensi');
    });


});

Route::middleware(IsUser::class)->group(function(){
    Route::get('/logout-user', [AuthController::class, 'logout'])->name('logout');
    Route::prefix('user')->group(function () {
        Route::get('/', [PagesController::class, 'index'])->name('Homeuser');
        Route::get('/absen-masuk', [PagesController::class, 'absen_masuk'])->name('AbsenMasuk');
        Route::get('/absen-keluar', [PagesController::class, 'absen_keluar'])->name('AbsenKeluar');
        Route::get('/izin', [PagesController::class, 'izin'])->name('Izin');
        Route::post('/simpan-izin', [PagesController::class, 'simpan_izin']);
        Route::get('/riwayat-absen', [PagesController::class, 'riwayat_absen'])->name('RiwayatAbsen');
    });
});

