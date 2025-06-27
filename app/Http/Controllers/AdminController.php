<?php

namespace App\Http\Controllers;

use App\Models\Activity_logs;
use App\Models\User;
use App\Models\product;
use App\Models\suppliers;
use App\Models\categories;
use App\Models\Purchase;
use App\Models\PurchaseItem;
use App\Models\purchases;
use App\Models\sale_items;
use App\Models\sales;
use Database\Seeders\SaleItemSeeder;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function dashboardPage()
    {
        $recentActivities = Activity_logs::with('user')->latest()->take(5)->get();


        // Hitung total produk, supplier, dan pengguna non-admin
        $totalProduk = Product::count();
        $totalPengguna = User::where('role', '!=', 'admin')->count();

        // Hitung total pembelian dan penjualan dari seluruh data
        $totalPembelian = Purchase::sum('total_price');
        $totalPenjualan = sales::sum('total_price');

        // Ambil range waktu dari query string, default 7 hari
        $range = request('range', 7);
        $fromDate = now()->subDays($range);

        // Ambil data penjualan per hari dalam rentang waktu
        $sales = sales::selectRaw('DATE(sale_date) as date, SUM(total_price) as total')
            ->where('sale_date', '>=', $fromDate)
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $salesDates = $sales->pluck('date');
        $salesTotals = $sales->pluck('total');

        $purchases = Purchase::selectRaw('DATE(purchase_date) as date, SUM(total_price) as total')
            ->where('purchase_date', '>=', $fromDate)
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $purchaseDates = $purchases->pluck('date');
        $purchaseTotals = $purchases->pluck('total');


        return view('Admin.dashboard', [
            'totalPenjualan' => $totalPenjualan,
            'totalPembelian' => $totalPembelian,
            'totalProduk' => $totalProduk,
            'totalPengguna' => $totalPengguna,
            'salesDates' => $salesDates,
            'salesTotals' => $salesTotals,
            'purchaseDates' => $purchaseDates,
            'purchaseTotals' => $purchaseTotals,
            'recentActivities' => $recentActivities,
        ]);
    }



    public function productsPage(Request $request)
    {
        $query = Product::with('category'); // gunakan relasi eager loading

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        $products = $query->orderBy('created_at', 'desc')->get(); // produk terbaru di atas
        $categories = categories::all();

        return view('Admin.product.ViewProduct', compact('products', 'categories'));
    }

    public function suppliersPage()
    {
        $suppliers = suppliers::all();

        return view('Admin.supplier.ViewSupplier', compact('suppliers',));
    }

    public function usersPage()
    {
        $users = User::where('role', '!=', 'admin')->orderBy('id', 'asc')->get();
        return view('Admin.user.ViewUser', compact('users'));
    }


    public function laporanPage(Request $request)
    {
        $tanggal = $request->input('tanggal', today());

        // Barang Masuk (pembelian)
        $barangMasuk = PurchaseItem::with(['product', 'purchase.supplier'])
            ->whereHas('purchase', function ($q) use ($tanggal) {
                $q->whereDate('purchase_date', $tanggal);
            })->get();

        // Barang Keluar (penjualan)
        $barangKeluar = sale_items::with(['product', 'sale.user'])
            ->whereHas('sale', function ($q) use ($tanggal) {
                $q->whereDate('sale_date', $tanggal);
            })->get();

        // Stok Opname
        $stok = \App\Models\Product::all();

        // Laporan Keuangan
        $totalPembelian = Purchase::whereDate('purchase_date', $tanggal)->sum('total_price');
        $totalPenjualan = sales::whereDate('sale_date', $tanggal)->sum('total_price');

        return view('admin.laporan', [
            'tanggal' => $tanggal,
            'barangMasuk' => $barangMasuk,
            'barangKeluar' => $barangKeluar,
            'stok' => $stok,
            'totalPembelian' => $totalPembelian,
            'totalPenjualan' => $totalPenjualan,
            'selisih' => $totalPenjualan - $totalPembelian,
        ]);
    }
}
