<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\product;
use App\Models\suppliers;
use App\Models\categories;
use App\Models\purchases;
use App\Models\sales;
use Database\Seeders\SaleItemSeeder;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function dashboardPage()
    {
        // Hitung total produk, supplier, dan pengguna non-admin
        $totalProduk = Product::count();
        $totalPengguna = User::where('role', '!=', 'admin')->count();

        // Hitung total pembelian dan penjualan dari seluruh data
        $totalPembelian = purchases::sum('total_price');
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

        $purchases = purchases::selectRaw('DATE(purchase_date) as date, SUM(total_price) as total')
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
}
