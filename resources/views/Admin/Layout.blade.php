<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    @vite('resources/css/app.css')
</head>
<body class="bg-gray-100">

    <div class="flex min-h-screen">
        <!-- Sidebar -->
        <aside class="w-64 bg-gray-600 shadow-lg flex flex-col h-screen">
            <!-- Top menu -->
            <div class="p-6 font-bold text-xl border-b">Admin Dashboard</div>
            <nav class="p-4 space-y-2 flex-1">
                <a href="{{ route('admin.dashboard') }}" class="block px-4 py-2 rounded hover:bg-gray-500">Dashboard</a>
                <a href="{{ route('admin.product.index') }}" class="block px-4 py-2 rounded hover:bg-gray-500">Products</a>
                <a href="{{ route('admin.supplier.index') }}" class="block px-4 py-2 rounded hover:bg-gray-500">Suppliers</a>
                <a href="{{ route('admin.user.index') }}" class="block px-4 py-2 rounded hover:bg-gray-500">Users</a>
                <a href="#" class="block px-4 py-2 rounded hover:bg-gray-200">Laporan</a>
            </nav>

            <!-- Logout button di bawah -->
            <form method="GET" action="{{ route('logout') }}" class="p-4 mt-auto border-t">
                @csrf
                <button type="submit" class="w-full flex text-left px-4 py-2 rounded hover:bg-gray-500 text-red-600 font-semibold">
                    Logout
                </button>
            </form>
        </aside>


    <!-- Main Content -->
    <main class="flex-1 p-6 h-screen overflow-y-auto">
        
    @yield('content')


    </main>
</div>
    @yield('script')
</body>
</html>
