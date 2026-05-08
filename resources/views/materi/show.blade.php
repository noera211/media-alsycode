@extends('layouts.app')
@section('title', $materi->title)

@section('content')

    <a href="{{ route('materi.index') }}"
        class="text-sm text-indigo-600 font-medium hover:underline flex items-center gap-1 mb-4">
        ← Kembali ke daftar materi
    </a>

    <div class="card p-8">

        {{-- HEADER --}}
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
            @if (auth()->user()->isSiswa())
                <div class="flex flex-row flex-nowrap gap-2">

                    @if ($status !== 'sedang' && $status !== 'selesai')
                        <form action="{{ route('materi.status', $materi) }}" method="POST" class="flex-1 min-w-0">
                            @csrf
                            <input type="hidden" name="status" value="sedang">
                            <button class="btn-outline text-xs w-full whitespace-nowrap">
                                📖 Mulai Belajar
                            </button>
                        </form>
                    @endif

                    @if ($status !== 'selesai')
                        <form action="{{ route('materi.status', $materi) }}" method="POST" class="flex-1 min-w-0">
                            @csrf
                            <input type="hidden" name="status" value="selesai">
                            <button class="btn-primary text-xs w-full whitespace-nowrap">
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

        {{-- ================= TUJUAN PEMBELAJARAN ================= --}}
        @if ($materi->tujuan_pembelajaran)
            @php
                $tujuanList = collect(preg_split('/\r\n|\r|\n/', $materi->tujuan_pembelajaran))
                    ->map(fn($t) => trim($t))
                    ->filter()
                    ->values();
            @endphp
            <div class="bg-indigo-50 border border-indigo-100 rounded-xl p-5 mb-6">
                <div class="flex items-center gap-2 mb-3">
                    <span class="text-base">🎯</span>
                    <h2 class="text-sm font-bold text-indigo-800">Tujuan Pembelajaran</h2>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                    @foreach ($tujuanList as $t)
                        <div class="flex items-start gap-2 text-sm text-indigo-700">
                            <span
                                class="mt-0.5 flex-shrink-0 w-4 h-4 rounded-full bg-indigo-200 flex items-center justify-center text-xs font-bold text-indigo-600">✓</span>
                            {{ $t }}
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        {{-- ================= TEXT CONTENT ================= --}}
        @if ($materi->content)
            <div class="bg-gray-50 rounded-xl p-6">
                <pre class="whitespace-pre-wrap text-sm leading-relaxed text-gray-800 font-sans">{{ $materi->content }}</pre>
            </div>
        @endif

        {{-- EMPTY --}}
        @if (!$materi->content && !$materi->video_url && !$materi->pdf_file)
            <div class="text-center py-10 text-gray-400">
                <p class="text-sm">Konten materi belum tersedia.</p>
            </div>
        @endif

        {{-- ================= VIDEO YOUTUBE ================= --}}
        @if ($materi->video_url && $materi->youtube_id)
            <div class="mb-6">

                <div class="relative w-full rounded-xl overflow-hidden bg-black aspect-video">

                    {{-- Thumbnail --}}
                    <div id="yt-thumb-{{ $materi->id }}" class="absolute inset-0 cursor-pointer group"
                        onclick="loadYTPlayer('{{ $materi->id }}','{{ $materi->youtube_id }}')">

                        <img src="https://img.youtube.com/vi/{{ $materi->youtube_id }}/maxresdefault.jpg"
                            onerror="this.src='https://img.youtube.com/vi/{{ $materi->youtube_id }}/hqdefault.jpg'"
                            class="w-full h-full object-cover" alt="Thumbnail {{ $materi->title }}">

                        <div class="absolute inset-0 bg-black/25 group-hover:bg-black/40 transition"></div>

                        <div class="absolute inset-0 flex items-center justify-center">
                            <div
                                class="w-16 h-16 bg-red-600 rounded-full flex items-center justify-center shadow-xl group-hover:scale-110 transition">
                                <svg class="w-7 h-7 text-white ml-1" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M8 5v14l11-7z" />
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
                    <a href="{{ $materi->video_url }}" target="_blank" class="text-indigo-500 hover:underline">
                        Buka di YouTube ↗
                    </a>
                </p>

            </div>
        @endif

        {{-- ================= PDF ================= --}}
        @if ($materi->pdf_file)
            @php
                $pdfSrc = $materi->pdf_file;
                if (str_contains($pdfSrc, 'drive.google.com/file/d/')) {
                    preg_match('/\/file\/d\/([^\/]+)/', $pdfSrc, $m);
                    if (!empty($m[1])) {
                        $pdfSrc = 'https://drive.google.com/file/d/' . $m[1] . '/preview';
                    }
                }
            @endphp

            <div class="mb-6">
                <div class="flex items-center justify-between mb-2">
                    <h3 class="font-semibold text-gray-800 text-sm">📎 Dokumen PDF</h3>
                    <a href="{{ $materi->pdf_file }}" target="_blank" class="text-xs text-indigo-600 hover:underline">
                        ↗ Buka di tab baru
                    </a>
                </div>

                <div class="rounded-xl border border-gray-200 overflow-hidden bg-gray-50 h-[600px]">
                    <iframe src="{{ $pdfSrc }}" class="w-full h-full" allowfullscreen>
                    </iframe>
                </div>
            </div>
        @endif

    </div>

    {{-- FOOTER BUTTON --}}
    <div class="mt-4 flex flex-col sm:flex-row gap-2 sm:justify-between">

        {{-- Kiri: Materi Sebelumnya atau Semua Materi --}}
        @if ($prevMateri)
            <a href="{{ route('materi.show', $prevMateri) }}"
                class="btn-outline text-xs text-center flex items-center justify-center gap-1">
                ← {{ $prevMateri->title }}
            </a>
        @else
            <a href="{{ route('materi.index') }}" class="btn-outline text-xs text-center">
                ← Semua Materi
            </a>
        @endif

        {{-- Kanan: Materi Selanjutnya atau Ke Aktivitas PBL --}}
        @if ($nextMateri)
            <a href="{{ route('materi.show', $nextMateri) }}"
                class="btn-primary text-xs text-center flex items-center justify-center gap-1">
                {{ $nextMateri->title }} →
            </a>
        @else
            <a href="{{ route('pbl.index') }}" class="btn-primary text-xs text-center">
                Ke Aktivitas PBL →
            </a>
        @endif

    </div>

@endsection


@push('scripts')
    <script>
        function loadYTPlayer(id, ytId) {
            const thumb = document.getElementById('yt-thumb-' + id);
            const player = document.getElementById('yt-player-' + id);

            thumb.classList.add('hidden');
            player.classList.remove('hidden');

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