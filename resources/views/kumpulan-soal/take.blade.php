@extends('layouts.app')
@section('title', $set->name)

@push('styles')
<style>
.option-label {
    display: flex;
    align-items: flex-start;
    gap: .85rem;
    padding: .85rem 1rem;
    border: 1.5px solid #e5e7eb;
    border-radius: .9rem;
    cursor: pointer;
    transition: .2s;
    background: #fff;
}
.option-label:hover { border-color: #a5b4fc; background: #f5f3ff; }
.option-label input { display: none; }
.option-label.picked { border-color: #6366f1; background: #eef2ff; }
.option-badge {
    width: 1.75rem; height: 1.75rem; min-width: 1.75rem;
    border-radius: 50%; border: 2px solid #d1d5db;
    display: flex; align-items: center; justify-content: center;
    font-size: .75rem; font-weight: 800; color: #9ca3af;
    transition: .2s;
}
.option-label.picked .option-badge { border-color: #6366f1; background: #6366f1; color: #fff; }
</style>
@endpush

@section('content')

{{-- Header --}}
<div class="mb-6 flex items-center justify-between">
    <div class="flex items-center gap-3">
        <a href="{{ route('kumpulan-soal.siswa') }}" class="text-gray-400 hover:text-gray-600">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
        </a>
        <div>
            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-bold border {{ $set->type_color }} mr-2">{{ $set->type_label }}</span>
            <span class="font-bold text-gray-900">{{ $set->name }}</span>
        </div>
    </div>
    <div class="bg-indigo-50 text-indigo-700 px-3 py-1.5 rounded-xl text-sm font-bold" id="progressBadge">
        0/{{ $set->questions->count() }} dijawab
    </div>
</div>

<form action="{{ route('kumpulan-soal.submit', $set) }}" method="POST" id="testForm" onsubmit="return confirmSubmit()">
@csrf

<div class="space-y-6">
    @foreach($set->questions as $i => $q)
    <div class="bg-white border border-gray-200 rounded-2xl p-5 shadow-sm" id="q{{ $q->id }}">

        <div class="flex items-start gap-3 mb-4">
            <div class="w-8 h-8 min-w-[2rem] rounded-full bg-indigo-100 text-indigo-600 text-sm font-bold flex items-center justify-center">
                {{ $i + 1 }}
            </div>
            <div class="flex-1 text-sm text-gray-800 leading-relaxed prose max-w-none">
                {!! $q->question !!}
            </div>
        </div>

        <div class="space-y-2 pl-11">
            @foreach($q->options as $key => $val)
            <label class="option-label" id="opt-{{ $q->id }}-{{ $key }}"
                   onclick="pickOption({{ $q->id }}, '{{ $key }}', this)">
                <input type="radio" name="answers[{{ $q->id }}]" value="{{ $key }}"
                       onchange="trackAnswer({{ $q->id }})">
                <span class="option-badge">{{ $key }}</span>
                <span class="text-sm text-gray-700">{{ $val }}</span>
            </label>
            @endforeach
        </div>
    </div>
    @endforeach
</div>

<div class="mt-8 bg-white border border-gray-200 rounded-2xl p-5 shadow-sm flex items-center justify-between gap-4">
    <div>
        <p class="text-sm font-semibold text-gray-700" id="submitInfo">Jawab semua soal sebelum submit.</p>
        <p class="text-xs text-gray-400 mt-0.5">{{ $set->questions->count() }} soal total</p>
    </div>
    <button type="submit" id="submitBtn"
            class="px-6 py-3 rounded-xl bg-indigo-600 text-white font-bold text-sm hover:bg-indigo-700 transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
        Kumpulkan Jawaban
    </button>
</div>

</form>

@endsection

@push('scripts')
<script>
const total = {{ $set->questions->count() }};
const answered = new Set();

function pickOption(qid, key, el) {
    // unmark semua opsi untuk soal ini
    document.querySelectorAll(`[id^="opt-${qid}-"]`).forEach(l => l.classList.remove('picked'));
    el.classList.add('picked');
    el.querySelector('input').checked = true;
    trackAnswer(qid);
}

function trackAnswer(qid) {
    answered.add(qid);
    document.getElementById('progressBadge').textContent = `${answered.size}/${total} dijawab`;

    if (answered.size === total) {
        document.getElementById('submitInfo').textContent = '✅ Semua soal sudah dijawab.';
        document.getElementById('submitInfo').className = 'text-sm font-semibold text-green-600';
    }
}

function confirmSubmit() {
    if (answered.size < total) {
        return confirm(`Masih ada ${total - answered.size} soal belum dijawab. Tetap kumpulkan?`);
    }
    return true;
}
</script>
@endpush
