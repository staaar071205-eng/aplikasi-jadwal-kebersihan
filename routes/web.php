<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\JadwalController;
use App\Http\Controllers\KegiatanController;
use App\Http\Controllers\PetugasController;
use App\Http\Controllers\WilayahController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Halaman tamu: login
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('/', [AuthController::class, 'index'])->name('login');
    Route::post('/login', [AuthController::class, 'proses'])
        ->middleware('throttle:10,1')
        ->name('login.proses');
});

/*
|--------------------------------------------------------------------------
| Halaman terlindungi (harus login)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/dashboard', DashboardController::class)->name('dashboard');

    // Data master
    Route::resource('wilayah', WilayahController::class)
        ->except('show')
        ->parameters(['wilayah' => 'wilayah']);

    Route::resource('kegiatan', KegiatanController::class)
        ->except('show')
        ->parameters(['kegiatan' => 'kegiatan']);

    Route::resource('petugas', PetugasController::class)
        ->except('show')
        ->parameters(['petugas' => 'petugas']);

    // Jadwal kebersihan
    Route::patch('jadwal/{jadwal}/selesai', [JadwalController::class, 'selesai'])->name('jadwal.selesai');
    Route::resource('jadwal', JadwalController::class)
        ->parameters(['jadwal' => 'jadwal']);
});
