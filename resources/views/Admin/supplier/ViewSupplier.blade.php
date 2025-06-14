@extends('Admin.Layout')

@section('content')
<div class="p-6">
    <div class="flex justify-between items-center mb-4">
        <h1 class="text-2xl font-bold">Daftar Supplier</h1>
        <a href="{{ route('admin.supplier.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition">
            + Tambah Supplier
        </a>
    </div>

    <div class="overflow-x-auto bg-white rounded shadow">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">#</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Nama</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Kontak</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Alamat</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse ($suppliers as $supplier)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4">{{ $loop->iteration }}</td>
                        <td class="px-6 py-4">{{ $supplier->name }}</td>
                        <td class="px-6 py-4">{{ $supplier->contact }}</td>
                        <td class="px-6 py-4">{{ $supplier->address }}</td>
                        <td class="px-6 py-4 space-x-2">
                            <a href="{{ route('admin.supplier.edit', $supplier->id) }}" class="text-blue-600 hover:underline text-sm">Edit</a>
                            <form action="{{ route('admin.supplier.destroy', $supplier->id) }}" method="POST" class="inline" onsubmit="return confirm('Yakin hapus supplier ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:underline text-sm">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-4 text-center text-gray-500">Belum ada data supplier.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
