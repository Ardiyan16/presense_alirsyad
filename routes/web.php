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
    });


});

Route::middleware(IsUser::class)->group(function(){
    Route::get('/logout-user', [AuthController::class, 'logout'])->name('logout');
    Route::prefix('user')->group(function () {
        Route::get('/', [PagesController::class, 'index'])->name('Homeuser');
        Route::get('/absen-masuk', [PagesController::class, 'absen_masuk'])->name('AbsenMasuk');
    });
});

