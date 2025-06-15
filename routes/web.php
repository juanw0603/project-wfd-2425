<?php

use App\Policies\SalesPolicy;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\KasirController;
use App\Http\Controllers\SalesController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SuppliersController;
use App\Http\Controllers\Auth\LoginController;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');
    Route::resource('/product', ProductController::class);
    Route::resource('/supplier', SuppliersController::class);
    Route::resource('/user', UserController::class);
});

Route::middleware(['auth', 'role:kasir'])->prefix('kasir')->name('kasir.')->group(function () {
    Route::get('/transaksi', [KasirController::class, 'transaksi'])->name('transaksi');
    Route::post('/transaksi', [KasirController::class, 'prosesTransaksi'])->name('transaksi.proses');
    Route::get('/laporan-transaksi', [KasirController::class, 'LaporanTransaksi'])->name('laporan-transaksi');
});




Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.attempt');
Route::get('/logout', [LoginController::class, 'logout'])->name('logout');


Route::get('/testing', function () {
    return view('testing');
})->name('testing');