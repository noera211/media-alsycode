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
    <div class="grid grid-cols-3 gap-3">
        @foreach(['Mudah' => '🟢', 'Sedang' => '🟡', 'Sulit' => '🔴'] as $diff => $icon)
            @php $min = $levelSettings[$diff] ?? 1; $unlocked = in_array($diff, $accessible); @endphp
            <div class="rounded-lg border p-3 {{ $unlocked ? 'border-indigo-200 bg-indigo-50' : 'border-gray-200 bg-gray-50' }}">
                <div class="flex items-center gap-2 mb-1.5">
                    <span class="text-sm">{{ $icon }}</span>
                    <span class="text-xs font-semibold text-gray-700">{{ $diff }}</span>
                    <span class="ml-auto text-xs {{ $unlocked ? 'text-emerald-500' : 'text-gray-400' }}">
                        {{ $unlocked ? '✓' : '🔒' }}
                    </span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-1.5 mb-1">
                    <div class="bg-indigo-400 h-1.5 rounded-full" style="width: {{ min(100, $min > 0 ? round(($completedCount/$min)*100) : 100) }}%"></div>
                </div>
                <p class="text-xs text-gray-400">Min. {{ $min }} materi</p>
            </div>
        @endforeach
    </div>
</div>
@endif

{{-- Level Settings (guru) --}}
@if(auth()->user()->isGuru())
<div class="card p-5 mb-6">
    <div class="flex items-center justify-between mb-3">
        <h2 class="font-semibold text-gray-800 text-sm">⚙ Pengaturan Level</h2>
    </div>
    <form action="{{ route('pbl.level-settings') }}" method="POST" class="grid grid-cols-3 gap-4">
        @csrf
        @foreach(['mudah' => 'Mudah', 'sedang' => 'Sedang', 'sulit' => 'Sulit'] as $key => $label)
        <div>
            <label class="block text-xs font-medium text-gray-600 mb-1">Min. materi ({{ $label }})</label>
            <input type="number" name="{{ $key }}" value="{{ $levelSettings[ucfirst($label)] ?? 1 }}" min="0"
                class="w-full border border-gray-300 rounded-lg px-3 py-1.5 text-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none">
        </div>
        @endforeach
        <div class="col-span-3 flex justify-end">
            <button class="btn-primary text-xs">Simpan Pengaturan</button>
        </div>
    </form>
</div>
@endif

{{-- Aktivitas per level --}}
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
                    <button onclick="openEditPbl({{ $act->id }}, '{{ addslashes($act->title) }}', '{{ addslashes($act->topic) }}', '{{ $act->difficulty }}', '{{ addslashes($act->problem) }}', '{{ addslashes($act->related_materi) }}')"
                        class="btn-outline text-xs">✏ Edit</button>
                    <form action="{{ route('pbl.destroy', $act) }}" method="POST" onsubmit="return confirm('Hapus aktivitas ini?')">
                        @csrf @method('DELETE')
                        <button class="btn-danger text-xs">🗑</button>
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
                @include('pbl._form')
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
            <h2 class="text-lg font-bold mb-4">Edit Aktivitas PBL</h2>
            <form id="edit-pbl-form" action="" method="POST" class="space-y-4">
                @csrf @method('PUT')
                @include('pbl._form', ['edit' => true])
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
function openEditPbl(id, title, topic, difficulty, problem, relatedMateri) {
    const f = document.getElementById('edit-pbl-form');
    f.action = '/aktivitas-pbl/' + id;
    f.querySelector('[name=title]').value = title;
    f.querySelector('[name=topic]').value = topic;
    f.querySelector('[name=difficulty]').value = difficulty;
    f.querySelector('[name=problem]').value = problem;
    f.querySelector('[name=related_materi]').value = relatedMateri;
    document.getElementById('modal-edit').classList.remove('hidden');
}
</script>
@endpush
