@extends('layouts.app')
@section('title', 'Kumpulan Soal')

@push('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/lucide-static@0.441.0/font/lucide.min.css">
@endpush

@section('content')

    <div class="mb-2">
        <a href="{{ route('nilai.index') }}"
            class="inline-flex items-center gap-1.5 text-sm font-medium text-gray-500 hover:text-indigo-600 transition-colors">
            <i class="icon-arrow-left" style="font-size: 16px;"></i>
            Kembali
        </a>
    </div>

    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Kumpulan Soal</h1>
            <p class="text-sm text-gray-500 mt-1">Kelola pretest, posttest, dan kumpulan soal lainnya.</p>
        </div>

        <a href="{{ route('kumpulan-soal.create') }}"
            class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-indigo-600 text-white text-sm font-semibold hover:bg-indigo-700 transition-colors shadow-sm">
            <i class="icon-circle-plus" style="font-size: 16px;"></i>
            Buat Kumpulan Soal
        </a>
    </div>

    @if (session('success'))
        <div
            class="mb-5 px-4 py-3 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm flex items-center gap-2">
            <i class="icon-check-circle flex-shrink-0" style="font-size: 20px;"></i>
            {{ session('success') }}
        </div>
    @endif

    @if ($sets->isEmpty())
        <div class="bg-white border border-gray-200 rounded-2xl p-16 text-center shadow-sm">
            <div class="flex justify-center mb-4 text-gray-300">
                <i class="icon-clipboard-list" style="font-size: 120px;"></i>
            </div>
            <p class="font-semibold text-gray-700">Belum ada kumpulan soal</p>
            <p class="text-sm text-gray-400 mt-1">Klik tombol di atas untuk membuat kumpulan soal pertama.</p>
        </div>
    @else
        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-5">
            @foreach ($sets as $set)
                <div
                    class="bg-white border border-gray-200 rounded-2xl overflow-hidden shadow-sm hover:shadow-md transition-shadow flex flex-col">

                    <div class="p-5 flex-1">
                        <div class="flex items-start justify-between gap-3 mb-3">
                            <div>
                                <span
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold border {{ $set->type_color }} mb-2">
                                    {{ $set->type_label }}
                                </span>
                                <h3 class="font-bold text-gray-900 text-base leading-snug">{{ $set->name }}</h3>
                            </div>
                            <div class="flex-shrink-0 w-10 h-10 bg-indigo-50 rounded-xl flex items-center justify-center text-indigo-600"
                                title="{{ $set->questions_count }} Soal">
                                <span class="font-bold text-sm">{{ $set->questions_count }}</span>
                            </div>
                        </div>

                        @if ($set->description)
                            <p class="text-sm text-gray-500 mb-3 line-clamp-2">{{ $set->description }}</p>
                        @endif

                        <p class="text-xs text-gray-400 flex items-center gap-3">
                            <span class="flex items-center gap-1">
                                <i class="icon-file-question" style="font-size: 14px;"></i>
                                {{ $set->questions_count }} soal
                            </span>
                            <span class="flex items-center gap-1">
                                <i class="icon-clock" style="font-size: 14px;"></i>
                                {{ $set->created_at->diffForHumans() }}
                            </span>
                        </p>
                    </div>

                    <div class="border-t border-gray-100 px-5 py-3 bg-gray-50 flex items-center gap-2">
                        @if($evaluationSet && $evaluationSet->id === $set->id)
                        <div class="flex-1 flex items-center justify-center gap-1.5 py-2 px-3 rounded-lg bg-emerald-50 text-emerald-700 text-xs font-semibold border border-emerald-200">
                            <i class="icon-check-circle" style="font-size: 16px;"></i> Test Evaluasi Aktif
                        </div>
                        @else
                        <form action="{{ route('nilai.evaluation-set.update') }}" method="POST" class="flex-1">
                            @csrf
                            <input type="hidden" name="evaluation_set_id" value="{{ $set->id }}">
                            <button type="submit" class="w-full flex items-center justify-center gap-1.5 py-2 rounded-lg bg-amber-50 text-amber-700 text-xs font-semibold hover:bg-amber-100 transition-colors border border-amber-200">
                                <i class="icon-target" style="font-size: 16px;"></i> Jadikan Test Evaluasi
                            </button>
                        </form>
                        @endif
                        <a href="{{ route('kumpulan-soal.show', $set) }}"
                            class="flex-1 flex items-center justify-center gap-1.5 py-2 rounded-lg bg-indigo-50 text-indigo-700 text-xs font-semibold hover:bg-indigo-100 transition-colors">
                            <i class="icon-history" style="font-size: 16px;"></i> Riwayat
                        </a>
                        <a href="{{ route('kumpulan-soal.edit', $set) }}"
                            class="flex-1 flex items-center justify-center gap-1.5 py-2 rounded-lg bg-white border border-gray-200 text-gray-700 text-xs font-semibold hover:bg-gray-50 transition-colors">
                            <i class="icon-edit-3" style="font-size: 16px;"></i> Edit
                        </a>
                        <form action="{{ route('kumpulan-soal.destroy', $set) }}" method="POST"
                            onsubmit="return confirm('Hapus kumpulan soal ini beserta semua riwayatnya?')" class="flex">
                            @csrf @method('DELETE')
                            <button title="Hapus Soal"
                                class="py-2 px-3 flex items-center justify-center rounded-lg bg-red-50 text-red-600 hover:bg-red-100 transition-colors border border-red-100">
                                <i class="icon-trash-2" style="font-size: 16px;"></i>
                            </button>
                        </form>
                    </div>

                </div>
            @endforeach
        </div>

    @endif

@endsection