<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login – ALSYCODE</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-50 min-h-screen flex items-center justify-center px-4">
    <div class="w-full max-w-md">
        {{-- Header --}}
        <div class="text-center mb-8">
            <div class="flex justify-center">
                <img src="{{ asset('images/logo (2).png') }}" alt="ALSYCODE Logo" class="h-9 w-auto">
            </div>
            <h1 class="text-2xl font-bold text-gray-900">Masuk ke ALSYCODE</h1>
            <p class="text-sm text-gray-500 mt-1">Platform Pembelajaran Algoritma Pemrograman</p>
        </div>

        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-8">
            {{-- Errors --}}
            @if ($errors->any())
                <div class="mb-5 p-3 bg-red-50 border border-red-200 rounded-lg text-sm text-red-700">
                    @foreach ($errors->all() as $err)
                        <p>{{ $err }}</p>
                    @endforeach
                </div>
            @endif

            <form action="{{ route('login.post') }}" method="POST" class="space-y-5">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" required
                        class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                        placeholder="email@alsycode.com">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Password</label>
                    <input type="password" name="password" required
                        class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                        placeholder="••••••••">
                </div>
                <div class="flex items-center gap-2">
                    <input type="checkbox" name="remember" id="remember"
                        class="rounded border-gray-300 text-indigo-600">
                    <label for="remember" class="text-sm text-gray-600">Ingat saya</label>
                </div>
                <button type="submit"
                    class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2.5 rounded-lg text-sm transition-colors">
                    Masuk
                </button>
            </form>

            {{-- Demo accounts --}}
            <div class="mt-6 pt-6 border-t border-gray-100">
                <p class="text-xs text-gray-400 text-center mb-3">Akun Demo</p>
                <div class="grid grid-cols-3 gap-2 text-xs text-center">
                    <div class="bg-gray-50 rounded-lg p-2">
                        <p class="font-semibold text-gray-700">Admin</p>
                        <p class="text-gray-400">admin@alsycode.com</p>
                        <p class="text-gray-400">admin123</p>
                    </div>
                    <div class="bg-gray-50 rounded-lg p-2">
                        <p class="font-semibold text-gray-700">Guru</p>
                        <p class="text-gray-400">guru@alsycode.com</p>
                        <p class="text-gray-400">guru123</p>
                    </div>
                    <div class="bg-gray-50 rounded-lg p-2">
                        <p class="font-semibold text-gray-700">Siswa</p>
                        <p class="text-gray-400">siswa@alsycode.com</p>
                        <p class="text-gray-400">siswa123</p>
                    </div>
                </div>
            </div>
        </div>

        <p class="text-center text-xs text-gray-400 mt-6">
            <a href="{{ route('landing') }}" class="hover:text-indigo-600">← Kembali ke beranda</a>
        </p>
    </div>
</body>

</html>
