@extends('Gudang.Layout')

@section('content')
    @include('partials.alert')
    <div class="p-6">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
            <div class="md:col-span-3 bg-white p-6 rounded shadow">
                <h2 class="text-lg font-semibold mb-4">New Purchase Transaction</h2>
                <form id="purchase-form" action="{{ route('gudang.purchase.proses') }}" method="POST">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block font-medium mb-1">Supplier</label>
                            <select name="supplier_id" class="w-full border rounded px-3 py-2" required>
                                <option value="">Select Supplier</option>
                                @foreach ($suppliers as $supplier)
                                    <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block font-medium mb-1">Product Name</label>
                            <select name="product_id" class="w-full border rounded px-3 py-2 select2" required>
                                <option value="">Pilih atau cari produk...</option>
                                @foreach ($products as $product)
                                    <option value="{{ $product->id }}">{{ $product->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block font-medium mb-1">Quantity</label>
                            <input type="number" name="quantity" class="w-full border rounded px-3 py-2" min="1"
                                required>
                        </div>
                        <div>
                            <label class="block font-medium mb-1">Unit Price</label>
                            <input type="number" name="unit_price" class="w-full border rounded px-3 py-2" min="0"
                                required>
                        </div>
                    </div>
                    <div class="mb-4">
                        <label class="block font-medium mb-1">Notes</label>
                        <textarea name="notes" class="w-full border rounded px-3 py-2" rows="2" placeholder="Additional notes..."></textarea>
                    </div>
                    <div class="flex justify-end gap-2">
                        <button type="button" onclick="cancelForm()" class="px-4 py-2 border rounded">Cancel</button>
                        <button type="submit" class="px-4 py-2 bg-black text-white rounded">+ Add Transaction</button>
                    </div>
                </form>
            </div>

            {{-- Low Stock Alerts --}}
            <div class="bg-white p-4 rounded shadow">
                <h3 class="text-md font-semibold mb-3">Low Stock Alerts</h3>
                <ul class="space-y-2 text-sm">
                    @foreach ($lowStockProducts as $product)
                        <li class="flex justify-between items-center border-b pb-2">
                            <div>
                                <strong>{{ $product->name }}</strong><br>
                                <span class="text-gray-600">Current stock: {{ $product->stock }}</span><br>
                                <span class="text-red-500">⚠ Below minimal ({{ $product->minimal_stock }})</span>
                            </div>
                            <span class="text-red-600 font-bold animate-pulse">⚠</span>
                        </li>
                    @endforeach
                </ul>
                @if (count($lowStockProducts) > 0)
                    <button onclick="document.getElementById('modal-alerts').classList.remove('hidden')"
                        class="block text-center mt-4 bg-gray-100 rounded px-3 py-2 hover:bg-gray-200 w-full">
                        View All Alerts
                    </button>
                @endif
            </div>
        </div>

        {{-- Tabel Transaksi Terbaru --}}
        <div class="bg-white p-6 rounded shadow mb-6">
            <h2 class="text-md font-semibold mb-4">Recent Transactions</h2>
            <table class="w-full table-auto text-sm">
                <thead>
                    <tr class="bg-gray-100 text-left">
                        <th class="px-3 py-2">Date</th>
                        <th class="px-3 py-2">Supplier</th>
                        <th class="px-3 py-2">Product</th>
                        <th class="px-3 py-2">Quantity</th>
                        <th class="px-3 py-2">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($recentPurchases as $purchase)
                        <tr class="border-b">
                            <td class="px-3 py-2">{{ $purchase->purchase_date }}</td>
                            <td class="px-3 py-2">{{ $purchase->supplier->name }}</td>
                            <td class="px-3 py-2">{{ $purchase->items->first()->product->name ?? '-' }}</td>
                            <td class="px-3 py-2">{{ $purchase->items->first()->quantity ?? '-' }}</td>
                            <td class="px-3 py-2">Rp {{ number_format($purchase->total_price, 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Ringkasan Hari Ini --}}
        <div class="bg-white p-6 rounded shadow">
            <h2 class="text-md font-semibold mb-4">Today's Summary</h2>
            <ul class="text-sm space-y-2">
                <li>📦 Transactions: <strong>{{ $summary['transactions'] }}</strong></li>
                <li>💰 Total Value: <strong>Rp {{ number_format($summary['total'], 0, ',', '.') }}</strong></li>
                <li>🏢 Suppliers: <strong>{{ $summary['suppliers'] }}</strong></li>
            </ul>
        </div>
    </div>

    <!-- Modal Low Stock Alerts -->
    <div id="modal-alerts" class="fixed inset-0 flex items-center justify-center hidden z-50">
        <!-- Background gelap seperti Tambah User -->
        <div class="absolute inset-0 bg-black opacity-50"></div>

        <!-- Konten modal -->
        <div class="relative bg-white p-6 rounded-lg shadow-lg w-full max-w-md z-10">
            <h2 class="text-xl font-semibold mb-4">Semua Produk Stok Rendah</h2>
            <ul class="space-y-3 text-sm max-h-64 overflow-y-auto">
                @forelse ($lowStockProducts as $product)
                    <li class="border-b pb-2">
                        <strong>{{ $product->name }}</strong><br>
                        Stok saat ini: {{ $product->stock }}<br>
                        <span class="text-red-500">Batas minimum: {{ $product->minimal_stock }}</span>
                    </li>
                @empty
                    <li class="text-gray-500">Tidak ada produk dengan stok rendah.</li>
                @endforelse
            </ul>
            <div class="flex justify-end mt-4">
                <button type="button" onclick="document.getElementById('modal-alerts').classList.add('hidden')"
                    class="px-4 py-2 border rounded">Tutup</button>
            </div>
        </div>
    </div>
@endsection


@push('styles')
<style>
/* Bikin Select2 tampil seperti input Tailwind */
.select2-container--default .select2-selection--single {
    height: 42px;
    padding: 6px 12px;
    border: 1px solid #000000;
    border-radius: 0.375rem;
    font-size: 0.875rem;
    line-height: 1.25rem;
}
.select2-container--default .select2-selection--single .select2-selection__rendered {
    line-height: 2rem;
    padding-left: 0.5rem;
    padding-right: 0.5rem;
    color: #111827;
}
.select2-container--default .select2-selection--single .select2-selection__arrow {
    height: 100%;
    right: 10px;
}
</style>
@endpush

@section('script')
    <script>
        function cancelForm() {
            if (confirm("Batalkan dan bersihkan form?")) {
                document.getElementById('purchase-form').reset();
                window.scrollTo({
                    top: 0,
                    behavior: 'smooth'
                });
            }
        }


        $(document).ready(function() {
            $('.select2').select2({
                placeholder: 'Pilih atau cari produk...',
                width: '100%'
            });
        });
    </script>
@endsection
