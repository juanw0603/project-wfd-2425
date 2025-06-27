<?php

use App\Policies\SalesPolicy;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\KasirController;
use App\Http\Controllers\SalesController;
use App\Http\Controllers\GudangController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SuppliersController;
use App\Http\Controllers\Auth\LoginController;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboardPage'])->name('dashboard.page');
    Route::get('/products', [AdminController::class, 'productsPage'])->name('product.page');
    Route::get('/suppliers', [AdminController::class, 'suppliersPage'])->name('supplier.page');
    Route::get('/users', [AdminController::class, 'usersPage'])->name('user.page');
    Route::get('/laporan',[AdminController::class, 'laporanPage'])->name('laporan.page');
    
    Route::resource('/product', ProductController::class);
    Route::resource('/supplier', SuppliersController::class);
    Route::resource('/user', UserController::class);
});

Route::middleware(['auth', 'role:kasir'])->prefix('kasir')->name('kasir.')->group(function () {
    Route::get('/transaksi', [KasirController::class, 'transaksi'])->name('transaksi.page');
    Route::post('/transaksi', [KasirController::class, 'prosesTransaksi'])->name('transaksi.proses');
    Route::get('/laporan-transaksi', [KasirController::class, 'LaporanTransaksi'])->name('laporan-transaksi.page');
});


Route::middleware((['auth','role:gudang']))->prefix('gudang')->name('gudang.')->group(function(){
    Route::get('/purchase', [GudangController::class, 'purchasePage'])->name('purchase.page');
    Route::post('/purchase', [GudangController::class, 'prosesPurchase'])->name('purchase.proses');
    Route::get('/laporan-purchase', [GudangController::class, 'laporanPage'])->name('laporan-purchase.page');
});




Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.attempt');
Route::get('/logout', [LoginController::class, 'logout'])->name('logout');


Route::get('/testing', function () {
    return view('testing');
})->name('testing');