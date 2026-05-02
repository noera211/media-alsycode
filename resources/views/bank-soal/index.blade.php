@extends('layouts.app')
@section('title', 'Bank Soal')

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote-lite.min.css" rel="stylesheet">

<style>
/* ===============================
   CARD
================================= */
.soft-card{
    background:#ffffff;
    border:1px solid #eef2f7;
    border-radius:1rem;
    box-shadow:
        0 10px 25px rgba(15,23,42,.05),
        0 3px 8px rgba(15,23,42,.04);
}

/* ===============================
   FORM
================================= */
.form-label{
    display:block;
    font-size:.78rem;
    font-weight:700;
    color:#6b7280;
    text-transform:uppercase;
    margin-bottom:.55rem;
    letter-spacing:.03em;
}

.form-input{
    width:100%;
    border:1px solid #e5e7eb;
    background:#f9fafb;
    border-radius:.8rem;
    padding:.75rem .95rem;
    font-size:.92rem;
    outline:none;
    transition:.2s;
}

.form-input:focus{
    background:#fff;
    border-color:#6366f1;
    box-shadow:0 0 0 4px rgba(99,102,241,.10);
}

.option-wrap{
    position:relative;
}

.option-badge{
    position:absolute;
    left:.7rem;
    top:50%;
    transform:translateY(-50%);
    width:1.55rem;
    height:1.55rem;
    border-radius:999px;
    background:#e0e7ff;
    color:#4338ca;
    font-size:.72rem;
    font-weight:800;
    display:flex;
    align-items:center;
    justify-content:center;
}

.option-wrap input{
    padding-left:2.65rem;
}

.answer-box input{
    display:none;
}

.answer-box span{
    display:flex;
    justify-content:center;
    align-items:center;
    padding:.7rem 0;
    border-radius:.75rem;
    border:1px solid #e5e7eb;
    font-size:.9rem;
    font-weight:700;
    cursor:pointer;
    background:#fff;
    color:#6b7280;
    transition:.2s;
}

.answer-box input:checked + span{
    background:#eef2ff;
    color:#4338ca;
    border-color:#6366f1;
}

