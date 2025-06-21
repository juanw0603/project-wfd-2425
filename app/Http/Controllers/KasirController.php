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
        $request->validate([
            'items' => 'required|array|min:1',
            'items.*.id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.price' => 'required|numeric|min:0',
        ]);
    }
}
