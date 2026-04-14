@extends('layouts.app')
@section('title', 'Nilai & Evaluasi')

@section('content')

<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-900">Nilai & Evaluasi</h1>
    <p class="text-gray-500 mt-1 text-sm">Lihat hasil belajar dan kerjakan test mandiri</p>
</div>

{{-- ===================== --}}
{{-- RINGKASAN NILAI --}}
{{-- ===================== --}}
@if($lastTestResult)
<div class="card p-5 mb-6 flex items-center gap-4">
    <div class="h-14 w-14 bg-indigo-100 rounded-xl flex items-center justify-center">
        <span class="text-xl font-bold text-indigo-600">
            {{ $lastTestResult->persentase }}
        </span>
    </div>
    <div>
        <p class="font-semibold text-gray-800">Hasil Test Terakhir</p>
        <p class="text-xs text-gray-400">
            {{ $lastTestResult->score }}/{{ $lastTestResult->total_questions }} benar · 
            {{ \Carbon\Carbon::parse($lastTestResult->taken_at)->diffForHumans() }}
        </p>
    </div>
</div>
@endif

{{-- ===================== --}}
{{-- GRID NILAI --}}
{{-- ===================== --}}
<div class="grid md:grid-cols-2 gap-4">

@forelse($submissions as $sub)
<div class="card p-5 hover:shadow-md transition-shadow">

    <div class="flex items-start justify-between mb-3">
        <div>
            <h3 class="font-semibold text-gray-800 text-sm">
                {{ $sub->activity->title }}
            </h3>
            <p class="text-xs text-gray-400">
                {{ \Carbon\Carbon::parse($sub->submitted_at)->format('d M Y') }}
                @if($sub->graded_at)
                    · Dinilai {{ \Carbon\Carbon::parse($sub->graded_at)->format('d M Y') }}
                @endif
            </p>
        </div>

        {{-- NILAI --}}
        @if($sub->nilai !== null)
        <span class="text-lg font-bold px-3 py-1 rounded-lg
            {{ $sub->nilai >= 80 ? 'bg-emerald-100 text-emerald-700' :
               ($sub->nilai >= 60 ? 'bg-amber-100 text-amber-700' :
               'bg-red-100 text-red-700') }}">
            {{ $sub->nilai }}
        </span>
        @else
        <span class="text-xs text-gray-400">Pending</span>
        @endif
    </div>

    {{-- FEEDBACK --}}
    @if($sub->feedback)
    <div class="bg-gray-50 rounded-lg p-3 text-sm text-gray-700">
        💬 {{ $sub->feedback }}
    </div>
    @endif

</div>
@empty
<div class="col-span-2 text-center text-gray-400 py-10">
    Belum ada nilai.
    <br>
    <a href="{{ route('pbl.index') }}" class="text-indigo-500 underline">
        Kerjakan aktivitas sekarang →
    </a>
</div>
@endforelse

</div>

{{-- ===================== --}}
{{-- TEST MANDIRI --}}
{{-- ===================== --}}
<div class="mt-8">
    <div class="card p-5">
        <h2 class="font-semibold text-gray-800 mb-4">
            Test Mandiri
        </h2>

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
                    <label class="block border rounded-lg px-3 py-2 cursor-pointer hover:border-indigo-400">
                        <input type="radio"
                               name="answers[{{ $q->id }}]"
                               value="{{ $key }}"
                               class="mr-2">
                        <span class="font-medium">{{ $key }}.</span> {{ $val }}
                    </label>
                    @endforeach
                </div>
            </div>
            @endforeach

            <button type="submit" class="btn-primary w-full mt-4">
                Kumpulkan Jawaban
            </button>
        </form>
    </div>
</div>

@endsection