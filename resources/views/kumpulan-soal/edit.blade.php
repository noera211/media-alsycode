@extends('layouts.app')
@section('title', 'Edit Kumpulan Soal')

@push('styles')
<style>
.question-card { border: 1px solid #e5e7eb; border-radius: .85rem; padding: 1rem 1.1rem; background: #fff; cursor: pointer; transition: .2s; user-select: none; }
.question-card:hover { border-color: #a5b4fc; background: #f5f3ff; }
.question-card.selected { border-color: #6366f1; background: #eef2ff; }
.question-card .check-icon { display: none; }
.question-card.selected .check-icon { display: flex; }
.type-btn { border: 2px solid #e5e7eb; border-radius: .75rem; padding: .6rem 1rem; font-size: .85rem; font-weight: 700; cursor: pointer; transition: .2s; background: #fff; color: #6b7280; }
.type-btn.active { border-color: #6366f1; background: #eef2ff; color: #4338ca; }
</style>
@endpush

@section('content')

<div class="mb-6 flex items-center gap-4">
    <a href="{{ route('kumpulan-soal.index') }}" class="text-gray-400 hover:text-gray-600">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
        </svg>
    </a>
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Edit Kumpulan Soal</h1>
        <p class="text-sm text-gray-500 mt-0.5">Ubah nama, tipe, atau pilihan soal.</p>
    </div>
</div>

@if($errors->any())
<div class="mb-5 px-4 py-3 rounded-xl bg-red-50 border border-red-200 text-red-700 text-sm">
    <ul class="list-disc list-inside space-y-1">
        @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
    </ul>
</div>
@endif

<form action="{{ route('kumpulan-soal.update', $set) }}" method="POST" id="setForm">
@csrf @method('PUT')

<div class="grid grid-cols-1 xl:grid-cols-5 gap-6">

    <div class="xl:col-span-2">
        <div class="bg-white border border-gray-200 rounded-2xl p-5 sticky top-6 shadow-sm">
            <h3 class="font-bold text-gray-900 mb-4">Informasi Kumpulan</h3>
            <div class="space-y-4">

                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-1.5">Nama</label>
                    <input type="text" name="name" value="{{ old('name', $set->name) }}" required
                           class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:border-indigo-400 focus:ring-2 focus:ring-indigo-100">
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-1.5">Tipe</label>
                    <div class="grid grid-cols-2 gap-2">
                        @foreach(\App\Models\QuestionSet::TYPE_LABELS as $val => $label)
                        <button type="button"
                                class="type-btn {{ old('type', $set->type) === $val ? 'active' : '' }}"
                                onclick="setType(this, '{{ $val }}')">
                            {{ $label }}
                        </button>
                        @endforeach
                    </div>
                    <input type="hidden" name="type" id="typeInput" value="{{ old('type', $set->type) }}">
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-1.5">Deskripsi</label>
                    <textarea name="description" rows="3"
                              class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:border-indigo-400 resize-none">{{ old('description', $set->description) }}</textarea>
                </div>

                <div class="bg-indigo-50 rounded-xl px-3 py-2.5 text-sm text-indigo-700 font-semibold">
                    <span id="selectedCount">0</span> soal dipilih
                </div>

                <button type="submit" class="w-full py-3 rounded-xl bg-indigo-600 text-white font-bold text-sm hover:bg-indigo-700 transition-colors">
                    Simpan Perubahan
                </button>
            </div>
        </div>
    </div>

    <div class="xl:col-span-3">
        <div class="bg-white border border-gray-200 rounded-2xl overflow-hidden shadow-sm">
            <div class="px-5 py-4 border-b border-gray-100">
                <h3 class="font-bold text-gray-900">Pilih Soal</h3>
            </div>
            <div class="p-4 space-y-3">
                @foreach($questions as $i => $q)
                <div class="question-card {{ in_array($q->id, old('question_ids', $selectedIds)) ? 'selected' : '' }}"
                     data-id="{{ $q->id }}" onclick="toggleQuestion(this)">
                    <div class="flex items-start gap-3">
                        <div class="flex-shrink-0 mt-0.5 w-6 h-6 rounded-full bg-indigo-100 text-indigo-600 text-xs font-bold flex items-center justify-center">{{ $i+1 }}</div>
                        <div class="flex-1 min-w-0">
                            <div class="text-sm text-gray-800 leading-relaxed line-clamp-2">{!! strip_tags($q->question) !!}</div>
                            <div class="mt-1 flex flex-wrap gap-1.5">
                                @foreach($q->options as $key => $val)
                                <span class="text-xs px-2 py-0.5 rounded-full {{ $key === $q->correct_answer ? 'bg-green-100 text-green-700 font-bold' : 'bg-gray-100 text-gray-500' }}">
                                    {{ $key }}. {{ Str::limit($val, 25) }}
                                </span>
                                @endforeach
                            </div>
                        </div>
                        <div class="check-icon flex-shrink-0 w-6 h-6 rounded-full bg-indigo-600 items-center justify-center">
                            <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                            </svg>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

</div>

<div id="hiddenInputs"></div>
</form>

@endsection

@push('scripts')
<script>
    const selected = new Set(@json(old('question_ids', $selectedIds)).map(Number));

    function updateCount() {
        document.getElementById('selectedCount').textContent = selected.size;
        const c = document.getElementById('hiddenInputs');
        c.innerHTML = '';
        selected.forEach(id => {
            const i = document.createElement('input');
            i.type = 'hidden'; i.name = 'question_ids[]'; i.value = id;
            c.appendChild(i);
        });
    }

    function toggleQuestion(card) {
        const id = parseInt(card.dataset.id);
        if (selected.has(id)) { selected.delete(id); card.classList.remove('selected'); }
        else { selected.add(id); card.classList.add('selected'); }
        updateCount();
    }

    function setType(btn, val) {
        document.getElementById('typeInput').value = val;
        document.querySelectorAll('.type-btn').forEach(b => b.classList.remove('active'));
        btn.classList.add('active');
    }

    updateCount();
</script>
@endpush
