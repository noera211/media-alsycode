@extends('layouts.app')
@section('title', 'Riwayat Kumpulan Soal')

@section('content')

<div class="mb-6 flex items-center gap-4">
    <a href="{{ route('kumpulan-soal.index') }}" class="text-gray-400 hover:text-gray-600">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
        </svg>
    </a>
    <div class="flex-1">
        <div class="flex items-center gap-2 mb-0.5">
            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold border {{ $set->type_color }}">
                {{ $set->type_label }}
            </span>
            <h1 class="text-xl font-bold text-gray-900">{{ $set->name }}</h1>
        </div>
        @if($set->description)
        <p class="text-sm text-gray-500">{{ $set->description }}</p>
        @endif
    </div>
    <a href="{{ route('kumpulan-soal.edit', $set) }}"
       class="px-4 py-2 rounded-xl bg-indigo-50 text-indigo-700 border border-indigo-200 text-sm font-semibold hover:bg-indigo-100">
        ✏ Edit
    </a>
</div>

{{-- Stats cards --}}
<div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-6">
    <div class="bg-white border border-gray-200 rounded-2xl p-4 text-center">
        <p class="text-2xl font-bold text-indigo-600">{{ $set->questions->count() }}</p>
        <p class="text-xs text-gray-500 mt-1">Jumlah Soal</p>
    </div>
    <div class="bg-white border border-gray-200 rounded-2xl p-4 text-center">
        <p class="text-2xl font-bold text-indigo-600">{{ $riwayat->count() }}</p>
        <p class="text-xs text-gray-500 mt-1">Total Pengerjaan</p>
    </div>
    <div class="bg-white border border-gray-200 rounded-2xl p-4 text-center">
        <p class="text-2xl font-bold text-indigo-600">{{ $riwayat->unique('student_id')->count() }}</p>
        <p class="text-xs text-gray-500 mt-1">Siswa Mengerjakan</p>
    </div>
    <div class="bg-white border border-gray-200 rounded-2xl p-4 text-center">
        <p class="text-2xl font-bold text-indigo-600">
            {{ $riwayat->count() > 0 ? round($riwayat->avg('score') / max($set->questions->count(), 1) * 100) : '—' }}{{ $riwayat->count() > 0 ? '%' : '' }}
        </p>
        <p class="text-xs text-gray-500 mt-1">Rata-rata Skor</p>
    </div>
</div>

{{-- Riwayat tabel --}}
<div class="bg-white border border-gray-200 rounded-2xl overflow-hidden shadow-sm">
    <div class="px-5 py-4 border-b border-gray-100">
        <h3 class="font-bold text-gray-900">Riwayat Pengerjaan Siswa</h3>
    </div>

    @if($riwayat->isEmpty())
    <div class="py-14 text-center text-gray-400 text-sm">
        Belum ada siswa yang mengerjakan kumpulan soal ini.
    </div>
    @else
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-gray-50 text-xs text-gray-500 uppercase tracking-wide">
                    <th class="px-5 py-3 text-left font-semibold">Siswa</th>
                    <th class="px-5 py-3 text-center font-semibold">Skor</th>
                    <th class="px-5 py-3 text-center font-semibold">Nilai (%)</th>
                    <th class="px-5 py-3 text-center font-semibold">Attempt ke-</th>
                    <th class="px-5 py-3 text-left font-semibold">Waktu</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @php
                    $attemptCounter = [];
                @endphp
                @foreach($riwayat as $r)
                @php
                    $sid = $r->student_id;
                    if (!isset($attemptCounter[$sid])) $attemptCounter[$sid] = 0;
                    $attemptCounter[$sid]++;
                    $pct = $r->persentase;
                    $color = $pct >= 80 ? 'text-green-600' : ($pct >= 60 ? 'text-yellow-600' : 'text-red-500');
                @endphp
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-5 py-3">
                        <div class="flex items-center gap-2.5">
                            <div class="w-8 h-8 rounded-full bg-indigo-100 flex items-center justify-center text-xs font-bold text-indigo-600">
                                {{ substr($r->student->name ?? '?', 0, 1) }}
                            </div>
                            <div>
                                <p class="font-medium text-gray-800">{{ $r->student->name ?? '-' }}</p>
                                <p class="text-xs text-gray-400">{{ $r->student->email ?? '' }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-5 py-3 text-center font-semibold text-gray-700">
                        {{ $r->score }}/{{ $r->total_questions }}
                    </td>
                    <td class="px-5 py-3 text-center">
                        <span class="font-bold {{ $color }}">{{ $pct }}%</span>
                    </td>
                    <td class="px-5 py-3 text-center text-gray-500">
                        #{{ $attemptCounter[$sid] }}
                    </td>
                    <td class="px-5 py-3 text-gray-400 text-xs">
                        {{ \Carbon\Carbon::parse($r->taken_at)->format('d M Y, H:i') }}
                        <span class="block">{{ \Carbon\Carbon::parse($r->taken_at)->diffForHumans() }}</span>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif

</div>

@endsection
