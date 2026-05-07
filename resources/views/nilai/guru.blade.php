@extends('layouts.app')
@section('title', 'Nilai & Evaluasi')

@push('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/lucide-static@0.441.0/font/lucide.min.css">
@endpush

@section('content')
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Nilai & Evaluasi</h1>
            <p class="text-gray-500 mt-1 text-sm">Kelola nilai siswa dan bank soal test.</p>
        </div>
    </div>

    <div x-data="{ tab: 'nilai' }">
        <div class="flex gap-1 bg-gray-100 p-1 rounded-xl mb-6 w-fit">
            <button @click="tab='nilai'"
                :class="tab === 'nilai' ? 'bg-white shadow text-gray-900' : 'text-gray-500 hover:text-gray-700'"
                class="px-4 py-2 rounded-lg text-sm font-medium transition-all flex items-center gap-1.5">
                <i class="icon-trophy" style="font-size: 16px;"></i> Nilai Siswa
            </button>
            <a href="{{ route('kumpulan-soal.index') }}"
                class="px-4 py-2 rounded-lg text-sm font-medium transition-all text-gray-500 hover:text-gray-700 hover:bg-white flex items-center gap-1.5">
                <i class="icon-book-copy" style="font-size: 16px;"></i> Kumpulan Soal
            </a>
            <a href="{{ route('bank-soal.index') }}"
                class="px-4 py-2 rounded-lg text-sm font-medium transition-all text-gray-500 hover:text-gray-700 hover:bg-white flex items-center gap-1.5">
                <i class="icon-clipboard-list" style="font-size: 16px;"></i> Bank Soal Test
            </a>
        </div>

        {{-- Tab Nilai --}}
        <div x-show="tab==='nilai'">
            <div class="card p-4 mb-4 bg-indigo-50 border-indigo-100">
                <p class="text-xs text-indigo-700 flex items-center gap-1">
                    <i class="icon-info" style="font-size: 14px;"></i>
                    <span>
                        <span class="font-semibold">Nilai PBL</span> diinput manual oleh guru berdasarkan nilai terbaik / level tertinggi siswa.
                        <span class="font-semibold ml-2">Nilai Evaluasi</span> otomatis dari hasil test mandiri siswa.
                        <span class="font-semibold ml-2">Nilai Akhir</span> = rata-rata keduanya.
                    </span>
                </p>
            </div>

            <div class="card overflow-hidden">
                <div class="p-5 border-b border-gray-100 flex items-center justify-between">
                    <h2 class="font-semibold text-gray-800">Daftar Nilai Siswa</h2>
                    <span class="text-xs text-gray-400 flex items-center gap-1.5">
                        <i class="icon-users" style="font-size: 14px;"></i> {{ $siswaList->count() }} siswa
                    </span>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-gray-50 border-b border-gray-100">
                            <tr>
                                <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500">No</th>
                                <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500">Nama Siswa</th>
                                <th class="text-center px-5 py-3 text-xs font-semibold text-gray-500">
                                    Nilai PBL
                                    <span class="block text-gray-400 font-normal">(input guru)</span>
                                </th>
                                <th class="text-center px-5 py-3 text-xs font-semibold text-gray-500">
                                    Nilai Evaluasi
                                    <span class="block text-gray-400 font-normal">(otomatis test)</span>
                                </th>
                                <th class="text-center px-5 py-3 text-xs font-semibold text-gray-500">
                                    Nilai Akhir
                                    <span class="block text-gray-400 font-normal">(rata-rata)</span>
                                </th>
                                <th class="text-center px-5 py-3 text-xs font-semibold text-gray-500">Status Test</th>
                                <th class="text-center px-5 py-3 text-xs font-semibold text-gray-500">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach ($siswaList as $i => $siswa)
                                @php
                                    $grade        = $gradeMap[$siswa->id] ?? null;
                                    $nilaiPbl     = $grade?->nilai_pbl;
                                    $testResult   = $testMap[$siswa->id] ?? null;
                                    $nilaiEvaluasi = $testResult ? $testResult->persentase : null;
                                    $nilaiAkhir   = $nilaiPbl !== null && $nilaiEvaluasi !== null
                                        ? round(($nilaiPbl + $nilaiEvaluasi) / 2) : null;
                                    $subsSiswa    = $submissionMap[$siswa->id] ?? collect();
                                    $isTestOpen   = $grade ? $grade->is_test_open : true;
                                    $sudahTest    = $testResult !== null;
                                @endphp
                                <tr class="hover:bg-gray-50">
                                    <td class="px-5 py-4 text-gray-400">{{ $i + 1 }}</td>
                                    <td class="px-5 py-4">
                                        <p class="font-medium text-gray-800">{{ $siswa->name }}</p>
                                        @if ($subsSiswa->count() > 0)
                                            <p class="text-xs text-gray-400 mt-0.5">
                                                {{ $subsSiswa->count() }} studi kasus ·
                                                tertinggi: <span class="text-indigo-600 font-semibold">{{ $subsSiswa->max('nilai') }}</span>
                                                ({{ $subsSiswa->first()->activity->title ?? '-' }})
                                            </p>
                                        @else
                                            <p class="text-xs text-gray-400 mt-0.5">Belum ada submission dinilai</p>
                                        @endif
                                    </td>

                                    {{-- Nilai PBL --}}
                                    <td class="px-5 py-4 text-center">
                                        @if ($nilaiPbl !== null)
                                            <span class="inline-flex items-center justify-center h-8 w-10 rounded-lg text-sm font-bold
                                                {{ $nilaiPbl >= 80 ? 'bg-emerald-100 text-emerald-700' : ($nilaiPbl >= 60 ? 'bg-amber-100 text-amber-700' : 'bg-red-100 text-red-700') }}">
                                                {{ $nilaiPbl }}
                                            </span>
                                            @if ($grade->catatan_pbl)
                                                <p class="text-xs text-gray-400 mt-1 max-w-[100px] mx-auto truncate" title="{{ $grade->catatan_pbl }}">
                                                    {{ $grade->catatan_pbl }}</p>
                                            @endif
                                        @else
                                            <span class="text-gray-300 text-xs">—</span>
                                        @endif
                                    </td>

                                    {{-- Nilai Evaluasi --}}
                                    <td class="px-5 py-4 text-center">
                                        @if ($nilaiEvaluasi !== null)
                                            <span class="inline-flex items-center justify-center h-8 w-10 rounded-lg text-sm font-bold
                                                {{ $nilaiEvaluasi >= 80 ? 'bg-emerald-100 text-emerald-700' : ($nilaiEvaluasi >= 60 ? 'bg-amber-100 text-amber-700' : 'bg-red-100 text-red-700') }}">
                                                {{ $nilaiEvaluasi }}
                                            </span>
                                            <p class="text-xs text-gray-400 mt-1">
                                                {{ $testResult->score }}/{{ $testResult->total_questions }} benar
                                            </p>
                                        @else
                                            <span class="text-gray-300 text-xs">Belum test</span>
                                        @endif
                                    </td>

                                    {{-- Nilai Akhir --}}
                                    <td class="px-5 py-4 text-center">
                                        @if ($nilaiAkhir !== null)
                                            <span class="inline-flex items-center justify-center h-9 w-12 rounded-lg text-sm font-bold ring-2
                                                {{ $nilaiAkhir >= 80 ? 'bg-emerald-100 text-emerald-700 ring-emerald-200' : ($nilaiAkhir >= 60 ? 'bg-amber-100 text-amber-700 ring-amber-200' : 'bg-red-100 text-red-700 ring-red-200') }}">
                                                {{ $nilaiAkhir }}
                                            </span>
                                        @else
                                            <span class="text-gray-300 text-xs">—</span>
                                        @endif
                                    </td>

                                    {{-- Status Test --}}
                                    <td class="px-5 py-4 text-center">
                                        @if ($sudahTest)
                                            @if (!$isTestOpen)
                                                <span class="inline-flex items-center gap-1.5 text-xs font-medium px-2.5 py-1 rounded-full bg-gray-100 text-gray-600 border border-gray-200">
                                                    <i class="icon-lock" style="font-size: 14px;"></i> Sudah test
                                                </span>
                                            @else
                                                <span class="inline-flex items-center gap-1.5 text-xs font-medium px-2.5 py-1 rounded-full bg-blue-100 text-blue-700 border border-blue-200">
                                                    <i class="icon-unlock" style="font-size: 14px;"></i> Terbuka
                                                </span>
                                            @endif
                                        @else
                                            <span class="text-gray-300 text-xs">—</span>
                                        @endif
                                    </td>

                                    {{-- Aksi --}}
                                    <td class="px-5 py-4 text-center">
                                        <div class="flex items-center justify-center gap-2 flex-wrap">

                                            {{-- Tombol Beri/Edit Nilai PBL --}}
                                            @if ($nilaiPbl !== null)
                                                <button onclick="openPblModal({{ $siswa->id }}, '{{ addslashes($siswa->name) }}', {{ $nilaiPbl ?? 'null' }}, '{{ addslashes($grade?->catatan_pbl ?? '') }}')"
                                                    class="inline-flex items-center gap-1.5 text-xs font-semibold px-3 py-1.5 rounded-lg bg-indigo-100 text-indigo-700 hover:bg-indigo-200 transition">
                                                    <i class="icon-pencil" style="font-size: 13px;"></i> Edit PBL
                                                </button>
                                            @else
                                                <button onclick="openPblModal({{ $siswa->id }}, '{{ addslashes($siswa->name) }}', {{ $nilaiPbl ?? 'null' }}, '{{ addslashes($grade?->catatan_pbl ?? '') }}')"
                                                    class="inline-flex items-center gap-1.5 text-xs font-semibold px-3 py-1.5 rounded-lg bg-emerald-100 text-emerald-700 hover:bg-emerald-200 transition">
                                                    <i class="icon-plus-circle" style="font-size: 13px;"></i> Beri Nilai
                                                </button>
                                            @endif

                                            {{-- Tombol Buka/Kunci Test --}}
                                            @if ($sudahTest)
                                                <form action="{{ route('nilai.toggle.test', $siswa) }}" method="POST">
                                                    @csrf
                                                    @if (!$isTestOpen)
                                                        <button type="submit"
                                                            class="inline-flex items-center gap-1.5 text-xs font-semibold px-3 py-1.5 rounded-lg bg-blue-100 text-blue-700 hover:bg-blue-200 transition">
                                                            <i class="icon-unlock" style="font-size: 13px;"></i> Buka Ulang
                                                        </button>
                                                    @else
                                                        <button type="submit"
                                                            class="inline-flex items-center gap-1.5 text-xs font-semibold px-3 py-1.5 rounded-lg bg-rose-100 text-rose-700 hover:bg-rose-200 transition">
                                                            <i class="icon-lock" style="font-size: 13px;"></i> Kunci
                                                        </button>
                                                    @endif
                                                </form>
                                            @endif

                                        </div>
                                    </td>
                                </tr>
                            @endforeach

                            @if ($siswaList->isEmpty())
                                <tr>
                                    <td colspan="7" class="px-5 py-10 text-center text-gray-400 text-sm">
                                        <i class="icon-users" style="font-size: 24px; display:block; margin: 0 auto 8px;"></i>
                                        Belum ada siswa terdaftar.
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal Input Nilai PBL --}}
    <div id="modal-pbl" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/50"
        onclick="if(event.target === this) document.getElementById('modal-pbl').classList.add('hidden')">
        <div class="bg-white rounded-2xl shadow-xl w-full max-w-sm mx-4">
            <div class="p-6">
                <div class="flex items-center justify-between mb-1">
                    <h2 class="text-lg font-bold flex items-center gap-2">
                        <i class="icon-clipboard-edit" style="font-size: 20px;"></i> Nilai PBL
                    </h2>
                    <button type="button" onclick="document.getElementById('modal-pbl').classList.add('hidden')"
                        class="text-gray-400 hover:text-gray-600">
                        <i class="icon-x" style="font-size: 20px;"></i>
                    </button>
                </div>
                <p id="modal-pbl-name" class="text-sm text-gray-500 mb-4"></p>
                <form id="pbl-form" action="" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label class="text-sm font-medium text-gray-700 block mb-1">Nilai PBL (0 – 100)</label>
                        <input type="number" id="modal-pbl-val" name="nilai_pbl" min="0" max="100"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none"
                            placeholder="Contoh: 85">
                        <p class="text-xs text-gray-400 mt-1">Ambil nilai terbaik atau level tertinggi yang mampu dikerjakan siswa.</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-700 block mb-1">Catatan <span class="font-normal text-gray-400">(opsional)</span></label>
                        <input type="text" id="modal-pbl-catatan" name="catatan_pbl"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none"
                            placeholder="Contoh: Level Sulit — Algoritma Sorting">
                    </div>
                    <div class="flex gap-3 justify-end pt-1">
                        <button type="button" onclick="document.getElementById('modal-pbl').classList.add('hidden')"
                            class="px-4 py-2 text-sm font-medium text-gray-600 border border-gray-300 rounded-lg hover:bg-gray-50 transition">
                            Batal
                        </button>
                        <button type="submit"
                            class="inline-flex items-center gap-1.5 px-4 py-2 text-sm font-semibold text-white bg-indigo-600 rounded-lg hover:bg-indigo-700 transition">
                            <i class="icon-save" style="font-size: 16px;"></i> Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="//unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <script>
        function openPblModal(siswaId, name, nilaiPbl, catatan) {
            document.getElementById('modal-pbl-name').textContent = name;
            document.getElementById('modal-pbl-val').value = nilaiPbl !== null ? nilaiPbl : '';
            document.getElementById('modal-pbl-catatan').value = catatan;
            document.getElementById('pbl-form').action = '/nilai/pbl/' + siswaId;
            document.getElementById('modal-pbl').classList.remove('hidden');
        }
    </script>
@endpush