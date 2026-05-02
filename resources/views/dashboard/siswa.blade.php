@extends('layouts.app')
@section('title', 'Dashboard')

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/lucide-static@0.441.0/font/lucide.min.css">
<style>
    .hero-banner-siswa {
        background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
        border-radius: 1.25rem; padding: 1.75rem 2rem; color: white;
        position: relative; overflow: hidden;
    }
    .hero-banner-siswa::before {
        content: ''; position: absolute; top: -50px; right: -50px;
        width: 200px; height: 200px; background: rgba(255,255,255,0.06); border-radius: 50%;
    }
    .hero-banner-siswa::after {
        content: ''; position: absolute; bottom: -30px; left: 30%;
        width: 120px; height: 120px; background: rgba(255,255,255,0.04); border-radius: 50%;
    }
    .progress-track {
        background: rgba(255,255,255,0.2); border-radius: 999px; height: 8px; overflow: hidden;
    }
    .progress-fill {
        height: 100%; border-radius: 999px; background: white;
        transition: width 0.8s cubic-bezier(0.4,0,0.2,1);
    }
    .stat-card-siswa {
        border-radius: 1rem; padding: 1.25rem 1.5rem;
        position: relative; overflow: hidden; color: white;
        transition: transform 0.2s, box-shadow 0.2s;
    }
    .stat-card-siswa:hover { transform: translateY(-3px); box-shadow: 0 12px 28px rgba(0,0,0,0.1); }
    .stat-card-siswa::after {
        content: ''; position: absolute; top: -20px; right: -20px;
        width: 80px; height: 80px; background: rgba(255,255,255,0.12); border-radius: 50%;
    }
    .stat-indigo  { background: linear-gradient(135deg, #4f46e5, #6366f1); }
    .stat-emerald { background: linear-gradient(135deg, #059669, #10b981); }
    .stat-amber   { background: linear-gradient(135deg, #d97706, #f59e0b); }
    .level-card {
        border-radius: 1rem; border: 1.5px solid #e5e7eb;
        padding: 1.1rem; transition: all 0.2s; background: white;
    }
    .level-card.unlocked { border-color: #a5b4fc; background: linear-gradient(135deg, #f5f3ff, #ede9fe); }
    .level-card:hover { transform: translateY(-2px); box-shadow: 0 6px 20px rgba(99,102,241,0.1); }
    .level-progress-track {
        background: #e5e7eb; border-radius: 999px; height: 5px;
        overflow: hidden; margin: 0.5rem 0;
    }
    .level-progress-fill {
        height: 100%; border-radius: 999px;
        background: linear-gradient(90deg, #6366f1, #818cf8); transition: width 0.6s;
    }
    .level-progress-fill.locked { background: #d1d5db; }
    .quick-link {
        border: 1.5px solid #e5e7eb; border-radius: 1rem;
        padding: 1.1rem 1.25rem;
        display: flex; align-items: center; gap: 0.875rem;
        text-decoration: none; transition: all 0.2s; background: white;
    }
    .quick-link:hover {
        border-color: #6366f1; background: #f5f3ff;
        transform: translateY(-2px); box-shadow: 0 6px 20px rgba(99,102,241,0.12);
    }
    .quick-link-icon {
        width: 44px; height: 44px; border-radius: 0.75rem;
        display: flex; align-items: center; justify-content: center; flex-shrink: 0;
    }
</style>
@endpush

@section('content')
<div class="space-y-6">

    {{-- Hero --}}
    <div class="hero-banner-siswa">
        <div class="flex items-start justify-between gap-4">
            <div class="relative z-10 flex-1">
                <p class="text-indigo-200 text-xs font-semibold uppercase tracking-widest mb-1">
                    {{ now()->translatedFormat('l, d F Y') }}
                </p>
                <h1 class="text-2xl font-extrabold mb-1">Halo, {{ auth()->user()->name }}!</h1>
                <p class="text-indigo-200 text-sm mb-4">Lanjutkan belajar algoritmamu hari ini.</p>
                <div class="max-w-xs">
                    <div class="flex items-center justify-between mb-1.5">
                        <span class="text-xs font-semibold text-indigo-200">Progress Materi</span>
                        <span class="text-xs font-bold text-white">{{ $completedCount }}/{{ $totalMateri }}</span>
                    </div>
                    <div class="progress-track">
                        <div class="progress-fill" style="width: {{ $progressPct }}%"></div>
                    </div>
                    <p class="text-indigo-300 text-xs mt-1.5">{{ $progressPct }}% selesai</p>
                </div>
            </div>
            <div class="relative z-10 hidden sm:flex items-center justify-center">
                <div class="bg-white/10 rounded-2xl p-4 border border-white/20 text-center">
                    <p class="text-3xl font-extrabold">{{ $progressPct }}%</p>
                    <p class="text-indigo-200 text-xs mt-0.5">Progres</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Stats --}}
    <div class="grid grid-cols-3 gap-4">
        <div class="stat-card-siswa stat-indigo">
            <p class="text-indigo-200 text-xs font-semibold uppercase tracking-wide mb-2">Materi Selesai</p>
            <p class="text-3xl font-extrabold">{{ $completedCount }}</p>
            <div class="mt-2 text-indigo-200 text-xs flex items-center gap-1.5">
                <i class="icon-book-open" style="width:12px;height:12px;flex-shrink:0;"></i> dari {{ $totalMateri }} total
            </div>
        </div>
        <div class="stat-card-siswa stat-emerald">
            <p class="text-emerald-100 text-xs font-semibold uppercase tracking-wide mb-2">Aktivitas Dikerjakan</p>
            <p class="text-3xl font-extrabold">{{ $submissionCount }}</p>
            <div class="mt-2 text-emerald-100 text-xs flex items-center gap-1.5">
                <i class="icon-layers" style="width:12px;height:12px;flex-shrink:0;"></i> submission
            </div>
        </div>
        <div class="stat-card-siswa stat-amber">
            <p class="text-amber-100 text-xs font-semibold uppercase tracking-wide mb-2">Skor Test</p>
            <p class="text-3xl font-extrabold">
                {{ $lastResult ? $lastResult->persentase : '—' }}
                @if($lastResult)<span class="text-lg font-normal opacity-70">/100</span>@endif
            </p>
            <div class="mt-2 text-amber-100 text-xs flex items-center gap-1.5">
                <i class="icon-trophy" style="width:12px;height:12px;flex-shrink:0;"></i>
                {{ $lastResult ? $lastResult->taken_at->diffForHumans() : 'Belum test' }}
            </div>
        </div>
    </div>

    {{-- Level Access --}}
    <div class="bg-white border border-gray-100 rounded-2xl p-5 shadow-sm">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h2 class="font-bold text-gray-900 text-sm">Level Aktivitas PBL</h2>
                <p class="text-xs text-gray-400 mt-0.5">Selesaikan materi untuk membuka level lebih tinggi</p>
            </div>
            <a href="{{ route('pbl.index') }}" class="text-xs font-semibold text-indigo-600 hover:text-indigo-800 transition-colors">Kerjakan →</a>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
            @foreach(['Mudah' => 'emerald', 'Sedang' => 'amber', 'Sulit' => 'red'] as $diff => $color)
                @php
                    $min = $levelSettings[$diff] ?? 1;
                    $unlocked = in_array($diff, $accessible);
                    $pct = min(100, $min > 0 ? round(($completedCount / $min) * 100) : 100);
                    $icons = ['Mudah' => 'icon-circle-check', 'Sedang' => 'icon-circle-alert', 'Sulit' => 'icon-circle-x'];
                    $iconColors = ['Mudah' => 'text-emerald-500', 'Sedang' => 'text-amber-500', 'Sulit' => 'text-red-500'];
                @endphp
                <div class="level-card {{ $unlocked ? 'unlocked' : '' }}">
                    <div class="flex items-center justify-between mb-2">
                        <div class="flex items-center gap-2">
                            <i class="{{ $icons[$diff] }} {{ $iconColors[$diff] }}" style="font-size:1rem;"></i>
                            <span class="text-sm font-bold text-gray-800">{{ $diff }}</span>
                        </div>
                        @if($unlocked)
                            <span class="text-xs font-bold px-2 py-0.5 rounded-full bg-emerald-100 text-emerald-700 flex items-center gap-1">
                                <i class="icon-unlock" style="width:10px;height:10px;"></i> Terbuka
                            </span>
                        @else
                            <span class="text-xs font-medium text-gray-400 flex items-center gap-1">
                                <i class="icon-lock" style="width:10px;height:10px;"></i> Terkunci
                            </span>
                        @endif
                    </div>
                    <div class="level-progress-track">
                        <div class="level-progress-fill {{ !$unlocked ? 'locked' : '' }}" style="width: {{ $pct }}%"></div>
                    </div>
                    <div class="flex items-center justify-between">
                        <p class="text-xs text-gray-400">Min. {{ $min }} materi</p>
                        <p class="text-xs font-semibold {{ $unlocked ? 'text-indigo-600' : 'text-gray-400' }}">{{ $pct }}%</p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    {{-- Quick Links --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <a href="{{ route('materi.index') }}" class="quick-link">
            <div class="quick-link-icon bg-indigo-100 text-indigo-600"><i class="icon-book-open"></i></div>
            <div class="flex-1">
                <p class="font-bold text-gray-800 text-sm">Lanjutkan Materi</p>
                <p class="text-xs text-gray-400 mt-0.5">
                    {{ $totalMateri - $completedCount > 0 ? ($totalMateri - $completedCount) . ' materi belum selesai' : 'Semua materi selesai!' }}
                </p>
            </div>
            <i class="icon-chevron-right text-gray-300"></i>
        </a>
        <a href="{{ route('pbl.index') }}" class="quick-link">
            <div class="quick-link-icon bg-violet-100 text-violet-600"><i class="icon-layers"></i></div>
            <div class="flex-1">
                <p class="font-bold text-gray-800 text-sm">Aktivitas PBL</p>
                <p class="text-xs text-gray-400 mt-0.5">Kerjakan studi kasus pemrograman</p>
            </div>
            <i class="icon-chevron-right text-gray-300"></i>
        </a>
        <a href="{{ route('nilai.index') }}" class="quick-link">
            <div class="quick-link-icon bg-amber-100 text-amber-600"><i class="icon-chart-bar"></i></div>
            <div class="flex-1">
                <p class="font-bold text-gray-800 text-sm">Lihat Nilai</p>
                <p class="text-xs text-gray-400 mt-0.5">Cek progres & evaluasi kamu</p>
            </div>
            <i class="icon-chevron-right text-gray-300"></i>
        </a>
        <a href="{{ route('compiler') }}" class="quick-link">
            <div class="quick-link-icon bg-emerald-100 text-emerald-600"><i class="icon-terminal"></i></div>
            <div class="flex-1">
                <p class="font-bold text-gray-800 text-sm">Mini Compiler</p>
                <p class="text-xs text-gray-400 mt-0.5">Coba kode langsung di browser</p>
            </div>
            <i class="icon-chevron-right text-gray-300"></i>
        </a>
    </div>

</div>
@endsection