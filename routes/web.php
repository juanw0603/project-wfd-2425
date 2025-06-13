<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\SuppliersController;
use App\Http\Controllers\UserController;

Route::get('/', function () {
    return view('Login');
});

Route::middleware(['auth', 'role:admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
    Route::resource('/produk', ProductController::class);
    Route::resource('/supplier', SuppliersController::class);
    Route::resource('/pengguna', UserController::class);
});

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.attempt');
Route::get('/logout', [LoginController::class, 'logout'])->name('logout');


Route::get('/testing', function () {
    return view('testing');
})->name('testing');