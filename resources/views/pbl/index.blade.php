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
    console.log('Opening edit modal for:', id, title);
    
    const form = document.getElementById('edit-pbl-form');
    if (!form) {
        alert('Form edit tidak ditemukan');
        return;
    }
    
    // Set form action
    form.action = '/aktivitas-pbl/' + id;
    
    // Fill form fields
    const fields = {
        'title': title,
        'topic': topic,
        'difficulty': difficulty,
        'problem': problem,
        'related_materi': relatedMateri
    };
    
    Object.keys(fields).forEach(name => {
        const field = form.querySelector('[name="' + name + '"]');
        if (field) {
            field.value = fields[name];
            console.log('Set', name, '=', fields[name]);
        }
    });
    
    // Show modal
    const modal = document.getElementById('modal-edit');
    if (modal) {
        modal.classList.remove('hidden');
        console.log('Modal opened');
    } else {
        alert('Modal tidak ditemukan');
    }
};
</script>
@endpush
