@extends('Admin.Layout')

@section('content')
    @include('partials.alert')



    <div class="p-6">
        <div class="flex justify-between items-center mb-4">
            <h1 class="text-2xl font-bold">Daftar Produk</h1>



            <button class="bg-black hover:bg-gray-800 text-white px-4 py-2 rounded shadow"
                onclick="document.getElementById('modal-product').classList.remove('hidden')">
                + Tambah Produk
            </button>
        </div>

        <form method="GET" action="{{ route('admin.product.page') }}" class="mb-4 flex items-center gap-2">
            <select name="category_id" class="border rounded px-3 py-2">
                <option value="">Semua Kategori</option>
                @foreach ($categories as $category)
                    <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                Filter
            </button>
        </form>



        <div class="overflow-x-auto bg-white rounded shadow">
            <table class="min-w-full divide-y divide-gray-200 text-sm">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-4 py-2 text-left font-semibold text-gray-600">#</th>
                        <th class="px-4 py-2 text-left font-semibold text-gray-600">Nama Produk</th>
                        <th class="px-4 py-2 text-left font-semibold text-gray-600">Kategori</th>
                        <th class="px-4 py-2 text-left font-semibold text-gray-600">Harga</th>
                        <th class="px-4 py-2 text-left font-semibold text-gray-600">Stok</th>
                        <th class="px-4 py-2 text-left font-semibold text-gray-600">Minimal Stok</th>
                        <th class="px-4 py-2 text-left font-semibold text-gray-600">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach ($products as $index => $product)
                        <tr>
                            <td class="px-4 py-2">{{ $index + 1 }}</td>
                            <td class="px-4 py-2">{{ $product->name }}</td>
                            <td class="px-4 py-2">{{ $product->category->name ?? '-' }}</td>
                            <td class="px-4 py-2">Rp {{ number_format($product->price, 0, ',', '.') }}</td>
                            <td class="px-4 py-2">
                                @if ($product->stock <= $product->minimal_stock)
                                    <span class="text-red-600 font-semibold">{{ $product->stock }} (Rendah)</span>
                                @else
                                    {{ $product->stock }}
                                @endif
                            </td>
                            <td class="px-4 py-2">{{ $product->minimal_stock }}</td>
                            <td class="px-4 py-2 space-x-2">
                                <button class="bg-blue-600 text-white rounded hover:bg-blue-700 px-4 py-1"
                                    onclick="openEditProductModal({{ $product->id }}, '{{ $product->name }}', '{{ $product->category_id }}', '{{ $product->price }}', '{{ $product->stock }}','{{ $product->minimal_stock }}')">
                                    Edit
                                </button>
                                <form action="{{ route('admin.product.destroy', $product->id) }}" method="POST"
                                    class="inline-block" onsubmit="return confirm('Yakin hapus?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="bg-red-600 text-white rounded hover:bg-red-700 px-4 py-1">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach

                    @if ($products->isEmpty())
                        <tr>
                            <td colspan="6" class="px-4 py-4 text-center text-gray-500">Belum ada data produk.</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal Tambah Produk -->
    <div id="modal-product" class="fixed inset-0 flex items-center justify-center hidden z-50">
        <div class="absolute inset-0 bg-black opacity-50"></div>
        <div class="relative bg-white p-6 rounded-lg shadow-lg w-full max-w-md z-10">
            <h2 class="text-xl font-semibold mb-4">Tambah Produk</h2>
            <form action="{{ route('admin.product.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label class="block mb-1 font-medium">Nama</label>
                    <input type="text" name="name" class="w-full border rounded px-3 py-2" required>
                </div>
                <div class="mb-3">
                    <label class="block mb-1 font-medium">Kategori</label>
                    <select name="category_id" class="w-full border rounded px-3 py-2" required>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label class="block mb-1 font-medium">Harga</label>
                    <input type="number" name="price" class="w-full border rounded px-3 py-2" required>
                </div>

                <div class="mb-3">
                    <label class="block mb-1 font-medium">Minimal Stok</label>
                    <input type="number" name="minimal_stock" class="w-full border rounded px-3 py-2" required>
                </div>

                <div class="mb-3">
                    <label class="block mb-1 font-medium">Stok</label>
                    <input type="number" name="stock" class="w-full border rounded px-3 py-2" required>
                </div>
                <div class="flex justify-end space-x-2 mt-4">
                    <button type="button" onclick="document.getElementById('modal-product').classList.add('hidden')"
                        class="px-4 py-2 border rounded">Batal</button>
                    <button type="submit"
                        class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Edit Produk -->
    <div id="modal-edit-product" class="fixed inset-0 flex items-center justify-center hidden z-50">
        <div class="absolute inset-0 bg-black opacity-50"></div>
        <div class="relative bg-white p-6 rounded-lg shadow-lg w-full max-w-md z-10">
            <h2 class="text-xl font-semibold mb-4">Edit Produk</h2>
            <form id="edit-product-form" method="POST">
                @csrf
                @method('PUT')
                <input type="hidden" name="id" id="edit-product-id">

                <div class="mb-3">
                    <label class="block mb-1 font-medium">Nama</label>
                    <input type="text" name="name" id="edit-product-name" class="w-full border rounded px-3 py-2"
                        required>
                </div>
                <div class="mb-3">
                    <label class="block mb-1 font-medium">Kategori</label>
                    <select name="category_id" id="edit-product-category" class="w-full border rounded px-3 py-2"
                        required>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label class="block mb-1 font-medium">Harga</label>
                    <input type="number" name="price" id="edit-product-price" class="w-full border rounded px-3 py-2"
                        required>
                </div>

                <div class="mb-3">
                    <label class="block mb-1 font-medium">Minimal Stok</label>
                    <input type="number" name="minimal_stock" id="edit-product-minimal-stock"
                        class="w-full border rounded px-3 py-2" required>
                </div>

                <div class="mb-3">
                    <label class="block mb-1 font-medium">Stok</label>
                    <input type="number" name="stock" id="edit-product-stock" class="w-full border rounded px-3 py-2"
                        required>
                </div>
                <div class="flex justify-end space-x-2 mt-4">
                    <button type="button" onclick="closeEditModal()" class="px-4 py-2 border rounded">Batal</button>
                    <button type="submit"
                        class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Simpan</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('script')
    <script>
        function openEditProductModal(id, name, category_id, price, stock, minimal_stock) {
            document.getElementById('edit-product-name').value = name;
            document.getElementById('edit-product-category').value = category_id;
            document.getElementById('edit-product-price').value = price;
            document.getElementById('edit-product-stock').value = stock;
            document.getElementById('edit-product-minimal-stock').value = minimal_stock;

            const form = document.getElementById('edit-product-form');
            form.action = `/admin/product/${id}`;
            document.getElementById('modal-edit-product').classList.remove('hidden');
        }


        function closeEditModal() {
            document.getElementById('modal-edit-product').classList.add('hidden');
        }
    </script>
@endsection
