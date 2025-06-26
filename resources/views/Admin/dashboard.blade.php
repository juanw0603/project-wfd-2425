@extends('Admin.Layout')
@auth
    @section('content')
        <div class="p-6 overflow">
            <h1 class="text-2xl font-bold mb-4">Dashboard Overview</h1>
            <p class="text-gray-600 mb-8">Welcome back {{ Auth::user()->name }}! Here's what’s happening with your business
                today.</p>

            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
                <div class="bg-white p-4 rounded shadow transform transition duration-300 hover:scale-110">
                    <p class="text-sm text-black">Total Sales</p>
                    <h2 class="text-xl font-bold text-black">Rp {{ number_format($totalPenjualan ?? 0, 0, ',', '.') }}</h2>
                    <p class="text-xs text-black mt-1">↑ +12.5% from last month</p>
                </div>
                <div class="bg-white p-4 rounded shadow transform transition duration-300 hover:scale-110">
                    <p class="text-sm text-black">Total Purchase</p>
                    <h2 class="text-xl font-bold text-black">Rp {{ number_format($totalPembelian ?? 0, 0, ',', '.') }}</h2>
                    <p class="text-xs text-black mt-1">↓ -3.2% from last month</p>
                </div>
                <div class="bg-white p-4 rounded shadow transform transition duration-300 hover:scale-110">
                    <p class="text-sm text-black">Total Products</p>
                    <h2 class="text-xl font-bold text-black">{{ $totalProduk }}</h2>
                    <p class="text-xs text-black mt-1">+8.1% from last month</p>
                </div>
                <div class="bg-white p-4 rounded shadow transform transition duration-300 hover:scale-110">
                    <p class="text-sm text-black">Active Users</p>
                    <h2 class="text-xl font-bold text-black">{{ $totalPengguna }}</h2>
                    <p class="text-xs text-black mt-1">+4.5% from last month</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <div class="bg-white p-6 rounded shadow">
                    <div class="flex justify-between mb-4">
                        <h2 class="text-lg font-semibold">Sales Overview</h2>
                        <form method="GET" action="{{ route('admin.dashboard.page') }}">
                            <select name="range" onchange="this.form.submit()" class="text-sm border-gray-300 rounded">
                                <option value="7" {{ request('range', 7) == 7 ? 'selected' : '' }}>Last 7 days</option>
                                <option value="30" {{ request('range', 7) == 30 ? 'selected' : '' }}>Last 30 days</option>
                            </select>
                        </form>

                    </div>
                    @if (count($salesTotals) > 0)
                        <canvas id="salesChart" height="150"></canvas>
                    @else
                        <div class="h-48 flex items-center justify-center text-gray-400 text-sm border-2 border-dashed rounded">
                            Tidak ada data penjualan.
                        </div>
                    @endif

                </div>

                <div class="bg-white p-6 rounded shadow">
                    <div class="flex justify-between mb-4">
                        <h2 class="text-lg font-semibold">Purchase Analysis</h2>
                        <form method="GET" action="{{ route('admin.dashboard.page') }}">
                            <select name="range" onchange="this.form.submit()" class="text-sm border-gray-300 rounded">
                                <option value="7" {{ request('range', 7) == 7 ? 'selected' : '' }}>Last 7 days</option>
                                <option value="30" {{ request('range', 7) == 30 ? 'selected' : '' }}>Last 30 days</option>
                            </select>
                        </form>
                    </div>
                    @if (count($purchaseTotals) > 0)
                        <canvas id="purchaseChart" height="150"></canvas>
                    @else
                        <div class="h-48 flex items-center justify-center text-gray-400 text-sm border-2 border-dashed rounded">
                            Tidak ada data pembelian.
                        </div>
                    @endif

                </div>
            </div>

            <div class="bg-white p-6 rounded shadow">
                <h2 class="text-lg font-semibold mb-4">Recent Activity</h2>
                <ul class="text-sm text-gray-600 space-y-2">
                    <li>🛒 New order <span class="font-semibold">#12345</span> received</li>
                    <li>📦 Stock updated for product <span class="font-semibold">"Pens"</span></li>
                    <li>👤 User <span class="font-semibold">"kasir1"</span> logged in</li>
                </ul>
            </div>
        </div>
    @endsection
@endauth

@section('script')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('salesChart').getContext('2d');
        const salesChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: {!! json_encode($salesDates) !!},
                datasets: [{
                    label: 'Total Penjualan',
                    data: {!! json_encode($salesTotals) !!},
                    backgroundColor: 'rgba(59, 130, 246, 0.2)',
                    borderColor: 'rgba(59, 130, 246, 1)',
                    borderWidth: 2,
                    tension: 0.4
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return 'Rp ' + value.toLocaleString('id-ID');
                            }
                        }
                    }
                }
            }
        });


        const ctxPurchase = document.getElementById('purchaseChart')?.getContext('2d');
        if (ctxPurchase) {
            new Chart(ctxPurchase, {
                type: 'bar',
                data: {
                    labels: {!! json_encode($purchaseDates) !!},
                    datasets: [{

                        data: {!! json_encode($purchaseTotals) !!},
                        backgroundColor: 'rgba(34, 197, 94, 0.5)',
                        borderColor: 'rgba(34, 197, 94, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return 'Rp ' + Number(value).toLocaleString('id-ID');
                                }
                            }
                        }
                    }
                }
            });
        }
    </script>
@endsection
