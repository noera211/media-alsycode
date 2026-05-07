@extends('layouts.app')
@section('title', 'Edit Info Mata Pelajaran')

@section('content')

    <a href="{{ route('dashboard') }}"
        class="text-sm text-indigo-600 font-medium hover:underline flex items-center gap-1 mb-4">
        ← Kembali ke Dashboard
    </a>

    <div class="max-w-2xl mx-auto">

        <div class="flex items-center gap-3 mb-6">
            <div class="h-11 w-11 bg-teal-100 rounded-xl flex items-center justify-center text-xl flex-shrink-0">🎯</div>
            <div>
                <h1 class="text-xl font-bold text-gray-900">Info Mata Pelajaran</h1>
                <p class="text-sm text-gray-400">Informasi ini ditampilkan di dashboard siswa</p>
            </div>
        </div>

        @if(session('success'))
            <div class="mb-4 bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm px-4 py-3 rounded-xl flex items-center gap-2">
                <span>✓</span> {{ session('success') }}
            </div>
        @endif

        <div class="bg-white border border-gray-100 rounded-2xl shadow-sm p-6">
            <form action="{{ route('subject-info.update') }}" method="POST" class="space-y-5">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nama Mata Pelajaran</label>
                        <input type="text" name="mata_pelajaran"
                            value="{{ old('mata_pelajaran', $info->mata_pelajaran ?? 'Informatika') }}"
                            required
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent focus:outline-none">
                        @error('mata_pelajaran')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Kelas</label>
                        <input type="text" name="kelas"
                            value="{{ old('kelas', $info->kelas ?? 'X') }}"
                            required placeholder="X / XI / XII"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent focus:outline-none">
                        @error('kelas')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi Singkat</label>
                    <textarea name="deskripsi" rows="3"
                        placeholder="Deskripsi singkat tentang mata pelajaran ini..."
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent focus:outline-none resize-none">{{ old('deskripsi', $info->deskripsi ?? '') }}</textarea>
                    @error('deskripsi')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Tujuan Pembelajaran
                        <span class="text-xs text-gray-400 font-normal ml-1">(satu tujuan per baris)</span>
                    </label>
                    <textarea name="tujuan_pembelajaran" rows="6"
                        placeholder="Contoh:&#10;Siswa mampu memahami konsep algoritma&#10;Siswa mampu membuat pseudocode&#10;Siswa mampu menyelesaikan masalah dengan PBL"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent focus:outline-none">{{ old('tujuan_pembelajaran', $info->tujuan_pembelajaran ?? '') }}</textarea>
                    <p class="text-xs text-gray-400 mt-1">Pisahkan setiap tujuan dengan Enter. Akan tampil sebagai daftar ✓ di dashboard siswa.</p>
                    @error('tujuan_pembelajaran')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                </div>

                {{-- Preview tujuan yang sudah tersimpan --}}
                @if($info && $info->tujuan_pembelajaran)
                <div class="bg-indigo-50 border border-indigo-100 rounded-xl p-4">
                    <p class="text-xs font-semibold text-indigo-600 mb-2">📋 Preview saat ini di dashboard siswa:</p>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-1.5">
                        @foreach($info->tujuan_array as $t)
                        <div class="flex items-start gap-2 text-sm text-indigo-700">
                            <span class="mt-0.5 flex-shrink-0 w-4 h-4 rounded-full bg-indigo-200 flex items-center justify-center text-xs font-bold text-indigo-600">✓</span>
                            {{ $t }}
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                <div class="flex justify-end gap-3 pt-2">
                    <a href="{{ route('dashboard') }}"
                        class="px-4 py-2 text-sm font-medium text-gray-600 border border-gray-300 rounded-lg hover:bg-gray-50 transition">
                        Batal
                    </a>
                    <button type="submit" class="btn-primary text-sm px-5 py-2">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>

@endsection