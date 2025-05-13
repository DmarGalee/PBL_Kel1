<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/



Route::pattern('id', '[0-9]+'); //jika ada parameter id, maka harus berupa angka
// register
Route::get('register', [AuthController::class, 'register'])->name('register');
Route::post('register', [AuthController::class, 'postRegister']);
// login
Route::get('login', [AuthController::class, 'login'])->name('login');
Route::post('login', [AuthController::class, 'postlogin']);
//logout
Route::get('logout', [AuthController::class, 'logout'])->middleware('auth');



Route::middleware(['auth'])->group(function () { //artinya semua route di dalam goup ini harus login dulu
    // masukkan semua route yang perlu autentikasi di sini

    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

      Route::middleware(['authorize:ADM'])->group(function () {
         
        //route user
        Route::group(['prefix' => 'user'], function () {
          
        });
         //route level
         Route::group(['prefix' => 'level'], function () {
          
        });

        //route gedung
         Route::group(['prefix' => 'gedung'], function () {
          
        });

         //route lantai
         Route::group(['prefix' => 'lantai'], function () {
          
        });

         //route ruang
         Route::group(['prefix' => 'ruang'], function () {
          
        });

        //route periode
         Route::group(['prefix' => 'periode'], function () {
         
        });
    });

    // Pelapor Mahasiswa, Dosen, Tenga Kependidikan
    Route::middleware(['authorize:MHS,DSN,TENDIK'])->group(function () {
    });

    // Sarana Prasarana
    Route::middleware(['authorize:SARPRAS'])->group(function () {
    });

    // Teknisi
    Route::middleware(['authorize:TEKNISI'])->group(function () {
    });
    
});