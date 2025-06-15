@extends('Kasir.Layout')

@section('content')
<div class="grid grid-cols-3 gap-6">
    <!-- Sidebar Kiri: List Produk -->
    <div class="col-span-2">
        <div class="flex items-center justify-between mb-4">
            <h1 class="text-2xl font-bold">Buat Transaksi Penjualan</h1>
            <input type="text" id="search" placeholder="Cari produk berdasarkan nama atau ID..." 
                class="w-1/3 px-3 py-2 border rounded focus:ring-2 focus:ring-blue-500">
        </div>
        
        <div class="overflow-auto bg-white rounded shadow">
            <table class="min-w-full text-sm text-left">
                <thead class="bg-gray-100 text-gray-700">
                    <tr>
                        <th class="px-4 py-2">ID</th>
                        <th class="px-4 py-2">Nama</th>
                        <th class="px-4 py-2">Stok</th>
                        <th class="px-4 py-2">Harga</th>
                        <th class="px-4 py-2">Aksi</th>
                    </tr>
                </thead>
                <tbody id="product-table">
                    @foreach ($products as $product)
                    <tr class="border-t">
                        <td class="px-4 py-2">{{ $product->id }}</td>
                        <td class="px-4 py-2">{{ $product->name }}</td>
                        <td class="px-4 py-2">{{ $product->stock }}</td>
                        <td class="px-4 py-2 text-green-600">Rp {{ number_format($product->price, 0, ',', '.') }}</td>
                        <td class="px-4 py-2">
                            <button class="bg-blue-600 text-white px-3 py-1 rounded text-sm hover:bg-blue-700 add-to-cart"
                                    data-id="{{ $product->id }}"
                                    data-name="{{ $product->name }}"
                                    data-price="{{ $product->price }}">
                                Tambah
                            </button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Sidebar Kanan: Keranjang & Total -->
    <div>
        <div class="bg-white p-4 rounded shadow sticky top-6">
            <h2 class="text-lg font-semibold mb-3">Item Transaksi</h2>
            <div id="cart-items" class="space-y-4 text-sm max-h-96 overflow-y-auto"></div>
            <div class="border-t pt-3 mt-3 font-semibold">
                Total: Rp <span id="total">0</span>
            </div>
            <button id="save-transaction" class="mt-4 w-full bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded">Simpan Transaksi</button>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
    let cart = [];

    function updateCart() {
        const container = document.getElementById('cart-items');
        const totalElem = document.getElementById('total');
        container.innerHTML = '';
        let total = 0;

        cart.forEach((item, index) => {
            total += item.price * item.quantity;
            container.innerHTML += `
                <div class="flex justify-between items-center">
                    <div>
                        <p>${item.name}</p>
                        <p class="text-gray-500">Qty: ${item.quantity}</p>
                    </div>
                    <div class="text-right">
                        <p>Rp ${(item.price * item.quantity).toLocaleString()}</p>
                        <button onclick="removeItem(${index})" class="text-red-500 text-xs">Hapus</button>
                    </div>
                </div>`;
        });

        totalElem.textContent = total.toLocaleString();
    }

        function removeItem(index) {
            if (cart[index].quantity > 1) {
                cart[index].quantity--;
            } else {
                cart.splice(index, 1);
            }
            updateCart();
        }

    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.add-to-cart').forEach(button => {
            button.addEventListener('click', () => {
                const id = button.dataset.id;
                const name = button.dataset.name;
                const price = parseInt(button.dataset.price);
                const existing = cart.find(item => item.id === id);

                if (existing) {
                    existing.quantity++;
                } else {
                    cart.push({ id, name, price, quantity: 1 });
                }

                updateCart();
            });
        });

        // Pencarian
        const searchInput = document.getElementById('search');
        const productRows = document.querySelectorAll('#product-table tr');

        searchInput.addEventListener('input', function () {
            const value = this.value.toLowerCase();
            productRows.forEach(row => {
                const text = row.innerText.toLowerCase();
                row.style.display = text.includes(value) ? '' : 'none';
            });
        });
    });

    document.getElementById('save-transaction').addEventListener('click', function () {
    if (cart.length === 0) {
        alert("Keranjang kosong!");
        return;
    }

    fetch('/kasir/transaksi', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}' // penting untuk keamanan Laravel
        },
        body: JSON.stringify({ items: cart }) // kirim seluruh isi keranjang
    })
    .then(response => {
        if (!response.ok) throw new Error('Gagal menyimpan transaksi.');
        return response.json();
    })
    .then(data => {
        alert('Transaksi berhasil disimpan!');
        cart = []; // kosongkan keranjang
        updateCart(); // refresh tampilan keranjang
    })
    .catch(error => {
        console.error('Terjadi error:', error);
        alert('Terjadi kesalahan saat menyimpan transaksi.');
    });
});

</script>
@endsection
