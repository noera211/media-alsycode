@extends('layouts.app')
@section('title', 'Manajemen User')

@section('content')

{{-- HEADER --}}
<div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Manajemen User</h1>
        <p class="text-gray-500 mt-1 text-sm">Kelola akun guru dan siswa</p>
    </div>

    {{-- BUTTON TAMBAH USER (Diperbarui dengan OpenModal & Lucide) --}}
    <button
        onclick="openModal('modal-create')"
        class="btn-primary text-sm flex items-center gap-2 px-4 py-2 bg-indigo-600 text-white rounded-xl hover:bg-indigo-700 transition">
        <i data-lucide="user-plus" class="w-4 h-4"></i>
        Tambah User
    </button>
</div>

{{-- SEARCH --}}
<form method="GET" action="{{ route('admin.users') }}" class="mb-5">
    <div class="flex flex-col sm:flex-row gap-2 max-w-xl">

        {{-- Input Search --}}
        <div class="relative flex-1">
            <input
                type="text"
                name="search"
                value="{{ $search }}"
                placeholder="Cari nama atau email..."
                class="w-full border border-gray-300 rounded-xl pl-10 pr-4 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none">

            {{-- ICON SEARCH DI DALAM INPUT --}}
            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">
                <i data-lucide="search" class="w-4 h-4"></i>
            </span>
        </div>

        {{-- BUTTON SEARCH --}}
        <button
            type="submit"
            class="btn-primary px-4 py-2 text-sm whitespace-nowrap flex items-center gap-2 bg-indigo-600 text-white rounded-xl hover:bg-indigo-700 transition">
            <i data-lucide="search" class="w-4 h-4"></i>
            Cari
        </button>

    </div>
</form>


