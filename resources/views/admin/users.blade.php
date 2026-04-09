@extends('layouts.app')
@section('title', 'Manajemen User')

@section('content')
<div class="mb-6 flex items-center justify-between">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Manajemen User</h1>
        <p class="text-gray-500 mt-1 text-sm">Kelola akun guru dan siswa</p>
    </div>
    <button onclick="document.getElementById('modal-create').classList.remove('hidden')" class="btn-primary">+ Tambah User</button>
</div>

{{-- Search --}}
<form method="GET" action="{{ route('admin.users') }}" class="mb-4">
    <div class="relative max-w-sm">
        <input type="text" name="search" value="{{ $search }}" placeholder="Cari nama atau email..."
            class="w-full border border-gray-300 rounded-lg pl-9 pr-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none">
        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">🔍</span>
    </div>
</form>

<div class="card overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 border-b border-gray-100">
            <tr>
                <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 w-10">No</th>
                <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500">Nama</th>
                <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500">Email</th>
                <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 w-20">Role</th>
                <th class="text-center px-5 py-3 text-xs font-semibold text-gray-500 w-24">Status</th>
                <th class="text-center px-5 py-3 text-xs font-semibold text-gray-500 w-36">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($users as $i => $u)
            <tr class="hover:bg-gray-50">
                <td class="px-5 py-3 text-gray-400">{{ $users->firstItem() + $i }}</td>
                <td class="px-5 py-3 font-medium text-gray-800">{{ $u->name }}</td>
                <td class="px-5 py-3 text-gray-500 text-xs">{{ $u->email }}</td>
                <td class="px-5 py-3">
                    <span class="text-xs px-2 py-0.5 rounded-md font-medium {{ $u->role === 'guru' ? 'bg-indigo-100 text-indigo-600' : 'bg-gray-100 text-gray-600' }}">
                        {{ ucfirst($u->role) }}
                    </span>
                </td>
                <td class="px-5 py-3 text-center">
                    <span class="text-xs px-2 py-0.5 rounded-full font-medium {{ $u->is_active ? 'bg-emerald-100 text-emerald-600' : 'bg-red-100 text-red-600' }}">
                        {{ $u->is_active ? 'Aktif' : 'Nonaktif' }}
                    </span>
                </td>
                <td class="px-5 py-3">
                    <div class="flex items-center justify-center gap-1">
                        <button onclick="openEditUser({{ $u->id }}, '{{ addslashes($u->name) }}', '{{ $u->email }}', '{{ $u->role }}')"
                            class="text-xs text-indigo-500 hover:text-indigo-700 px-2 py-1 rounded hover:bg-indigo-50" title="Edit">✏</button>
                        <form action="{{ route('admin.users.toggle', $u) }}" method="POST" onsubmit="return confirm('Ubah status akun ini?')">
                            @csrf
                            <button class="text-xs text-amber-500 hover:text-amber-700 px-2 py-1 rounded hover:bg-amber-50" title="{{ $u->is_active ? 'Nonaktifkan' : 'Aktifkan' }}">
                                {{ $u->is_active ? '🔒' : '🔓' }}
                            </button>
                        </form>
                        <button onclick="openResetModal({{ $u->id }}, '{{ addslashes($u->name) }}')"
                            class="text-xs text-gray-400 hover:text-gray-600 px-2 py-1 rounded hover:bg-gray-100" title="Reset Password">🔑</button>
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="6" class="text-center py-10 text-gray-400 text-sm">Tidak ada user ditemukan.</td></tr>
            @endforelse
        </tbody>
    </table>
    @if($users->hasPages())
    <div class="p-4 border-t border-gray-100">{{ $users->appends(['search' => $search])->links() }}</div>
    @endif
</div>

{{-- Modal Create --}}
<div id="modal-create" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/50">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-md mx-4">
        <div class="p-6">
            <h2 class="text-lg font-bold mb-4">Tambah User Baru</h2>
            <form action="{{ route('admin.users.store') }}" method="POST" class="space-y-4">
                @csrf
                @include('admin._user_form')
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                    <input type="password" name="password" required minlength="6"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                </div>
                <div class="flex gap-3 justify-end">
                    <button type="button" onclick="document.getElementById('modal-create').classList.add('hidden')" class="btn-outline">Batal</button>
                    <button type="submit" class="btn-primary">Tambah</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Modal Edit --}}
<div id="modal-edit" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/50">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-md mx-4">
        <div class="p-6">
            <h2 class="text-lg font-bold mb-4">Edit User</h2>
            <form id="edit-user-form" action="" method="POST" class="space-y-4">
                @csrf @method('PUT')
                @include('admin._user_form')
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Password Baru <span class="text-gray-400 font-normal">(kosongkan jika tidak diubah)</span></label>
                    <input type="password" name="password" minlength="6"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                </div>
                <div class="flex gap-3 justify-end">
                    <button type="button" onclick="document.getElementById('modal-edit').classList.add('hidden')" class="btn-outline">Batal</button>
                    <button type="submit" class="btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Modal Reset Password --}}
<div id="modal-reset" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/50">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-sm mx-4">
        <div class="p-6">
            <h2 class="text-lg font-bold mb-1">Reset Password</h2>
            <p id="reset-user-name" class="text-sm text-gray-500 mb-4"></p>
            <form id="reset-form" action="" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Password Baru</label>
                    <input type="password" name="password" required minlength="6"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                </div>
                <div class="flex gap-3 justify-end">
                    <button type="button" onclick="document.getElementById('modal-reset').classList.add('hidden')" class="btn-outline">Batal</button>
                    <button type="submit" class="btn-primary">Reset</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
function openEditUser(id, name, email, role) {
    const f = document.getElementById('edit-user-form');
    f.action = '/admin/users/' + id;
    f.querySelector('[name=name]').value = name;
    f.querySelector('[name=email]').value = email;
    f.querySelector('[name=role]').value = role;
    document.getElementById('modal-edit').classList.remove('hidden');
}
function openResetModal(id, name) {
    document.getElementById('reset-user-name').textContent = name;
    document.getElementById('reset-form').action = '/admin/users/' + id + '/reset-password';
    document.getElementById('modal-reset').classList.remove('hidden');
}
</script>
@endpush
