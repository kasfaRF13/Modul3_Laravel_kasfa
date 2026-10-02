<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ActivityController;

// Halaman utama (opsional)
Route::get('/', function () {
    return view('welcome');
});

// Route untuk melihat daftar kegiatan
Route::get('/activities', [ActivityController::class, 'index'])->name('activities.index');

// Route untuk menampilkan form tambah kegiatan (file create.blade.php kamu)
Route::get('/activities/create', [ActivityController::class, 'create'])->name('activities.create');

// Route untuk memproses simpan data dari form
Route::post('/activities', [ActivityController::class, 'store'])->name('activities.store');