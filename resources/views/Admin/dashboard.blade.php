@extends('Admin.Layout')
@auth 
@section('content')
<div class="p-6 overflow">
    <h1 class="text-2xl font-bold mb-4">Dashboard Overview</h1>
    <p class="text-gray-600 mb-8">Welcome back {{Auth::user()->name}}! Here's what’s happening with your business today.</p>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
        <div class="bg-white p-4 rounded shadow transform transition duration-300 hover:scale-110">
            <p class="text-sm text-gray-500">Total Sales</p>
            <h2 class="text-xl font-bold text-green-600">Rp {{ number_format($totalPenjualan ?? 0, 0, ',', '.') }}</h2>
            <p class="text-xs text-green-500 mt-1">↑ +12.5% from last month</p>
        </div>
        <div class="bg-white p-4 rounded shadow transform transition duration-300 hover:scale-110">
            <p class="text-sm text-gray-500">Total Purchase</p>
            <h2 class="text-xl font-bold text-red-600">Rp {{ number_format($totalPembelian ?? 0, 0, ',', '.') }}</h2>
            <p class="text-xs text-red-500 mt-1">↓ -3.2% from last month</p>
        </div>
        <div class="bg-white p-4 rounded shadow transform transition duration-300 hover:scale-110">
            <p class="text-sm text-gray-500">Total Products</p>
            <h2 class="text-xl font-bold">{{ $totalProduk }}</h2>
            <p class="text-xs text-gray-400 mt-1">+8.1% from last month</p>
        </div>
        <div class="bg-white p-4 rounded shadow transform transition duration-300 hover:scale-110">
            <p class="text-sm text-gray-500">Active Users</p>
            <h2 class="text-xl font-bold">{{ $totalPengguna }}</h2>
            <p class="text-xs text-gray-400 mt-1">+4.5% from last month</p>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
        <div class="bg-white p-6 rounded shadow">
            <div class="flex justify-between mb-4">
                <h2 class="text-lg font-semibold">Sales Overview</h2>
                <select class="text-sm border-gray-300 rounded">
                    <option>Last 7 days</option>
                    <option>Last 30 days</option>
                </select>
            </div>
            <div class="h-48 flex items-center justify-center text-gray-400 text-sm border-2 border-dashed rounded">
                <svg class="w-8 h-8 mb-2" fill="none" stroke="currentColor" stroke-width="1.5"
                    viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M9 17v-2a2 2 0 00-2-2H4m16 0h-3a2 2 0 00-2 2v2m4-10v2a2 2 0 01-2 2h-3a2 2 0 01-2-2V7m-4 0v2a2 2 0 01-2 2H4a2 2 0 01-2-2V7"></path>
                </svg>
                <span>Empty Sales</span>
            </div>
        </div>

        <div class="bg-white p-6 rounded shadow">
            <div class="flex justify-between mb-4">
                <h2 class="text-lg font-semibold">Purchase Analysis</h2>
                <select class="text-sm border-gray-300 rounded">
                    <option>Last 7 days</option>
                    <option>Last 30 days</option>
                </select>
            </div>
            <div class="h-48 flex items-center justify-center text-gray-400 text-sm border-2 border-dashed rounded">
                Purchase Chart Visualization
            </div>
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