{{-- TABLE --}}
<div class="card overflow-hidden bg-white rounded-xl shadow-sm border border-gray-100">

    {{-- MOBILE SCROLL --}}
    <div class="overflow-x-auto">

        <table class="w-full text-sm min-w-190">
            <thead class="bg-gray-50 border-b border-gray-100">
                <tr>
                    <th class="text-left px-5 py-4 text-xs font-semibold text-gray-500 w-10 uppercase tracking-wider">No</th>
                    <th class="text-left px-5 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Nama</th>
                    <th class="text-left px-5 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Email</th>
                    <th class="text-left px-5 py-4 text-xs font-semibold text-gray-500 w-24 uppercase tracking-wider">Role</th>
                    <th class="text-center px-5 py-4 text-xs font-semibold text-gray-500 w-28 uppercase tracking-wider">Status</th>
                    <th class="text-center px-5 py-4 text-xs font-semibold text-gray-500 w-36 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>

            <tbody class="divide-y divide-gray-100">

                @forelse($users as $i => $u)
                <tr class="hover:bg-gray-50 transition duration-150">

                    <td class="px-5 py-3 text-gray-400">
                        {{ $users->firstItem() + $i }}
                    </td>

                    <td class="px-5 py-3 font-medium text-gray-800 whitespace-nowrap">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center font-bold text-xs">
                                {{ strtoupper(substr($u->name, 0, 1)) }}
                            </div>
                            {{ $u->name }}
                        </div>
                    </td>

                    <td class="px-5 py-3 text-gray-500 text-xs">
                        {{ $u->email }}
                    </td>

                    <td class="px-5 py-3">
                        <span class="text-xs px-2.5 py-1 rounded-md font-medium
                            {{ $u->role === 'guru'
                                ? 'bg-indigo-100 text-indigo-600'
                                : 'bg-gray-100 text-gray-600' }}">
                            {{ ucfirst($u->role) }}
                        </span>
                    </td>

                    <td class="px-5 py-3 text-center">
                        <span class="text-xs px-2.5 py-1 rounded-full font-medium inline-flex items-center gap-1
                            {{ $u->is_active
                                ? 'bg-emerald-100 text-emerald-600'
                                : 'bg-red-100 text-red-600' }}">
                            @if($u->is_active)
                                <i data-lucide="check-circle-2" class="w-3 h-3"></i> Aktif
                            @else
                                <i data-lucide="x-circle" class="w-3 h-3"></i> Nonaktif
                            @endif
                        </span>
                    </td>

                    <td class="px-5 py-3">
                        <div class="flex items-center justify-center gap-2">

                            {{-- EDIT --}}
                            <button
                                onclick="openEditUser(
                                    {{ $u->id }},
                                    '{{ addslashes($u->name) }}',
                                    '{{ $u->email }}',
                                    '{{ $u->role }}'
                                )"
                                class="text-indigo-500 p-1.5 rounded-lg hover:bg-indigo-50 transition"
                                title="Edit">
                                <i data-lucide="pencil" class="w-4 h-4"></i>
                            </button>

                            {{-- TOGGLE --}}
                            <form
                                action="{{ route('admin.users.toggle', $u) }}"
                                method="POST"
                                class="inline-block"
                                onsubmit="return confirm('Ubah status akun ini?')">
                                @csrf

                                <button
                                    class="{{ $u->is_active ? 'text-amber-500 hover:bg-amber-50' : 'text-emerald-500 hover:bg-emerald-50' }} p-1.5 rounded-lg transition"
                                    title="{{ $u->is_active ? 'Nonaktifkan' : 'Aktifkan' }}">
                                    @if($u->is_active)
                                        <i data-lucide="lock" class="w-4 h-4"></i>
                                    @else
                                        <i data-lucide="unlock" class="w-4 h-4"></i>
                                    @endif
                                </button>
                            </form>

                            {{-- RESET --}}
                            <button
                                onclick="openResetModal(
                                    {{ $u->id }},
                                    '{{ addslashes($u->name) }}'
                                )"
                                class="text-gray-500 p-1.5 rounded-lg hover:bg-gray-100 transition"
                                title="Reset Password">
                                <i data-lucide="key-round" class="w-4 h-4"></i>
                            </button>

                            {{-- HAPUS / DELETE --}}
                            <form
                                action="{{ route('admin.users.destroy', $u->id) }}"
                                method="POST"
                                class="inline-block"
                                onsubmit="return confirm('Yakin ingin menghapus user {{ addslashes($u->name) }} secara permanen? Semua data terkait mungkin akan hilang.')">
                                @csrf
                                @method('DELETE')

                                <button
                                    type="submit"
                                    class="text-red-500 p-1.5 rounded-lg hover:bg-red-50 transition"
                                    title="Hapus Permanen">
                                    <i data-lucide="trash-2" class="w-4 h-4"></i>
                                </button>
                            </form>

                        </div>
                    </td>

                </tr>
                @empty

                <tr>
                    <td colspan="6" class="text-center py-10">
                        <div class="flex flex-col items-center justify-center text-gray-400">
                            <i data-lucide="users" class="w-10 h-10 mb-2 opacity-50"></i>
                            <p class="text-sm">Tidak ada user ditemukan.</p>
                        </div>
                    </td>
                </tr>

                @endforelse

            </tbody>
        </table>
    </div>

    {{-- PAGINATION --}}
    @if($users->hasPages())
    <div class="p-4 border-t border-gray-100">
        {{ $users->appends(['search' => $search])->links() }}
    </div>
    @endif
</div>



{{-- ================= MODAL CREATE (Dihapus class flex bawaannya) ================= --}}
<div id="modal-create"
     class="hidden fixed inset-0 z-50 bg-gray-900/50 backdrop-blur-sm items-center justify-center px-4 transition-all">

    <div class="bg-white rounded-2xl shadow-xl w-full max-w-md overflow-hidden">
        <div class="p-6">

            <div class="flex items-center gap-3 mb-5">
                <div class="p-2 bg-indigo-50 text-indigo-600 rounded-lg">
                    <i data-lucide="user-plus" class="w-5 h-5"></i>
                </div>
                <h2 class="text-lg font-bold text-gray-800">Tambah User Baru</h2>
            </div>

            <form action="{{ route('admin.users.store') }}" method="POST" class="space-y-4">
                @csrf

                @include('admin._user_form')

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Password
                    </label>

                    <div class="relative">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">
                            <i data-lucide="lock" class="w-4 h-4"></i>
                        </span>
                        <input
                            type="password"
                            name="password"
                            required
                            minlength="6"
                            placeholder="Minimal 6 karakter"
                            class="w-full border border-gray-300 rounded-xl pl-10 pr-4 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                    </div>
                </div>

                <div class="flex justify-end gap-3 pt-4">
                    <button
                        type="button"
                        onclick="closeModal('modal-create')"
                        class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-xl hover:bg-gray-50 transition">
                        Batal
                    </button>

                    <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-indigo-600 rounded-xl hover:bg-indigo-700 transition">
                        Tambah User
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>



