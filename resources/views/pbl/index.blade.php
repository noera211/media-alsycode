@extends('layouts.app')
@section('title', 'Aktivitas PBL')

@section('content')
<div class="mb-6 flex items-center justify-between">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Aktivitas PBL</h1>
        <p class="text-gray-500 mt-1 text-sm">Problem-Based Learning — selesaikan studi kasus nyata</p>
    </div>
    @if(auth()->user()->isGuru())
    <button onclick="document.getElementById('modal-create').classList.remove('hidden')" class="btn-primary">+ Tambah Aktivitas</button>
    @endif
</div>

{{-- Level Progress (siswa) --}}
@if(auth()->user()->isSiswa())
<div class="card p-5 mb-6">
    <div class="flex items-center justify-between mb-3">
        <h2 class="font-semibold text-gray-800 text-sm">Progress Level</h2>
        <span class="text-sm text-indigo-600 font-medium">{{ $completedCount }} materi selesai</span>
    </div>
    <div class="grid grid-cols-3 gap-2 sm:gap-3">
        @foreach(['Mudah' => '🟢', 'Sedang' => '🟡', 'Sulit' => '🔴'] as $diff => $icon)
            @php $min = $levelSettings[$diff] ?? 1; $unlocked = in_array($diff, $accessible); @endphp
            <div class="rounded-lg border p-2 sm:p-3 {{ $unlocked ? 'border-indigo-200 bg-indigo-50' : 'border-gray-200 bg-gray-50' }}">
                <div class="flex items-center gap-1 sm:gap-2 mb-1.5">
                    <span class="text-xs sm:text-sm">{{ $icon }}</span>
                    <span class="text-xs font-semibold text-gray-700 truncate">{{ $diff }}</span>
                    <span class="ml-auto text-xs {{ $unlocked ? 'text-emerald-500' : 'text-gray-400' }} flex-shrink-0">
                        {{ $unlocked ? '✓' : '🔒' }}
                    </span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-1 sm:h-1.5 mb-1">
                    <div class="bg-indigo-400 h-1 sm:h-1.5 rounded-full" style="width: {{ min(100, $min > 0 ? round(($completedCount/$min)*100) : 100) }}%"></div>
                </div>
                <p class="text-xs text-gray-400 leading-tight">Min. {{ $min }} materi</p>
            </div>
        @endforeach
    </div>
</div>

{{-- PANDUAN PENGERJAAN --}}
<div class="card p-5 mb-6">
    <div class="flex items-center gap-2 mb-4">
        <div class="w-8 h-8 bg-indigo-600 rounded-lg flex items-center justify-center flex-shrink-0">
            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
            </svg>
        </div>
        <div>
            <h2 class="font-bold text-gray-900 text-sm">Panduan Pengerjaan Studi Kasus (PBL)</h2>
            <p class="text-xs text-gray-400 mt-0.5">Ikuti langkah berikut untuk menyelesaikan setiap aktivitas</p>
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
        <div class="flex gap-3 p-3 bg-indigo-50 border border-indigo-100 rounded-xl">
            <div class="w-7 h-7 bg-indigo-600 rounded-full flex items-center justify-center flex-shrink-0 text-white text-xs font-bold">1</div>
            <div>
                <p class="text-xs font-bold text-indigo-800">Baca & Pahami Masalah</p>
                <p class="text-xs text-indigo-600 mt-0.5 leading-relaxed">Pahami konteks, tujuan, dan batasan masalah.</p>
            </div>
        </div>
        <div class="flex gap-3 p-3 bg-violet-50 border border-violet-100 rounded-xl">
            <div class="w-7 h-7 bg-violet-600 rounded-full flex items-center justify-center flex-shrink-0 text-white text-xs font-bold">2</div>
            <div>
                <p class="text-xs font-bold text-violet-800">Identifikasi Solusi</p>
                <p class="text-xs text-violet-600 mt-0.5 leading-relaxed">Tentukan masalah inti dan rencana pendekatan solusi.</p>
            </div>
        </div>
        <div class="flex gap-3 p-3 bg-cyan-50 border border-cyan-100 rounded-xl">
            <div class="w-7 h-7 bg-cyan-600 rounded-full flex items-center justify-center flex-shrink-0 text-white text-xs font-bold">3</div>
            <div>
                <p class="text-xs font-bold text-cyan-800">Susun Algoritma</p>
                <p class="text-xs text-cyan-600 mt-0.5 leading-relaxed">Tuliskan langkah solusi dalam bentuk pseudocode.</p>
            </div>
        </div>
        <div class="flex gap-3 p-3 bg-emerald-50 border border-emerald-100 rounded-xl">
            <div class="w-7 h-7 bg-emerald-600 rounded-full flex items-center justify-center flex-shrink-0 text-white text-xs font-bold">4</div>
            <div>
                <p class="text-xs font-bold text-emerald-800">Implementasi Kode</p>
                <p class="text-xs text-emerald-600 mt-0.5 leading-relaxed">Terjemahkan ke kode program. Gunakan Mini Compiler.</p>
            </div>
        </div>
        <div class="flex gap-3 p-3 bg-amber-50 border border-amber-100 rounded-xl">
            <div class="w-7 h-7 bg-amber-500 rounded-full flex items-center justify-center flex-shrink-0 text-white text-xs font-bold">5</div>
            <div>
                <p class="text-xs font-bold text-amber-800">Pengujian Solusi</p>
                <p class="text-xs text-amber-600 mt-0.5 leading-relaxed">Uji program dengan berbagai input data.</p>
            </div>
        </div>
        <div class="flex gap-3 p-3 bg-rose-50 border border-rose-100 rounded-xl">
            <div class="w-7 h-7 bg-rose-500 rounded-full flex items-center justify-center flex-shrink-0 text-white text-xs font-bold">6</div>
            <div>
                <p class="text-xs font-bold text-rose-800">Kumpulkan Hasil</p>
                <p class="text-xs text-rose-600 mt-0.5 leading-relaxed">Upload file jawaban (algoritma + kode + output).</p>
            </div>
        </div>
    </div>
