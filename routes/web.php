<?php

use App\Http\Controllers\KegiatanController;
use Illuminate\Support\Facades\Route;

// Redirect root to /kegiatan
Route::redirect('/', '/kegiatan');

// Resource route untuk Kegiatan (Halaman Utama, CRUD Admin)
Route::resource('kegiatan', KegiatanController::class);
