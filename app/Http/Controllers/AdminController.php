<?php

namespace App\Http\Controllers;

use App\Models\product;
use App\Models\suppliers;
use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {
        $totalProduk = product::count();
        $totalSupplier = suppliers::count();
        $totalPengguna = User::where('role', '!=', 'admin')->count();

        // Contoh data penjualan/pembelian real, nanti bisa pakai query lebih kompleks
        $penjualan = [
            'labels' => ['Jan', 'Feb', 'Mar', 'Apr'],
            'data' => [1500000, 2000000, 1700000, 2200000], // bisa ganti jadi hasil query agregat
        ];

        $pembelian = [
            'labels' => ['Jan', 'Feb', 'Mar', 'Apr'],
            'data' => [1200000, 1800000, 1600000, 2100000],
        ];

        return view('admin.dashboard', compact(
            'totalProduk',
            'totalSupplier',
            'totalPengguna',
            'penjualan',
            'pembelian'
        ));
    }
}
