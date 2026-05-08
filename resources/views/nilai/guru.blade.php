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
                        <span class="font-semibold">Nilai PBL Final</span> = rata-rata nilai tertinggi tiap level (Mudah + Sedang + Sulit) ÷ 3.
                        <span class="font-semibold ml-2">Nilai Akhir</span> = (Nilai PBL Final + Nilai Evaluasi) ÷ 2.
                    </span>
                </p>
            </div>

            <div class="card overflow-hidden">
                <div class="p-5 border-b border-gray-100 flex items-center justify-between">
                    <h2 class="font-semibold text-gray-800">Daftar Nilai Siswa</h2>
                    <div class="flex items-center gap-3">
                        <span class="text-xs text-gray-400 flex items-center gap-1.5">
                            <i class="icon-users" style="font-size: 14px;"></i> {{ $siswaList->count() }} siswa
                        </span>
                        {{-- Tombol Export (Revisi 4) --}}
                        <a href="{{ route('nilai.export') }}"
                            class="inline-flex items-center gap-1.5 text-xs font-semibold px-3 py-1.5 rounded-lg bg-emerald-100 text-emerald-700 hover:bg-emerald-200 transition">
                            <i class="icon-download" style="font-size: 13px;"></i> Export CSV/Excel
                        </a>
                    </div>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-gray-50 border-b border-gray-100">
                            <tr>
                                <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500">No</th>
                                <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500">Nama Siswa</th>
                                <th class="text-center px-3 py-3 text-xs font-semibold text-gray-500">
                                    PBL Mudah<span class="block text-gray-400 font-normal">(tertinggi)</span>
                                </th>
                                <th class="text-center px-3 py-3 text-xs font-semibold text-gray-500">
                                    PBL Sedang<span class="block text-gray-400 font-normal">(tertinggi)</span>
                                </th>
                                <th class="text-center px-3 py-3 text-xs font-semibold text-gray-500">
                                    PBL Sulit<span class="block text-gray-400 font-normal">(tertinggi)</span>
                                </th>
                                <th class="text-center px-3 py-3 text-xs font-semibold text-gray-500">
                                    Nilai PBL Final<span class="block text-gray-400 font-normal">(rata-rata)</span>
                                </th>
                                <th class="text-center px-5 py-3 text-xs font-semibold text-gray-500">
                                    Nilai Evaluasi<span class="block text-gray-400 font-normal">(otomatis test)</span>
                                </th>
                                <th class="text-center px-5 py-3 text-xs font-semibold text-gray-500">
                                    Nilai Akhir<span class="block text-gray-400 font-normal">(rata-rata)</span>
                                </th>
                                <th class="text-center px-5 py-3 text-xs font-semibold text-gray-500">Status Test</th>
                                <th class="text-center px-5 py-3 text-xs font-semibold text-gray-500">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach ($siswaList as $i => $siswa)
                                @php
                                    $grade         = $gradeMap[$siswa->id] ?? null;
                                    $testResult    = $testMap[$siswa->id] ?? null;
                                    $nilaiEvaluasi = $testResult ? $testResult->persentase : null;
                                    $subsSiswa     = $submissionMap[$siswa->id] ?? collect();
                                    $isTestOpen    = $grade ? $grade->is_test_open : true;
                                    $sudahTest     = $testResult !== null;

                                    // Nilai tertinggi per level
                                    $nilaiMudah  = $subsSiswa->filter(fn($s) => $s->activity?->difficulty === 'Mudah')->max('nilai');
                                    $nilaiSedang = $subsSiswa->filter(fn($s) => $s->activity?->difficulty === 'Sedang')->max('nilai');
                                    $nilaiSulit  = $subsSiswa->filter(fn($s) => $s->activity?->difficulty === 'Sulit')->max('nilai');

                                    // Nilai PBL Final dari nilaiPblFinalMap (sudah dihitung di controller)
                                    $nilaiPblFinal = $nilaiPblFinalMap[$siswa->id] ?? null;

                                    $nilaiAkhir = $nilaiPblFinal !== null && $nilaiEvaluasi !== null
                                        ? round(($nilaiPblFinal + $nilaiEvaluasi) / 2)
                                        : null;
                                @endphp
                                <tr class="hover:bg-gray-50">
                                    <td class="px-5 py-4 text-gray-400">{{ $i + 1 }}</td>
                                    <td class="px-5 py-4">
                                        <p class="font-medium text-gray-800">{{ $siswa->name }}</p>
                                        <p class="text-xs text-gray-400 mt-0.5">{{ $subsSiswa->count() }} submission dinilai</p>
                                    </td>

                                    {{-- PBL per level --}}
                                    @foreach ([['val' => $nilaiMudah, 'color' => 'blue'], ['val' => $nilaiSedang, 'color' => 'amber'], ['val' => $nilaiSulit, 'color' => 'rose']] as $lvl)
                                        <td class="px-3 py-4 text-center">
                                            @if ($lvl['val'] !== null)
                                                <span class="inline-flex items-center justify-center h-8 w-10 rounded-lg text-sm font-bold
                                                    {{ $lvl['val'] >= 80 ? 'bg-emerald-100 text-emerald-700' : ($lvl['val'] >= 60 ? 'bg-amber-100 text-amber-700' : 'bg-red-100 text-red-700') }}">
                                                    {{ $lvl['val'] }}
                                                </span>
                                            @else
                                                <span class="text-gray-300 text-xs">—</span>
                                            @endif
                                        </td>
                                    @endforeach

                                    {{-- Nilai PBL Final --}}
                                    <td class="px-3 py-4 text-center">
                                        @if ($nilaiPblFinal !== null)
                                            <span class="inline-flex items-center justify-center h-8 w-10 rounded-lg text-sm font-bold ring-1
                                                {{ $nilaiPblFinal >= 80 ? 'bg-emerald-100 text-emerald-700 ring-emerald-300' : ($nilaiPblFinal >= 60 ? 'bg-amber-100 text-amber-700 ring-amber-300' : 'bg-red-100 text-red-700 ring-red-300') }}">
                                                {{ $nilaiPblFinal }}
                                            </span>
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
                                    <td colspan="10" class="px-5 py-10 text-center text-gray-400 text-sm">
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
@endsection

@push('scripts')
    <script src="//unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
@endpush