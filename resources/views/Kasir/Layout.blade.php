<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Cashier Dashboard</title>
    @vite('resources/css/app.css')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="bg-gray-100 text-gray-800">

    <div class="flex min-h-screen">
        <!-- Sidebar -->
        <aside class="w-64 bg-white shadow p-4 flex flex-col h-screen">
            <h2 class="text-lg font-semibold mb-4">Cashier Dashboard</h2>
            <nav class="space-y-2">
                <a href="" class="block py-2 px-3 rounded hover:bg-gray-200">Sales Transactions</a>
                <a href="#" class="block py-2 px-3 rounded hover:bg-gray-200">Sales Reports</a>
            </nav>

            <!-- Logout button di bawah -->
            <form method="GET" action="{{ route('logout') }}" class="p-4 mt-auto border-t">
                @csrf
                <button type="submit" class="w-full flex text-left px-4 py-2 rounded hover:bg-red-100 text-red-600 font-semibold">
                    Logout
                </button>
            </form>
        </aside>

        <!-- Main Content -->
        <div class="flex-1 p-6 h-screen overflow-y-auto">
            @yield('content')
        </div>
    </div>

    @yield('script')
</body>
</html>
