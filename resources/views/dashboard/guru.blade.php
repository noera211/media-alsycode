@extends('layouts.app')
@section('title', 'Dashboard Guru')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-900">Dashboard Pengajar</h1>
    <p class="text-gray-500 mt-1 text-sm">Pantau progres dan aktivitas seluruh siswa.</p>
</div>

<div class="grid grid-cols-4 gap-4 mb-6">
    <div class="card p-5">
        <p class="text-2xl font-bold text-indigo-600">{{ $totalSiswa }}</p>
        <p class="text-xs text-gray-500 mt-1">Siswa Aktif</p>
    </div>
    <div class="card p-5">
        <p class="text-2xl font-bold text-indigo-600">{{ $totalMateri }}</p>
        <p class="text-xs text-gray-500 mt-1">Total Materi</p>
    </div>
    <div class="card p-5">
        <p class="text-2xl font-bold text-indigo-600">{{ $totalPbl }}</p>
        <p class="text-xs text-gray-500 mt-1">Aktivitas PBL</p>
    </div>
    <div class="card p-5">
        <p class="text-2xl font-bold text-amber-500">{{ $pendingGrading }}</p>
        <p class="text-xs text-gray-500 mt-1">Belum Dinilai</p>
    </div>
</div>

<div class="card">
    <div class="p-5 border-b border-gray-100">
        <h2 class="font-semibold text-gray-800">Pengumpulan Terbaru</h2>
    </div>
    <div class="divide-y divide-gray-100">
        @forelse($recentSubmissions as $sub)
        <div class="p-4 flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-800">{{ $sub->student->name }}</p>
                <p class="text-xs text-gray-400">{{ $sub->activity->title }} · {{ $sub->submitted_at->diffForHumans() }}</p>
            </div>
            <div>
                @if($sub->nilai !== null)
                    <span class="text-xs font-bold px-2 py-1 rounded-lg {{ $sub->nilai >= 80 ? 'bg-emerald-100 text-emerald-700' : ($sub->nilai >= 60 ? 'bg-amber-100 text-amber-700' : 'bg-red-100 text-red-700') }}">
                        {{ $sub->nilai }}
                    </span>
                @else
                    <span class="text-xs text-amber-600 font-medium">Belum dinilai</span>
                @endif
            </div>
        </div>
        @empty
        <div class="p-8 text-center text-gray-400 text-sm">Belum ada pengumpulan.</div>
        @endforelse
    </div>
</div>
@endsection
