<?php

namespace App\Http\Controllers;

use App\Models\product;

use Illuminate\Http\Request;


class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = product::all();
        return view('Admin.product.ViewProduct', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create() {}

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'minimal_stock' => 'required|integer|min:0',
        ]);

        Product::create($validated);

        return redirect()->route('admin.product.page')->with('success', 'Produk berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(product $product)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(product $product)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, product $product)
    {
        $request->validate([
            'name' => 'required',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'minimal_stock' => 'required|integer|min:0',
        ]);

        $product->update($request->only('name', 'category_id', 'price', 'stock', 'minimal_stock'));

        return redirect()->route('admin.product.page')->with('success', 'Produk berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $product = product::findOrFail($id);

        $product->delete();

        return redirect()->back()->with('success', 'produk berhasil dihapus.');
    }
}
