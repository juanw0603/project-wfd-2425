@extends('Kasir.Layout')

@section('content')
<div class="p-6 space-y-6">
    <h1 class="text-2xl font-bold mb-4">Laporan Penjualan</h1>

    <!-- Filter -->
    <form method="GET" action="{{ route('kasir.laporan-transaksi.page') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4 bg-white p-4 rounded shadow">
        <div>
            <label class="block text-sm font-medium text-gray-700">Tanggal Mulai</label>
            <input type="date" name="start_date" value="{{ request('start_date') }}" class="w-full border rounded px-3 py-2">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700">Tanggal Selesai</label>
            <input type="date" name="end_date" value="{{ request('end_date') }}" class="w-full border rounded px-3 py-2">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700">Kasir</label>
            <select name="user_id" class="w-full border rounded px-3 py-2">
                <option value="">Semua Kasir</option>
                @foreach($kasirs as $kasir)
                    <option value="{{ $kasir->id }}" {{ request('user_id') == $kasir->id ? 'selected' : '' }}>
                        {{ $kasir->name }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="flex items-end">
            <button type="submit" class="w-full bg-black text-white px-4 py-2 rounded hover:bg-gray-800">
                Filter
            </button>
        </div>
    </form>

    <!-- Statistik -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="bg-white p-4 rounded shadow">
            <div class="text-sm text-gray-500">Total Penjualan</div>
            <div class="text-xl font-semibold text-green-700">Rp {{ number_format($totalPenjualan, 0, ',', '.') }}</div>
        </div>
        <div class="bg-white p-4 rounded shadow">
            <div class="text-sm text-gray-500">Jumlah Transaksi</div>
            <div class="text-xl font-semibold">{{ $jumlahTransaksi }}</div>
        </div>
        <div class="bg-white p-4 rounded shadow">
            <div class="text-sm text-gray-500">Rata-rata per Transaksi</div>
            <div class="text-xl font-semibold">Rp {{ number_format($rataRata, 0, ',', '.') }}</div>
        </div>
    </div>

    <!-- Tabel -->
    <div class="bg-white rounded shadow overflow-x-auto">
        <table class="min-w-full text-sm text-left">
            <thead class="bg-gray-100 text-gray-700">
                <tr>
                    <th class="px-4 py-2">No Invoice</th>
                    <th class="px-4 py-2">Tanggal</th>
                    <th class="px-4 py-2">Waktu</th>
                    <th class="px-4 py-2">Kasir</th>
                    <th class="px-4 py-2">Pelanggan</th>
                    <th class="px-4 py-2">Total</th>

                </tr>
            </thead>
            <tbody>
                @forelse ($sales as $sale)
                <tr class="border-t">
                    <td class="px-4 py-2">INV-{{ $sale->id }}</td>
                    <td class="px-4 py-2">{{ \Carbon\Carbon::parse($sale->sale_date)->format('d M Y') }}</td>
                    <td class="px-4 py-2">{{ \Carbon\Carbon::parse($sale->sale_date)->format('H:i') }}</td>
                    <td class="px-4 py-2">{{ $sale->user->name }}</td>
                    <td class="px-4 py-2">{{ $sale->customer_name ?? '-' }}</td>
                    <td class="px-4 py-2 text-green-700">Rp {{ number_format($sale->total_price, 0, ',', '.') }}</td>
                    {{-- <td class="px-4 py-2">
                        <span class="text-green-600 font-medium">Selesai</span>
                    </td>
                    <td class="px-4 py-2">
                        <a href="#" class="text-blue-600 hover:underline">Detail</a>
                    </td> --}}
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center py-4 text-gray-500">Tidak ada transaksi ditemukan.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="mt-4">
        {{ $sales->links() }}
    </div>
</div>
@endsection
