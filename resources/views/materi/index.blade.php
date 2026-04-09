@extends('layouts.app')
@section('title', 'Materi Pembelajaran')

@section('content')
<div class="mb-6 flex items-center justify-between">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Materi Pembelajaran</h1>
        <p class="text-gray-500 mt-1 text-sm">Pelajari konsep algoritma pemrograman langkah demi langkah</p>
    </div>

    @if(auth()->user()->isGuru())
    <button onclick="toggleModal('modal-create', true)" class="btn-primary flex items-center gap-2">
        <span>+</span> Tambah Materi
    </button>
    @endif
</div>

{{-- Progress --}}
@if(auth()->user()->isSiswa())
<div class="card p-5 mb-6">
    <div class="flex justify-between mb-2">
        <span class="text-sm font-semibold">Progress Materi</span>
        <span class="text-sm text-indigo-600">{{ $completedCount }}/{{ $totalMateri }}</span>
    </div>

    <div class="w-full bg-gray-100 rounded-full h-2.5">
        <div class="bg-indigo-600 h-2.5 rounded-full"
             style="width: {{ $progressWidth }}"></div>
    </div>
</div>
@endif

{{-- Materi --}}
<div class="grid md:grid-cols-2 gap-4">
@foreach($materiList as $m)
@php $status = $statusMap[$m->id] ?? 'belum'; @endphp

<div class="card p-5">
    <a href="{{ route('materi.show', $m) }}" class="flex gap-4 mb-3">
        <div class="h-10 w-10 bg-indigo-100 rounded-xl flex items-center justify-center">
            {{ $m->type === 'video' ? '▶' : '📄' }}
        </div>

        <div class="flex-1">
            <h3 class="font-semibold">{{ $m->title }}</h3>
            <p class="text-xs text-gray-500">{{ $m->description }}</p>

            <div class="flex gap-2 mt-2 text-xs">
                <span class="bg-indigo-50 text-indigo-600 px-2 rounded">{{ $m->type }}</span>
                <span>{{ $m->duration }}</span>
            </div>
        </div>
    </a>

    @if(auth()->user()->isGuru())
    <div class="flex gap-2 pt-3 border-t">
        <button 
            class="btn-outline text-xs"
            data-id="{{ $m->id }}"
            data-title="{{ $m->title }}"
            data-description="{{ $m->description }}"
            data-type="{{ $m->type }}"
            data-duration="{{ $m->duration }}"
            data-content="{{ $m->content }}"
            data-video="{{ $m->video_url }}"
            data-pdf="{{ $m->pdf_file }}"
            onclick="openEdit(this)">
            ✏ Edit
        </button>

        <form action="{{ route('materi.destroy', $m) }}" method="POST">
            @csrf @method('DELETE')
            <button class="btn-danger text-xs">🗑</button>
        </form>
    </div>
    @endif
</div>

@endforeach
</div>

{{-- Modal Create --}}
<div id="modal-create" class="hidden fixed inset-0 flex items-center justify-center bg-black/50">
    <div class="bg-white p-6 rounded-xl w-full max-w-lg">
        <form action="{{ route('materi.store') }}" method="POST">
            @csrf
            @include('materi._form')

            <div class="flex justify-end gap-2 mt-3">
                <button type="button" onclick="toggleModal('modal-create', false)">Batal</button>
                <button type="submit" class="btn-primary">Simpan</button>
            </div>
        </form>
    </div>
</div>

{{-- Modal Edit --}}
<div id="modal-edit" class="hidden fixed inset-0 flex items-center justify-center bg-black/50">
    <div class="bg-white p-6 rounded-xl w-full max-w-lg">
        <form id="edit-form" method="POST">
            @csrf @method('PUT')
            @include('materi._form')

            <div class="flex justify-end gap-2 mt-3">
                <button type="button" onclick="toggleModal('modal-edit', false)">Batal</button>
                <button type="submit" class="btn-primary">Update</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
function toggleModal(id, show) {
    document.getElementById(id).classList.toggle('hidden', !show);
}

function openEdit(btn) {
    const f = document.getElementById('edit-form');

    f.action = '/materi/' + btn.dataset.id;

    f.querySelector('[name=title]').value = btn.dataset.title || '';
    f.querySelector('[name=description]').value = btn.dataset.description || '';
    f.querySelector('[name=type]').value = btn.dataset.type || '';
    f.querySelector('[name=duration]').value = btn.dataset.duration || '';
    f.querySelector('[name=content]').value = btn.dataset.content || '';
    f.querySelector('[name=video_url]').value = btn.dataset.video || '';
    f.querySelector('[name=pdf_file]').value = btn.dataset.pdf || '';

    toggleModal('modal-edit', true);
}
</script>
@endpush