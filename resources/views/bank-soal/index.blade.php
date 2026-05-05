@extends('layouts.app')
@section('title', 'Bank Soal')

@push('styles')
<style>
.soft-card{background:#ffffff;border:1px solid #eef2f7;border-radius:1rem;box-shadow:0 10px 25px rgba(15,23,42,.05),0 3px 8px rgba(15,23,42,.04);}
.rich-toolbar{display:flex;flex-wrap:wrap;gap:.3rem;padding:.5rem .75rem;background:#f9fafb;border:1px solid #e5e7eb;border-bottom:none;border-radius:.9rem .9rem 0 0;}
.rich-toolbar button{padding:.3rem .55rem;border-radius:.4rem;border:1px solid #e5e7eb;background:#fff;color:#374151;font-size:.78rem;font-weight:600;cursor:pointer;transition:.15s;line-height:1;user-select:none;}
.rich-toolbar button:hover{background:#eef2ff;border-color:#a5b4fc;color:#4338ca;}
.rich-editor{min-height:180px;border:1px solid #e5e7eb;border-radius:0 0 .9rem .9rem;padding:1rem;font-size:.92rem;outline:none;line-height:1.7;background:#fff;overflow-y:auto;}
.rich-editor:focus{border-color:#6366f1;box-shadow:0 0 0 3px rgba(99,102,241,.1);}
.rich-editor p{margin:0 0 .5em;}
.rich-editor ul,.rich-editor ol{margin:.3em 0 .3em 1.5em;}
.rich-editor:empty:before{content:attr(data-placeholder);color:#9ca3af;pointer-events:none;}
.form-label{display:block;font-size:.78rem;font-weight:700;color:#6b7280;text-transform:uppercase;margin-bottom:.55rem;letter-spacing:.03em;}
.form-input{width:100%;border:1px solid #e5e7eb;background:#f9fafb;border-radius:.8rem;padding:.75rem .95rem;font-size:.92rem;outline:none;transition:.2s;}
.form-input:focus{background:#fff;border-color:#6366f1;box-shadow:0 0 0 4px rgba(99,102,241,.10);}
.option-wrap{position:relative;}
.option-badge{position:absolute;left:.7rem;top:50%;transform:translateY(-50%);width:1.55rem;height:1.55rem;border-radius:999px;background:#e0e7ff;color:#4338ca;font-size:.72rem;font-weight:800;display:flex;align-items:center;justify-content:center;pointer-events:none;}
.option-wrap input{padding-left:2.65rem;}
.answer-box input{display:none;}
.answer-box span{display:flex;justify-content:center;align-items:center;padding:.7rem 0;border-radius:.75rem;border:1px solid #e5e7eb;font-size:.9rem;font-weight:700;cursor:pointer;background:#fff;color:#6b7280;transition:.2s;}
.answer-box input:checked + span{background:#eef2ff;color:#4338ca;border-color:#6366f1;}
.answer-box.disabled-e span{opacity:.4;cursor:not-allowed;}
.btn-save{width:100%;border:none;padding:.85rem 1rem;border-radius:.85rem;background:linear-gradient(135deg,#4338ca,#6366f1);color:#fff;font-weight:700;font-size:.92rem;cursor:pointer;}
.btn-save:hover{opacity:.93;}
.question-item{padding:1.35rem 1.5rem;border-bottom:1px solid #f3f4f6;}
.question-item:last-child{border-bottom:none;}
.question-item:hover{background:#fafafa;}
.question-number{width:2rem;height:2rem;border-radius:999px;background:#eef2ff;color:#4338ca;font-size:.8rem;font-weight:800;display:flex;align-items:center;justify-content:center;flex-shrink:0;}
.option-pill{display:flex;gap:.45rem;align-items:flex-start;padding:.55rem .75rem;border-radius:.75rem;border:1px solid #eef2f7;background:#f8fafc;font-size:.84rem;}
.option-pill.correct{background:#ecfdf5;border-color:#bbf7d0;color:#065f46;font-weight:700;}
.option-pill strong{min-width:1rem;}
.mini-btn{padding:.45rem .75rem;border-radius:.7rem;font-size:.78rem;font-weight:600;transition:.2s;}
.mini-btn-edit{background:#eef2ff;color:#4338ca;border:1px solid #c7d2fe;}
.mini-btn-delete{background:#fef2f2;color:#dc2626;border:1px solid #fecaca;}
</style>
@endpush


@section('content')

<div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Bank Soal</h1>
        <p class="text-sm text-gray-500 mt-1">Kelola soal evaluasi siswa.</p>
    </div>
    <a href="{{ route('nilai.index') }}"
       class="px-4 py-2 rounded-xl bg-indigo-50 text-indigo-700 border border-indigo-200 text-sm font-semibold hover:bg-indigo-100">
        ← Kembali
    </a>
</div>

@if(session('success'))
<div class="mb-5 px-4 py-3 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm">{{ session('success') }}</div>
@endif
@if($errors->any())
<div class="mb-5 px-4 py-3 rounded-xl bg-red-50 border border-red-200 text-red-700 text-sm">
    <ul class="list-disc list-inside space-y-1">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
</div>
@endif


<div class="grid grid-cols-1 xl:grid-cols-5 gap-6">

    {{-- FORM TAMBAH --}}
    <div class="xl:col-span-2">
        <div class="soft-card p-5 sticky top-6">
            <div class="mb-5">
                <h3 class="text-lg font-bold text-gray-900">Tambah Soal Baru</h3>
                <p class="text-sm text-gray-500 mt-1">Masukkan pertanyaan dan jawaban.</p>
            </div>

            <form action="{{ route('bank-soal.store') }}" method="POST" class="space-y-4" id="addForm">
                @csrf

                <div>
                    <label class="form-label">Pertanyaan</label>
                    <div class="rich-toolbar">
                        <button type="button" onmousedown="event.preventDefault();fmt('bold','add')"><b>B</b></button>
                        <button type="button" onmousedown="event.preventDefault();fmt('italic','add')"><i>I</i></button>
                        <button type="button" onmousedown="event.preventDefault();fmt('underline','add')"><u>U</u></button>
                        <button type="button" onmousedown="event.preventDefault();fmt('insertUnorderedList','add')">• List</button>
                        <button type="button" onmousedown="event.preventDefault();fmt('insertOrderedList','add')">1. List</button>
                        <button type="button" onmousedown="event.preventDefault();fmt('removeFormat','add')">✕ Format</button>
                    </div>
                    <div class="rich-editor" id="editor-add" contenteditable="true"
                         data-placeholder="Tulis pertanyaan soal di sini..."></div>
                    <textarea name="question" id="question-add" class="hidden" required>{{ old('question') }}</textarea>
                </div>

                <div>
                    <label class="form-label">Pilihan Jawaban</label>
                    <div class="space-y-2">
                        @foreach(['A','B','C','D'] as $opt)
                        <div class="option-wrap">
                            <span class="option-badge">{{ $opt }}</span>
                            <input type="text" required class="form-input"
                                   name="option_{{ strtolower($opt) }}"
                                   value="{{ old('option_'.strtolower($opt)) }}"
                                   placeholder="Pilihan {{ $opt }}">
                        </div>
                        @endforeach
                        <div class="option-wrap">
                            <span class="option-badge">E</span>
                            <input type="text" class="form-input" id="option-e-add"
                                   name="option_e" value="{{ old('option_e') }}"
                                   placeholder="Pilihan E (opsional)"
                                   oninput="toggleEOption('add', this.value)">
                        </div>
                    </div>
                </div>

                <div>
                    <label class="form-label">Jawaban Benar</label>
                    <div class="grid grid-cols-5 gap-2">
                        @foreach(['A','B','C','D'] as $opt)
                        <label class="answer-box">
                            <input type="radio" name="correct_answer" value="{{ $opt }}"
                                   {{ old('correct_answer','A') == $opt ? 'checked' : '' }}>
                            <span>{{ $opt }}</span>
                        </label>
                        @endforeach
                        <label class="answer-box disabled-e" id="answer-box-e-add">
                            <input type="radio" name="correct_answer" value="E" disabled
                                   {{ old('correct_answer') == 'E' ? 'checked' : '' }}>
                            <span>E</span>
                        </label>
                    </div>
                    <p class="text-xs text-gray-400 mt-1">Isi Pilihan E dulu agar bisa dipilih sebagai kunci jawaban</p>
                </div>

                <button class="btn-save" type="submit">Simpan Soal</button>
            </form>
        </div>
    </div>


    {{-- LIST SOAL --}}
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
                    <div class="question-number">{{ $i + 1 }}</div>
                    <div class="flex-1">
                        <div class="flex flex-wrap justify-between gap-2 mb-3">
                            <div class="text-sm font-semibold text-gray-500">Soal {{ $i + 1 }}</div>
                            <div class="flex gap-2">
                                <button onclick="showEdit({{ $q->id }})" class="mini-btn mini-btn-edit">✏ Edit</button>
                                <form action="{{ route('bank-soal.destroy',$q) }}" method="POST"
                                      onsubmit="return confirm('Hapus soal ini?')">
                                    @csrf @method('DELETE')
                                    <button class="mini-btn mini-btn-delete">🗑 Hapus</button>
                                </form>
                            </div>
                        </div>
                        <div class="prose max-w-none text-sm text-gray-800 leading-relaxed mb-4">{!! $q->question !!}</div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                            @foreach($q->options as $key => $val)
                            <div class="option-pill {{ $key == $q->correct_answer ? 'correct' : '' }}">
                                <strong>{{ $key }}.</strong><span>{{ $val }}</span>
                                @if($key == $q->correct_answer)<span class="ml-auto">✓</span>@endif
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            {{-- MODAL EDIT --}}
            <div id="editModal-{{ $q->id }}"
                 class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
                <div class="bg-white rounded-2xl shadow-xl w-full max-w-2xl max-h-[90vh] overflow-y-auto"
                     onclick="event.stopPropagation()">

                    <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100 sticky top-0 bg-white z-10">
                        <h4 class="font-bold text-gray-900">Edit Soal {{ $i + 1 }}</h4>
                        <button onclick="closeEdit({{ $q->id }})"
                                class="w-8 h-8 flex items-center justify-center rounded-full hover:bg-gray-100 text-gray-500 text-xl">&times;</button>
                    </div>

                    <form action="{{ route('bank-soal.update',$q) }}" method="POST"
                          class="p-5 space-y-4" id="editForm-{{ $q->id }}">
                        @csrf @method('PUT')

                        <div>
                            <label class="form-label">Pertanyaan</label>
                            <div class="rich-toolbar">
                                <button type="button" onmousedown="event.preventDefault();fmt('bold','edit-{{ $q->id }}')"><b>B</b></button>
                                <button type="button" onmousedown="event.preventDefault();fmt('italic','edit-{{ $q->id }}')"><i>I</i></button>
                                <button type="button" onmousedown="event.preventDefault();fmt('underline','edit-{{ $q->id }}')"><u>U</u></button>
                                <button type="button" onmousedown="event.preventDefault();fmt('insertUnorderedList','edit-{{ $q->id }}')">• List</button>
                                <button type="button" onmousedown="event.preventDefault();fmt('insertOrderedList','edit-{{ $q->id }}')">1. List</button>
                                <button type="button" onmousedown="event.preventDefault();fmt('removeFormat','edit-{{ $q->id }}')">✕ Format</button>
                            </div>
                            <div class="rich-editor" id="editor-edit-{{ $q->id }}" contenteditable="true">{!! $q->question !!}</div>
                            <textarea name="question" id="question-edit-{{ $q->id }}" class="hidden" required></textarea>
                        </div>

                        <div>
                            <label class="form-label">Pilihan Jawaban</label>
                            <div class="space-y-2">
                                @foreach(['A','B','C','D'] as $opt)
                                <div class="option-wrap">
                                    <span class="option-badge">{{ $opt }}</span>
                                    <input type="text" required class="form-input"
                                           name="option_{{ strtolower($opt) }}"
                                           value="{{ $q->{'option_'.strtolower($opt)} }}"
                                           placeholder="Pilihan {{ $opt }}">
                                </div>
                                @endforeach
                                <div class="option-wrap">
                                    <span class="option-badge">E</span>
                                    <input type="text" class="form-input"
                                           id="option-e-edit-{{ $q->id }}"
                                           name="option_e"
                                           value="{{ $q->option_e }}"
                                           placeholder="Pilihan E (opsional)"
                                           oninput="toggleEOption('edit-{{ $q->id }}', this.value)">
                                </div>
                            </div>
                        </div>

                        <div>
                            <label class="form-label">Jawaban Benar</label>
                            <div class="grid grid-cols-5 gap-2">
                                @foreach(['A','B','C','D'] as $opt)
                                <label class="answer-box">
                                    <input type="radio" name="correct_answer" value="{{ $opt }}"
                                           {{ $q->correct_answer == $opt ? 'checked' : '' }}>
                                    <span>{{ $opt }}</span>
                                </label>
                                @endforeach
                                <label class="answer-box {{ $q->option_e ? '' : 'disabled-e' }}"
                                       id="answer-box-e-edit-{{ $q->id }}">
                                    <input type="radio" name="correct_answer" value="E"
                                           {{ $q->option_e ? '' : 'disabled' }}
                                           {{ $q->correct_answer == 'E' ? 'checked' : '' }}>
                                    <span>E</span>
                                </label>
                            </div>
                            <p class="text-xs text-gray-400 mt-1">Isi Pilihan E dulu agar bisa dipilih sebagai kunci jawaban</p>
                        </div>

                        <button class="btn-save" type="submit">Simpan Perubahan</button>
                    </form>
                </div>
            </div>

            @empty
            <div class="py-16 text-center">
                <div class="text-5xl mb-3">📚</div>
                <p class="font-semibold text-gray-700">Belum ada soal</p>
                <p class="text-sm text-gray-400 mt-1">Tambahkan soal pertama.</p>
            </div>
            @endforelse

        </div>
    </div>

</div>

@endsection


@push('scripts')
<script>
// ===========================================
// Rich Editor
// ===========================================
function fmt(cmd, editorId) {
    const editor = document.getElementById('editor-' + editorId);
    if (!editor) return;
    editor.focus();
    document.execCommand(cmd, false, null);
    syncEditor(editorId);
}

function syncEditor(editorId) {
    const editor   = document.getElementById('editor-' + editorId);
    const textarea = document.getElementById('question-' + editorId);
    if (editor && textarea) {
        const html = editor.innerHTML.trim();
        textarea.value = (html === '<br>' || html === '') ? '' : html;
    }
}

// ===========================================
// Pilihan E toggle
// ===========================================
function toggleEOption(prefix, value) {
    const box   = document.getElementById('answer-box-e-' + prefix);
    if (!box) return;
    const radio = box.querySelector('input[type=radio]');
    const hasValue = value && value.trim() !== '';

    box.classList.toggle('disabled-e', !hasValue);
    if (radio) {
        radio.disabled = !hasValue;
        if (!hasValue && radio.checked) {
            radio.checked = false;
            // Reset ke A
            const form = radio.closest('form');
            if (form) {
                const aRadio = form.querySelector('input[name=correct_answer][value=A]');
                if (aRadio) aRadio.checked = true;
            }
        }
    }
}

// ===========================================
// DOMContentLoaded — init semua
// ===========================================
document.addEventListener('DOMContentLoaded', function () {

    document.querySelectorAll('.rich-editor').forEach(function (editor) {
        const id = editor.id.replace('editor-', '');

        editor.addEventListener('input',  function () { syncEditor(id); });
        editor.addEventListener('keyup',  function () { syncEditor(id); });
        editor.addEventListener('blur',   function () { syncEditor(id); });
        editor.addEventListener('paste', function (e) {
            e.preventDefault();
            const text = (e.clipboardData || window.clipboardData).getData('text/plain');
            document.execCommand('insertText', false, text);
            syncEditor(id);
        });
    });

    // Form tambah — pre-fill dari old()
    const addEditor   = document.getElementById('editor-add');
    const addTextarea = document.getElementById('question-add');
    if (addEditor && addTextarea && addTextarea.value) {
        addEditor.innerHTML = addTextarea.value;
    }

    // Status E form tambah
    const optEAdd = document.getElementById('option-e-add');
    if (optEAdd) toggleEOption('add', optEAdd.value);

    // Submit form tambah
    document.getElementById('addForm')?.addEventListener('submit', function () {
        syncEditor('add');
    });

    // Init setiap form edit
    document.querySelectorAll('[id^="editForm-"]').forEach(function (form) {
        const qid = form.id.replace('editForm-', '');

        form.addEventListener('submit', function () {
            syncEditor('edit-' + qid);
        });

        const optE = document.getElementById('option-e-edit-' + qid);
        if (optE) toggleEOption('edit-' + qid, optE.value);
    });
});

// ===========================================
// Modal
// ===========================================
function showEdit(id) {
    const modal = document.getElementById('editModal-' + id);
    modal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';

    // Sync textarea dari editor saat modal dibuka
    const editor   = document.getElementById('editor-edit-' + id);
    const textarea = document.getElementById('question-edit-' + id);
    if (editor && textarea) {
        textarea.value = editor.innerHTML;
    }

    // Klik backdrop tutup modal
    modal.addEventListener('click', function backdropHandler(e) {
        if (e.target === modal) {
            closeEdit(id);
            modal.removeEventListener('click', backdropHandler);
        }
    });
}

function closeEdit(id) {
    document.getElementById('editModal-' + id).classList.add('hidden');
    document.body.style.overflow = '';
}
</script>
@endpush
