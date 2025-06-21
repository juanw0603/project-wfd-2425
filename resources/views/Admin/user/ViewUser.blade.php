@extends('Admin.Layout')

@section('content')

@include('partials.alert')


<div class="p-6">
<div class="flex justify-between items-center mb-4">
    <h1 class="text-2xl font-bold">Daftar Users</h1>

    <button class="bg-black hover:bg-gray-800 text-white px-4 py-2 rounded shadow" 
            onclick="document.getElementById('modal-user').classList.remove('hidden')">
        + Tambah Users
    </button>
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
                        <button class="text-blue-600 hover:underline"
                            onclick="openEditModal({{ $user->id }}, '{{ $user->name }}', '{{ $user->email }}', '{{ $user->role }}')">
                            Edit
                        </button>
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
                    <td colspan="6" class="px-4 py-4 text-center text-gray-500">Belum ada data User.</td>
                </tr>
                @endif
            </tbody>
        </table>
    </div>
</div>

{{-- modals add user --}}

<div id="modal-user" class="fixed inset-0 flex items-center justify-center hidden z-50">
    <!-- Background gelap -->
    <div class="absolute inset-0 bg-black opacity-50"></div>

    <!-- Konten modal -->
    <div class="relative bg-white p-6 rounded-lg shadow-lg w-full max-w-md z-10">
        <h2 class="text-xl font-semibold mb-4">Tambah User</h2>
        <form action="{{ route('admin.user.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label class="block mb-1 font-medium">Nama</label>
                <input type="text" name="name" class="w-full border rounded px-3 py-2" required>
            </div>
            <div class="mb-3">
                <label class="block mb-1 font-medium">Email</label>
                <input type="email" name="email" class="w-full border rounded px-3 py-2" required>
            </div>
            <div class="mb-3">
                <label class="block mb-1 font-medium">Password</label>
                <input type="password" name="password" class="w-full border rounded px-3 py-2" required>
            </div>
            <div class="mb-3">
                <label class="block mb-1 font-medium">Role</label>
                <select name="role" class="w-full border rounded px-3 py-2" required>
                    <option value="kasir">Kasir</option>
                    <option value="gudang">Gudang</option>
                </select>
            </div>

            <div class="flex justify-end space-x-2 mt-4">
                <button type="button" onclick="document.getElementById('modal-user').classList.add('hidden')" class="px-4 py-2 border rounded">Batal</button>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Simpan</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Edit User -->
<div id="modal-edit-user" class="fixed inset-0 flex items-center justify-center hidden z-50">
    <!-- Background gelap -->
    <div class="absolute inset-0 bg-black opacity-50"></div>

    <!-- Konten modal -->
    <div class="relative bg-white p-6 rounded-lg shadow-lg w-full max-w-md z-10">
        <h2 class="text-xl font-semibold mb-4">Edit User</h2>
        <form id="edit-user-form" method="POST">
            @csrf
            @method('PUT')
            <input type="hidden" name="id" id="edit-user-id">
            
            <div class="mb-3">
                <label class="block mb-1 font-medium">Nama</label>
                <input type="text" name="name" id="edit-user-name" class="w-full border rounded px-3 py-2" required>
            </div>
            <div class="mb-3">
                <label class="block mb-1 font-medium">Email</label>
                <input type="email" name="email" id="edit-user-email" class="w-full border rounded px-3 py-2" required>
            </div>
            <div class="mb-3">
                <label class="block mb-1 font-medium">Role</label>
                <select name="role" id="edit-user-role" class="w-full border rounded px-3 py-2" required>
                    <option value="kasir">Kasir</option>
                    <option value="gudang">Gudang</option>
                </select>
            </div>

            <div class="flex justify-end space-x-2 mt-4">
                <button type="button" onclick="document.getElementById('modal-edit-user').classList.add('hidden')" class="px-4 py-2 border rounded">Batal</button>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Simpan</button>
            </div>
        </form>
    </div>
</div>

@endsection

@section('script')
    <script>
        function openEditModal(id, name, email, role) {
            document.getElementById('edit-user-id').value = id;
            document.getElementById('edit-user-name').value = name;
            document.getElementById('edit-user-email').value = email;
            document.getElementById('edit-user-role').value = role;

            const form = document.getElementById('edit-user-form');
            form.action = `/admin/user/${id}`;

            document.getElementById('modal-edit-user').classList.remove('hidden');
        }

        function closeEditModal() {
            document.getElementById('modal-edit-user').classList.add('hidden');
        }
    </script>

@endsection