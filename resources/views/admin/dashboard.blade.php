@extends('layouts.app')
@section('title', 'Dashboard Admin')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-900">Dashboard Administrator</h1>
    <p class="text-gray-500 mt-1 text-sm">Kelola seluruh sistem ALSYCODE.</p>
</div>

<div class="grid grid-cols-3 gap-4 mb-6">
    <div class="card p-5">
        <p class="text-2xl font-bold text-indigo-600">{{ $stats['total_guru'] }}</p>
        <p class="text-xs text-gray-500 mt-1">Total Guru</p>
    </div>
    <div class="card p-5">
        <p class="text-2xl font-bold text-indigo-600">{{ $stats['total_siswa'] }}</p>
        <p class="text-xs text-gray-500 mt-1">Total Siswa</p>
    </div>
    <div class="card p-5">
        <p class="text-2xl font-bold text-amber-500">{{ $stats['pending_nilai'] }}</p>
        <p class="text-xs text-gray-500 mt-1">Submission Belum Dinilai</p>
    </div>
    <div class="card p-5">
        <p class="text-2xl font-bold text-indigo-600">{{ $stats['total_materi'] }}</p>
        <p class="text-xs text-gray-500 mt-1">Total Materi</p>
    </div>
    <div class="card p-5">
        <p class="text-2xl font-bold text-indigo-600">{{ $stats['total_pbl'] }}</p>
        <p class="text-xs text-gray-500 mt-1">Total Aktivitas PBL</p>
    </div>
    <div class="card p-5">
        <p class="text-2xl font-bold text-indigo-600">{{ $stats['total_users'] }}</p>
        <p class="text-xs text-gray-500 mt-1">Total Pengguna</p>
    </div>
</div>

<div class="card">
    <div class="p-5 border-b border-gray-100 flex items-center justify-between">
        <h2 class="font-semibold text-gray-800">Pengguna Terbaru</h2>
        <a href="{{ route('admin.users') }}" class="text-xs text-indigo-500 hover:underline">Lihat semua →</a>
    </div>
    <div class="divide-y divide-gray-100">
        @foreach($recentUsers as $u)
        <div class="px-5 py-3 flex items-center gap-3">
            <div class="h-8 w-8 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600 font-bold text-sm">
                {{ strtoupper(substr($u->name, 0, 1)) }}
            </div>
            <div class="flex-1">
                <p class="text-sm font-medium text-gray-800">{{ $u->name }}</p>
                <p class="text-xs text-gray-400">{{ $u->email }}</p>
            </div>
            <span class="text-xs capitalize px-2 py-0.5 rounded-md font-medium {{ $u->role === 'guru' ? 'bg-indigo-100 text-indigo-600' : 'bg-gray-100 text-gray-600' }}">
                {{ $u->role }}
            </span>
            <span class="text-xs px-2 py-0.5 rounded-full font-medium {{ $u->is_active ? 'bg-emerald-100 text-emerald-600' : 'bg-red-100 text-red-600' }}">
                {{ $u->is_active ? 'Aktif' : 'Nonaktif' }}
            </span>
        </div>
        @endforeach
    </div>
</div>
@endsection
