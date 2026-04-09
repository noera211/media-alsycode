@extends('layouts.app')
@section('title', 'Materi Pembelajaran')

@section('content')
<div class="mb-6 flex items-center justify-between">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Materi Pembelajaran</h1>
        <p class="text-gray-500 mt-1 text-sm">Pelajari konsep algoritma pemrograman langkah demi langkah</p>
    </div>
    @if(auth()->user()->isGuru())
    <button onclick="document.getElementById('modal-create').classList.remove('hidden')" class="btn-primary flex items-center gap-2">
        <span>+</span> Tambah Materi
    </button>
    @endif
</div>

{{-- Progress (siswa) --}}
@if(auth()->user()->isSiswa())
<div class="card p-5 mb-6">
    <div class="flex items-center justify-between mb-2">
        <span class="text-sm font-semibold text-gray-700">Progress Materi</span>
        <span class="text-sm text-indigo-600 font-medium">{{ $completedCount }}/{{ $totalMateri }} selesai</span>
    </div>
    <div class="w-full bg-gray-100 rounded-full h-2.5">
        <div class="bg-indigo-600 h-2.5 rounded-full" style="width: {{ $progressPct }}%"></div>
    </div>
</div>
@endif

{{-- Materi Grid --}}
<div class="grid md:grid-cols-2 gap-4">
    @foreach($materiList as $m)
        @php $status = $statusMap[$m->id] ?? 'belum'; @endphp
        <div class="card p-5 hover:shadow-md transition-shadow">
            <a href="{{ route('materi.show', $m) }}" class="flex items-start gap-4 mb-3">
                <div class="h-10 w-10 bg-indigo-100 rounded-xl flex items-center justify-center flex-shrink-0 text-indigo-600">
                    {{ $m->type === 'video' ? '▶' : '📄' }}
                </div>
                <div class="flex-1">
                    <h3 class="font-semibold text-gray-800 mb-1">{{ $m->title }}</h3>
                    <p class="text-xs text-gray-500 mb-2">{{ $m->description }}</p>
                    <div class="flex flex-wrap gap-2">
                        <span class="text-xs bg-indigo-50 text-indigo-600 px-2 py-0.5 rounded-md font-medium">{{ $m->type }}</span>
                        <span class="text-xs text-gray-400">{{ $m->duration }}</span>
                        @if($m->video_url) <span class="text-xs text-gray-400">📹 Video</span> @endif
                        @if($m->pdf_file)  <span class="text-xs text-gray-400">📎 PDF</span>   @endif
                        @if(auth()->user()->isSiswa())
                            @if($status === 'selesai')    <span class="text-xs text-emerald-600 font-semibold">✓ Selesai</span>
                            @elseif($status === 'sedang') <span class="text-xs text-amber-600 font-semibold">⏳ Sedang</span>
                            @else                         <span class="text-xs text-gray-400">Belum dipelajari</span>
                            @endif
                        @endif
                    </div>
                </div>
            </a>
            @if(auth()->user()->isGuru())
            <div class="flex gap-2 pt-3 border-t border-gray-100">
                <button onclick="openEditMateri({{ $m->id }}, '{{ addslashes($m->title) }}', '{{ addslashes($m->description) }}', '{{ $m->type }}', '{{ $m->duration }}', '{{ addslashes($m->content ?? '') }}', '{{ $m->video_url ?? '' }}', '{{ $m->pdf_file ?? '' }}')"
                    class="btn-outline text-xs px-3 py-1.5">✏ Edit</button>
                <form action="{{ route('materi.destroy', $m) }}" method="POST" onsubmit="return confirm('Hapus materi ini?')">
                    @csrf @method('DELETE')
                    <button class="btn-danger text-xs px-3 py-1.5">🗑 Hapus</button>
                </form>
            </div>
            @endif
        </div>
    @endforeach
</div>

{{-- Modal Create --}}
<div id="modal-create" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/50">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-lg mx-4 max-h-[90vh] overflow-y-auto">
        <div class="p-6">
            <h2 class="text-lg font-bold mb-4">Tambah Materi Baru</h2>
            <form action="{{ route('materi.store') }}" method="POST" class="space-y-4">
                @csrf
                @include('materi._form')
                <div class="flex gap-3 justify-end pt-2">
                    <button type="button" onclick="document.getElementById('modal-create').classList.add('hidden')" class="btn-outline">Batal</button>
                    <button type="submit" class="btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Modal Edit --}}
<div id="modal-edit" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/50">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-lg mx-4 max-h-[90vh] overflow-y-auto">
        <div class="p-6">
            <h2 class="text-lg font-bold mb-4">Edit Materi</h2>
            <form id="edit-form" action="" method="POST" class="space-y-4">
                @csrf @method('PUT')
                @include('materi._form', ['edit' => true])
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
function openEditMateri(id, title, desc, type, duration, content, videoUrl, pdfFile) {
    const f = document.getElementById('edit-form');
    f.action = '/materi/' + id;
    f.querySelector('[name=title]').value = title;
    f.querySelector('[name=description]').value = desc;
    f.querySelector('[name=type]').value = type;
    f.querySelector('[name=duration]').value = duration;
    f.querySelector('[name=content]').value = content;
    f.querySelector('[name=video_url]').value = videoUrl;
    f.querySelector('[name=pdf_file]').value = pdfFile;
    document.getElementById('modal-edit').classList.remove('hidden');
}
</script>
@endpush
