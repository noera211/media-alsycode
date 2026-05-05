<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ALSYCODE – Platform Belajar Algoritma</title>
    
    {{-- Menggunakan Tailwind CSS v4 --}}
    <script src="https://unpkg.com/@tailwindcss/browser@4"></script>
    
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .clip-path-custom { clip-path: polygon(0 0, 100% 0, 100% 85%, 0% 100%); }
    </style>
</head>
<body class="bg-white text-slate-800 overflow-x-hidden">

    {{-- ── 1. NAVBAR ── --}}
    <nav class="w-full bg-white py-4 px-6 lg:px-20 flex items-center justify-between sticky top-0 z-50 shadow-sm">
        <div class="flex items-center gap-3">
            <img src="{{ asset('images/logo (2).png') }}" alt="ALSYCODE" class="h-8 lg:h-10 w-auto">
            <div class="h-6 w-px bg-slate-300 hidden md:block"></div>
            <img src="{{ asset('images/antartika.png') }}" alt="SMK Antartika" class="h-8 lg:h-10 w-auto hidden md:block">
        </div>

        <div class="hidden lg:flex items-center gap-8 text-sm font-semibold text-slate-600">
            <a href="#" class="hover:text-indigo-600 transition-colors">Beranda</a>
            <a href="#fitur" class="hover:text-indigo-600 transition-colors">Fitur</a>
            <a href="#materi" class="hover:text-indigo-600 transition-colors">Materi</a>
            <a href="#testimoni" class="hover:text-indigo-600 transition-colors">Testimoni</a>
        </div>

        <div class="flex items-center gap-2 lg:gap-4">
            <a href="{{ route('login') }}" class="hidden md:block text-slate-700 font-bold px-4 py-2 hover:text-indigo-600 transition-colors">Masuk</a>
            <a href="{{ route('login') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold px-5 py-2 lg:px-6 lg:py-2.5 rounded-full text-sm transition-all shadow-md shadow-indigo-200">
                Mulai Belajar
            </a>
            
            <button class="lg:hidden p-2 text-slate-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
            </button>
        </div>
    </nav>

    {{-- ── 2. HERO SECTION (Persis seperti referensi) ── --}}
    <section class="relative pt-10 pb-20 px-6 lg:px-20 flex flex-col-reverse lg:flex-row items-center gap-12 lg:gap-8 max-w-7xl mx-auto">
        <div class="w-full lg:w-1/2 text-center lg:text-left mt-8 lg:mt-0">
            <h1 class="text-4xl md:text-5xl lg:text-6xl font-black text-slate-900 leading-tight mb-6">
                Kembangkan <span class="text-indigo-600">logikamu</span><br>
                dengan cara yang<br>baru & unik
            </h1>
            <p class="text-slate-500 font-medium text-base md:text-lg mb-8 max-w-lg mx-auto lg:mx-0">
                Platform pembelajaran interaktif berbasis Problem Based Learning untuk mengasah kemampuan algoritmamu. Ketik kodemu, pecahkan masalahnya.
            </p>
            <div class="flex flex-col sm:flex-row items-center justify-center lg:justify-start gap-4">
                <a href="{{ route('login') }}" class="w-full sm:w-auto bg-indigo-600 hover:bg-indigo-700 text-white font-bold px-8 py-3.5 rounded-full shadow-lg shadow-indigo-200 transition-all text-center">
                    Masuk Sekarang
                </a>
                <a href="#fitur" class="w-full sm:w-auto flex items-center justify-center gap-2 text-slate-700 font-bold px-8 py-3.5 rounded-full border border-slate-200 hover:bg-slate-50 transition-all">
                    <svg class="w-5 h-5 text-indigo-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z" clip-rule="evenodd"/></svg>
                    Cara Kerja
                </a>
            </div>
        </div>

        <div class="w-full lg:w-1/2 relative flex justify-center">
            <div class="absolute inset-0 bg-indigo-200/50 rounded-full filter blur-3xl -z-10 w-80 h-80 m-auto"></div>
            
            <div class="relative w-full max-w-md">
                <img src="{{ asset('images/login.png') }}" alt="Student" class="w-full h-auto relative z-10" onerror="this.src='https://cdn-icons-png.flaticon.com/512/3413/3413535.png'">
                
                <div class="absolute top-10 -left-4 md:-left-10 bg-white/90 backdrop-blur-sm p-3 rounded-xl shadow-lg border border-slate-100 flex items-center gap-3 z-20 animate-[bounce_3s_infinite]">
                    <div class="bg-blue-100 p-2 rounded-lg text-blue-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/></svg>
                    </div>
                    <div>
                        <p class="text-xs text-slate-500 font-bold">Mini Compiler</p>
                        <p class="text-[10px] text-slate-400">Tersedia</p>
                    </div>
                </div>

                <div class="absolute bottom-20 -right-4 md:-right-10 bg-white/90 backdrop-blur-sm p-3 rounded-xl shadow-lg border border-slate-100 flex items-center gap-3 z-20 animate-[bounce_4s_infinite]">
                    <div class="bg-purple-100 p-2 rounded-lg text-purple-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/></svg>
                    </div>
                    <div>
                        <p class="text-xs text-slate-500 font-bold">Studi Kasus</p>
                        <p class="text-[10px] text-slate-400">PBL Method</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ── 3. LOGO BANNER (Gradient) ── --}}
    <section class="bg-linear-to-r from-cyan-500 via-indigo-500 to-purple-600 py-6 px-6 relative z-10">
        <div class="max-w-7xl mx-auto flex flex-wrap justify-center items-center gap-8 md:gap-16 opacity-90">
            <span class="text-white font-bold text-lg flex items-center gap-2"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/></svg> C++</span>
            <span class="text-white font-bold text-lg flex items-center gap-2"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l3 3-3 3m5 0h3M5 20h14a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg> Python</span>
            <span class="text-white font-bold text-lg flex items-center gap-2"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/></svg> Algoritma</span>
            <span class="text-white font-bold text-lg flex items-center gap-2"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"/></svg> Flowchart</span>
        </div>
    </section>

    {{-- ── 4. BENEFITS SECTION ── --}}
    <section id="fitur" class="py-20 px-6 lg:px-20 max-w-7xl mx-auto">
        <div class="flex flex-col lg:flex-row items-center gap-12 lg:gap-20">
            <div class="w-full lg:w-1/2 grid grid-cols-2 gap-4">
                <div class="bg-cyan-100 rounded-3xl aspect-square overflow-hidden"><img src="https://images.unsplash.com/photo-1522202176988-66273c2fd55f?q=80&w=500&auto=format&fit=crop" class="w-full h-full object-cover mix-blend-multiply" alt="Belajar 1"></div>
                <div class="bg-indigo-100 rounded-3xl aspect-square overflow-hidden mt-8"><img src="https://images.unsplash.com/photo-1517694712202-14dd9538aa97?q=80&w=500&auto=format&fit=crop" class="w-full h-full object-cover mix-blend-multiply" alt="Belajar 2"></div>
                <div class="bg-purple-100 rounded-3xl aspect-square overflow-hidden -mt-8"><img src="https://images.unsplash.com/photo-1498050108023-c5249f4df085?q=80&w=500&auto=format&fit=crop" class="w-full h-full object-cover mix-blend-multiply" alt="Belajar 3"></div>
                <div class="bg-blue-100 rounded-3xl aspect-square overflow-hidden"><img src="https://images.unsplash.com/photo-1531482615713-2afd69097998?q=80&w=500&auto=format&fit=crop" class="w-full h-full object-cover mix-blend-multiply" alt="Belajar 4"></div>
            </div>

            <div class="w-full lg:w-1/2">
                <h2 class="text-3xl lg:text-4xl font-bold text-slate-900 mb-2">Manfaat Dari <br>Platform <span class="text-indigo-600">ALSYCODE</span></h2>
                <p class="text-slate-500 mb-8 font-medium">Metode belajar yang disesuaikan untuk siswa SMK guna memperkuat nalar dan logika pemrograman secara efektif.</p>

                <div class="space-y-6">
                    <div class="flex gap-4 items-start">
                        <div class="w-12 h-12 rounded-full bg-cyan-100 text-cyan-600 flex items-center justify-center shrink-0">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                        </div>
                        <div>
                            <h4 class="text-lg font-bold text-slate-900">Materi Terstruktur</h4>
                            <p class="text-slate-500 text-sm mt-1">Akses berbagai modul algoritma pemrograman yang disusun rapi dan mudah dipahami.</p>
                        </div>
                    </div>
                    <div class="flex gap-4 items-start">
                        <div class="w-12 h-12 rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center shrink-0">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        </div>
                        <div>
                            <h4 class="text-lg font-bold text-slate-900">Problem Based Learning</h4>
                            <p class="text-slate-500 text-sm mt-1">Belajar dengan memecahkan studi kasus nyata untuk melatih kemampuan nalar analitis.</p>
                        </div>
                    </div>
                    <div class="flex gap-4 items-start">
                        <div class="w-12 h-12 rounded-full bg-purple-100 text-purple-600 flex items-center justify-center shrink-0">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/></svg>
                        </div>
                        <div>
                            <h4 class="text-lg font-bold text-slate-900">Live Coding Browser</h4>
                            <p class="text-slate-500 text-sm mt-1">Tidak perlu install aplikasi tambahan, tulis dan jalankan kodemu langsung di dalam platform.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ── 5. COURSES/MATERI SECTION ── --}}
    <section id="materi" class="bg-indigo-50 py-20 px-6 lg:px-20">
        <div class="max-w-7xl mx-auto">
            <div class="text-center mb-12">
                <h2 class="text-3xl lg:text-4xl font-bold text-slate-900 mb-4">Materi <span class="text-indigo-600">Populer</span></h2>
                <p class="text-slate-500 max-w-2xl mx-auto">Jelajahi berbagai materi algoritma dasar hingga menengah yang disiapkan khusus untuk meningkatkan kemampuan logika pemogramanmu.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                
                <div class="bg-white rounded-3xl p-4 shadow-sm hover:shadow-xl transition-shadow border border-slate-100">
                    <div class="bg-slate-100 h-48 rounded-2xl mb-4 overflow-hidden relative">
                        <img src="https://images.unsplash.com/photo-1555066931-4365d14bab8c?q=80&w=500&auto=format&fit=crop" class="w-full h-full object-cover" alt="Course 1">
                        <span class="absolute top-3 right-3 bg-white/90 text-indigo-600 text-xs font-bold px-3 py-1 rounded-full">Pemula</span>
                    </div>
                    <div class="px-2 pb-4">
                        <h3 class="font-bold text-lg text-slate-900 mb-2">Pengantar Algoritma & Flowchart</h3>
                        <p class="text-slate-500 text-sm mb-4 line-clamp-2">Pahami konsep dasar aliran data dan bagaimana menerjemahkan logika menjadi flowchart.</p>
                        <div class="flex items-center justify-between border-t border-slate-100 pt-4">
                            <span class="text-sm font-semibold text-slate-700 flex items-center gap-1"><svg class="w-4 h-4 text-indigo-500" fill="currentColor" viewBox="0 0 20 20"><path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"/></svg> Kelas X</span>
                            <a href="{{ route('login') }}" class="text-indigo-600 text-sm font-bold hover:underline">Mulai Belajar →</a>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-3xl p-4 shadow-sm hover:shadow-xl transition-shadow border border-slate-100">
                    <div class="bg-slate-100 h-48 rounded-2xl mb-4 overflow-hidden relative">
                        <img src="https://images.unsplash.com/photo-1542831371-29b0f74f9713?q=80&w=500&auto=format&fit=crop" class="w-full h-full object-cover" alt="Course 2">
                        <span class="absolute top-3 right-3 bg-white/90 text-indigo-600 text-xs font-bold px-3 py-1 rounded-full">Menengah</span>
                    </div>
                    <div class="px-2 pb-4">
                        <h3 class="font-bold text-lg text-slate-900 mb-2">Struktur Kontrol & Perulangan</h3>
                        <p class="text-slate-500 text-sm mb-4 line-clamp-2">Pelajari cara kerja If-Else, Switch Case, For, dan While dalam studi kasus nyata.</p>
                        <div class="flex items-center justify-between border-t border-slate-100 pt-4">
                            <span class="text-sm font-semibold text-slate-700 flex items-center gap-1"><svg class="w-4 h-4 text-indigo-500" fill="currentColor" viewBox="0 0 20 20"><path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"/></svg> Kelas X</span>
                            <a href="{{ route('login') }}" class="text-indigo-600 text-sm font-bold hover:underline">Mulai Belajar →</a>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-3xl p-4 shadow-sm hover:shadow-xl transition-shadow border border-slate-100">
                    <div class="bg-slate-100 h-48 rounded-2xl mb-4 overflow-hidden relative">
                        <img src="https://images.unsplash.com/photo-1504639725590-34d0984388bd?q=80&w=500&auto=format&fit=crop" class="w-full h-full object-cover" alt="Course 3">
                        <span class="absolute top-3 right-3 bg-white/90 text-indigo-600 text-xs font-bold px-3 py-1 rounded-full">Lanjutan</span>
                    </div>
                    <div class="px-2 pb-4">
                        <h3 class="font-bold text-lg text-slate-900 mb-2">Studi Kasus: Mini Project</h3>
                        <p class="text-slate-500 text-sm mb-4 line-clamp-2">Terapkan semua logika yang telah dipelajari untuk menyelesaikan studi kasus dari guru.</p>
                        <div class="flex items-center justify-between border-t border-slate-100 pt-4">
                            <span class="text-sm font-semibold text-slate-700 flex items-center gap-1"><svg class="w-4 h-4 text-indigo-500" fill="currentColor" viewBox="0 0 20 20"><path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"/></svg> Kelas X</span>
                            <a href="{{ route('login') }}" class="text-indigo-600 text-sm font-bold hover:underline">Mulai Belajar →</a>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    {{-- ── 6. CTA / BERGABUNG BERSAMA KAMI ── --}}
    <section class="py-20 px-6 lg:px-20 max-w-7xl mx-auto">
        <div class="flex flex-col lg:flex-row items-center gap-12 lg:gap-20">
            <div class="w-full lg:w-1/2">
                <h2 class="text-3xl lg:text-4xl font-bold text-slate-900 mb-4">Siap untuk menjadi <br><span class="text-indigo-600">Programmer Handal?</span></h2>
                <p class="text-slate-500 mb-8 font-medium">Bergabunglah di platform ALSYCODE dan rasakan pengalaman belajar koding yang terintegrasi langsung dengan kurikulum sekolahmu.</p>
                
                <ul class="space-y-3 mb-8">
                    <li class="flex items-center gap-3 text-slate-700 font-semibold">
                        <svg class="w-5 h-5 text-indigo-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                        Akses modul 24/7 di mana saja
                    </li>
                    <li class="flex items-center gap-3 text-slate-700 font-semibold">
                        <svg class="w-5 h-5 text-indigo-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                        Tugas dinilai secara real-time
                    </li>
                    <li class="flex items-center gap-3 text-slate-700 font-semibold">
                        <svg class="w-5 h-5 text-indigo-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                        Bimbingan langsung oleh Guru
                    </li>
                </ul>

                <a href="{{ route('login') }}" class="inline-block bg-indigo-600 hover:bg-indigo-700 text-white font-bold px-8 py-4 rounded-full shadow-lg shadow-indigo-200 transition-all">
                    Buat Akun / Masuk
                </a>
            </div>

            <div class="w-full lg:w-1/2 flex justify-center lg:justify-end">
                <div class="w-full max-w-sm rounded-[3rem] rounded-br-none overflow-hidden border-8 border-indigo-50 shadow-2xl relative">
                    <img src="https://images.unsplash.com/photo-1525130413817-d45c1d127c42?q=80&w=600&auto=format&fit=crop" class="w-full h-auto object-cover" alt="Student Typing">
                    <div class="absolute inset-0 bg-linear-to-t from-indigo-900/60 to-transparent"></div>
                </div>
            </div>
        </div>
    </section>

    {{-- ── 7. FOOTER ── --}}
    <footer class="bg-slate-900 text-slate-300 py-12 px-6 lg:px-20 mt-10">
        <div class="max-w-7xl mx-auto flex flex-col md:flex-row justify-between items-center gap-6">
            <div class="flex items-center gap-3">
                <img src="{{ asset('images/logo (2).png') }}" alt="ALSYCODE" class="h-8 brightness-0 invert opacity-80">
                <div class="h-6 w-px bg-slate-700"></div>
                <img src="{{ asset('images/antartika.png') }}" alt="SMK Antartika" class="h-8 brightness-0 invert opacity-80">
            </div>
            
            <p class="text-sm font-medium text-slate-500 text-center md:text-right">
                © {{ date('Y') }} ALSYCODE - SMK Antartika Surabaya.<br class="block sm:hidden"> Hak Cipta Dilindungi.
            </p>
        </div>
    </footer>

</body>
</html>