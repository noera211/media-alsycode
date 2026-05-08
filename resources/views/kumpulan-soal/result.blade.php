@extends('layouts.app')
@section('title', 'Hasil ' . $result->questionSet->name)

@section('content')

<div class="max-w-2xl mx-auto">

    {{-- Score card --}}
    @php
        $pct    = $result->persentase;
        $isPass = $pct >= 70;
    @endphp
    <div class="bg-white border border-gray-200 rounded-2xl p-8 text-center shadow-sm mb-6">
        <div class="text-5xl mb-3">{{ $isPass ? '🎉' : '📖' }}</div>

        <div class="flex items-center justify-center gap-2 mb-2">
            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold border {{ $result->questionSet->type_color }}">
                {{ $result->questionSet->type_label }}
            </span>
            <h2 class="text-lg font-bold text-gray-900">{{ $result->questionSet->name }}</h2>
        </div>

        <div class="text-6xl font-black {{ $pct >= 80 ? 'text-green-500' : ($pct >= 60 ? 'text-yellow-500' : 'text-red-500') }} my-4">
            {{ $pct }}%
        </div>

        <p class="text-gray-600 text-sm">
            Kamu menjawab benar <strong class="text-gray-900">{{ $result->score }}</strong> dari
            <strong class="text-gray-900">{{ $result->total_questions }}</strong> soal
        </p>

        <p class="text-xs text-gray-400 mt-2">
            Dikerjakan {{ \Carbon\Carbon::parse($result->taken_at)->format('d M Y, H:i') }}
        </p>
    </div>

    {{-- Review jawaban --}}
    <div class="bg-white border border-gray-200 rounded-2xl overflow-hidden shadow-sm mb-6">
        <div class="px-5 py-4 border-b border-gray-100">
            <h3 class="font-bold text-gray-900">Review Jawaban</h3>
            @if($result->shuffled_options)
                <p class="text-xs text-amber-600 mt-1">🔀 Opsi jawaban diacak saat pengerjaan ulang — review ini menampilkan urutan yang sama seperti saat kamu mengerjakan.</p>
            @endif
        </div>

        <div class="divide-y divide-gray-100">
            @foreach($result->questionSet->questions as $i => $q)
            @php
                $myAnswerKey = $result->answers[$q->id] ?? null;

                // Jika ada shuffled_options (remedial), gunakan opsi teracak untuk review
                // sehingga tampilan sinkron dengan apa yang siswa lihat saat mengerjakan
                $shuffled      = $result->shuffled_options[$q->id] ?? null;
                $displayOptions = $shuffled ?? $q->options;

                // Tentukan apakah jawaban siswa benar
                // Pada mode remedial: key yang dipilih merujuk ke value teracak,
                // kita cari key aslinya untuk cek kebenaran
                if ($shuffled && $myAnswerKey !== null) {
                    $chosenValue = $shuffled[$myAnswerKey] ?? null;
                    $originalKey = $chosenValue ? array_search($chosenValue, $q->options) : null;
                    $isCorrect   = $originalKey !== false && $originalKey === $q->correct_answer;

                    // Cari key tampilan yang berisi jawaban benar (untuk highlight)
                    $correctDisplayKey = array_search($q->options[$q->correct_answer], $shuffled);
                } else {
                    $isCorrect         = $myAnswerKey === $q->correct_answer;
                    $correctDisplayKey = $q->correct_answer;
                }
            @endphp
            <div class="p-5">
                <div class="flex items-start gap-3 mb-3">
                    <div class="w-7 h-7 min-w-[1.75rem] rounded-full flex items-center justify-center text-xs font-bold
                        {{ $isCorrect ? 'bg-green-100 text-green-600' : 'bg-red-100 text-red-500' }}">
                        {{ $isCorrect ? '✓' : '✗' }}
                    </div>
                    <div class="text-sm text-gray-800 prose max-w-none flex-1">
                        {!! $q->question !!}
                    </div>
                </div>
                <div class="pl-10 space-y-1.5">
                    @foreach($displayOptions as $key => $val)
                    @php
                        $isCorrectOpt = $key === $correctDisplayKey;
                        $isMyOpt     = $key === $myAnswerKey;
                        $cls = 'border border-gray-200 bg-gray-50 text-gray-600';
                        if ($isCorrectOpt) $cls = 'border border-green-300 bg-green-50 text-green-700 font-semibold';
                        if ($isMyOpt && !$isCorrectOpt) $cls = 'border border-red-200 bg-red-50 text-red-600 line-through';
                    @endphp
                    <div class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm {{ $cls }}">
                        <span class="font-bold w-5">{{ $key }}.</span>
                        <span>{{ $val }}</span>
                        @if($isCorrectOpt)<span class="ml-auto text-xs">✓ Benar</span>@endif
                        @if($isMyOpt && !$isCorrectOpt)<span class="ml-auto text-xs">Jawabanmu</span>@endif
                    </div>
                    @endforeach
                </div>
            </div>
            @endforeach
        </div>
    </div>

    {{-- Actions --}}
    <div class="flex gap-3">
        <a href="{{ route('kumpulan-soal.siswa') }}"
           class="flex-1 text-center py-3 rounded-xl bg-gray-100 text-gray-700 font-bold text-sm hover:bg-gray-200 transition-colors">
            ← Riwayat Pengerjaan
        </a>
    </div>

</div>

@endsection