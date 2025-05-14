<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LevelController;
use App\Http\Controllers\PeriodeController;
use App\Http\Controllers\LantaiController;
use App\Http\Controllers\GedungController;
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
            Route::get('/', [LevelController::class, 'index']); // menampilkan halaman awal Level
            Route::post('/list', [LevelController::class, 'list']); // menampilkan data Level dalam bentuk json untuk datatable
            Route::get('/create_ajax', [LevelController::class, 'create_ajax']); // menampilkan halaman form tambah Level ajax
            Route::post('/ajax', [LevelController::class, 'store_ajax']); // menyimpan data Level baru ajax
            Route::get('/{id}/show_ajax', [LevelController::class, 'show_ajax']); // menampilkan detail Level ajax
            Route::get('/{id}/edit_ajax', [LevelController::class, 'edit_ajax']); // menampilkan halaman form edit Level ajax
            Route::put('/{id}/update_ajax', [LevelController::class, 'update_ajax']); // menyimpan perubahan data Level ajax
            Route::get('/{id}/delete_ajax', [LevelController::class, 'confirm_ajax']); // untuk tampilan form confirm delete Level ajax
            Route::delete('/{id}/delete_ajax', [LevelController::class, 'delete_ajax']); // menghapus data Level ajax
            Route::post('/import_ajax', [LevelController::class, 'import_ajax']); // menyimpan data Level dari file import
            Route::get('/export_excel', [LevelController::class,'export_excel']); // ajax export excel
            Route::get('/export_pdf', [LevelController::class,'export_pdf']); // ajax export pdf
        });

        //route gedung
        Route::group(['prefix' => 'gedung'], function () {
            Route::get('/', [GedungController::class, 'index']); // menampilkan halaman awal Gedung
            Route::post('/list', [GedungController::class, 'list']); // menampilkan data Gedung dalam bentuk json untuk datatable
            Route::get('/create_ajax', [GedungController::class, 'create_ajax']); // menampilkan halaman form tambah Gedung ajax
            Route::post('/ajax', [GedungController::class, 'store_ajax']); // menyimpan data Gedung baru ajax
            Route::get('/{id}/show_ajax', [GedungController::class, 'show_ajax']); // menampilkan detail Gedung ajax
            Route::get('/{id}/edit_ajax', [GedungController::class, 'edit_ajax']); // menampilkan halaman form edit Gedung ajax
            Route::put('/{id}/update_ajax', [GedungController::class, 'update_ajax']); // menyimpan perubahan data Gedung ajax
            Route::get('/{id}/delete_ajax', [GedungController::class, 'confirm_ajax']); // untuk tampilan form confirm delete Gedung ajax
            Route::delete('/{id}/delete_ajax', [GedungController::class, 'delete_ajax']); // menghapus data Gedung ajax
            Route::post('/import_ajax', [GedungController::class, 'import_ajax']); // menyimpan data Gedung dari file import
            Route::get('/export_excel', [GedungController::class, 'export_excel']); // ajax export excel
            Route::get('/export_pdf', [GedungController::class, 'export_pdf']); // ajax export pdf
        });


         //route lantai
         Route::group(['prefix' => 'lantai'], function () {
          Route::get('/', [LantaiController::class, 'index']);
          Route::post('/list', [LantaiController::class, 'list']);
          Route::get('/create_ajax', [LantaiController::class, 'create_ajax']); // Ajax form create
          Route::post('/ajax', [LantaiController::class, 'store_ajax']); // Ajax store
          Route::get('/{id}/edit_ajax', [LantaiController::class, 'edit_ajax']); // Ajax form edit
          Route::put('/{id}/update_ajax', [LantaiController::class, 'update_ajax']); // Ajax update
          Route::get('/{id}/delete_ajax', [LantaiController::class, 'confirm_ajax']); // Ajax form confirm
          Route::delete('/{id}/delete_ajax', [LantaiController::class, 'delete_ajax']); // Ajax delete
          Route::get('/import', [LantaiController::class, 'import']);
          Route::post('/import_ajax', [LantaiController::class, 'import_ajax']);
          Route::get('/export_excel', [LantaiController::class, 'export_excel']); // export excel
          Route::get('/export_pdf', [LantaiController::class, 'export_pdf']); // export pdf
        });

         //route ruang
         Route::group(['prefix' => 'ruang'], function () {
          
        });

        //route periode
         Route::group(['prefix' => 'periode'], function () {
         Route::get('/', [PeriodeController::class, 'index']); // menampilkan halaman awal Level
            Route::post('/list', [PeriodeController::class, 'list']); // menampilkan data Level dalam bentuk json untuk datatable
            Route::get('/create_ajax', [PeriodeController::class, 'create_ajax']); // menampilkan halaman form tambah Level ajax
            Route::post('/ajax', [PeriodeController::class, 'store_ajax']); // menyimpan data Level baru ajax
            Route::get('/{id}/show_ajax', [PeriodeController::class, 'show_ajax']); // menampilkan detail Level ajax
            Route::get('/{id}/edit_ajax', [PeriodeController::class, 'edit_ajax']); // menampilkan halaman form edit Level ajax
            Route::put('/{id}/update_ajax', [PeriodeController::class, 'update_ajax']); // menyimpan perubahan data Level ajax
            Route::get('/{id}/delete_ajax', [PeriodeController::class, 'confirm_ajax']); // untuk tampilan form confirm delete Level ajax
            Route::delete('/{id}/delete_ajax', [PeriodeController::class, 'delete_ajax']); // menghapus data Level ajax
            Route::post('/import_ajax', [PeriodeController::class, 'import_ajax']); // menyimpan data Level dari file import
            Route::get('/export_excel', [PeriodeController::class,'export_excel']); // ajax export excel
            Route::get('/export_pdf', [PeriodeController::class,'export_pdf']); // ajax export pdf
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