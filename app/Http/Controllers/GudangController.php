<?php

namespace App\Http\Controllers;

use App\Models\Activity_logs;
use App\Models\product;
use App\Models\Purchase;
use App\Models\purchases;
use App\Models\suppliers;
use App\Models\PurchaseItem;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class GudangController extends Controller
{
    public function purchasePage()
    {
        $suppliers = suppliers::all();
        $products = Product::all();
        $lowStockProducts = product::whereColumn('stock', '<=', 'minimal_stock')->get();

        $recentPurchases = Purchase::with(['supplier', 'items.product'])
            ->latest()
            ->take(5)
            ->get();

        $summary = [
            'transactions' => Purchase::whereDate('purchase_date', today())->count(),
            'total' => Purchase::whereDate('purchase_date', today())->sum('total_price'),
            'suppliers' => suppliers::count(),
        ];

        return view('gudang.purchase', compact(
            'suppliers',
            'lowStockProducts',
            'recentPurchases',
            'summary',
            'products'
        ));
    }

    public function laporanPage(Request $request)
    {
        $start = $request->input('start_date');
        $end = $request->input('end_date');
        $supplierId = $request->input('supplier_id');

        // Query dasar
        $baseQuery = Purchase::query();

        if ($start) {
            $baseQuery->whereDate('purchase_date', '>=', $start);
        }
        if ($end) {
            $baseQuery->whereDate('purchase_date', '<=', $end);
        }
        if ($supplierId) {
            $baseQuery->where('supplier_id', $supplierId);
        }

        // Copy query untuk summary (tanpa pagination)
        $summaryQuery = clone $baseQuery;

        // Hitung summary
        $summary = [
            'total' => $summaryQuery->sum('total_price'),
            'count' => $summaryQuery->count(),
            'average' => $summaryQuery->average('total_price') ?? 0,
            'active_suppliers' => $summaryQuery->distinct('supplier_id')->count('supplier_id'),
        ];

        // Ambil data transaksi untuk tabel (pakai pagination)
        $purchases = $baseQuery->with(['supplier', 'items'])->latest()->paginate(10);

        $suppliers = suppliers::all();

        return view('gudang.laporanPurchase', compact('purchases', 'summary', 'suppliers'));
    }




    public function prosesPurchase(Request $request)
    {
        $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'product_id' => 'required|exists:products,id', // ubah ke product_id
            'quantity' => 'required|integer|min:1',
            'unit_price' => 'required|numeric|min:0',
        ]);

        DB::beginTransaction();

        try {
            $product = Product::find($request->product_id);

            $subtotal = $request->quantity * $request->unit_price;

            $purchase = Purchase::create([
                'user_id' => Auth::id(),
                'supplier_id' => $request->supplier_id,
                'purchase_date' => now(),
                'total_price' => $subtotal,
            ]);

            PurchaseItem::create([
                'purchase_id' => $purchase->id,
                'product_id' => $product->id,
                'quantity' => $request->quantity,
                'price_per_unit' => $request->unit_price,
                'subtotal' => $subtotal,
            ]);

            // Tambahkan ke stok produk
            $product->stock += $request->quantity;
            $product->save();

            Activity_logs::create([
                'user_id' => Auth::id(),
                'activity_type' => 'purchase',
                'description' => 'User "' . Auth::user()->name . '" melakukan pembelian produk "' . $product->name . '" sebanyak ' . $request->quantity,

            ]);

            DB::commit();

            return redirect()->back()->with('success', 'Transaksi pembelian berhasil ditambahkan.');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Gagal menyimpan transaksi. ' . $e->getMessage());
        }
    }
}
