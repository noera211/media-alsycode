@extends('layouts.app')
@section('title', $materi->title)

@section('content')
<a href="{{ route('materi.index') }}" class="text-sm text-indigo-600 font-medium hover:underline flex items-center gap-1 mb-4">
    ← Kembali ke daftar materi
</a>

<div class="card p-8">
    {{-- Header --}}
    <div class="flex items-start justify-between mb-6">
        <div class="flex items-center gap-4">
            <div class="h-12 w-12 bg-indigo-100 rounded-xl flex items-center justify-center text-2xl">
                {{ $materi->type === 'video' ? '▶' : '📄' }}
            </div>
            <div>
                <h1 class="text-xl font-bold text-gray-900">{{ $materi->title }}</h1>
                <p class="text-sm text-gray-500 mt-0.5">{{ $materi->description }}</p>
                <div class="flex gap-2 mt-2">
                    <span class="text-xs bg-indigo-50 text-indigo-600 px-2 py-0.5 rounded-md font-medium">{{ $materi->type }}</span>
                    <span class="text-xs text-gray-400">⏱ {{ $materi->duration }}</span>
                </div>
            </div>
        </div>

        {{-- Status buttons untuk siswa --}}
        @if(auth()->user()->isSiswa())
        <div class="flex gap-2 flex-shrink-0">
            @if($status !== 'sedang' && $status !== 'selesai')
            <form action="{{ route('materi.status', $materi) }}" method="POST">
                @csrf
                <input type="hidden" name="status" value="sedang">
                <button class="btn-outline text-xs">📖 Mulai Belajar</button>
            </form>
            @endif

            @if($status !== 'selesai')
            <form action="{{ route('materi.status', $materi) }}" method="POST">
                @csrf
                <input type="hidden" name="status" value="selesai">
                <button class="btn-primary text-xs">✓ Tandai Selesai</button>
            </form>
            @else
            <span class="text-sm text-emerald-600 font-semibold flex items-center gap-1">✓ Sudah Selesai</span>
            @endif
        </div>
        @endif
    </div>

    {{-- YouTube Video --}}
    @if($materi->video_url && $materi->youtube_id)
    <div class="aspect-video bg-gray-100 rounded-xl overflow-hidden mb-6">
        <iframe
            src="https://www.youtube.com/embed/{{ $materi->youtube_id }}"
            title="{{ $materi->title }}"
            class="w-full h-full"
            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
            allowfullscreen>
        </iframe>
    </div>
    @endif

    {{-- PDF Embed --}}
    @if($materi->pdf_file)
    <div class="mb-6">
        <h3 class="font-semibold text-gray-800 mb-2 text-sm">📎 Dokumen PDF</h3>
        <div class="rounded-xl border border-gray-200 overflow-hidden" style="height: 500px;">
            <iframe src="{{ $materi->pdf_file }}" title="PDF - {{ $materi->title }}" class="w-full h-full"></iframe>
        </div>
    </div>
    @endif

    {{-- Text Content --}}
    @if($materi->content)
    <div class="bg-gray-50 rounded-xl p-6">
        <pre class="whitespace-pre-wrap text-sm leading-relaxed text-gray-800 font-sans">{{ $materi->content }}</pre>
    </div>
    @endif

    @if(!$materi->content && !$materi->video_url && !$materi->pdf_file)
    <div class="text-center py-10 text-gray-400">
        <p class="text-sm">Konten materi belum tersedia.</p>
    </div>
    @endif
</div>

{{-- Navigasi materi berikutnya --}}
<div class="mt-4 flex justify-between">
    <a href="{{ route('materi.index') }}" class="btn-outline text-xs">← Semua Materi</a>
    <a href="{{ route('pbl.index') }}" class="btn-primary text-xs">Ke Aktivitas PBL →</a>
</div>
@endsection
