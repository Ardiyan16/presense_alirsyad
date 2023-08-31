<?php

use App\Http\Controllers\API\DataUnitController;
use App\Http\Controllers\API\DataPegawaiController;
use App\Http\Controllers\API\AbsensiController;
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

    //myprofile
    Route::get('/data-profile/{id}', [DataPegawaiController::class, 'get_profile']);
    Route::post('/update-profile', [DataPegawaiController::class, 'update_profile']);

    //absen masuk
    Route::post('/simpan-absen-masuk', [AbsensiController::class, 'presence_in']);

    //izin
    Route::post('/simpan-izin', [AbsensiController::class, 'izin']);

});



// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });
