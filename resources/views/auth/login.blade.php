<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login – ALSYCODE SMK Antartika</title>
    
    {{-- Update: CDN Tailwind CSS Versi 4 agar support class canonical baru --}}
    <script src="https://unpkg.com/@tailwindcss/browser@4"></script>
    
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        
        /* Animasi mengambang untuk ilustrasi */
        @keyframes float {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-15px); }
            100% { transform: translateY(0px); }
        }
        .animate-float { animation: float 6s ease-in-out infinite; }

        /* Animasi Bintang Galaksi CSS Murni */
        .stars-bg {
            background-image: 
                radial-gradient(2px 2px at 20px 30px, #ffffff, rgba(0,0,0,0)),
                radial-gradient(2px 2px at 40px 70px, #ffffff, rgba(0,0,0,0)),
                radial-gradient(2px 2px at 50px 160px, #ffffff, rgba(0,0,0,0)),
                radial-gradient(2px 2px at 90px 40px, #ffffff, rgba(0,0,0,0)),
                radial-gradient(2px 2px at 130px 80px, #ffffff, rgba(0,0,0,0)),
                radial-gradient(2px 2px at 160px 120px, #ffffff, rgba(0,0,0,0));
            background-repeat: repeat;
            background-size: 200px 200px;
            animation: twinkle 5s infinite;
            opacity: 0.6;
        }

        @keyframes twinkle {
            0%, 100% { opacity: 0.3; transform: scale(1); }
            50% { opacity: 0.8; transform: scale(1.02); }
        }
    </style>
</head>

{{-- BACKGROUND GALAKSI (Warna Gelap + Efek Bintang) --}}
<body class="min-h-screen flex items-center justify-center p-4 md:p-8 relative overflow-hidden bg-[#0a0a1a]">
    
    {{-- Layer Bintang --}}
    <div class="absolute inset-0 z-0 stars-bg"></div>

    {{-- Layer Nebula / Cahaya Galaksi (Biru, Ungu, Cyan) --}}
    <div class="absolute top-[-10%] left-[-10%] w-[50vw] h-[50vw] bg-indigo-600/30 rounded-full mix-blend-screen filter blur-[120px] animate-pulse"></div>
    <div class="absolute bottom-[-10%] right-[-10%] w-[45vw] h-[45vw] bg-fuchsia-600/20 rounded-full mix-blend-screen filter blur-[120px] animate-pulse" style="animation-delay: 2s;"></div>
    <div class="absolute top-[20%] right-[20%] w-[35vw] h-[35vw] bg-cyan-500/20 rounded-full mix-blend-screen filter blur-[100px]"></div>

    {{-- KARTU UTAMA (PURE GLASSMORPHISM) - Menggunakan max-w-275 dan bg-white/3 --}}
    <div class="relative z-10 flex w-full max-w-275 bg-white/3 backdrop-blur-xl rounded-[2.5rem] shadow-[0_8px_32px_0_rgba(0,0,0,0.5)] border border-white/10 overflow-hidden">
        
        {{-- SISI KIRI (Ilustrasi - Hidden di Mobile) - Menggunakan bg-linear-to-br dan from-white/2 --}}
        <div class="hidden lg:flex lg:w-1/2 border-r border-white/10 flex-col justify-between p-12 relative bg-linear-to-br from-white/2 to-transparent">
            
            {{-- Logo - Menggunakan inline-flex (Konflik dihapus) --}}
            <div class="inline-flex items-center gap-5 z-20 bg-white/90 backdrop-blur-md py-3 px-5 rounded-2xl shadow-[0_0_15px_rgba(255,255,255,0.1)] border border-white/20 w-max">
                <img src="{{ asset('images/logo (2).png') }}" alt="ALSYCODE" class="h-7 w-auto">
                <div class="h-6 w-px bg-gray-300"></div>
                <div class="flex items-center gap-3">
                    <img src="{{ asset('images/antartika.png') }}" alt="SMK Antartika" class="h-9 w-auto">
                    <span class="text-xs font-extrabold text-gray-900 leading-tight uppercase tracking-wide">SMK Antartika<br>Surabaya</span>
                </div>
            </div>

            {{-- Ilustrasi --}}
            <div class="flex-1 flex items-center justify-center mt-8 mb-8 z-10">
                <div class="relative">
                    {{-- Glow effect di belakang ilustrasi --}}
                    <div class="absolute inset-0 bg-blue-500/30 blur-[60px] rounded-full"></div>
                    
                    {{-- Ilustrasi PNG Transparan - Menggunakan max-w-88 --}}
                    <img src="{{ asset('images/login.png') }}" 
                         alt="Ilustrasi Belajar" 
                         class="w-full max-w-88 object-contain drop-shadow-[0_0_25px_rgba(255,255,255,0.1)] animate-float relative z-10"
                         onerror="this.src='https://cdn-icons-png.flaticon.com/512/2941/2941584.png'"> 
                </div>
            </div>

            {{-- Teks Deksripsi - Menggunakan bg-white/5 --}}
            <div class="z-10 bg-white/5 backdrop-blur-md p-6 rounded-3xl border border-white/10 shadow-lg">
                <h2 class="text-[1.35rem] font-extrabold text-white mb-2 tracking-wide">Algoritma Learning System</h2>
                <p class="text-gray-300 text-sm leading-relaxed font-medium">
                    Tingkatkan logika pemrograman dengan tantangan algoritma berbasis Problem Based Learning di platform ALSYCODE.
                </p>
            </div>
        </div>

        {{-- SISI KANAN: Form Login --}}
        <div class="w-full lg:w-1/2 flex flex-col justify-center p-8 md:p-14 lg:px-20 relative bg-transparent">
            
            <div class="mb-10 text-center lg:text-left">
                {{-- Logo Mobile - Menggunakan inline-flex (Konflik dihapus) --}}
                <div class="lg:hidden inline-flex items-center justify-center gap-4 mb-8 bg-white/90 py-3 px-5 rounded-2xl shadow-lg mx-auto border border-white/20">
                    <img src="{{ asset('images/logo (2).png') }}" alt="ALSYCODE Logo" class="h-6">
                    <div class="h-5 w-px bg-gray-300"></div>
                    <img src="{{ asset('images/antartika.png') }}" alt="SMK Antartika" class="h-8">
                </div>

                {{-- Menggunakan bg-linear-to-r --}}
                <h1 class="text-3xl lg:text-[32px] font-extrabold text-white leading-[1.2] mb-3">
                    Mulai Pembelajaran Algoritma <br>
                    <span class="text-transparent bg-clip-text bg-linear-to-r from-cyan-400 to-blue-500">yang menyenangkan.</span>
                </h1>
                <p class="text-gray-400 font-medium text-[15px]">Masuk ke akun Anda untuk melanjutkan.</p>
            </div>

            @if ($errors->any())
                <div class="mb-6 p-4 bg-red-500/10 border border-red-500/30 rounded-2xl text-sm text-red-400 font-semibold flex items-start gap-3 shadow-sm">
                    <svg class="w-5 h-5 text-red-400 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <ul class="list-none space-y-1">
                        @foreach ($errors->all() as $err)
                            <li>{{ $err }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('login.post') }}" method="POST" class="space-y-5">
                @csrf
                
                {{-- Input 1: Menggunakan bg-white/5 dan focus:bg-white/10 --}}
                <div class="space-y-2">
                    <label class="text-sm font-semibold text-gray-300">Email Address <span class="text-red-500">*</span></label>
                    <input type="email" name="email" value="{{ old('email') }}" required
                        class="w-full bg-white/5 border border-white/10 rounded-2xl px-5 py-3.5 text-[15px] text-white transition-all focus:bg-white/10 focus:ring-2 focus:ring-cyan-500/50 focus:border-cyan-400 outline-none font-medium placeholder:text-gray-500"
                        placeholder="your email address">
                </div>

                {{-- Input 2: Menggunakan bg-white/5 dan focus:bg-white/10 --}}
                <div class="space-y-2">
                    <label class="text-sm font-semibold text-gray-300">Password <span class="text-red-500">*</span></label>
                    <input type="password" name="password" required
                        class="w-full bg-white/5 border border-white/10 rounded-2xl px-5 py-3.5 text-[15px] text-white transition-all focus:bg-white/10 focus:ring-2 focus:ring-cyan-500/50 focus:border-cyan-400 outline-none font-medium placeholder:text-gray-500"
                        placeholder="••••••••">
                </div>

                <div class="flex items-center pt-1">
                    <label class="flex items-center cursor-pointer group">
                        <input type="checkbox" name="remember" id="remember" class="w-5 h-5 rounded-md border-white/20 bg-white/10 text-cyan-500 focus:ring-cyan-500 focus:ring-offset-gray-900 transition-colors cursor-pointer">
                        <span class="ml-3 text-[14px] font-medium text-gray-400 group-hover:text-white transition-colors">Remember me</span>
                    </label>
                </div>

                <div class="pt-5">
                    {{-- Button: Menggunakan bg-linear-to-r --}}
                    <button type="submit"
                        class="w-full bg-linear-to-r from-blue-600 to-cyan-500 hover:from-blue-500 hover:to-cyan-400 text-white font-bold py-4 rounded-2xl text-[15px] transition-all transform active:scale-[0.98] shadow-[0_0_20px_rgba(6,182,212,0.4)] border border-cyan-400/50">
                        Masuk Sekarang
                    </button>
                </div>
            </form>

            <div class="mt-8 text-center">
                <a href="{{ route('landing') }}" class="inline-flex items-center justify-center gap-2 text-sm font-medium text-gray-500 hover:text-cyan-400 transition-colors group">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 transform group-hover:-translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Kembali ke Beranda
                </a>
            </div>

        </div>
    </div>
    
</body>

</html>