.btn-save{
    width:100%;
    border:none;
    padding:.85rem 1rem;
    border-radius:.85rem;
    background:linear-gradient(135deg,#4338ca,#6366f1);
    color:#fff;
    font-weight:700;
    font-size:.92rem;
    cursor:pointer;
}

.btn-save:hover{
    opacity:.93;
}

/* ===============================
   SUMMERNOTE
================================= */
.note-editor.note-frame{
    border:1px solid #e5e7eb !important;
    border-radius:.9rem !important;
    overflow:hidden;
}

.note-toolbar{
    background:#f9fafb !important;
    border-bottom:1px solid #e5e7eb !important;
}

.note-editable{
    min-height:220px;
    font-size:15px;
    padding:16px !important;
}

/* ===============================
   LIST SOAL
================================= */
.question-item{
    padding:1.35rem 1.5rem;
    border-bottom:1px solid #f3f4f6;
}

.question-item:last-child{
    border-bottom:none;
}

.question-item:hover{
    background:#fafafa;
}

.question-number{
    width:2rem;
    height:2rem;
    border-radius:999px;
    background:#eef2ff;
    color:#4338ca;
    font-size:.8rem;
    font-weight:800;
    display:flex;
    align-items:center;
    justify-content:center;
    flex-shrink:0;
}

.option-pill{
    display:flex;
    gap:.45rem;
    align-items:flex-start;
    padding:.55rem .75rem;
    border-radius:.75rem;
    border:1px solid #eef2f7;
    background:#f8fafc;
    font-size:.84rem;
}

.option-pill.correct{
    background:#ecfdf5;
    border-color:#bbf7d0;
    color:#065f46;
    font-weight:700;
}

.option-pill strong{
    min-width:1rem;
}

.mini-btn{
    padding:.45rem .75rem;
    border-radius:.7rem;
    font-size:.78rem;
    font-weight:600;
    transition:.2s;
}

.mini-btn-edit{
    background:#eef2ff;
    color:#4338ca;
    border:1px solid #c7d2fe;
}

.mini-btn-delete{
    background:#fef2f2;
    color:#dc2626;
    border:1px solid #fecaca;
}
</style>
@endpush


@section('content')

{{-- HEADER --}}
<div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Bank Soal</h1>
        <p class="text-sm text-gray-500 mt-1">
            Kelola soal evaluasi siswa.
        </p>
    </div>

    <a href="{{ route('nilai.index') }}"
       class="px-4 py-2 rounded-xl bg-indigo-50 text-indigo-700 border border-indigo-200 text-sm font-semibold hover:bg-indigo-100">
        ← Kembali
    </a>
</div>

{{-- ALERT --}}
@if(session('success'))
<div class="mb-5 px-4 py-3 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm">
    {{ session('success') }}
</div>
@endif

@if($errors->any())
<div class="mb-5 px-4 py-3 rounded-xl bg-red-50 border border-red-200 text-red-700 text-sm">
    <ul class="list-disc list-inside space-y-1">
        @foreach($errors->all() as $e)
            <li>{{ $e }}</li>
        @endforeach
    </ul>
</div>
@endif


<div class="grid grid-cols-1 xl:grid-cols-5 gap-6">

    {{-- ==========================
         FORM
    =========================== --}}
    <div class="xl:col-span-2">
        <div class="soft-card p-5 sticky top-6">

            <div class="mb-5">
                <h3 class="text-lg font-bold text-gray-900">
                    Tambah Soal Baru
                </h3>
                <p class="text-sm text-gray-500 mt-1">
                    Masukkan pertanyaan dan jawaban.
                </p>
            </div>

            <form action="{{ route('bank-soal.store') }}" method="POST" class="space-y-4">
                @csrf

                {{-- Pertanyaan --}}
                <div>
                    <label class="form-label">Pertanyaan</label>

                    <textarea
                        name="question"
                        id="summernote"
                        required>{{ old('question') }}</textarea>
                </div>

                {{-- Pilihan --}}
                <div>
                    <label class="form-label">Pilihan Jawaban</label>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        @foreach(['A','B','C','D'] as $opt)
                        <div class="option-wrap">
                            <span class="option-badge">{{ $opt }}</span>

                            <input type="text"
                                   required
                                   class="form-input"
                                   name="option_{{ strtolower($opt) }}"
                                   value="{{ old('option_'.strtolower($opt)) }}"
                                   placeholder="Pilihan {{ $opt }}">
                        </div>
                        @endforeach
                    </div>
                </div>

                {{-- Jawaban Benar --}}
                <div>
                    <label class="form-label">Jawaban Benar</label>

                    <div class="grid grid-cols-4 gap-2">
                        @foreach(['A','B','C','D'] as $opt)
                        <label class="answer-box">
                            <input type="radio"
                                   name="correct_answer"
                                   value="{{ $opt }}"
                                   {{ old('correct_answer','A') == $opt ? 'checked' : '' }}>
                            <span>{{ $opt }}</span>
                        </label>
                        @endforeach
                    </div>
                </div>

                <button class="btn-save">
                    Simpan Soal
                </button>

            </form>
        </div>
    </div>


    {{-- ==========================
         LIST SOAL
    =========================== --}}
    <div class="xl:col-span-3">
        <div class="soft-card overflow-hidden">

            <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
                <h3 class="font-bold text-gray-900">Daftar Soal</h3>

                <span class="text-xs font-bold px-3 py-1 rounded-full bg-indigo-50 text-indigo-600">
                    {{ $questions->count() }} soal
                </span>
            </div>

            @forelse($questions as $i => $q)
            <div class="question-item">

                <div class="flex gap-3">

                    <div class="question-number">
                        {{ $i + 1 }}
                    </div>

                    <div class="flex-1">

                        <div class="flex flex-wrap justify-between gap-2 mb-3">

                            <div class="text-sm font-semibold text-gray-500">
                                Soal {{ $i + 1 }}
                            </div>

                            <div class="flex gap-2">

                                <button onclick="showEdit({{ $q->id }})"
                                    class="mini-btn mini-btn-edit">
                                    ✏ Edit
                                </button>

                                <form action="{{ route('bank-soal.destroy',$q) }}"
                                      method="POST"
                                      onsubmit="return confirm('Hapus soal ini?')">
                                    @csrf
                                    @method('DELETE')

                                    <button class="mini-btn mini-btn-delete">
                                        🗑 Hapus
                                    </button>
                                </form>

                            </div>
                        </div>

                        {{-- tampilkan html summernote --}}
                        <div class="prose max-w-none text-sm text-gray-800 leading-relaxed mb-4">
                            {!! $q->question !!}
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                            @foreach($q->options as $key => $val)
                            <div class="option-pill {{ $key == $q->correct_answer ? 'correct' : '' }}">
                                <strong>{{ $key }}.</strong>
                                <span>{{ $val }}</span>

                                @if($key == $q->correct_answer)
                                    <span class="ml-auto">✓</span>
                                @endif
                            </div>
                            @endforeach
                        </div>

                    </div>
                </div>

            </div>
            @empty

            <div class="py-16 text-center">
                <div class="text-5xl mb-3">📚</div>
                <p class="font-semibold text-gray-700">Belum ada soal</p>
                <p class="text-sm text-gray-400 mt-1">
                    Tambahkan soal pertama.
                </p>
            </div>

            @endforelse

        </div>
    </div>

</div>

@endsection



@push('scripts')
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote-lite.min.js"></script>

<script>
$(document).ready(function () {
    $('#summernote').summernote({
        placeholder: 'Tulis pertanyaan soal di sini...',
        tabsize: 2,
        height: 230,
        toolbar: [
            ['style', ['bold', 'italic', 'underline']],
            ['font', ['fontsize']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['insert', ['link']],
            ['view', ['codeview']]
        ]
    });
});

function showEdit(id){
    alert('Mode edit bisa saya lanjutkan pakai Summernote juga jika Anda mau.');
}
</script>
@endpush