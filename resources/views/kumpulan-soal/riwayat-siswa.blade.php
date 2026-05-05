@extends('layouts.app')
@section('title', 'Riwayat Saya')

@section('content')

<div class="mb-6 flex items-center gap-4">
    <a href="{{ route('nilai.index') }}" class="text-gray-400 hover:text-gray-600">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
        </svg>
    </a>
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Riwayat Pengerjaan Saya</h1>
        <p class="text-sm text-gray-500 mt-0.5">Semua attempt yang pernah kamu kerjakan.</p>
    </div>
</div>

@if($riwayat->isEmpty())
<div class="bg-white border border-gray-200 rounded-2xl p-16 text-center">
    <div class="text-5xl mb-3">📭</div>
    <p class="font-semibold text-gray-700">Belum ada riwayat</p>
    <p class="text-sm text-gray-400 mt-1">Kamu belum mengerjakan kumpulan soal apapun.</p>
    <a href="{{ route('kumpulan-soal.siswa') }}" class="mt-4 inline-block px-5 py-2.5 rounded-xl bg-indigo-600 text-white text-sm font-bold hover:bg-indigo-700 transition-colors">
        Kerjakan Sekarang
    </a>
</div>
@else

{{-- Group by kumpulan soal --}}
@php
    $grouped = $riwayat->groupBy('question_set_id');
@endphp

<div class="space-y-6">
    @foreach($grouped as $setId => $results)
    @php $firstResult = $results->first(); @endphp
    <div class="bg-white border border-gray-200 rounded-2xl overflow-hidden shadow-sm">

        <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
            <div class="flex items-center gap-2">
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold border {{ $firstResult->questionSet->type_color }}">
                    {{ $firstResult->questionSet->type_label }}
                </span>
                <h3 class="font-bold text-gray-900">{{ $firstResult->questionSet->name }}</h3>
            </div>
            <span class="text-xs text-gray-400">{{ $results->count() }} attempt</span>
        </div>

        <div class="divide-y divide-gray-100">
            @foreach($results as $attempt => $r)
            <div class="flex items-center justify-between px-5 py-3.5 hover:bg-gray-50 transition-colors">
                <div class="flex items-center gap-3">
                    <div class="text-xs text-gray-400 font-medium w-16">
                        Attempt #{{ $results->count() - $attempt }}
                    </div>
                    <div>
                        <span class="font-bold {{ $r->persentase >= 80 ? 'text-green-600' : ($r->persentase >= 60 ? 'text-yellow-600' : 'text-red-500') }}">
                            {{ $r->score }}/{{ $r->total_questions }} ({{ $r->persentase }}%)
                        </span>
                        <span class="text-xs text-gray-400 ml-2">
                            {{ \Carbon\Carbon::parse($r->taken_at)->format('d M Y, H:i') }}
                        </span>
                    </div>
                </div>
                <a href="{{ route('kumpulan-soal.result', $r) }}"
                   class="text-xs px-3 py-1.5 rounded-lg bg-indigo-50 text-indigo-600 font-semibold hover:bg-indigo-100 transition-colors">
                    Lihat Detail
                </a>
            </div>
            @endforeach
        </div>

    </div>
    @endforeach
</div>

@endif

@endsection