</div>

{{-- TOMBOL FILE CONTOH PENGERJAAN --}}
<div class="card p-5 mb-6 border-l-4 border-emerald-500 shadow-sm">
    <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 bg-emerald-100 rounded-2xl flex items-center justify-center flex-shrink-0">
                <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
            </div>
            <div>
                <h2 class="font-bold text-gray-900 text-sm">Contoh Hasil Pengerjaan Siswa</h2>
                <p class="text-xs text-gray-500 mt-0.5">Referensi format Algoritma, Pseudocode, dan Kode Program yang benar.</p>
            </div>
        </div>
        <a href="{{ asset('files/contoh-pengerjaan-pbl.pdf') }}" target="_blank" 
           class="inline-flex items-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white px-5 py-2.5 rounded-xl text-xs font-bold transition-all shadow-md shadow-emerald-100">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
            </svg>
            Lihat Contoh (PDF)
        </a>
    </div>
</div>
@endif

{{-- Level Settings (guru) --}}
@if(auth()->user()->isGuru())
<div class="card p-5 mb-6">
    <div class="flex items-center justify-between mb-3">
        <h2 class="font-semibold text-gray-800 text-sm">⚙ Pengaturan Level</h2>
    </div>
    <form action="{{ route('pbl.level-settings') }}" method="POST" class="grid grid-cols-1 sm:grid-cols-3 gap-3">
        @csrf
        @foreach(['mudah' => 'Mudah', 'sedang' => 'Sedang', 'sulit' => 'Sulit'] as $key => $label)
        <div>
            <label class="block text-xs font-medium text-gray-600 mb-1">Min. materi ({{ $label }})</label>
            <input type="number" name="{{ $key }}" value="{{ $levelSettings[ucfirst($label)] ?? 1 }}" min="0"
                class="w-full border border-gray-300 rounded-lg px-3 py-1.5 text-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none">
        </div>
        @endforeach
        <div class="sm:col-span-3 flex justify-end">
            <button class="btn-primary text-xs">Simpan Pengaturan</button>
        </div>
    </form>
</div>
@endif

