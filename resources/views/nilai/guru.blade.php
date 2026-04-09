@extends('layouts.app')
@section('title', 'Nilai & Evaluasi')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-900">Nilai & Evaluasi</h1>
    <p class="text-gray-500 mt-1 text-sm">Kelola nilai siswa dan bank soal test.</p>
</div>

{{-- Tabs --}}
<div x-data="{ tab: 'nilai' }">
    <div class="flex gap-1 bg-gray-100 p-1 rounded-xl mb-6 w-fit">
        <button @click="tab='nilai'" :class="tab==='nilai' ? 'bg-white shadow text-gray-900' : 'text-gray-500 hover:text-gray-700'"
            class="px-4 py-2 rounded-lg text-sm font-medium transition-all">🏆 Nilai Siswa</button>
        <button @click="tab='soal'" :class="tab==='soal' ? 'bg-white shadow text-gray-900' : 'text-gray-500 hover:text-gray-700'"
            class="px-4 py-2 rounded-lg text-sm font-medium transition-all">📋 Bank Soal Test</button>
    </div>

    {{-- Tab Nilai --}}
    <div x-show="tab==='nilai'">
        <div class="card overflow-hidden">
            <div class="p-5 border-b border-gray-100 flex items-center justify-between">
                <h2 class="font-semibold text-gray-800">Daftar Nilai Siswa</h2>
                <span class="text-xs text-gray-400">{{ $siswaList->count() }} siswa</span>
            </div>
            <table class="w-full text-sm">
                <thead class="bg-gray-50 border-b border-gray-100">
                    <tr>
                        <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500">No</th>
                        <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500">Nama Siswa</th>
                        <th class="text-center px-5 py-3 text-xs font-semibold text-gray-500">Nilai Terakhir</th>
                        <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500">Feedback</th>
                        <th class="text-center px-5 py-3 text-xs font-semibold text-gray-500">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($siswaList as $i => $siswa)
                    @php
                        $subs = $nilaiMap[$siswa->id] ?? collect();
                        $latestSub = $subs->first();
                    @endphp
                    <tr class="hover:bg-gray-50">
                        <td class="px-5 py-3 text-gray-400">{{ $i + 1 }}</td>
                        <td class="px-5 py-3 font-medium text-gray-800">{{ $siswa->name }}</td>
                        <td class="px-5 py-3 text-center">
                            @if($latestSub)
                                <span class="inline-flex items-center justify-center h-8 w-10 rounded-lg text-sm font-bold
                                    {{ $latestSub->nilai >= 80 ? 'bg-emerald-100 text-emerald-700' : ($latestSub->nilai >= 60 ? 'bg-amber-100 text-amber-700' : 'bg-red-100 text-red-700') }}">
                                    {{ $latestSub->nilai }}
                                </span>
                            @else
                                <span class="text-gray-300 text-xs">—</span>
                            @endif
                        </td>
                        <td class="px-5 py-3 text-xs text-gray-500 max-w-xs truncate">
                            {{ $latestSub?->feedback ?: '—' }}
                        </td>
                        <td class="px-5 py-3 text-center">
                            <button onclick="openNilaiModal({{ $latestSub?->id ?? 0 }}, '{{ $siswa->name }}', {{ $latestSub?->nilai ?? 'null' }}, '{{ addslashes($latestSub?->feedback ?? '') }}')"
                                class="text-xs btn-outline px-3 py-1">
                                {{ $latestSub ? '✏ Edit' : '+ Beri Nilai' }}
                            </button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    {{-- Tab Bank Soal --}}
    <div x-show="tab==='soal'" style="display:none">
        <div class="card mb-6 p-5">
            <h3 class="font-semibold text-gray-800 mb-4">+ Tambah Soal Baru</h3>
            <form action="{{ route('nilai.question.store') }}" method="POST" class="space-y-3">
                @csrf
                <div>
                    <label class="text-xs font-medium text-gray-600 block mb-1">Pertanyaan</label>
                    <textarea name="question" rows="2" required class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none"></textarea>
                </div>
                <div class="grid grid-cols-2 gap-3">
                    @foreach(['A','B','C','D'] as $opt)
                    <div>
                        <label class="text-xs font-medium text-gray-600 block mb-1">Pilihan {{ $opt }}</label>
                        <input type="text" name="option_{{ strtolower($opt) }}" required
                            class="w-full border border-gray-200 rounded-lg px-3 py-1.5 text-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                    </div>
                    @endforeach
                </div>
                <div>
                    <label class="text-xs font-medium text-gray-600 block mb-1">Jawaban Benar</label>
                    <select name="correct_answer" class="border border-gray-200 rounded-lg px-3 py-1.5 text-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                        @foreach(['A','B','C','D'] as $opt)
                        <option value="{{ $opt }}">{{ $opt }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex justify-end">
                    <button class="btn-primary text-xs">Simpan Soal</button>
                </div>
            </form>
        </div>

        <div class="card">
            <div class="p-5 border-b border-gray-100">
                <h3 class="font-semibold text-gray-800">Bank Soal ({{ $questions->count() }} soal)</h3>
            </div>
            <div class="divide-y divide-gray-100">
                @forelse($questions as $qi => $q)
                <div class="p-5">
                    <div class="flex items-start justify-between gap-4">
                        <div class="flex-1">
                            <p class="text-sm font-medium text-gray-800 mb-2">
                                <span class="text-indigo-600 font-bold mr-1">{{ $qi + 1 }}.</span>
                                {{ $q->question }}
                            </p>
                            <div class="grid grid-cols-2 gap-1 ml-4">
                                @foreach($q->options as $key => $val)
                                <p class="text-xs {{ $key === $q->correct_answer ? 'text-emerald-600 font-semibold' : 'text-gray-500' }}">
                                    {{ $key === $q->correct_answer ? '✓' : '' }} {{ $key }}. {{ $val }}
                                </p>
                                @endforeach
                            </div>
                        </div>
                        <form action="{{ route('nilai.question.destroy', $q) }}" method="POST" onsubmit="return confirm('Hapus soal ini?')">
                            @csrf @method('DELETE')
                            <button class="text-red-400 hover:text-red-600 text-sm">🗑</button>
                        </form>
                    </div>
                </div>
                @empty
                <div class="p-10 text-center text-gray-400 text-sm">Belum ada soal.</div>
                @endforelse
            </div>
        </div>
    </div>
</div>

{{-- Modal Nilai --}}
<div id="modal-nilai" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/50">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-md mx-4">
        <div class="p-6">
            <h2 class="text-lg font-bold mb-1">Beri / Edit Nilai</h2>
            <p id="modal-siswa-name" class="text-sm text-gray-500 mb-4"></p>
            <form id="nilai-form" action="" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="text-sm font-medium text-gray-700 block mb-1">Nilai (0 – 100)</label>
                    <input type="number" id="modal-nilai-val" name="nilai" min="0" max="100"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-700 block mb-1">Feedback / Komentar</label>
                    <textarea id="modal-feedback" name="feedback" rows="3"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none"></textarea>
                </div>
                <div class="flex gap-3 justify-end">
                    <button type="button" onclick="document.getElementById('modal-nilai').classList.add('hidden')" class="btn-outline">Batal</button>
                    <button type="submit" class="btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="//unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
@push('scripts')
<script>
function openNilaiModal(subId, name, nilai, feedback) {
    if (!subId) { alert('Siswa ini belum memiliki pengumpulan PBL yang bisa dinilai.'); return; }
    document.getElementById('modal-siswa-name').textContent = name;
    document.getElementById('modal-nilai-val').value = nilai !== 'null' ? nilai : '';
    document.getElementById('modal-feedback').value = feedback;
    document.getElementById('nilai-form').action = '/nilai/submission/' + subId;
    document.getElementById('modal-nilai').classList.remove('hidden');
}
</script>
@endpush
