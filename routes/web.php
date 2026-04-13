<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Rute untuk 3 tampilan antrian
Route::get('/pengunjung', function () {
    return view('pengunjung');
})->name('pengunjung');

Route::get('/petugas', function () {
    return view('petugas');
})->name('petugas');

Route::get('/monitor', function () {
    return view('monitor');
})->name('monitor');