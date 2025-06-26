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
        $range = request('range', 7);
        $fromDate = now()->subDays($range);

        $sales = sales::selectRaw('DATE(sale_date) as date, SUM(total_price) as total')
            ->where('sale_date', '>=', $fromDate)
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $purchases = purchases::selectRaw('DATE(purchase_date) as date, SUM(total_price) as total')
            ->where('purchase_date', '>=', $fromDate)
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return view('Admin.dashboard', [
            'totalPenjualan' => $sales->sum('total'),
            'totalPembelian' => $purchases->sum('total'),
            'totalProduk' => Product::count(),
            'totalPengguna' => User::where('role', '!=', 'admin')->count(),
            'salesDates' => $sales->pluck('date')->map(fn($d) => \Carbon\Carbon::parse($d)->format('d M')),
            'salesTotals' => $sales->pluck('total'),
            'purchaseDates' => $purchases->pluck('date')->map(fn($d) => \Carbon\Carbon::parse($d)->format('d M')),
            'purchaseTotals' => $purchases->pluck('total'),
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
