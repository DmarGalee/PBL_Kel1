<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LevelController;
use App\Http\Controllers\PeriodeController;
use App\Http\Controllers\LantaiController;
use App\Http\Controllers\GedungController;
use App\Http\Controllers\RuangController;
use App\Http\Controllers\FasilitasController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

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
            Route::get('/', [UserController::class, 'index']); // menampilkan halaman awal user
            Route::post('/list', [UserController::class, 'list']); // menampilkan data user dalam bentuk json untuk datables
            Route::get('/create', [UserController::class, 'create']); // menampilkan halaman form tambah user
            Route::post('/', [UserController::class, 'store']); // menyimpan data user baru
            Route::get('/create_ajax', [UserController::class, 'create_ajax']); //Menampilkan halaman form tambah user ajax
            Route::post('/ajax', [UserController::class, 'store_ajax']); // Menyimpan data user baru Ajax
            Route::get('/{id}', [UserController::class, 'show']); // menampilkan detail user
            Route::get('/{id}/show_ajax', [UserController::class, 'show_ajax']); // menampilkan detail user ajax
            Route::get('/{id}/edit', [UserController::class, 'edit']); // menampilkan halaman form edit user
            Route::put('/{id}', [UserController::class, 'update']); // menyimpan data user yang diubah
            Route::get('/{id}/edit_ajax', [UserController::class, 'edit_ajax']); //Menampilkan halaman form edit user ajax
            Route::put('/{id}/update_ajax', [UserController::class, 'update_ajax']); // menyimpan perubahan data user ajax
            Route::get('/{id}/delete_ajax', [UserController::class, 'confirm_ajax']); //untuk tampilkan form confirm delete user ajax
            Route::delete('/{id}/delete_ajax', [UserController::class, 'delete_ajax']); // Untuk hapus data User Ajax
            Route::delete('/{id}', [UserController::class, 'destroy']); // menghapus data user
            Route::get('/import', [UserController::class, 'import']); // ajax form upload excel
            Route::post('/import_ajax', [UserController::class, 'import_ajax']); // ajax import excel
            Route::get('/export_excel', [UserController::class, 'export_excel']); //export excel
            Route::get('/export_pdf', [UserController::class, 'export_pdf']);
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
            Route::get('/export_excel', [LevelController::class,'export_excel']); // ajax export excel
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
            Route::get('/', [RuangController::class, 'index']); // menampilkan halaman awal Ruang
            Route::post('/list', [RuangController::class, 'list']); // menampilkan data Ruang dalam bentuk json untuk datatable
            Route::get('/create_ajax', [RuangController::class, 'create_ajax']); // menampilkan halaman form tambah Ruang ajax
            Route::post('/ajax', [RuangController::class, 'store_ajax']); // menyimpan data Ruang baru ajax
            Route::get('/{id}/show_ajax', [RuangController::class, 'show_ajax']); // menampilkan detail Ruang ajax
            Route::get('/{id}/edit_ajax', [RuangController::class, 'edit_ajax']); // menampilkan halaman form edit Ruang ajax
            Route::put('/{id}/update_ajax', [RuangController::class, 'update_ajax']); // menyimpan perubahan data Ruang ajax
            Route::get('/{id}/delete_ajax', [RuangController::class, 'confirm_ajax']); // untuk tampilan form confirm delete Ruang ajax
            Route::delete('/{id}/delete_ajax', [RuangController::class, 'delete_ajax']); // menghapus data Ruang ajax
            Route::post('/import_ajax', [RuangController::class, 'import_ajax']); // menyimpan data Ruang dari file import
            Route::get('/export_excel', [RuangController::class, 'export_excel']); // ajax export excel
            Route::get('/export_pdf', [RuangController::class, 'export_pdf']); // ajax export pdf
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

        //route fasilitas
         Route::group(['prefix' => 'fasilitas'], function () {
         Route::get('/', [FasilitasController::class, 'index']); // menampilkan halaman awal Level
            Route::post('/list', [FasilitasController::class, 'list']); // menampilkan data Level dalam bentuk json untuk datatable
            Route::get('/create_ajax', [FasilitasController::class, 'create_ajax']); // menampilkan halaman form tambah Level ajax
            Route::post('/ajax', [FasilitasController::class, 'store_ajax']); // menyimpan data Level baru ajax
            Route::get('/{id}/show_ajax', [FasilitasController::class, 'show_ajax']); // menampilkan detail Level ajax
            Route::get('/{id}/edit_ajax', [FasilitasController::class, 'edit_ajax']); // menampilkan halaman form edit Level ajax
            Route::put('/{id}/update_ajax', [FasilitasController::class, 'update_ajax']); // menyimpan perubahan data Level ajax
            Route::get('/{id}/delete_ajax', [FasilitasController::class, 'confirm_ajax']); // untuk tampilan form confirm delete Level ajax
            Route::delete('/{id}/delete_ajax', [FasilitasController::class, 'delete_ajax']); // menghapus data Level ajax
            Route::post('/import_ajax', [FasilitasController::class, 'import_ajax']); // menyimpan data Level dari file import
            Route::get('/export_excel', [FasilitasController::class,'export_excel']); // ajax export excel
            Route::get('/export_pdf', [FasilitasController::class,'export_pdf']); // ajax export pdf
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