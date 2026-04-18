@extends('layouts.app')
@section('title', $materi->title)

@section('content')

<a href="{{ route('materi.index') }}"
   class="text-sm text-indigo-600 font-medium hover:underline flex items-center gap-1 mb-4">
    ← Kembali ke daftar materi
</a>

<div class="card p-8">

    {{-- HEADER --}}
    {{-- 🔥 DIUBAH: responsive mobile -> flex-col, desktop -> flex-row --}}
    <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-4 mb-6">

        <div class="flex items-start gap-4">
            <div class="h-12 w-12 bg-indigo-100 rounded-xl flex items-center justify-center text-2xl flex-shrink-0">
                {{ $materi->type === 'video' ? '▶' : '📄' }}
            </div>

            <div>
                <h1 class="text-xl font-bold text-gray-900">
                    {{ $materi->title }}
                </h1>

                <p class="text-sm text-gray-500 mt-1">
                    {{ $materi->description }}
                </p>

                {{-- 🔥 DIUBAH: flex-wrap agar badge tidak overflow di mobile --}}
                <div class="flex flex-wrap gap-2 mt-2">
                    <span class="text-xs bg-indigo-50 text-indigo-600 px-2 py-1 rounded-md font-medium">
                        {{ $materi->type }}
                    </span>

                    <span class="text-xs text-gray-400">
                        ⏱ {{ $materi->duration }}
                    </span>
                </div>
            </div>
        </div>

        {{-- STATUS SISWA --}}
        @if(auth()->user()->isSiswa())

        {{-- 🔥 DIUBAH: flex-wrap agar tombol turun ke bawah di HP --}}
        <div class="flex flex-wrap gap-2">

            @if($status !== 'sedang' && $status !== 'selesai')
            <form action="{{ route('materi.status', $materi) }}" method="POST">
                @csrf
                <input type="hidden" name="status" value="sedang">
                <button class="btn-outline text-xs">
                    📖 Mulai Belajar
                </button>
            </form>
            @endif

            @if($status !== 'selesai')
            <form action="{{ route('materi.status', $materi) }}" method="POST">
                @csrf
                <input type="hidden" name="status" value="selesai">
                <button class="btn-primary text-xs">
                    ✓ Tandai Selesai
                </button>
            </form>
            @else
            <span class="text-sm text-emerald-600 font-semibold flex items-center gap-1">
                ✓ Sudah Selesai
            </span>
            @endif

        </div>
        @endif
    </div>

    {{-- ================= VIDEO YOUTUBE ================= --}}
    @if($materi->video_url && $materi->youtube_id)

    <div class="mb-6">

        {{-- 🔥 DIUBAH: pakai aspect-video, ganti style padding-top lama --}}
        <div class="relative w-full rounded-xl overflow-hidden bg-black aspect-video">

            {{-- Thumbnail --}}
            <div id="yt-thumb-{{ $materi->id }}"
                 class="absolute inset-0 cursor-pointer group"
                 onclick="loadYTPlayer('{{ $materi->id }}','{{ $materi->youtube_id }}')">

                <img
                    src="https://img.youtube.com/vi/{{ $materi->youtube_id }}/maxresdefault.jpg"
                    onerror="this.src='https://img.youtube.com/vi/{{ $materi->youtube_id }}/hqdefault.jpg'"
                    class="w-full h-full object-cover"
                    alt="Thumbnail {{ $materi->title }}"
                >

                <div class="absolute inset-0 bg-black/25 group-hover:bg-black/40 transition"></div>

                <div class="absolute inset-0 flex items-center justify-center">
                    <div class="w-16 h-16 bg-red-600 rounded-full flex items-center justify-center shadow-xl group-hover:scale-110 transition">
                        <svg class="w-7 h-7 text-white ml-1" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M8 5v14l11-7z"/>
                        </svg>
                    </div>
                </div>

                <div class="absolute bottom-3 left-4">
                    <span class="text-white text-xs bg-black/50 px-2 py-1 rounded">
                        Klik untuk memutar
                    </span>
                </div>
            </div>

            {{-- Player --}}
            <div id="yt-player-{{ $materi->id }}" class="absolute inset-0 hidden"></div>

        </div>

        <p class="text-xs text-gray-400 mt-2">
            🎬 Video YouTube ·
            <a href="{{ $materi->video_url }}"
               target="_blank"
               class="text-indigo-500 hover:underline">
                Buka di YouTube ↗
            </a>
        </p>

    </div>
    @endif

    {{-- ================= PDF ================= --}}
    @if($materi->pdf_file)

    <div class="mb-6">

        <div class="flex items-center justify-between mb-2">
            <h3 class="font-semibold text-gray-800 text-sm">
                📎 Dokumen PDF
            </h3>

            <a href="{{ $materi->pdf_file }}"
               target="_blank"
               class="text-xs text-indigo-600 hover:underline">
                ↗ Buka di tab baru
            </a>
        </div>

        {{-- 🔥 DIUBAH: pakai class h-[600px], hapus inline style --}}
        <div class="rounded-xl border border-gray-200 overflow-hidden bg-gray-50 h-[600px]">

            <object
                data="{{ $materi->pdf_file }}#toolbar=1&navpanes=0"
                type="application/pdf"
                class="w-full h-full">

                <div class="flex flex-col items-center justify-center h-full gap-3 p-8 text-center">
                    <p class="text-gray-500 text-sm font-medium">
                        PDF tidak dapat ditampilkan langsung
                    </p>

                    <a href="{{ $materi->pdf_file }}"
                       target="_blank"
                       class="btn-primary text-sm">
                        📄 Unduh / Buka PDF
                    </a>
                </div>

            </object>
        </div>
    </div>
    @endif

    {{-- ================= TEXT CONTENT ================= --}}
    @if($materi->content)
    <div class="bg-gray-50 rounded-xl p-6">
        <pre class="whitespace-pre-wrap text-sm leading-relaxed text-gray-800 font-sans">
{{ $materi->content }}
        </pre>
    </div>
    @endif

    {{-- EMPTY --}}
    @if(!$materi->content && !$materi->video_url && !$materi->pdf_file)
    <div class="text-center py-10 text-gray-400">
        <p class="text-sm">Konten materi belum tersedia.</p>
    </div>
    @endif

</div>

{{-- FOOTER BUTTON --}}
{{-- 🔥 DIUBAH: mobile vertical, desktop horizontal --}}
<div class="mt-4 flex flex-col sm:flex-row gap-2 sm:justify-between">

    {{-- 🔥 DIUBAH: text-center --}}
    <a href="{{ route('materi.index') }}"
       class="btn-outline text-xs text-center">
        ← Semua Materi
    </a>

    {{-- 🔥 DIUBAH: text-center --}}
    <a href="{{ route('pbl.index') }}"
       class="btn-primary text-xs text-center">
        Ke Aktivitas PBL →
    </a>

</div>

@endsection


@push('scripts')
<script>
function loadYTPlayer(id, ytId) {

    // 🔥 DIUBAH: pakai variable agar readable
    const thumb = document.getElementById('yt-thumb-' + id);
    const player = document.getElementById('yt-player-' + id);

    thumb.classList.add('hidden');
    player.classList.remove('hidden');

    // 🔥 DIUBAH: template literal modern
    player.innerHTML = `
        <iframe
            class="w-full h-full"
            src="https://www.youtube.com/embed/${ytId}?autoplay=1&rel=0"
            title="YouTube video player"
            frameborder="0"
            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
            allowfullscreen>
        </iframe>
    `;
}
</script>
@endpush