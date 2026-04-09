<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ALSYCODE – Platform Belajar Algoritma</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap');
        body { font-family: 'Inter', sans-serif; }
        .hero-bg {
            background: linear-gradient(135deg, #eef2ff 0%, #e0e7ff 50%, #ede9fe 100%);
        }
        .text-gradient {
            background: linear-gradient(135deg, #4f46e5, #7c3aed);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        .btn-primary {
            background: linear-gradient(135deg, #4f46e5, #6d28d9);
            transition: all 0.2s;
        }
        .btn-primary:hover {
            background: linear-gradient(135deg, #4338ca, #5b21b6);
            transform: translateY(-1px);
            box-shadow: 0 10px 25px rgba(79, 70, 229, 0.35);
        }
        .feature-card {
            transition: all 0.2s;
        }
        .feature-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 30px rgba(0,0,0,0.08);
        }
    </style>
</head>
<body class="bg-gray-50">

    {{-- ── Navbar ── --}}
    <nav class="bg-white/80 backdrop-blur-sm border-b border-gray-100 sticky top-0 z-50">
        <div class="max-w-6xl mx-auto px-6 py-4 flex items-center justify-between">
            <div class="flex items-center gap-2.5">
                <div class="h-8 w-8 bg-indigo-600 rounded-lg flex items-center justify-center">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/>
                    </svg>
                </div>
                <span class="font-bold text-gray-900 text-lg tracking-tight">ALSYCODE</span>
            </div>
            <a href="{{ route('login') }}"
               class="btn-primary text-white font-semibold px-5 py-2.5 rounded-xl text-sm flex items-center gap-2">
                Masuk
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                </svg>
            </a>
        </div>
    </nav>

    {{-- ── Hero ── --}}
    <section class="hero-bg min-h-[88vh] flex items-center justify-center px-6 py-20">
        <div class="text-center max-w-3xl mx-auto">
            {{-- Badge --}}
            <div class="inline-flex items-center gap-2 bg-white/70 backdrop-blur-sm border border-indigo-100 text-indigo-700 text-sm font-medium px-4 py-2 rounded-full mb-8 shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                </svg>
                Informatika – Kelas X SMK
            </div>

            {{-- Heading --}}
            <h1 class="text-5xl md:text-6xl font-black text-gray-900 leading-tight mb-6">
                Belajar <span class="text-gradient">Algoritma<br>Pemrograman</span> dengan<br>Pendekatan PBL
            </h1>

            {{-- Subtitle --}}
            <p class="text-lg text-gray-500 leading-relaxed mb-10 max-w-2xl mx-auto">
                Media pembelajaran interaktif yang membantu siswa memahami algoritma melalui
                pemecahan masalah nyata, dilengkapi mini compiler untuk simulasi kode.
            </p>

            {{-- CTA Button --}}
            <a href="{{ route('login') }}"
               class="btn-primary inline-flex items-center gap-3 text-white font-semibold px-8 py-4 rounded-2xl text-base shadow-lg">
                Mulai Belajar
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                </svg>
            </a>
        </div>
    </section>

    {{-- ── Fitur Utama ── --}}
    <section class="bg-gray-50 py-20 px-6">
        <div class="max-w-5xl mx-auto">
            <div class="text-center mb-14">
                <h2 class="text-3xl font-bold text-gray-900 mb-3">Fitur Utama</h2>
                <p class="text-gray-500">Dirancang khusus untuk mendukung pembelajaran berbasis masalah yang efektif</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">

                {{-- Materi Interaktif --}}
                <div class="feature-card bg-white rounded-2xl border border-gray-100 p-6 shadow-sm">
                    <div class="h-12 w-12 bg-indigo-100 rounded-xl flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                        </svg>
                    </div>
                    <h3 class="font-bold text-gray-800 mb-2">Materi Interaktif</h3>
                    <p class="text-sm text-gray-500 leading-relaxed">Akses materi algoritma pemrograman lengkap dengan teks, video, dan referensi.</p>
                </div>

                {{-- Problem Based Learning --}}
                <div class="feature-card bg-white rounded-2xl border border-gray-100 p-6 shadow-sm">
                    <div class="h-12 w-12 bg-indigo-100 rounded-xl flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                        </svg>
                    </div>
                    <h3 class="font-bold text-gray-800 mb-2">Problem Based Learning</h3>
                    <p class="text-sm text-gray-500 leading-relaxed">Belajar melalui masalah kontekstual yang melatih kemampuan berpikir kritis.</p>
                </div>

                {{-- Mini Compiler --}}
                <div class="feature-card bg-white rounded-2xl border border-gray-100 p-6 shadow-sm">
                    <div class="h-12 w-12 bg-indigo-100 rounded-xl flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/>
                        </svg>
                    </div>
                    <h3 class="font-bold text-gray-800 mb-2">Mini Compiler</h3>
                    <p class="text-sm text-gray-500 leading-relaxed">Tulis dan jalankan kode langsung di browser untuk simulasi algoritma.</p>
                </div>

                {{-- Evaluasi Terpadu --}}
                <div class="feature-card bg-white rounded-2xl border border-gray-100 p-6 shadow-sm">
                    <div class="h-12 w-12 bg-indigo-100 rounded-xl flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                        </svg>
                    </div>
                    <h3 class="font-bold text-gray-800 mb-2">Evaluasi Terpadu</h3>
                    <p class="text-sm text-gray-500 leading-relaxed">Kumpulkan solusi dan dapatkan feedback dari guru secara langsung.</p>
                </div>

            </div>
        </div>
    </section>

    {{-- ── Footer ── --}}
    <footer class="bg-gray-50 border-t border-gray-100 py-8 px-6">
        <div class="max-w-5xl mx-auto text-center">
            <p class="text-sm text-gray-400">© {{ date('Y') }} ALSYCODE — Media Pembelajaran Algoritma Pemrograman Berbasis PBL</p>
        </div>
    </footer>

</body>
</html>