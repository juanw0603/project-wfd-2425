<?php

namespace App\Http\Controllers;

use App\Models\product;
use Illuminate\Http\Request;

class KasirController extends Controller
{
    public function transaksi()
    {

        $products = product::all();

        return view('Kasir.Transactions', compact('products'));
    }

    public function LaporanTransaksi()
    {
        $produk = product::all();
        return view('kasir.Laporantransaksi', compact('produk'));
    }

    public function prosesTransaksi(Request $request)
    {
        // logika untuk menyimpan transaksi penjualan
        // validasi + simpan ke tabel sales dan sale_items
    }
}
