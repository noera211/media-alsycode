@extends('layouts.app')
@section('title', 'Dashboard')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-900">Selamat datang, {{ auth()->user()->name }}! 👋</h1>
    <p class="text-gray-500 mt-1 text-sm">Lanjutkan belajar algoritmamu hari ini.</p>
</div>

{{-- Progress Materi --}}
<div class="card p-6 mb-6">
    <div class="flex items-center justify-between mb-3">
        <h2 class="font-semibold text-gray-800">Progress Materi</h2>
        <span class="text-sm font-medium text-indigo-600">{{ $completedCount }}/{{ $totalMateri }} selesai</span>
    </div>
    <div class="w-full bg-gray-100 rounded-full h-3 mb-2">
        <div class="bg-indigo-600 h-3 rounded-full transition-all" style="width: {{ $progressPct }}%"></div>
    </div>
    <p class="text-xs text-gray-400">Selesaikan lebih banyak materi untuk membuka level PBL yang lebih tinggi</p>
</div>

{{-- Stats --}}
<div class="grid grid-cols-3 gap-4 mb-6">
    <div class="card p-5">
        <p class="text-2xl font-bold text-indigo-600">{{ $completedCount }}</p>
        <p class="text-xs text-gray-500 mt-1">Materi Selesai</p>
    </div>
    <div class="card p-5">
        <p class="text-2xl font-bold text-indigo-600">{{ $submissionCount }}</p>
        <p class="text-xs text-gray-500 mt-1">Aktivitas Dikerjakan</p>
    </div>
    <div class="card p-5">
        <p class="text-2xl font-bold text-indigo-600">
            {{ $lastResult ? $lastResult->persentase : '—' }}
            @if($lastResult)<span class="text-sm font-normal">/100</span>@endif
        </p>
        <p class="text-xs text-gray-500 mt-1">Skor Test Terakhir</p>
    </div>
</div>

{{-- Level Access --}}
<div class="card p-6 mb-6">
    <h2 class="font-semibold text-gray-800 mb-4">Level Aktivitas PBL</h2>
    <div class="flex flex-col sm:grid sm:grid-cols-3 gap-3">
        @foreach(['Mudah' => ['icon' => '🟢'], 'Sedang' => ['icon' => '🟡'], 'Sulit' => ['icon' => '🔴']] as $diff => $cfg)
            @php
                $min = $levelSettings[$diff] ?? 1;
                $unlocked = in_array($diff, $accessible);
                $pct = min(100, $min > 0 ? round(($completedCount / $min) * 100) : 100);
            @endphp
            <div class="rounded-xl border p-4 {{ $unlocked ? 'border-indigo-200 bg-indigo-50' : 'border-gray-200 bg-gray-50' }}">
                <div class="flex items-center gap-2 mb-2">
                    <span class="text-base">{{ $cfg['icon'] }}</span>
                    <span class="text-sm font-semibold text-gray-800">{{ $diff }}</span>
                    @if($unlocked)
                        <span class="ml-auto text-emerald-500 text-xs font-medium">✓ Terbuka</span>
                    @else
                        <span class="ml-auto text-gray-400 text-xs">🔒 Terkunci</span>
                    @endif
                </div>
                <div class="w-full bg-gray-200 rounded-full h-1.5 mb-2">
                    <div class="bg-indigo-400 h-1.5 rounded-full" style="width: {{ $pct }}%"></div>
                </div>
                <p class="text-xs text-gray-400">Min. {{ $min }} materi</p>
            </div>
        @endforeach
    </div>
</div>

{{-- Quick links --}}
<div class="grid grid-cols-2 gap-4">
    <a href="{{ route('materi.index') }}" class="card p-5 hover:border-indigo-300 transition-colors flex items-center gap-4">
        <div class="h-10 w-10 bg-indigo-100 rounded-xl flex items-center justify-center text-indigo-600">📚</div>
        <div>
            <p class="font-semibold text-gray-800 text-sm">Lanjutkan Materi</p>
            <p class="text-xs text-gray-400">{{ $totalMateri - $completedCount }} materi belum selesai</p>
        </div>
    </a>
    <a href="{{ route('pbl.index') }}" class="card p-5 hover:border-indigo-300 transition-colors flex items-center gap-4">
        <div class="h-10 w-10 bg-indigo-100 rounded-xl flex items-center justify-center text-indigo-600">🎓</div>
        <div>
            <p class="font-semibold text-gray-800 text-sm">Aktivitas PBL</p>
            <p class="text-xs text-gray-400">Kerjakan studi kasus pemrograman</p>
        </div>
    </a>
</div>
@endsection