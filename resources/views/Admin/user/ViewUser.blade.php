@extends('Admin.Layout')

@section('content')
<div class="p-6">
<div class="flex justify-between items-center mb-4">
    <h1 class="text-2xl font-bold">Daftar Users</h1>

    <a href="{{ route('admin.product.create') }}" class="bg-black hover:bg-gray-800 text-white px-4 py-2 rounded shadow">
        + Tambah Users
    </a>
</div>


    <div class="overflow-x-auto bg-white rounded shadow">
        <table class="min-w-full divide-y divide-gray-200 text-sm">
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-4 py-2 text-left font-semibold text-gray-600">#</th>
                    <th class="px-4 py-2 text-left font-semibold text-gray-600">Nama</th>
                    <th class="px-4 py-2 text-left font-semibold text-gray-600">Email</th>
                    <th class="px-4 py-2 text-left font-semibold text-gray-600">Role</th>
                    <th class="px-4 py-2 text-left font-semibold text-gray-600  ">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @foreach ($users as $index => $user)
                <tr>
                    <td class="px-4 py-2">{{ $index + 1 }}</td>
                    <td class="px-4 py-2">{{ $user->name }}</td>
                    <td class="px-4 py-2">{{ $user->email}}</td>
                    <td class="px-4 py-2">{{ $user->role }}</td>
                    <td class="px-4 py-2 space-x-2">
                        <a href="{{ route('admin.user.update', $user->id) }}" class="text-blue-600 hover:underline">Edit</a>
                        <form action="{{ route('admin.user.destroy', $user->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Yakin hapus?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:underline">Hapus</button>
                        </form>
                    </td>
                </tr>
                @endforeach

                @if($users->isEmpty())
                <tr>
                    <td colspan="6" class="px-4 py-4 text-center text-gray-500">Belum ada data produk.</td>
                </tr>
                @endif
            </tbody>
        </table>
    </div>
</div>
@endsection
