<?php

use App\Http\Controllers\WalletController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// --- Halaman Publik (Bisa diakses tanpa login) ---
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

Route::get('/register', [AuthController::class, 'showRegister']);
Route::post('/register', [AuthController::class, 'register']);

Route::get('/logout', [AuthController::class, 'logout']);


// --- Halaman Privat (Hanya bisa diakses jika sudah login) ---
Route::middleware(['auth'])->group(function () {
    
    // Halaman Utama Dashboard
    Route::get('/', [WalletController::class, 'index']);
    
    // Fitur Simpan & Hapus Transaksi
    Route::post('/save', [WalletController::class, 'store']);
    Route::delete('/delete/{id}', [WalletController::class, 'destroy']);
    
});