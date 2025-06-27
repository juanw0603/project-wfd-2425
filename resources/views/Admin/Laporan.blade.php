@extends('Admin.Layout')

@section('content')
<div class="p-6">
    <h1 class="text-2xl font-bold mb-4">Laporan Harian</h1>

    <!-- Filter Tanggal -->
    <form method="GET" action="{{ route('admin.laporan.page') }}" class="mb-6 flex items-center gap-2">
        <input type="date" name="tanggal" value="{{ $tanggal }}" class="border rounded px-3 py-2">
        <button type="submit" class="bg-black text-white px-4 py-2 rounded hover:bg-gray-800">Filter</button>
    </form>

    <!-- Barang Masuk -->
    <div class="bg-white p-4 rounded shadow mb-6">
        <h2 class="text-lg font-semibold mb-2">Barang Masuk (Pembelian)</h2>
        @if($barangMasuk->isEmpty())
            <p class="text-gray-500 text-sm">Tidak ada data pembelian.</p>
        @else
        <table class="w-full table-auto text-sm">
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-3 py-2">Produk</th>
                    <th class="px-3 py-2">Supplier</th>
                    <th class="px-3 py-2">Qty</th>
                    <th class="px-3 py-2">Harga Satuan</th>
                    <th class="px-3 py-2">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($barangMasuk as $item)
                <tr class="border-b">
                    <td class="px-3 py-2">{{ $item->product->name }}</td>
                    <td class="px-3 py-2">{{ $item->purchase->supplier->name ?? '-' }}</td>
                    <td class="px-3 py-2">{{ $item->quantity }}</td>
                    <td class="px-3 py-2">Rp {{ number_format($item->price_per_unit, 0, ',', '.') }}</td>
                    <td class="px-3 py-2">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @endif
    </div>

    <!-- Barang Keluar -->
    <div class="bg-white p-4 rounded shadow mb-6">
        <h2 class="text-lg font-semibold mb-2">Barang Keluar (Penjualan)</h2>
        @if($barangKeluar->isEmpty())
            <p class="text-gray-500 text-sm">Tidak ada data penjualan.</p>
        @else
        <table class="w-full table-auto text-sm">
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-3 py-2">Produk</th>
                    <th class="px-3 py-2">Kasir</th>
                    <th class="px-3 py-2">Qty</th>
                    <th class="px-3 py-2">Harga Satuan</th>
                    <th class="px-3 py-2">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($barangKeluar as $item)
                <tr class="border-b">
                    <td class="px-3 py-2">{{ $item->product->name }}</td>
                    <td class="px-3 py-2">{{ $item->sale->user->name ?? '-' }}</td>
                    <td class="px-3 py-2">{{ $item->quantity }}</td>
                    <td class="px-3 py-2">Rp {{ number_format($item->price_per_unit, 0, ',', '.') }}</td>
                    <td class="px-3 py-2">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @endif
    </div>

    <!-- Stok Opname -->
    <div class="bg-white p-4 rounded shadow mb-6">
        <h2 class="text-lg font-semibold mb-2">Stok Opname (Semua Produk)</h2>
        <table class="w-full table-auto text-sm">
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-3 py-2">Nama Produk</th>
                    <th class="px-3 py-2">Stok Saat Ini</th>
                    <th class="px-3 py-2">Minimal Stok</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($stok as $product)
                <tr class="border-b">
                    <td class="px-3 py-2">{{ $product->name }}</td>
                    <td class="px-3 py-2">{{ $product->stock }}</td>
                    <td class="px-3 py-2">{{ $product->minimal_stock }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Laporan Keuangan -->
    <div class="bg-white p-4 rounded shadow">
        <h2 class="text-lg font-semibold mb-2">Laporan Keuangan</h2>
        <ul class="text-sm space-y-2">
            <li>💰 Total Pembelian: <strong>Rp {{ number_format($totalPembelian, 0, ',', '.') }}</strong></li>
            <li>💵 Total Penjualan: <strong>Rp {{ number_format($totalPenjualan, 0, ',', '.') }}</strong></li>
            <li>📊 Selisih: <strong class="{{ $selisih >= 0 ? 'text-green-600' : 'text-red-600' }}">
                Rp {{ number_format($selisih, 0, ',', '.') }}</strong>
            </li>
        </ul>
    </div>
</div>
@endsection
