<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ActivityController;
use App\Http\Controllers\CategoryController;

// Halaman utama (opsional)
Route::get('/', function () {
    return view('welcome');
});

Route::resource('activities', ActivityController::class);
Route::resource('categories', CategoryController::class);
Route::patch('/activities/{activity}/publish', [ActivityController::class, 'publish'])->name('activities.publish');
Route::patch('/activities/{activity}/complete', [ActivityController::class, 'complete'])->name('activities.complete');