{{-- ================= MODAL EDIT (Dihapus class flex bawaannya) ================= --}}
<div id="modal-edit"
     class="hidden fixed inset-0 z-50 bg-gray-900/50 backdrop-blur-sm items-center justify-center px-4 transition-all">

    <div class="bg-white rounded-2xl shadow-xl w-full max-w-md overflow-hidden">
        <div class="p-6">

            <div class="flex items-center gap-3 mb-5">
                <div class="p-2 bg-indigo-50 text-indigo-600 rounded-lg">
                    <i data-lucide="user-cog" class="w-5 h-5"></i>
                </div>
                <h2 class="text-lg font-bold text-gray-800">Edit User</h2>
            </div>

            <form id="edit-user-form" method="POST" class="space-y-4">
                @csrf
                @method('PUT')

                @include('admin._user_form')

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Password Baru <span class="text-gray-400 font-normal">(Kosongkan jika tidak diubah)</span>
                    </label>

                    <div class="relative">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">
                            <i data-lucide="lock" class="w-4 h-4"></i>
                        </span>
                        <input
                            type="password"
                            name="password"
                            minlength="6"
                            placeholder="Ketik password baru..."
                            class="w-full border border-gray-300 rounded-xl pl-10 pr-4 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                    </div>
                </div>

                <div class="flex justify-end gap-3 pt-4">
                    <button
                        type="button"
                        onclick="closeModal('modal-edit')"
                        class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-xl hover:bg-gray-50 transition">
                        Batal
                    </button>

                    <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-indigo-600 rounded-xl hover:bg-indigo-700 transition">
                        Simpan Perubahan
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>



{{-- ================= MODAL RESET (Dihapus class flex bawaannya) ================= --}}
<div id="modal-reset"
     class="hidden fixed inset-0 z-50 bg-gray-900/50 backdrop-blur-sm items-center justify-center px-4 transition-all">

    <div class="bg-white rounded-2xl shadow-xl w-full max-w-sm overflow-hidden">
        <div class="p-6">

            <div class="flex items-center gap-3 mb-2">
                <div class="p-2 bg-amber-50 text-amber-600 rounded-lg">
                    <i data-lucide="shield-alert" class="w-5 h-5"></i>
                </div>
                <h2 class="text-lg font-bold text-gray-800">Reset Password</h2>
            </div>

            <p class="text-sm text-gray-500 mb-5 ml-12">Atur ulang password untuk: <strong id="reset-user-name" class="text-gray-800"></strong></p>

            <form id="reset-form" method="POST" class="space-y-4">
                @csrf

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Password Baru
                    </label>

                    <div class="relative">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">
                            <i data-lucide="key-round" class="w-4 h-4"></i>
                        </span>
                        <input
                            type="password"
                            name="password"
                            required
                            minlength="6"
                            placeholder="Ketik password baru..."
                            class="w-full border border-gray-300 rounded-xl pl-10 pr-4 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                    </div>
                </div>

                <div class="flex justify-end gap-3 pt-4">
                    <button
                        type="button"
                        onclick="closeModal('modal-reset')"
                        class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-xl hover:bg-gray-50 transition">
                        Batal
                    </button>

                    <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-amber-500 rounded-xl hover:bg-amber-600 transition">
                        Reset Password
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
{{-- Panggil Library Lucide Icon via CDN --}}
<script src="https://unpkg.com/lucide@latest"></script>

<script>
// Inisialisasi ikon Lucide
lucide.createIcons();

// Fungsi openModal untuk mengatasi konflik class "hidden" vs "flex"
function openModal(id) {
    const modal = document.getElementById(id);
    modal.classList.remove('hidden');
    modal.classList.add('flex');
}

function closeModal(id) {
    const modal = document.getElementById(id);
    modal.classList.add('hidden');
    modal.classList.remove('flex');
}

function openEditUser(id, name, email, role) {
    const form = document.getElementById('edit-user-form');

    form.action = '/admin/users/' + id;
    form.querySelector('[name=name]').value = name;
    form.querySelector('[name=email]').value = email;
    form.querySelector('[name=role]').value = role;

    openModal('modal-edit'); // Gunakan fungsi openModal
}

function openResetModal(id, name) {
    document.getElementById('reset-user-name').textContent = name;
    document.getElementById('reset-form').action =
        '/admin/users/' + id + '/reset-password';

    openModal('modal-reset'); // Gunakan fungsi openModal
}
</script>
@endpush