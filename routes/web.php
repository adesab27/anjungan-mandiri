<?php

use Illuminate\Support\Facades\Route;


Route::get('/pengunjung', function () {
    return view('pengunjung');
});

Route::get('/monitor', function () {
    return view('monitor');
});

Route::get('/petugas', function () {
    return view('petugas');
});

