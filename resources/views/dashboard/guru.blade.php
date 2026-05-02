@extends('layouts.app')
@section('title', 'Dashboard Pengajar')

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/lucide-static@0.441.0/font/lucide.min.css">
<style>
    .hero-banner {
        background: linear-gradient(135deg, #4f46e5 0%, #6366f1 50%, #818cf8 100%);
        border-radius: 1.25rem;
        padding: 1.75rem 2rem;
        color: white;
        position: relative;
        overflow: hidden;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 1rem;
    }
    .hero-banner::before {
        content: ''; position: absolute;
        top: -40px; right: -40px;
        width: 220px; height: 220px;
        background: rgba(255,255,255,0.07); border-radius: 50%;
    }
    .hero-banner::after {
        content: ''; position: absolute;
        bottom: -60px; right: 120px;
        width: 160px; height: 160px;
        background: rgba(255,255,255,0.05); border-radius: 50%;
    }
    .stat-card {
        border-radius: 1rem; padding: 1.25rem 1.5rem;
        position: relative; overflow: hidden; color: white;
        transition: transform 0.2s ease, box-shadow 0.2s ease; cursor: default;
    }
    .stat-card:hover { transform: translateY(-3px); box-shadow: 0 12px 28px rgba(0,0,0,0.13); }
    .stat-card::after {
        content: ''; position: absolute;
        top: -20px; right: -20px; width: 80px; height: 80px;
        background: rgba(255,255,255,0.12); border-radius: 50%;
    }
    .stat-yellow { background: linear-gradient(135deg, #f59e0b, #fbbf24); }
    .stat-indigo { background: linear-gradient(135deg, #4f46e5, #6366f1); }
    .stat-pink   { background: linear-gradient(135deg, #ec4899, #f472b6); }
    .stat-violet { background: linear-gradient(135deg, #7c3aed, #8b5cf6); }
    .submission-row { transition: background 0.15s; }
    .submission-row:hover { background: #f5f3ff; }
    .badge-pill {
        display: inline-flex; align-items: center;
        padding: 0.2rem 0.65rem; border-radius: 999px;
        font-size: 0.7rem; font-weight: 700;
    }
    .avatar-circle {
        width: 36px; height: 36px; border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        font-weight: 700; font-size: 0.75rem; flex-shrink: 0;
    }
    .quick-action {
        border: 1.5px solid #e5e7eb; border-radius: 1rem;
        padding: 1rem 1.25rem;
        display: flex; align-items: center; gap: 0.875rem;
        text-decoration: none; transition: all 0.2s; background: white;
    }
    .quick-action:hover {
        border-color: #6366f1; background: #f5f3ff;
        transform: translateY(-2px); box-shadow: 0 6px 20px rgba(99,102,241,0.12);
    }
    .quick-action-icon {
        width: 42px; height: 42px; border-radius: 0.75rem;
        display: flex; align-items: center; justify-content: center; flex-shrink: 0;
    }
</style>
@endpush

@section('content')
<div class="space-y-6">

    {{-- Hero --}}
    <div class="hero-banner">
        <div class="relative z-10">
            <p class="text-indigo-200 text-xs font-semibold uppercase tracking-widest mb-1">
                {{ now()->translatedFormat('l, d F Y') }}
            </p>
            <h1 class="text-2xl font-extrabold mb-1">Halo, {{ auth()->user()->name }}!</h1>
            <p class="text-indigo-200 text-sm">
                Ada <span class="text-white font-bold">{{ $pendingGrading }}</span> submission yang menunggu penilaian.
            </p>
            <a href="{{ route('pbl.index') }}"
                class="mt-4 inline-flex items-center gap-2 bg-white text-indigo-600 text-xs font-bold px-4 py-2 rounded-xl hover:bg-indigo-50 transition-colors">
                <i class="icon-arrow-right" style="width:14px;height:14px;"></i> Lihat Submission
            </a>
        </div>
        <div class="relative z-10 hidden sm:block">
            <svg width="120" height="100" viewBox="0 0 120 100" fill="none">
                <ellipse cx="60" cy="90" rx="40" ry="6" fill="rgba(255,255,255,0.1)"/>
                <rect x="20" y="20" width="80" height="55" rx="6" fill="rgba(255,255,255,0.15)" stroke="rgba(255,255,255,0.3)" stroke-width="1.5"/>
                <rect x="28" y="28" width="64" height="8" rx="3" fill="rgba(255,255,255,0.3)"/>
                <rect x="28" y="42" width="44" height="5" rx="2" fill="rgba(255,255,255,0.2)"/>
                <rect x="28" y="52" width="56" height="5" rx="2" fill="rgba(255,255,255,0.15)"/>
                <rect x="28" y="62" width="36" height="5" rx="2" fill="rgba(255,255,255,0.15)"/>
                <circle cx="92" cy="18" r="10" fill="rgba(255,255,255,0.2)" stroke="rgba(255,255,255,0.4)" stroke-width="1.5"/>
                <text x="87" y="22" font-size="11" fill="white" font-weight="bold">✓</text>
            </svg>
        </div>
    </div>

    {{-- Stats --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="stat-card stat-yellow">
            <p class="text-yellow-100 text-xs font-semibold uppercase tracking-wide mb-2">Siswa Aktif</p>
            <p class="text-3xl font-extrabold">{{ $totalSiswa }}</p>
            <div class="mt-2 flex items-center gap-1.5 text-yellow-100 text-xs">
                <i class="icon-users" style="width:12px;height:12px;flex-shrink:0;"></i> Terdaftar
            </div>
        </div>
        <div class="stat-card stat-indigo">
            <p class="text-indigo-200 text-xs font-semibold uppercase tracking-wide mb-2">Total Materi</p>
            <p class="text-3xl font-extrabold">{{ $totalMateri }}</p>
            <div class="mt-2 flex items-center gap-1.5 text-indigo-200 text-xs">
                <i class="icon-book-open" style="width:12px;height:12px;flex-shrink:0;"></i> Konten belajar
            </div>
        </div>
        <div class="stat-card stat-pink">
            <p class="text-pink-100 text-xs font-semibold uppercase tracking-wide mb-2">Aktivitas PBL</p>
            <p class="text-3xl font-extrabold">{{ $totalPbl }}</p>
            <div class="mt-2 flex items-center gap-1.5 text-pink-100 text-xs">
                <i class="icon-layers" style="width:12px;height:12px;flex-shrink:0;"></i> Studi kasus
            </div>
        </div>
        <div class="stat-card stat-violet">
            <p class="text-violet-200 text-xs font-semibold uppercase tracking-wide mb-2">Belum Dinilai</p>
            <p class="text-3xl font-extrabold">{{ $pendingGrading }}</p>
            <div class="mt-2 flex items-center gap-1.5 text-violet-200 text-xs">
                <i class="icon-clock" style="width:12px;height:12px;flex-shrink:0;"></i> Menunggu review
            </div>
        </div>
    </div>

    {{-- Submissions + Quick Actions --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

        <div class="lg:col-span-2 bg-white border border-gray-100 rounded-2xl overflow-hidden shadow-sm">
            <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
                <div>
                    <h2 class="font-bold text-gray-900 text-sm">Pengumpulan Terbaru</h2>
                    <p class="text-xs text-gray-400 mt-0.5">5 submission terakhir dari siswa</p>
                </div>
                <a href="{{ route('pbl.index') }}" class="text-xs font-semibold text-indigo-600 hover:text-indigo-800 transition-colors">Lihat Semua →</a>
            </div>
            <div class="divide-y divide-gray-50">
                @forelse($recentSubmissions as $sub)
                @php
                    $colors = ['bg-indigo-100 text-indigo-700','bg-violet-100 text-violet-700','bg-pink-100 text-pink-700','bg-amber-100 text-amber-700','bg-emerald-100 text-emerald-700'];
                    $color = $colors[$loop->index % count($colors)];
                @endphp
                <div class="submission-row px-5 py-3.5 flex items-center justify-between gap-4">
                    <div class="flex items-center gap-3 min-w-0">
                        <div class="avatar-circle {{ $color }}">{{ strtoupper(substr($sub->student->name ?? '?', 0, 2)) }}</div>
                        <div class="min-w-0">
                            <p class="text-sm font-semibold text-gray-800 truncate">{{ $sub->student->name }}</p>
                            <p class="text-xs text-gray-400 truncate">{{ $sub->activity->title }} · {{ $sub->submitted_at->diffForHumans() }}</p>
                        </div>
                    </div>
                    @if($sub->nilai !== null)
                        <span class="badge-pill shrink-0 {{ $sub->nilai >= 80 ? 'bg-emerald-100 text-emerald-700' : ($sub->nilai >= 60 ? 'bg-amber-100 text-amber-700' : 'bg-red-100 text-red-700') }}">{{ $sub->nilai }}</span>
                    @else
                        <span class="badge-pill shrink-0 bg-amber-50 text-amber-600 border border-amber-200">Belum dinilai</span>
                    @endif
                </div>
                @empty
                <div class="px-5 py-12 text-center">
                    <i class="icon-inbox" style="font-size:2rem;color:#d1d5db;display:block;margin:0 auto 0.75rem;"></i>
                    <p class="text-sm text-gray-400">Belum ada submission.</p>
                </div>
                @endforelse
            </div>
        </div>

        <div class="space-y-4">
            <div class="bg-white border border-gray-100 rounded-2xl p-5 shadow-sm">
                <h3 class="font-bold text-gray-900 text-sm mb-4">Akses Cepat</h3>
                <div class="space-y-2.5">
                    <a href="{{ route('materi.index') }}" class="quick-action">
                        <div class="quick-action-icon bg-indigo-100 text-indigo-600"><i class="icon-book-open"></i></div>
                        <div><p class="text-sm font-semibold text-gray-800">Kelola Materi</p><p class="text-xs text-gray-400">Tambah & edit konten</p></div>
                    </a>
                    <a href="{{ route('pbl.index') }}" class="quick-action">
                        <div class="quick-action-icon bg-pink-100 text-pink-600"><i class="icon-layers"></i></div>
                        <div><p class="text-sm font-semibold text-gray-800">Aktivitas PBL</p><p class="text-xs text-gray-400">Nilai submission siswa</p></div>
                    </a>
                    <a href="{{ route('nilai.index') }}" class="quick-action">
                        <div class="quick-action-icon bg-amber-100 text-amber-600"><i class="icon-chart-bar"></i></div>
                        <div><p class="text-sm font-semibold text-gray-800">Rekap Nilai</p><p class="text-xs text-gray-400">Lihat nilai akhir siswa</p></div>
                    </a>
                    <a href="{{ route('bank-soal.index') }}" class="quick-action">
                        <div class="quick-action-icon bg-violet-100 text-violet-600"><i class="icon-clipboard-list"></i></div>
                        <div><p class="text-sm font-semibold text-gray-800">Bank Soal</p><p class="text-xs text-gray-400">Kelola soal evaluasi</p></div>
                    </a>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection