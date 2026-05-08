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

    {{-- Nilai PBL Final --}}
    <div class="card p-5 text-center">
        <p class="text-xs font-medium text-gray-500 mb-2">Nilai Aktivitas PBL</p>
        @if($nilaiPblFinal !== null)
            <span class="text-3xl font-bold {{ $nilaiPblFinal >= 80 ? 'text-emerald-600' : ($nilaiPblFinal >= 60 ? 'text-amber-600' : 'text-red-600') }}">
                {{ $nilaiPblFinal }}
            </span>
            <p class="text-xs text-gray-400 mt-1">rata-rata 3 level</p>
        @else
            <span class="text-2xl font-bold text-gray-300">—</span>
            <p class="text-xs text-gray-400 mt-1">belum ada submission dinilai</p>
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
            <p class="text-xs text-gray-400 mt-1">(PBL Final + Evaluasi) ÷ 2</p>
        @else
            <span class="text-2xl font-bold text-gray-300">—</span>
            <p class="text-xs text-gray-400 mt-1">butuh kedua nilai</p>
        @endif
    </div>

</div>

{{-- Breakdown Nilai PBL per Level --}}
<div class="card p-5 mb-6">
    <h2 class="font-semibold text-gray-800 text-sm mb-4">🎯 Rincian Nilai PBL per Level</h2>
    <div class="grid grid-cols-3 gap-4">
        @foreach([
            ['label' => 'Level Mudah',  'key' => 'Mudah',  'color' => 'blue'],
            ['label' => 'Level Sedang', 'key' => 'Sedang', 'color' => 'amber'],
            ['label' => 'Level Sulit',  'key' => 'Sulit',  'color' => 'rose'],
        ] as $lvl)
        @php $val = $nilaiPerLevel[$lvl['key']] ?? null; @endphp
        <div class="rounded-xl border p-4 text-center
            {{ $val !== null ? 'border-gray-200 bg-white' : 'border-dashed border-gray-200 bg-gray-50' }}">
            <p class="text-xs font-medium text-gray-500 mb-2">{{ $lvl['label'] }}</p>
            @if($val !== null)
                <span class="text-2xl font-bold
                    {{ $val >= 80 ? 'text-emerald-600' : ($val >= 60 ? 'text-amber-600' : 'text-red-600') }}">
                    {{ $val }}
                </span>
                <p class="text-xs text-gray-400 mt-1">nilai tertinggi</p>
            @else
                <span class="text-xl font-bold text-gray-300">—</span>
                <p class="text-xs text-gray-400 mt-1">belum ada</p>
            @endif
        </div>
        @endforeach
    </div>
    @if($nilaiPblFinal !== null)
    <div class="mt-4 pt-4 border-t border-gray-100 flex items-center justify-end gap-2 text-sm text-gray-500">
        <span>
            ({{ $nilaiPerLevel['Mudah'] ?? 0 }} + {{ $nilaiPerLevel['Sedang'] ?? 0 }} + {{ $nilaiPerLevel['Sulit'] ?? 0 }}) ÷ 3
        </span>
        <span class="text-gray-400">=</span>
        <span class="font-bold text-gray-800">{{ $nilaiPblFinal }}</span>
        <span class="text-gray-400 text-xs">(Nilai PBL Final)</span>
    </div>
    @endif
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
                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium mt-1
                        {{ $sub->activity->difficulty === 'Mudah' ? 'bg-blue-100 text-blue-700' :
                           ($sub->activity->difficulty === 'Sedang' ? 'bg-amber-100 text-amber-700' : 'bg-rose-100 text-rose-700') }}">
                        {{ $sub->activity->difficulty }}
                    </span>
                    <p class="text-xs text-gray-400 mt-1">
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

    @if($evaluationSet)
    <div class="card p-5 mb-4 bg-white border">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="text-xs uppercase tracking-wide text-gray-400 mb-2">Set Evaluasi Aktif</p>
                <p class="text-sm font-semibold text-gray-900">{{ $evaluationSet->name }}</p>
                <p class="text-xs text-gray-500 mt-1">{{ $evaluationSet->questions_count }} soal</p>
            </div>
            @if(!$isTestOpen)
            <div class="inline-flex items-center gap-2 rounded-xl bg-gray-100 px-4 py-3 text-sm text-gray-600">
                <span>🔒 Test dikunci</span>
            </div>
            @else
            <a href="{{ route('kumpulan-soal.take', $evaluationSet) }}" class="btn-primary w-full sm:w-auto text-center">
                Kerjakan Test Evaluasi
            </a>
            @endif
        </div>
    </div>

    @if($evaluationSet->questions_count === 0)
    <div class="card p-10 text-center text-gray-400 text-sm">
        Set evaluasi terpilih belum berisi soal. Hubungi guru untuk memilih set lain atau menambahkan soal.
    </div>
    @else
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
    @endif

    @else
    <div class="card p-10 text-center text-gray-400 text-sm">
        Belum ada set evaluasi yang dipilih guru.
    </div>
    @endif
</div>

@endsection