<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Rekap;

Route::resource('/', Rekap::class);

Route::get('/rekap', [Rekap::class, 'dataMaster'])->name('rekap.index');
Route::post('/rekap/manual', [Rekap::class, 'storeManual'])->name('rekap.storeManual');
Route::post('/rekap/upload', [Rekap::class, 'importExcel'])->name('rekap.importExcel');
Route::put('/update/{id_master}', [Rekap::class, 'update'])->name('rekap.update');
Route::delete('/delete/{id_master}', [Rekap::class, 'destroy'])->name('rekap.destroy');

Route::get('/rekap-otomatis', [Rekap::class, 'rekapAuto']);
Route::get('/dataRekap', [Rekap::class, 'dataRekap'])->name('dataRekap');
Route::get('/rekap/export', [Rekap::class, 'export'])->name('rekap.export');
