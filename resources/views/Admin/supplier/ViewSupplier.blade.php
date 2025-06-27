@extends('Admin.Layout')

@section('content')
    @include('partials.alert')
    <p>Role kamu: {{ auth()->user()->role }}</p>
    <div class="p-6">
        <div class="flex justify-between items-center mb-4">
            <h1 class="text-2xl font-bold">Daftar Supplier</h1>
            <button class="bg-black hover:bg-gray-800 text-white px-4 py-2 rounded shadow"
                onclick="document.getElementById('modal-supplier').classList.remove('hidden')">
                + Tambah supplier
            </button>
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
                                <button class="bg-blue-600 text-white rounded hover:bg-blue-700 px-4 py-2 w-full"
                                    onclick="openEditModal({{ $supplier->id }}, '{{ $supplier->name }}', '{{ $supplier->contact }}', '{{ $supplier->address }}')">
                                    Edit
                                </button>
                                <form action="{{ route('admin.supplier.destroy', $supplier->id) }}" method="POST"
                                    class="inline" onsubmit="return confirm('Yakin hapus supplier ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="bg-red-600 text-white rounded hover:bg-red-700 px-4 py-2 w-full">
                                        Hapus
                                    </button>
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


        {{-- modals add supplier --}}

        <div id="modal-supplier" class="fixed inset-0 flex items-center justify-center hidden z-50">
            <!-- Background gelap -->
            <div class="absolute inset-0 bg-black opacity-50"></div>

            <!-- Konten modal -->
            <div class="relative bg-white p-6 rounded-lg shadow-lg w-full max-w-md z-10">
                <h2 class="text-xl font-semibold mb-4">Tambah supplier</h2>
                <form action="{{ route('admin.supplier.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="block mb-1 font-medium">Nama</label>
                        <input type="text" name="name" class="w-full border rounded px-3 py-2" required>
                    </div>
                    <div class="mb-3">
                        <label class="block mb-1 font-medium">kontak</label>
                        <input type="text" name="contact" class="w-full border rounded px-3 py-2" required>
                    </div>
                    <div class="mb-3">
                        <label class="block mb-1 font-medium">alamat</label>
                        <input type="text" name="address" class="w-full border rounded px-3 py-2" required>
                    </div>

                    <div class="flex justify-end space-x-2 mt-4">
                        <button type="buAAtton" onclick="document.getElementById('modal-supplier').classList.add('hidden')"
                            class="px-4 py-2 border rounded">Batal</button>
                        <button type="submit"
                            class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Simpan</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Modal Edit supplier -->
        <div id="modal-edit-supplier" class="fixed inset-0 flex items-center justify-center hidden z-50">
            <div class="absolute inset-0 bg-black opacity-50"></div>

            <div class="relative bg-white p-6 rounded-lg shadow-lg w-full max-w-md z-10">
                <h2 class="text-xl font-semibold mb-4">Edit Supplier</h2>
                <form id="edit-supplier-form" method="POST">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="id" id="edit-supplier-id">

                    <div class="mb-3">
                        <label class="block mb-1 font-medium">Nama</label>
                        <input type="text" name="name" id="edit-supplier-name" class="w-full border rounded px-3 py-2"
                            required>
                    </div>
                    <div class="mb-3">
                        <label class="block mb-1 font-medium">Kontak</label>
                        <input type="text" name="contact" id="edit-supplier-contact"
                            class="w-full border rounded px-3 py-2" required>
                    </div>
                    <div class="mb-3">
                        <label class="block mb-1 font-medium">Alamat</label>
                        <input type="text" name="address" id="edit-supplier-address"
                            class="w-full border rounded px-3 py-2" required>
                    </div>

                    <div class="flex justify-end space-x-2 mt-4">
                        <button type="button" onclick="closeEditModal()" class="px-4 py-2 border rounded">Batal</button>
                        <button type="submit"
                            class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @include('partials.errorAlert')
@endsection

@section('script')
    <script>
        function openEditModal(id, name, contact, address) {
            document.getElementById('edit-supplier-id').value = id;
            document.getElementById('edit-supplier-name').value = name;
            document.getElementById('edit-supplier-contact').value = contact;
            document.getElementById('edit-supplier-address').value = address;

            const form = document.getElementById('edit-supplier-form');
            form.action = `/admin/supplier/${id}`; // Laravel expects this for PUT

            document.getElementById('modal-edit-supplier').classList.remove('hidden');
        }

        function closeEditModal() {
            document.getElementById('modal-edit-supplier').classList.add('hidden');
        }
    </script>
@endsection
