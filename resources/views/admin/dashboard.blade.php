@extends('layouts.app')
@section('title', 'Dashboard Admin')

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/lucide-static@0.441.0/font/lucide.min.css">
<style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');

    .dash-font { font-family: 'Plus Jakarta Sans', sans-serif; }

    .hero-admin {
        background: linear-gradient(135deg, #1e1b4b 0%, #312e81 50%, #4338ca 100%);
        position: relative;
        overflow: hidden;
    }
    .hero-admin::before {
        content: '';
        position: absolute;
        top: -60px; right: -60px;
        width: 240px; height: 240px;
        background: rgba(255,255,255,0.05);
        border-radius: 50%;
    }
    .hero-admin::after {
        content: '';
        position: absolute;
        bottom: -40px; left: 25%;
        width: 140px; height: 140px;
        background: rgba(255,255,255,0.04);
        border-radius: 50%;
    }

    .stat-card-admin {
        border-radius: 1rem;
        padding: 1.25rem 1.5rem;
        position: relative;
        overflow: hidden;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
        cursor: default;
    }
    .stat-card-admin:hover {
        transform: translateY(-3px);
        box-shadow: 0 14px 30px rgba(0,0,0,0.12);
    }
    .stat-card-admin::after {
        content: '';
        position: absolute;
        top: -24px; right: -24px;
        width: 90px; height: 90px;
        background: rgba(255,255,255,0.1);
        border-radius: 50%;
    }

    .sc-navy   { background: linear-gradient(135deg, #1e1b4b, #3730a3); color: white; }
    .sc-indigo { background: linear-gradient(135deg, #4338ca, #6366f1); color: white; }
    .sc-violet { background: linear-gradient(135deg, #6d28d9, #7c3aed); color: white; }
    .sc-pink   { background: linear-gradient(135deg, #be185d, #ec4899); color: white; }
    .sc-amber  { background: linear-gradient(135deg, #b45309, #d97706); color: white; }
    .sc-emerald{ background: linear-gradient(135deg, #047857, #059669); color: white; }

    .user-row { transition: background 0.15s ease; }
    .user-row:hover { background: #f9fafb; }

    .avatar-admin {
        width: 38px; height: 38px;
        border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        font-weight: 800; font-size: 0.875rem;
        flex-shrink: 0;
    }
</style>
@endpush

@section('content')
<div class="dash-font space-y-6">

    {{-- Header Banner --}}
    <div class="hero-admin rounded-2xl p-6 md:p-8 text-white shadow-md">
        <div class="relative z-10">
            <div class="inline-flex items-center gap-2 bg-white/10 border border-white/20 rounded-full px-3 py-1 mb-3">
                <i class="icon-shield" style="width:14px;height:14px;"></i>
                <span class="text-xs font-semibold text-indigo-100 uppercase tracking-wider">Admin Panel</span>
            </div>
            <h1 class="text-2xl md:text-3xl font-extrabold mb-2">Dashboard Administrator</h1>
            <p class="text-indigo-200 text-sm">Kelola seluruh sistem ALSYCODE.</p>
        </div>
    </div>

    {{-- Stats Grid --}}
    <div class="grid grid-cols-2 lg:grid-cols-3 gap-4">
        <div class="stat-card-admin sc-indigo">
            <p class="text-indigo-200 text-xs font-semibold uppercase tracking-wide mb-2">Total Guru</p>
            <p class="text-3xl md:text-4xl font-extrabold">{{ $stats['total_guru'] }}</p>
            <div class="mt-2 text-indigo-200 text-xs flex items-center gap-1.5">
                <i class="icon-user-check" style="width:14px;height:14px;"></i> Akun Pengajar
            </div>
        </div>
        
        <div class="stat-card-admin sc-violet">
            <p class="text-violet-200 text-xs font-semibold uppercase tracking-wide mb-2">Total Siswa</p>
            <p class="text-3xl md:text-4xl font-extrabold">{{ $stats['total_siswa'] }}</p>
            <div class="mt-2 text-violet-200 text-xs flex items-center gap-1.5">
                <i class="icon-graduation-cap" style="width:14px;height:14px;"></i> Akun Pelajar
            </div>
        </div>

        <div class="stat-card-admin sc-amber col-span-2 lg:col-span-1">
            <p class="text-amber-100 text-xs font-semibold uppercase tracking-wide mb-2">Belum Dinilai</p>
            <p class="text-3xl md:text-4xl font-extrabold">{{ $stats['pending_nilai'] }}</p>
            <div class="mt-2 text-amber-100 text-xs flex items-center gap-1.5">
                <i class="icon-clock" style="width:14px;height:14px;"></i> Submission Pending
            </div>
        </div>

        <div class="stat-card-admin sc-emerald">
            <p class="text-emerald-100 text-xs font-semibold uppercase tracking-wide mb-2">Total Materi</p>
            <p class="text-3xl md:text-4xl font-extrabold">{{ $stats['total_materi'] }}</p>
            <div class="mt-2 text-emerald-100 text-xs flex items-center gap-1.5">
                <i class="icon-book-open" style="width:14px;height:14px;"></i> Konten Belajar
            </div>
        </div>

        <div class="stat-card-admin sc-pink">
            <p class="text-pink-100 text-xs font-semibold uppercase tracking-wide mb-2">Total Aktivitas PBL</p>
            <p class="text-3xl md:text-4xl font-extrabold">{{ $stats['total_pbl'] }}</p>
            <div class="mt-2 text-pink-100 text-xs flex items-center gap-1.5">
                <i class="icon-layers" style="width:14px;height:14px;"></i> Studi Kasus
            </div>
        </div>

        <div class="stat-card-admin sc-navy">
            <p class="text-indigo-300 text-xs font-semibold uppercase tracking-wide mb-2">Total Pengguna</p>
            <p class="text-3xl md:text-4xl font-extrabold">{{ $stats['total_users'] }}</p>
            <div class="mt-2 text-indigo-300 text-xs flex items-center gap-1.5">
                <i class="icon-users" style="width:14px;height:14px;"></i> Semua Pengguna
            </div>
        </div>
    </div>

    {{-- Recent Users List --}}
    <div class="bg-white border border-gray-100 rounded-2xl overflow-hidden shadow-sm">
        <div class="p-4 md:p-5 border-b border-gray-100 flex flex-col sm:flex-row sm:items-center justify-between gap-3 bg-gray-50/50">
            <div class="flex items-center gap-2">
                <i class="icon-user-plus text-indigo-600" style="width:18px;height:18px;"></i>
                <h2 class="font-bold text-gray-800 text-sm md:text-base">Pengguna Terbaru</h2>
            </div>
            <a href="{{ route('admin.users') }}" class="text-xs font-semibold text-indigo-600 hover:text-indigo-800 transition-colors flex items-center gap-1 self-start sm:self-auto">
                Lihat semua <i class="icon-arrow-right" style="width:12px;height:12px;"></i>
            </a>
        </div>
        <div class="divide-y divide-gray-50">
            @forelse($recentUsers as $u)
            <div class="user-row px-4 md:px-5 py-3.5 flex items-center gap-3 md:gap-4">
                <div class="avatar-admin {{ $u->role === 'guru' ? 'bg-indigo-100 text-indigo-700' : 'bg-gray-100 text-gray-700' }}">
                    {{ strtoupper(substr($u->name, 0, 1)) }}
                </div>
                
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-semibold text-gray-800 truncate">{{ $u->name }}</p>
                    <p class="text-xs text-gray-400 truncate">{{ $u->email }}</p>
                </div>
                
                <div class="flex flex-col sm:flex-row gap-1.5 sm:gap-2 shrink-0 items-end sm:items-center">
                    <span class="text-[10px] sm:text-xs capitalize px-2.5 py-1 rounded-md font-bold tracking-wide {{ $u->role === 'guru' ? 'bg-indigo-50 text-indigo-600 border border-indigo-100' : 'bg-gray-50 text-gray-600 border border-gray-200' }}">
                        {{ $u->role }}
                    </span>
                    <span class="text-[10px] sm:text-xs px-2.5 py-1 rounded-full font-bold flex items-center gap-1 {{ $u->is_active ? 'bg-emerald-50 text-emerald-600 border border-emerald-100' : 'bg-red-50 text-red-600 border border-red-100' }}">
                        <span class="w-1.5 h-1.5 rounded-full {{ $u->is_active ? 'bg-emerald-500' : 'bg-red-500' }}"></span>
                        {{ $u->is_active ? 'Aktif' : 'Nonaktif' }}
                    </span>
                </div>
            </div>
            @empty
            <div class="px-5 py-8 text-center">
                <i class="icon-users text-gray-300 mx-auto mb-2" style="width:32px;height:32px;"></i>
                <p class="text-sm text-gray-400">Belum ada pengguna yang mendaftar.</p>
            </div>
            @endforelse
        </div>
    </div>

</div>
@endsection