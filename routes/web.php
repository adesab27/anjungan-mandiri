<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AntrianController;


Route::get('/pengunjung', function () {
    return view('pengunjung');
});

Route::get('/monitor', function () {
    return view('monitor');
});

Route::get('/petugas', function () {
    return view('petugas');
});

Route::post('/ambil-antrian', [AntrianController::class, 'ambil']);

Route::get('/antrian-terakhir', [AntrianController::class, 'terakhir']);