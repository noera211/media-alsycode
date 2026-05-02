@extends('layouts.app')
@section('title', 'Nilai & Evaluasi')

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
                class="px-4 py-2 rounded-lg text-sm font-medium transition-all">🏆 Nilai Siswa</button>
            <a href="{{ route('bank-soal.index') }}"
                class="px-4 py-2 rounded-lg text-sm font-medium transition-all text-gray-500 hover:text-gray-700 hover:bg-white">
                📋 Bank Soal Test
            </a>
        </div>

        {{-- Tab Nilai --}}
        <div x-show="tab==='nilai'">
            <div class="card p-4 mb-4 bg-indigo-50 border-indigo-100">
                <p class="text-xs text-indigo-700">
                    <span class="font-semibold">Nilai PBL</span> diinput manual oleh guru berdasarkan nilai terbaik / level tertinggi siswa.
                    <span class="font-semibold ml-2">Nilai Evaluasi</span> otomatis dari hasil test mandiri siswa.
                    <span class="font-semibold ml-2">Nilai Akhir</span> = rata-rata keduanya.
                </p>
            </div>

            <div class="card overflow-hidden">
                <div class="p-5 border-b border-gray-100 flex items-center justify-between">
                    <h2 class="font-semibold text-gray-800">Daftar Nilai Siswa</h2>
                    <span class="text-xs text-gray-400">{{ $siswaList->count() }} siswa</span>
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
                                    $grade = $gradeMap[$siswa->id] ?? null;
                                    $nilaiPbl = $grade?->nilai_pbl;
                                    $testResult = $testMap[$siswa->id] ?? null;
                                    $nilaiEvaluasi = $testResult ? $testResult->persentase : null;
                                    $nilaiAkhir =
                                        $nilaiPbl !== null && $nilaiEvaluasi !== null
                                            ? round(($nilaiPbl + $nilaiEvaluasi) / 2)
                                            : null;
                                    $subsSiswa = $submissionMap[$siswa->id] ?? collect();
                                    $isTestOpen = $grade === null || $grade->is_test_open;
                                    $sudahTest = $testResult !== null;
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
                                                <p class="text-xs text-gray-400 mt-1 max-w-[100px] mx-auto truncate">
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
                                            <span class="inline-flex items-center gap-1 text-xs font-medium px-2 py-1 rounded-full bg-emerald-100 text-emerald-700">
                                                ✓ Sudah test
                                            </span>
                                        @elseif(!$isTestOpen)
                                            <span class="inline-flex items-center gap-1 text-xs font-medium px-2 py-1 rounded-full bg-gray-100 text-gray-500">
                                                🔒 Dikunci
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1 text-xs font-medium px-2 py-1 rounded-full bg-blue-100 text-blue-700">
                                                Terbuka
                                            </span>
                                        @endif
                                    </td>

                                    {{-- Aksi --}}
                                    <td class="px-5 py-4 text-center">
                                        <div class="flex items-center justify-center gap-2">
                                            <button onclick="openPblModal({{ $siswa->id }}, '{{ addslashes($siswa->name) }}', {{ $nilaiPbl ?? 'null' }}, '{{ addslashes($grade?->catatan_pbl ?? '') }}')"
                                                class="text-xs btn-outline px-3 py-1.5">
                                                {{ $nilaiPbl !== null ? '✏ Edit PBL' : '+ Beri Nilai PBL' }}
                                            </button>
                                            
                                            <form action="{{ route('nilai.toggle.test', $siswa) }}" method="POST">
                                                @csrf
                                                @if ($sudahTest)
                                                    <button type="submit" class="text-xs btn-outline px-3 py-1.5 border-blue-300 text-blue-600 hover:bg-blue-50">
                                                        🔓 Buka Ulang
                                                    </button>
                                                @elseif(!$isTestOpen)
                                                    <button type="submit" class="text-xs btn-outline px-3 py-1.5 border-blue-300 text-blue-600 hover:bg-blue-50">
                                                        🔓 Buka Test
                                                    </button>
                                                @else
                                                    <button type="submit" class="text-xs btn-outline px-3 py-1.5 border-red-300 text-red-500 hover:bg-red-50">
                                                        🔒 Kunci
                                                    </button>
                                                @endif
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach

                            @if ($siswaList->isEmpty())
                                <tr>
                                    <td colspan="7" class="px-5 py-10 text-center text-gray-400 text-sm">Belum ada siswa terdaftar.</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        {{-- TAB BANK SOAL TELAH DIHAPUS --}}
    </div>

    {{-- Modal Input Nilai PBL --}}
    <div id="modal-pbl" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/50"
        onclick="if(event.target === this) document.getElementById('modal-pbl').classList.add('hidden')">
        <div class="bg-white rounded-2xl shadow-xl w-full max-w-sm mx-4">
            <div class="p-6">
                <div class="flex items-center justify-between mb-1">
                    <h2 class="text-lg font-bold">Nilai PBL</h2>
                    <button type="button" onclick="document.getElementById('modal-pbl').classList.add('hidden')"
                        class="text-gray-400 hover:text-gray-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
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
                        <button type="button" onclick="document.getElementById('modal-pbl').classList.add('hidden')" class="btn-outline">Batal</button>
                        <button type="submit" class="btn-primary">Simpan</button>
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