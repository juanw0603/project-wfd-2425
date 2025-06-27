<?php

namespace App\Http\Controllers;

use App\Models\Activity_logs;
use App\Models\User;
use App\Models\sales;
use App\Models\product;
use App\Models\sale_items;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class KasirController extends Controller
{
    public function transaksi()
    {

        $products = product::orderBy('id', 'asc')->get();

        return view('Kasir.Transactions', compact('products'));
    }

    public function LaporanTransaksi(Request $request)
    {
        $query = Sales::with('user');

        if ($request->start_date) {
            $query->whereDate('sale_date', '>=', $request->start_date);
        }

        if ($request->end_date) {
            $query->whereDate('sale_date', '<=', $request->end_date);
        }

        if ($request->user_id) {
            $query->where('user_id', $request->user_id);
        }

        // CLONE query sebelum dimodifikasi oleh paginate
        $summaryQuery = (clone $query);

        // Paginasi hanya untuk menampilkan tabel
        $sales = $query->latest()->paginate(10);

        // Hitung total dan jumlah dari clone
        $totalPenjualan = $summaryQuery->sum('total_price');
        $jumlahTransaksi = $summaryQuery->count();
        $rataRata = $jumlahTransaksi > 0 ? $totalPenjualan / $jumlahTransaksi : 0;

        $kasirs = User::where('role', 'kasir')->get();

        return view('kasir.laporanTransaksi', compact(
            'sales',
            'totalPenjualan',
            'jumlahTransaksi',
            'rataRata',
            'kasirs'
        ));
    }

    public function prosesTransaksi(Request $request)
    {
        $request->validate([
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
        ], [
            'items.*.product_id.required' => 'Product ID wajib diisi.',
            'items.*.product_id.exists' => 'Produk tidak ditemukan di database.',
        ]);
        DB::beginTransaction();

        try {
            $totalPrice = 0;

            // Hitung total dan siapkan data item
            $itemsData = [];
            foreach ($request->items as $item) {
                $product = Product::findOrFail($item['product_id']);
                $quantity = $item['quantity'];
                $pricePerUnit = $product->price;
                $subtotal = $pricePerUnit * $quantity;

                $itemsData[] = [
                    'product_id' => $product->id,
                    'quantity' => $quantity,
                    'price_per_unit' => $pricePerUnit,
                    'subtotal' => $subtotal,
                ];

                $totalPrice += $subtotal;

                // Kurangi stok
                $product->stock -= $quantity;
                $product->save();
            }

            // Simpan ke tabel sales
            $sale = sales::create([
                'user_id' => Auth::id(),
                'sale_date' => now(),
                'total_price' => $totalPrice,
            ]);

            // Simpan ke tabel sale_items
            foreach ($itemsData as $item) {
                $item['sale_id'] = $sale->id;
                sale_items::create($item);
            }

            Activity_logs::create([
                'user_id'    => Auth::id(),
                'activity_type'        => 'purchase',
                'description' => 'User "' . Auth::user()->name . '" menambahkan pembelian produk ' . $product->name,

            ]);

            DB::commit();

            return response()->json(['success' => true, 'message' => 'Transaksi berhasil disimpan.']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }
}
