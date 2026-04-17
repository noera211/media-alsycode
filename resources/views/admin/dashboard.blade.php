@extends('layouts.app')
@section('title', 'Dashboard Admin')

@section('content')
<div class="mb-6">
    <h1 class="text-xl md:text-2xl font-bold text-gray-900">Dashboard Administrator</h1>
    <p class="text-gray-500 mt-1 text-sm">Kelola seluruh sistem ALSYCODE.</p>
</div>

<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 mb-6">
    <div class="card p-4 md:p-5">
        <p class="text-xl md:text-2xl font-bold text-indigo-600">{{ $stats['total_guru'] }}</p>
        <p class="text-xs text-gray-500 mt-1">Total Guru</p>
    </div>
    <div class="card p-4 md:p-5">
        <p class="text-xl md:text-2xl font-bold text-indigo-600">{{ $stats['total_siswa'] }}</p>
        <p class="text-xs text-gray-500 mt-1">Total Siswa</p>
    </div>
    <div class="card p-4 md:p-5 sm:col-span-2 lg:col-span-1">
        <p class="text-xl md:text-2xl font-bold text-amber-500">{{ $stats['pending_nilai'] }}</p>
        <p class="text-xs text-gray-500 mt-1">Submission Belum Dinilai</p>
    </div>
    <div class="card p-4 md:p-5">
        <p class="text-xl md:text-2xl font-bold text-indigo-600">{{ $stats['total_materi'] }}</p>
        <p class="text-xs text-gray-500 mt-1">Total Materi</p>
    </div>
    <div class="card p-4 md:p-5">
        <p class="text-xl md:text-2xl font-bold text-indigo-600">{{ $stats['total_pbl'] }}</p>
        <p class="text-xs text-gray-500 mt-1">Total Aktivitas PBL</p>
    </div>
    <div class="card p-4 md:p-5">
        <p class="text-xl md:text-2xl font-bold text-indigo-600">{{ $stats['total_users'] }}</p>
        <p class="text-xs text-gray-500 mt-1">Total Pengguna</p>
    </div>
</div>

<div class="card">
    <div class="p-4 md:p-5 border-b border-gray-100 flex flex-col sm:flex-row sm:items-center justify-between gap-2">
        <h2 class="font-semibold text-gray-800 text-sm md:text-base">Pengguna Terbaru</h2>
        <a href="{{ route('admin.users') }}" class="text-xs text-indigo-500 hover:underline self-start sm:self-auto">Lihat semua →</a>
    </div>
    <div class="divide-y divide-gray-100">
        @foreach($recentUsers as $u)
        <div class="px-4 md:px-5 py-3 flex items-center gap-3">
            <div class="h-8 w-8 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600 font-bold text-sm flex-shrink-0">
                {{ strtoupper(substr($u->name, 0, 1)) }}
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-sm font-medium text-gray-800 truncate">{{ $u->name }}</p>
                <p class="text-xs text-gray-400 truncate">{{ $u->email }}</p>
            </div>
            <div class="flex flex-col sm:flex-row gap-1 sm:gap-2 flex-shrink-0">
                <span class="text-xs capitalize px-2 py-0.5 rounded-md font-medium {{ $u->role === 'guru' ? 'bg-indigo-100 text-indigo-600' : 'bg-gray-100 text-gray-600' }}">
                    {{ $u->role }}
                </span>
                <span class="text-xs px-2 py-0.5 rounded-full font-medium {{ $u->is_active ? 'bg-emerald-100 text-emerald-600' : 'bg-red-100 text-red-600' }}">
                    {{ $u->is_active ? 'Aktif' : 'Nonaktif' }}
                </span>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection
