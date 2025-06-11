<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Rekap;
use App\Http\Controllers\Auth\AuthController;

// Route yang boleh diakses tanpa login (misalnya halaman login)
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Semua route lainnya wajib login
Route::middleware(['auth'])->group(function () {
    Route::resource('/', Rekap::class);

    Route::get('/rekap', [Rekap::class, 'dataMaster'])->name('rekap.index');
    Route::post('/rekap/manual', [Rekap::class, 'storeManual'])->name('rekap.storeManual');
    Route::post('/rekap/upload', [Rekap::class, 'importExcel'])->name('rekap.importExcel');
    Route::put('/update/{id_master}', [Rekap::class, 'update'])->name('rekap.update');
    Route::delete('/delete/{id_master}', [Rekap::class, 'destroy'])->name('rekap.destroy');

    Route::get('/rekap-otomatis', [Rekap::class, 'rekapAuto']);
    Route::get('/dataRekap', [Rekap::class, 'dataRekap'])->name('dataRekap');
    Route::get('/rekap/export', [Rekap::class, 'export'])->name('rekap.export');

    Route::get('/akun-pengguna', [Rekap::class, 'akunPengguna'])->name('akun.index');
    Route::post('/akun-pengguna', [Rekap::class, 'storeAkun'])->name('akun.store');
    Route::put('/akun-pengguna/{id}', [Rekap::class, 'updateAkun'])->name('akun.update');
    Route::delete('/akun-pengguna/{id}', [Rekap::class, 'deleteAkun'])->name('akun.delete');
});

Route::fallback(function () {
    return response()->view('errors.404', [], 404);
});
