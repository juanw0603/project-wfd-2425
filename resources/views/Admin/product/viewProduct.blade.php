@extends('Admin.Layout')

@section('content')

@include('partials.alert')

<div class="p-6">
<div class="flex justify-between items-center mb-4">
    <h1 class="text-2xl font-bold">Daftar Produk</h1>

    <a href="{{ route('admin.product.create') }}" class="bg-black hover:bg-gray-800 text-white px-4 py-2 rounded shadow">
        + Tambah product
    </a>
</div>


    <div class="overflow-x-auto bg-white rounded shadow">
        <table class="min-w-full divide-y divide-gray-200 text-sm">
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-4 py-2 text-left font-semibold text-gray-600">#</th>
                    <th class="px-4 py-2 text-left font-semibold text-gray-600">Nama Produk</th>
                    <th class="px-4 py-2 text-left font-semibold text-gray-600">Kategori</th>
                    <th class="px-4 py-2 text-left font-semibold text-gray-600">Harga</th>
                    <th class="px-4 py-2 text-left font-semibold text-gray-600">Stok</th>
                    <th class="px-4 py-2 text-left font-semibold text-gray-600  ">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @foreach ($products as $index => $product)
                <tr>
                    <td class="px-4 py-2">{{ $index + 1 }}</td>
                    <td class="px-4 py-2">{{ $product->name }}</td>
                    <td class="px-4 py-2">{{ $product->category->name ?? '-' }}</td>
                    <td class="px-4 py-2">Rp {{ number_format($product->price, 0, ',', '.') }}</td>
                    <td class="px-4 py-2">{{ $product->stock }}</td>
                    <td class="px-4 py-2 space-x-2">
                        <a href="{{ route('admin.product.update', $product->id) }}" class="text-blue-600 hover:underline">Edit</a>
                        <form action="{{ route('admin.product.destroy', $product->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Yakin hapus?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:underline">Hapus</button>
                        </form>
                    </td>
                </tr>
                @endforeach

                @if($products->isEmpty())
                <tr>
                    <td colspan="6" class="px-4 py-4 text-center text-gray-500">Belum ada data produk.</td>
                </tr>
                @endif
            </tbody>
        </table>
    </div>
</div>
@endsection
