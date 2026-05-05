@extends('layouts.app')
@section('title', 'Kerjakan Soal')

@section('content')

<div class="mb-6 flex items-center justify-between">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Kumpulan Soal</h1>
        <p class="text-sm text-gray-500 mt-1">Pilih kumpulan soal untuk dikerjakan.</p>
    </div>
    <a href="{{ route('kumpulan-soal.riwayat') }}"
       class="px-4 py-2 rounded-xl bg-gray-100 text-gray-700 text-sm font-semibold hover:bg-gray-200 transition-colors">
        📋 Riwayat Saya
    </a>
</div>

@if(session('error'))
<div class="mb-5 px-4 py-3 rounded-xl bg-red-50 border border-red-200 text-red-700 text-sm">{{ session('error') }}</div>
@endif

@if($sets->isEmpty())
<div class="bg-white border border-gray-200 rounded-2xl p-16 text-center">
    <div class="text-5xl mb-3">📚</div>
    <p class="font-semibold text-gray-700">Belum ada kumpulan soal</p>
    <p class="text-sm text-gray-400 mt-1">Guru belum membuat kumpulan soal.</p>
</div>
@else

<div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-5">
    @foreach($sets as $set)
    <div class="bg-white border border-gray-200 rounded-2xl overflow-hidden shadow-sm hover:shadow-md transition-shadow flex flex-col">

        <div class="p-5 flex-1">
            <div class="flex items-start justify-between gap-2 mb-2">
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold border {{ $set->type_color }}">
                    {{ $set->type_label }}
                </span>
                @if($set->attempt_count > 0)
                <span class="text-xs text-gray-400">{{ $set->attempt_count }}× dikerjakan</span>
                @endif
            </div>

            <h3 class="font-bold text-gray-900 mb-1">{{ $set->name }}</h3>

            @if($set->description)
            <p class="text-sm text-gray-500 mb-3 line-clamp-2">{{ $set->description }}</p>
            @endif

            <p class="text-xs text-gray-400">{{ $set->questions_count }} soal</p>

            @if($set->last_result)
            <div class="mt-3 bg-gray-50 rounded-xl px-3 py-2.5 flex items-center justify-between">
                <div>
                    <p class="text-xs text-gray-500">Skor terakhir</p>
                    <p class="text-sm font-bold {{ $set->last_result->persentase >= 80 ? 'text-green-600' : ($set->last_result->persentase >= 60 ? 'text-yellow-600' : 'text-red-500') }}">
                        {{ $set->last_result->score }}/{{ $set->last_result->total_questions }}
                        ({{ $set->last_result->persentase }}%)
                    </p>
                </div>
                <p class="text-xs text-gray-400">{{ \Carbon\Carbon::parse($set->last_result->taken_at)->diffForHumans() }}</p>
            </div>
            @endif
        </div>

        <div class="px-5 pb-5">
            <a href="{{ route('kumpulan-soal.take', $set) }}"
               class="block w-full text-center py-2.5 rounded-xl bg-indigo-600 text-white text-sm font-bold hover:bg-indigo-700 transition-colors">
                {{ $set->attempt_count > 0 ? '🔄 Kerjakan Lagi' : '▶ Mulai Mengerjakan' }}
            </a>
        </div>

    </div>
    @endforeach
</div>

@endif

@endsection