{{-- Daftar Aktivitas PBL --}}
@foreach(['Mudah', 'Sedang', 'Sulit'] as $level)
    @php
        $levelActivities = $activities->where('difficulty', $level);
        $isLocked = auth()->user()->isSiswa() && !in_array($level, $accessible);
        $icons = ['Mudah' => '🟢', 'Sedang' => '🟡', 'Sulit' => '🔴'];
    @endphp
    @if($levelActivities->count() > 0)
    <div class="mb-6">
        <h2 class="font-semibold text-gray-700 text-sm mb-3 flex items-center gap-2">
            {{ $icons[$level] }} Level {{ $level }}
            @if($isLocked) <span class="text-xs text-gray-400 font-normal">(🔒 Terkunci)</span> @endif
        </h2>
        <div class="grid md:grid-cols-2 gap-4">
            @foreach($levelActivities as $act)
            <div class="card p-5 {{ $isLocked ? 'opacity-60' : '' }}">
                <div class="flex items-start justify-between mb-3">
                    <div class="flex-1">
                        <h3 class="font-semibold text-gray-800 mb-1">{{ $act->title }}</h3>
                        <p class="text-xs text-gray-500 mb-2">{{ $act->topic }}</p>
                        <span class="badge-{{ strtolower($level) }}">{{ $level }}</span>
                        @if($act->relatedMateri)
                            <span class="ml-2 text-xs text-indigo-600 font-medium">📚 {{ $act->relatedMateri->title }}</span>
                        @endif
                        @if(auth()->user()->isSiswa() && in_array($act->id, $submittedIds))
                            <span class="ml-2 text-xs text-emerald-600 font-semibold">✓ Dikumpulkan</span>
                        @endif
                    </div>
                    @if($isLocked)
                        <span class="text-gray-400 text-lg ml-2">🔒</span>
                    @endif
                </div>
                <div class="flex gap-2">
                    @if(!$isLocked)
                    <a href="{{ route('pbl.show', $act) }}" class="btn-primary text-xs flex-1 text-center">Lihat Detail →</a>
                    @endif
                    @if(auth()->user()->isGuru())
                    <button type="button" onclick="openEditPbl({{ $act->id }}, {{ json_encode($act->title) }}, {{ json_encode($act->topic) }}, {{ json_encode($act->difficulty) }}, {{ json_encode($act->problem) }}, {{ json_encode($act->related_materi) }})"
                        class="btn-outline text-xs cursor-pointer">✏ Edit</button>
                    <form action="{{ route('pbl.destroy', $act) }}" method="POST" onsubmit="return confirm('Hapus aktivitas ini?')" class="inline">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn-danger text-xs cursor-pointer">🗑</button>
                    </form>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif
@endforeach

{{-- Modal Create --}}
<div id="modal-create" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/50">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-lg mx-4 max-h-[90vh] overflow-y-auto">
        <div class="p-6">
            <h2 class="text-lg font-bold mb-4">Tambah Aktivitas PBL</h2>
            <form action="{{ route('pbl.store') }}" method="POST" class="space-y-4">
                @csrf
                @include('pbl._form', ['materiList' => $materiList ?? []])
                <div class="flex gap-3 justify-end pt-2">
                    <button type="button" onclick="document.getElementById('modal-create').classList.add('hidden')" class="btn-outline">Batal</button>
                    <button type="submit" class="btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Modal Edit --}}
<div id="modal-edit" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/50" onclick="if(event.target === this) this.classList.add('hidden')">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-lg mx-4 max-h-[90vh] overflow-y-auto">
        <div class="p-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-bold">Edit Aktivitas PBL</h2>
                <button type="button" onclick="document.getElementById('modal-edit').classList.add('hidden')" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            <form id="edit-pbl-form" action="" method="POST" class="space-y-4">
                @csrf @method('PUT')
                @include('pbl._form', ['edit' => true, 'materiList' => $materiList ?? []])
                <div class="flex gap-3 justify-end pt-2">
                    <button type="button" onclick="document.getElementById('modal-edit').classList.add('hidden')" class="btn-outline">Batal</button>
                    <button type="submit" class="btn-primary">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
window.openEditPbl = function(id, title, topic, difficulty, problem, relatedMateri) {
    const form = document.getElementById('edit-pbl-form');
    if (!form) return;
    form.action = '/aktivitas-pbl/' + id;
    const fields = { 'title': title, 'topic': topic, 'difficulty': difficulty, 'problem': problem, 'related_materi': relatedMateri };
    Object.keys(fields).forEach(name => {
        const field = form.querySelector('[name="' + name + '"]');
        if (field) field.value = fields[name];
    });
    document.getElementById('modal-edit').classList.remove('hidden');
};
</script>
@endpush