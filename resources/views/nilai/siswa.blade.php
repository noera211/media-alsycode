@extends('layouts.app')
@section('title', 'Nilai & Evaluasi')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-900">Nilai & Evaluasi</h1>
    <p class="text-gray-500 mt-1 text-sm">Lihat nilai PBL, feedback guru, dan kerjakan test.</p>
</div>

<div x-data="{ tab: 'nilai' }">
    <div class="flex gap-1 bg-gray-100 p-1 rounded-xl mb-6 w-fit">
        <button @click="tab='nilai'" :class="tab==='nilai' ? 'bg-white shadow text-gray-900' : 'text-gray-500 hover:text-gray-700'"
            class="px-4 py-2 rounded-lg text-sm font-medium transition-all">🏆 Nilai PBL</button>
        <button @click="tab='test'" :class="tab==='test' ? 'bg-white shadow text-gray-900' : 'text-gray-500 hover:text-gray-700'"
            class="px-4 py-2 rounded-lg text-sm font-medium transition-all">📋 Test Mandiri</button>
    </div>

    {{-- Tab Nilai PBL --}}
    <div x-show="tab==='nilai'">
        @if($lastTestResult)
        <div class="card p-5 mb-5 flex items-center gap-4">
            <div class="h-12 w-12 bg-indigo-100 rounded-xl flex items-center justify-center">
                <span class="text-2xl font-bold text-indigo-600">{{ $lastTestResult->persentase }}</span>
            </div>
            <div>
                <p class="font-semibold text-gray-800">Hasil Test Terakhir</p>
                <p class="text-xs text-gray-400">{{ $lastTestResult->score }}/{{ $lastTestResult->total_questions }} benar · {{ \Carbon\Carbon::parse($lastTestResult->taken_at)->diffForHumans() }}</p>
            </div>
        </div>
        @endif

        <div class="card overflow-hidden">
            <div class="p-5 border-b border-gray-100">
                <h2 class="font-semibold text-gray-800">Nilai Aktivitas PBL</h2>
            </div>
            <div class="divide-y divide-gray-100">
                @forelse($submissions as $sub)
                <div class="p-5">
                    <div class="flex items-start justify-between">
                        <div>
                            <p class="font-medium text-gray-800 text-sm">{{ $sub->activity->title }}</p>
                            <p class="text-xs text-gray-400 mt-0.5">
                                Dikumpulkan {{ \Carbon\Carbon::parse($sub->submitted_at)->format('d M Y') }}
                                @if($sub->graded_at) · Dinilai {{ \Carbon\Carbon::parse($sub->graded_at)->format('d M Y') }} @endif
                            </p>
                        </div>
                        <div class="text-right">
                            @if($sub->nilai !== null)
                            <span class="inline-flex items-center justify-center h-9 w-12 rounded-lg text-base font-bold
                                {{ $sub->nilai >= 80 ? 'bg-emerald-100 text-emerald-700' : ($sub->nilai >= 60 ? 'bg-amber-100 text-amber-700' : 'bg-red-100 text-red-700') }}">
                                {{ $sub->nilai }}
                            </span>
                            @else
                            <span class="text-xs text-gray-400">Menunggu penilaian</span>
                            @endif
                        </div>
                    </div>
                    @if($sub->feedback)
                    <div class="mt-3 bg-gray-50 rounded-lg p-3">
                        <p class="text-xs text-gray-400 mb-1">💬 Feedback Guru:</p>
                        <p class="text-sm text-gray-700">{{ $sub->feedback }}</p>
                    </div>
                    @endif
                </div>
                @empty
                <div class="p-10 text-center text-gray-400 text-sm">
                    Belum ada pengumpulan aktivitas PBL.
                    <a href="{{ route('pbl.index') }}" class="text-indigo-500 underline">Kerjakan sekarang →</a>
                </div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- Tab Test Mandiri --}}
    <div x-show="tab==='test'" style="display:none"
         x-data="testApp({{ $questions->count() }})">

        {{-- Hasil --}}
        <template x-if="submitted">
            <div class="card p-6 mb-6">
                <div class="flex items-center gap-5">
                    <div class="text-center">
                        <p class="text-4xl font-bold text-indigo-600" x-text="scorePercent + '/100'"></p>
                        <p class="text-xs text-gray-400 mt-1">Skor Anda</p>
                    </div>
                    <div>
                        <p class="text-sm font-semibold" x-text="'Benar: ' + correctCount + '/{{ $questions->count() }}'"></p>
                        <p class="text-xs text-gray-500" x-text="scorePercent >= 80 ? 'Sangat Baik! 🎉' : scorePercent >= 60 ? 'Cukup Baik 👍' : 'Perlu belajar lagi 📚'"></p>
                    </div>
                </div>
                <div class="mt-4 flex gap-3">
                    <button @click="resetTest()" class="btn-outline text-xs">Ulangi Test</button>
                    <form action="{{ route('nilai.test.submit') }}" method="POST" id="save-test-form">
                        @csrf
                        <div id="answers-hidden"></div>
                        <button type="submit" onclick="populateAnswers()" class="btn-primary text-xs">💾 Simpan Hasil ke Database</button>
                    </form>
                </div>
            </div>
        </template>

        {{-- Soal --}}
        <div class="card">
            <div class="p-5 border-b border-gray-100 flex items-center justify-between">
                <div>
                    <h2 class="font-semibold text-gray-800">Test Algoritma Pemrograman</h2>
                    <p class="text-xs text-gray-400">{{ $questions->count() }} soal pilihan ganda</p>
                </div>
                <template x-if="!submitted">
                    <span class="text-xs text-gray-400" x-text="answeredCount + '/{{ $questions->count() }} terjawab'"></span>
                </template>
            </div>

            @foreach($questions as $qi => $q)
            <div class="p-5 {{ !$loop->last ? 'border-b border-gray-100' : '' }}">
                <p class="text-sm font-medium text-gray-800 mb-3">
                    <span class="text-indigo-600 font-bold mr-1">{{ $qi + 1 }}.</span>
                    {{ $q->question }}
                </p>
                <div class="space-y-2">
                    @foreach($q->options as $key => $val)
                    <button type="button"
                        @click="!submitted && selectAnswer({{ $q->id }}, '{{ $key }}')"
                        :class="{
                            'border-indigo-500 bg-indigo-50 text-indigo-700 font-medium': !submitted && answers[{{ $q->id }}] === '{{ $key }}',
                            'bg-emerald-50 border-emerald-400 text-emerald-800': submitted && '{{ $key }}' === '{{ $q->correct_answer }}',
                            'bg-red-50 border-red-300 text-red-700': submitted && answers[{{ $q->id }}] === '{{ $key }}' && '{{ $key }}' !== '{{ $q->correct_answer }}',
                            'opacity-50': submitted && answers[{{ $q->id }}] !== '{{ $key }}' && '{{ $key }}' !== '{{ $q->correct_answer }}'
                        }"
                        class="w-full text-left border border-gray-200 rounded-lg px-4 py-2.5 text-sm transition-all hover:border-indigo-300">
                        <span class="font-medium mr-2">{{ $key }}.</span>{{ $val }}
                        @if($key === $q->correct_answer) <span x-show="submitted" class="text-emerald-600 ml-2">✓</span> @endif
                    </button>
                    @endforeach
                </div>
            </div>
            @endforeach

            <div class="p-5 border-t border-gray-100" x-show="!submitted">
                <button
                    :disabled="answeredCount < {{ $questions->count() }}"
                    @click="submitTest()"
                    :class="answeredCount < {{ $questions->count() }} ? 'opacity-50 cursor-not-allowed' : ''"
                    class="btn-primary w-full">
                    Kumpulkan Jawaban (<span x-text="answeredCount"></span>/{{ $questions->count() }} terjawab)
                </button>
            </div>
        </div>
    </div>
</div>

<script src="//unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

@push('scripts')
<script>
const correctAnswers = {
    @foreach($questions as $q)
    {{ $q->id }}: '{{ $q->correct_answer }}',
    @endforeach
};

function testApp(total) {
    return {
        answers: {},
        submitted: false,
        correctCount: 0,
        scorePercent: 0,
        get answeredCount() { return Object.keys(this.answers).length; },
        selectAnswer(qId, key) { this.answers[qId] = key; },
        submitTest() {
            this.correctCount = 0;
            for (const [qId, chosen] of Object.entries(this.answers)) {
                if (correctAnswers[qId] === chosen) this.correctCount++;
            }
            this.scorePercent = Math.round((this.correctCount / total) * 100);
            this.submitted = true;
            window.scrollTo({ top: 0, behavior: 'smooth' });
        },
        resetTest() {
            this.answers = {};
            this.submitted = false;
            this.correctCount = 0;
            this.scorePercent = 0;
        }
    };
}

function populateAnswers() {
    const container = document.getElementById('answers-hidden');
    container.innerHTML = '';
    // Cek state Alpine
    const el = document.querySelector('[x-data]');
    if (!el) return;
    const data = Alpine.$data ? Alpine.$data(el) : null;
}
</script>
@endpush
