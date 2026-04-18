@extends('layouts.app')
@section('title', $pblActivity->title)

@section('content')
<a href="{{ route('pbl.index') }}" class="text-sm text-indigo-600 font-medium hover:underline flex items-center gap-1 mb-4">
    ← Kembali ke daftar aktivitas
</a>

<div class="grid lg:grid-cols-3 gap-6">
    {{-- Main --}}
    <div class="lg:col-span-2 space-y-5">
        {{-- Soal --}}
        <div class="card p-6">
            <div class="flex items-center gap-3 mb-4">
                <div class="h-10 w-10 bg-indigo-100 rounded-xl flex items-center justify-center text-indigo-600 font-bold">PBL</div>
                <div>
                    <h1 class="text-lg font-bold text-gray-900">{{ $pblActivity->title }}</h1>
                    <div class="flex gap-2 mt-0.5">
                        <span class="text-xs text-gray-400">{{ $pblActivity->topic }}</span>
                        <span class="badge-{{ strtolower($pblActivity->difficulty) }}">{{ $pblActivity->difficulty }}</span>
                    </div>
                </div>
            </div>
            <div class="text-sm leading-relaxed text-gray-700 whitespace-pre-line bg-gray-50 rounded-xl p-4">{{ $pblActivity->problem }}</div>
        </div>

        {{-- ── Siswa: Kumpulkan / Lihat / Edit Jawaban ── --}}
        @if(auth()->user()->isSiswa())
        <div class="card p-6">
            <h2 class="font-semibold text-gray-800 mb-4">📤 Jawaban Saya</h2>

            @if($submission && !request('edit'))
            {{-- ── Sudah Dikumpulkan ── --}}
            <div class="flex items-center gap-3 p-4 bg-emerald-50 border border-emerald-200 rounded-xl mb-4">
                <span class="text-emerald-500 text-xl">✓</span>
                <div class="flex-1">
                    <p class="text-sm font-semibold text-emerald-700">Jawaban sudah dikumpulkan</p>
                    <p class="text-xs text-emerald-600">{{ \Carbon\Carbon::parse($submission->submitted_at)->format('d M Y, H:i') }}</p>
                </div>
                {{-- Tombol Edit (hanya jika belum dinilai) --}}
                @if($submission->nilai === null)
                <a href="{{ route('pbl.show', $pblActivity) }}?edit=1"
                   class="btn-outline text-xs flex-shrink-0">✏ Edit Jawaban</a>
                @endif
            </div>

            {{-- Tampilkan jawaban yang sudah dikumpulkan --}}
            @if($submission->answer)
            <div class="bg-gray-50 rounded-xl p-4 mb-4">
                <p class="text-xs font-medium text-gray-500 mb-2">Jawaban Anda:</p>
                <p class="text-sm text-gray-700 whitespace-pre-line">{{ $submission->answer }}</p>
            </div>
            @endif

            @if($submission->file_path)
            <div class="mb-4">
                <a href="{{ route('pbl.submission.view', $submission) }}"
                   target="_blank"
                   class="text-xs text-indigo-500 hover:underline flex items-center gap-1">
                    📎 Lihat file yang dikumpulkan ↗
                </a>
            </div>
            @endif

            {{-- Feedback dari guru --}}
            @if($submission->feedback || $submission->nilai !== null)
            <div class="mt-2 p-4 border border-indigo-100 bg-indigo-50 rounded-xl">
                <p class="text-sm font-semibold text-indigo-800 mb-3">📋 Feedback Guru</p>
                @if($submission->nilai !== null)
                <div class="flex items-center gap-3 mb-3">
                    <span class="text-3xl font-bold text-indigo-600">{{ $submission->nilai }}</span>
                    <span class="text-sm text-gray-500">/ 100</span>
                    <span class="text-xs px-2 py-1 rounded-full font-medium
                        {{ $submission->nilai >= 80 ? 'bg-emerald-100 text-emerald-700'
                        : ($submission->nilai >= 60 ? 'bg-amber-100 text-amber-700' : 'bg-red-100 text-red-700') }}">
                        {{ $submission->nilai >= 80 ? 'Sangat Baik' : ($submission->nilai >= 60 ? 'Cukup' : 'Perlu Perbaikan') }}
                    </span>
                </div>
                @endif
                @if($submission->feedback)
                <p class="text-sm text-gray-700">{{ $submission->feedback }}</p>
                @endif
            </div>
            @else
            <p class="text-xs text-gray-400 mt-2">Menunggu penilaian dari guru...</p>
            @endif

            @elseif($submission && request('edit') && $submission->nilai === null)
            {{-- ── Mode Edit Submission ── --}}
            <div class="p-3 bg-amber-50 border border-amber-200 rounded-lg mb-4 flex items-center gap-2">
                <span class="text-amber-500">✏</span>
                <p class="text-xs text-amber-700 font-medium">Kamu sedang mengedit jawaban yang sudah dikumpulkan.</p>
            </div>

            <form action="{{ route('pbl.submit.update', [$pblActivity, $submission]) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf
                @method('PUT')
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Penjelasan Jawaban</label>
                    <textarea name="answer" rows="5"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none"
                        placeholder="Tuliskan penjelasan algoritma Anda...">{{ $submission->answer }}</textarea>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Upload File Baru (opsional, gantikan file lama)</label>
                    @if($submission->file_path)
                    <p class="text-xs text-gray-400 mb-2">File saat ini:
                        <a href="{{ asset('storage/'.$submission->file_path) }}" target="_blank" class="text-indigo-500 hover:underline">📎 Lihat ↗</a>
                    </p>
                    @endif
                    <input type="file" name="file" accept=".pdf,.doc,.docx,.txt"
                        class="w-full border border-dashed border-gray-300 rounded-lg px-3 py-4 text-sm text-gray-500 cursor-pointer file:mr-3 file:py-1 file:px-3 file:rounded-md file:border-0 file:bg-indigo-50 file:text-indigo-600 file:text-xs file:font-medium">
                    <p class="text-xs text-gray-400 mt-1">PDF/DOC/TXT, maks 5MB. Kosongkan jika tidak ingin mengganti file.</p>
                </div>
                <div class="flex gap-3">
                    <button type="submit" class="btn-primary flex-1 text-center">💾 Simpan Perubahan</button>
                    <a href="{{ route('pbl.show', $pblActivity) }}" class="btn-outline text-center px-4">Batal</a>
                </div>
            </form>

            @else
            {{-- ── Belum Pernah Submit ── --}}
            <form action="{{ route('pbl.submit', $pblActivity) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Penjelasan Jawaban (opsional)</label>
                    <textarea name="answer" rows="4" placeholder="Tuliskan penjelasan algoritma Anda..."
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none"></textarea>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Upload File (PDF/DOC, maks 5MB)</label>
                    <input type="file" name="file" accept=".pdf,.doc,.docx,.txt"
                        class="w-full border border-dashed border-gray-300 rounded-lg px-3 py-4 text-sm text-gray-500 cursor-pointer file:mr-3 file:py-1 file:px-3 file:rounded-md file:border-0 file:bg-indigo-50 file:text-indigo-600 file:text-xs file:font-medium">
                </div>
                <button type="submit" class="btn-primary w-full text-center">📤 Kumpulkan Jawaban</button>
            </form>
            @endif
        </div>
        @endif

        {{-- ── Guru: Daftar Submission ── --}}
        @if(auth()->user()->isGuru() && $submissions)
        <div class="card">
            <div class="p-5 border-b border-gray-100 flex items-center justify-between">
                <h2 class="font-semibold text-gray-800">Pengumpulan Siswa</h2>
                <span class="text-xs text-gray-400">{{ $submissions->count() }} pengumpulan</span>
            </div>
            <div class="divide-y divide-gray-100">
                @forelse($submissions as $sub)
                <div class="p-5">
                    <div class="flex items-start justify-between mb-3">
                        <div>
                            <p class="font-medium text-gray-800 text-sm">{{ $sub->student->name }}</p>
                            <p class="text-xs text-gray-400">{{ \Carbon\Carbon::parse($sub->submitted_at)->format('d M Y, H:i') }}</p>
                        </div>
                        @if($sub->nilai !== null)
                        <span class="text-sm font-bold px-3 py-1 rounded-lg
                            {{ $sub->nilai >= 80 ? 'bg-emerald-100 text-emerald-700'
                            : ($sub->nilai >= 60 ? 'bg-amber-100 text-amber-700' : 'bg-red-100 text-red-700') }}">
                            {{ $sub->nilai }}
                        </span>
                        @else
                        <span class="text-xs text-amber-600 font-medium">Belum dinilai</span>
                        @endif
                    </div>
                    @if($sub->answer)
                    <div class="bg-gray-50 rounded-lg p-3 mb-3">
                        <p class="text-xs text-gray-400 mb-1">Jawaban:</p>
                        <p class="text-sm text-gray-700">{{ Str::limit($sub->answer, 200) }}</p>
                    </div>
                    @endif
                    @if($sub->file_path)
                    <div class="flex items-center gap-3 mb-3">
                        <a href="{{ route('pbl.submission.view', $sub) }}"
                           target="_blank"
                           class="text-xs text-indigo-600 hover:underline flex items-center gap-1">
                            👁 Lihat File
                        </a>
                        <a href="{{ route('pbl.submission.download', $sub) }}"
                           class="text-xs text-gray-500 hover:underline flex items-center gap-1">
                            ↓ Unduh
                        </a>
                    </div>
                    @endif
                    <form action="{{ route('pbl.grade', $sub) }}" method="POST" class="flex gap-2 items-end">
                        @csrf
                        <div class="flex-1">
                            <label class="text-xs font-medium text-gray-600 block mb-1">Feedback</label>
                            <input type="text" name="feedback" value="{{ $sub->feedback }}"
                                class="w-full border border-gray-200 rounded-lg px-2 py-1.5 text-xs focus:ring-1 focus:ring-indigo-400 focus:outline-none">
                        </div>
                        <div class="w-20">
                            <label class="text-xs font-medium text-gray-600 block mb-1">Nilai</label>
                            <input type="number" name="nilai" value="{{ $sub->nilai }}" min="0" max="100"
                                class="w-full border border-gray-200 rounded-lg px-2 py-1.5 text-xs text-center focus:ring-1 focus:ring-indigo-400 focus:outline-none">
                        </div>
                        <button class="btn-primary text-xs px-3 py-1.5">Simpan</button>
                    </form>
                </div>
                @empty
                <div class="p-8 text-center text-gray-400 text-sm">Belum ada pengumpulan.</div>
                @endforelse
            </div>
        </div>
        @endif
    </div>

    {{-- Sidebar --}}
    <div class="space-y-4">
        <div class="card p-5">
            <h3 class="font-semibold text-gray-800 mb-3 text-sm">📚 Materi Terkait</h3>
            @if($pblActivity->relatedMateri)
                <a href="{{ route('materi.show', $pblActivity->relatedMateri) }}" class="text-sm text-indigo-600 hover:underline font-medium">
                    {{ $pblActivity->relatedMateri->title }}
                </a>
                <p class="text-xs text-gray-500 mt-1">{{ Str::limit($pblActivity->relatedMateri->description, 80) }}</p>
            @else
                <p class="text-sm text-gray-400">Tidak ada materi terkait</p>
            @endif
        </div>
        <div class="card p-5">
            <h3 class="font-semibold text-gray-800 mb-3 text-sm">💻 Mini Compiler</h3>
            <a href="{{ route('compiler') }}" class="btn-outline w-full block text-center text-xs">Buka Compiler →</a>
        </div>
        <div class="card p-5">
            <h3 class="font-semibold text-gray-800 mb-2 text-sm">Info Aktivitas</h3>
            <dl class="space-y-1.5 text-xs">
                <div class="flex justify-between"><dt class="text-gray-500">Tingkat</dt><dd class="font-medium">{{ $pblActivity->difficulty }}</dd></div>
                <div class="flex justify-between"><dt class="text-gray-500">Topik</dt><dd class="font-medium">{{ $pblActivity->topic }}</dd></div>
            </dl>
        </div>
    </div>
</div>
@endsection