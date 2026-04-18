@extends('layouts.app')
@section('title', 'Nilai & Evaluasi')

@section('content')
<div class="mb-6 flex items-center justify-between">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Nilai & Evaluasi</h1>
        <p class="text-gray-500 mt-1 text-sm">Lihat hasil belajar dan kerjakan test mandiri.</p>
    </div>
</div>

{{-- 3 Kartu Ringkasan Nilai --}}
<div class="grid grid-cols-3 gap-4 mb-6">

    {{-- Nilai PBL --}}
    <div class="card p-5 text-center">
        <p class="text-xs font-medium text-gray-500 mb-2">Nilai Aktivitas PBL</p>
        @if($nilaiPbl !== null)
            <span class="text-3xl font-bold {{ $nilaiPbl >= 80 ? 'text-emerald-600' : ($nilaiPbl >= 60 ? 'text-amber-600' : 'text-red-600') }}">
                {{ $nilaiPbl }}
            </span>
            @if($catatanPbl)
            <p class="text-xs text-gray-400 mt-1">{{ $catatanPbl }}</p>
            @else
            <p class="text-xs text-gray-400 mt-1">dinilai oleh guru</p>
            @endif
        @else
            <span class="text-2xl font-bold text-gray-300">—</span>
            <p class="text-xs text-gray-400 mt-1">belum dinilai guru</p>
        @endif
    </div>

    {{-- Nilai Evaluasi --}}
    <div class="card p-5 text-center">
        <p class="text-xs font-medium text-gray-500 mb-2">Nilai Evaluasi</p>
        @if($nilaiEvaluasi !== null)
            <span class="text-3xl font-bold {{ $nilaiEvaluasi >= 80 ? 'text-emerald-600' : ($nilaiEvaluasi >= 60 ? 'text-amber-600' : 'text-red-600') }}">
                {{ $nilaiEvaluasi }}
            </span>
            <p class="text-xs text-gray-400 mt-1">
                {{ $lastTestResult->score }}/{{ $lastTestResult->total_questions }} benar
                · {{ \Carbon\Carbon::parse($lastTestResult->taken_at)->diffForHumans() }}
            </p>
        @else
            <span class="text-2xl font-bold text-gray-300">—</span>
            <p class="text-xs text-gray-400 mt-1">belum mengerjakan test</p>
        @endif
    </div>

    {{-- Nilai Akhir --}}
    <div class="card p-5 text-center border-indigo-100 bg-indigo-50">
        <p class="text-xs font-medium text-indigo-600 mb-2">Nilai Akhir</p>
        @if($nilaiAkhir !== null)
            <span class="text-3xl font-bold {{ $nilaiAkhir >= 80 ? 'text-emerald-600' : ($nilaiAkhir >= 60 ? 'text-amber-600' : 'text-red-600') }}">
                {{ $nilaiAkhir }}
            </span>
            <p class="text-xs text-gray-400 mt-1">(PBL + Evaluasi) ÷ 2</p>
        @else
            <span class="text-2xl font-bold text-gray-300">—</span>
            <p class="text-xs text-gray-400 mt-1">butuh kedua nilai</p>
        @endif
    </div>

</div>

{{-- Detail Nilai PBL per Aktivitas --}}
<div class="mb-8">
    <h2 class="font-semibold text-gray-800 text-sm mb-3">📋 Riwayat Nilai Aktivitas PBL</h2>
    <div class="grid md:grid-cols-2 gap-4">
        @forelse($submissions as $sub)
        <div class="card p-5 hover:shadow-md transition-shadow">
            <div class="flex items-start justify-between mb-3">
                <div>
                    <h3 class="font-semibold text-gray-800 text-sm">{{ $sub->activity->title }}</h3>
                    <p class="text-xs text-gray-400">
                        Dikumpulkan {{ \Carbon\Carbon::parse($sub->submitted_at)->format('d M Y') }}
                        @if($sub->graded_at)
                            · Dinilai {{ \Carbon\Carbon::parse($sub->graded_at)->format('d M Y') }}
                        @endif
                    </p>
                </div>
                <span class="text-lg font-bold px-3 py-1 rounded-lg flex-shrink-0
                    {{ $sub->nilai >= 80 ? 'bg-emerald-100 text-emerald-700' :
                       ($sub->nilai >= 60 ? 'bg-amber-100 text-amber-700' : 'bg-red-100 text-red-700') }}">
                    {{ $sub->nilai }}
                </span>
            </div>
            @if($sub->feedback)
            <div class="bg-gray-50 rounded-lg p-3 text-sm text-gray-700">
                💬 {{ $sub->feedback }}
            </div>
            @endif
        </div>
        @empty
        <div class="col-span-2 card p-10 text-center text-gray-400 text-sm">
            Belum ada aktivitas PBL yang dinilai.
            <br>
            <a href="{{ route('pbl.index') }}" class="text-indigo-500 underline mt-1 inline-block">
                Kerjakan aktivitas sekarang →
            </a>
        </div>
        @endforelse
    </div>
</div>

{{-- Test Evaluasi Mandiri --}}
<div>
    <h2 class="font-semibold text-gray-800 text-sm mb-3">📝 Test Evaluasi Mandiri</h2>

    @if($lastTestResult)
    <div class="card p-4 mb-4 flex items-center gap-4 bg-gray-50">
        <div class="h-12 w-12 bg-indigo-100 rounded-xl flex items-center justify-center flex-shrink-0">
            <span class="text-lg font-bold text-indigo-600">{{ $lastTestResult->persentase }}</span>
        </div>
        <div>
            <p class="font-semibold text-gray-800 text-sm">Hasil Test Terakhir</p>
            <p class="text-xs text-gray-400">
                {{ $lastTestResult->score }}/{{ $lastTestResult->total_questions }} benar ·
                {{ \Carbon\Carbon::parse($lastTestResult->taken_at)->diffForHumans() }}
            </p>
        </div>
    </div>
    @endif

    @if($questions->count() > 0)
    <div class="card p-5">
        <form action="{{ route('nilai.test.submit') }}" method="POST">
            @csrf
            @foreach($questions as $i => $q)
            <div class="mb-5 {{ !$loop->last ? 'border-b pb-4' : '' }}">
                <p class="text-sm font-medium mb-2">
                    <span class="text-indigo-600 font-bold">{{ $i+1 }}.</span>
                    {{ $q->question }}
                </p>
                <div class="space-y-2">
                    @foreach($q->options as $key => $val)
                    <label class="flex items-center gap-2 border rounded-lg px-3 py-2 cursor-pointer hover:border-indigo-400 transition-colors">
                        <input type="radio" name="answers[{{ $q->id }}]" value="{{ $key }}" class="text-indigo-600">
                        <span class="font-medium text-sm">{{ $key }}.</span>
                        <span class="text-sm">{{ $val }}</span>
                    </label>
                    @endforeach
                </div>
            </div>
            @endforeach
            <button type="submit" class="btn-primary w-full mt-4">Kumpulkan Jawaban</button>
        </form>
    </div>
    @else
    <div class="card p-10 text-center text-gray-400 text-sm">
        Belum ada soal test dari guru.
    </div>
    @endif
</div>

@endsection