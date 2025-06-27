@extends('Gudang.Layout')

@section('content')
<div class="p-6">
    <h1 class="text-xl font-semibold mb-4">Laporan Pembelian</h1>
    <p class="text-gray-600 mb-6">Kelola dan lihat laporan pembelian berdasarkan periode dan supplier</p>

    {{-- Filter --}}
    <form method="GET" class="bg-white p-4 rounded shadow mb-6 flex flex-wrap items-end gap-4">
        <div class="flex flex-col">
            <label class="text-sm font-medium mb-1">Tanggal Mulai</label>
            <input type="date" name="start_date" value="{{ request('start_date') }}"
                class="border rounded px-3 py-2 text-sm">
        </div>
        <div class="flex flex-col">
            <label class="text-sm font-medium mb-1">Tanggal Akhir</label>
            <input type="date" name="end_date" value="{{ request('end_date') }}"
                class="border rounded px-3 py-2 text-sm">
        </div>
        <div class="flex flex-col">
            <label class="text-sm font-medium mb-1">Supplier</label>
            <select name="supplier_id" class="border rounded px-3 py-2 text-sm">
                <option value="">Semua Supplier</option>
                @foreach ($suppliers as $supplier)
                    <option value="{{ $supplier->id }}" {{ request('supplier_id') == $supplier->id ? 'selected' : '' }}>
                        {{ $supplier->name }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="flex gap-2 mt-4 md:mt-0">
            <button type="submit" class="bg-black text-white px-4 py-2 rounded">Terapkan Filter</button>
            <a href="{{ route('gudang.laporan-purchase.page') }}"
                class="border px-4 py-2 rounded text-gray-600 hover:bg-gray-100">Reset</a>
            <a href="#" class="border px-4 py-2 rounded text-gray-600 hover:bg-gray-100">Export Excel</a>
        </div>
    </form>

    {{-- Ringkasan --}}
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white p-4 rounded shadow text-center">
            <p class="text-sm text-gray-600">Total Pembelian</p>
            <h2 class="text-lg font-bold text-gray-800">Rp {{ number_format($summary['total'], 0, ',', '.') }}</h2>
        </div>
        <div class="bg-white p-4 rounded shadow text-center">
            <p class="text-sm text-gray-600">Jumlah Transaksi</p>
            <h2 class="text-lg font-bold text-gray-800">{{ $summary['count'] }}</h2>
        </div>
        <div class="bg-white p-4 rounded shadow text-center">
            <p class="text-sm text-gray-600">Rata-rata per Transaksi</p>
            <h2 class="text-lg font-bold text-gray-800">Rp {{ number_format($summary['average'], 0, ',', '.') }}</h2>
        </div>
        <div class="bg-white p-4 rounded shadow text-center">
            <p class="text-sm text-gray-600">Supplier Aktif</p>
            <h2 class="text-lg font-bold text-gray-800">{{ $summary['active_suppliers'] }}</h2>
        </div>
    </div>

    {{-- Detail Tabel --}}
    <div class="bg-white p-4 rounded shadow">
        <h2 class="text-md font-semibold mb-4">Detail Laporan Pembelian</h2>
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-3 py-2">No. Transaksi</th>
                        <th class="px-3 py-2">Tanggal</th>
                        <th class="px-3 py-2">Supplier</th>
                        <th class="px-3 py-2">Total Item</th>
                        <th class="px-3 py-2">Total Harga</th>

                    </tr>
                </thead>
                <tbody>
                    @forelse ($purchases as $purchase)
                        <tr class="border-b">
                            <td class="px-3 py-2">PO-{{ str_pad($purchase->id, 4, '0', STR_PAD_LEFT) }}</td>
                            <td class="px-3 py-2">{{ \Carbon\Carbon::parse($purchase->purchase_date)->format('d M Y') }}</td>
                            <td class="px-3 py-2">{{ $purchase->supplier->name }}</td>
                            <td class="px-3 py-2">{{ $purchase->items->sum('quantity') }}</td>
                            <td class="px-3 py-2">Rp {{ number_format($purchase->total_price, 0, ',', '.') }}</td>

                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-gray-500 py-4">Tidak ada data.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <div class="mt-4">
            {{ $purchases->withQueryString()->links() }}
        </div>
    </div>
</div>
@endsection
