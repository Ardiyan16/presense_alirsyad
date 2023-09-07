<?php

use App\Http\Controllers\API\DataUnitController;
use App\Http\Controllers\API\DataPegawaiController;
use App\Http\Controllers\API\AbsensiController;
use App\Http\Controllers\API\CutiController;
use App\Http\Controllers\API\PresensiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ApiAdminController;
use App\Http\Middleware\IsAdminApi;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('/login', [AuthController::class, 'login_action']);
Route::post('/ubah-password', [AuthController::class, 'action_lupa_password']);
Route::prefix('v1')->middleware(IsAdminApi::class)->group(function() {

    //dashboard
    Route::get('/data-dashboard', [DataUnitController::class, 'dashboard']);

    //unit
    Route::get('/data_unit', [DataUnitController::class, 'index']);
    Route::post('/simpan-unit', [DataUnitController::class, 'store']);
    Route::post('/update-unit', [DataUnitController::class, 'update']);
    Route::get('/hapus-unit/{id}', [DataUnitController::class, 'destroy']);

    //pegawai
    Route::get('/data-pegawai', [DataPegawaiController::class, 'index']);
    Route::get('/data-unit-select', [DataPegawaiController::class, 'get_unit']);
    Route::get('/data-pegawai-edit/{id}', [DataPegawaiController::class, 'get_edit']);
    Route::post('/simpan-pegawai', [DataPegawaiController::class, 'store']);
    Route::post('/update-pegawai', [DataPegawaiController::class, 'update']);
    Route::post('/nonaktif-pegawai', [DataPegawaiController::class, 'nonaktif']);
    Route::post('/aktif-pegawai', [DataPegawaiController::class, 'aktifkan']);
    Route::get('/hapus-pegawai/{id}', [DataPegawaiController::class, 'destroy']);

    //cuti
    Route::get('/data-cuti', [CutiController::class, 'index']);
    Route::post('/simpan-cuti-pegawai', [CutiController::class, 'store']);
    Route::post('/update-cuti-pegawai', [CutiController::class, 'update']);
    Route::get('/hapus-cuti/{id}', [CutiController::class, 'destroy']);
    Route::get('/data-cuti-edit/{id}', [CutiController::class, 'get_edit']);
    Route::get('/data-pegawai-select', [CutiController::class, 'get_pegawai']);
    Route::get('/data-jenis-cuti-select', [CutiController::class, 'get_jenis_cuti']);
    Route::get('/data-jenis-cuti', [CutiController::class, 'jenis_cuti']);
    Route::post('/simpan-jenis-cuti', [CutiController::class, 'simpan_jenis_cuti']);
    Route::post('/update-jenis-cuti', [CutiController::class, 'update_jenis_cuti']);
    Route::get('/hapus-jenis-cuti/{id}', [CutiController::class, 'hapus_jenis_cuti']);

    //myprofile
    Route::get('/data-profile/{id}', [DataPegawaiController::class, 'get_profile']);
    Route::post('/update-profile', [DataPegawaiController::class, 'update_profile']);

    //presensi
    Route::post('/data-presensi', [PresensiController::class, 'index']);
    Route::get('/data-by-userid/{id}', [PresensiController::class, 'get_user_id']);
    Route::post('/data-detail-presensi-masuk', [PresensiController::class, 'detail_presensi_masuk']);
    Route::post('/data-detail-presensi-keluar', [PresensiController::class, 'detail_presensi_keluar']);
    Route::post('/data-detail-izin', [PresensiController::class, 'detail_data_izin']);

    //absen
    Route::post('/simpan-absen-masuk', [AbsensiController::class, 'presence_in']);
    Route::post('/simpan-absen-keluar', [AbsensiController::class, 'presence_out']);

    //izin
    Route::post('/simpan-izin', [AbsensiController::class, 'izin']);

    //Riwayat absen
    Route::get('/dashboard-riwayat/{id}', [AbsensiController::class, 'dashboard_riwayat_absen']);
    Route::get('/riwayat-absen', [AbsensiController::class, 'riwayat_absen']);

});



// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });
