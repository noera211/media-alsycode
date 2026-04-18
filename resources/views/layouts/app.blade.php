<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'ALSYCODE') – Platform Algoritma</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#6366f1',
                        'primary-dark': '#4f46e5',
                        accent: '#10b981',
                    }
                }
            }
        }
    </script>
    <style>
        .sidebar-link {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.625rem 1rem;
            border-radius: 0.75rem;
            font-size: 0.875rem;
            font-weight: 500;
            color: #4b5563;
            transition: all 0.2s ease;
            text-decoration: none;
        }
        .sidebar-link:hover {
            background-color: #eef2ff;
            color: #4f46e5;
        }
        .sidebar-link.active {
            background-color: #4338ca;
            color: #ffffff;
        }
        .sidebar-link.collapsed {
            justify-content: center;
            padding: 0.625rem;
        }
        .sidebar-link.collapsed .link-text {
            display: none;
        }
        .btn-primary {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background-color: #4f46e5;
            color: #ffffff;
            font-weight: 500;
            padding: 0.5rem 1rem;
            border-radius: 0.75rem;
            font-size: 0.875rem;
            border: none;
            cursor: pointer;
            transition: background-color 0.2s ease;
            text-decoration: none;
        }
        .btn-primary:hover {
            background-color: #4338ca;
        }
        .btn-outline {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border: 1px solid #d1d5db;
            color: #374151;
            background-color: #ffffff;
            font-weight: 500;
            padding: 0.5rem 1rem;
            border-radius: 0.75rem;
            font-size: 0.875rem;
            cursor: pointer;
            transition: border-color 0.2s ease, color 0.2s ease;
            text-decoration: none;
        }
        .btn-outline:hover {
            border-color: #6366f1;
            color: #4f46e5;
        }
        .btn-danger {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background-color: #fee2e2;
            color: #dc2626;
            font-weight: 500;
            padding: 0.5rem 1rem;
            border-radius: 0.75rem;
            font-size: 0.875rem;
            border: none;
            cursor: pointer;
            transition: background-color 0.2s ease;
            text-decoration: none;
        }
        .btn-danger:hover {
            background-color: #fecaca;
        }
        .card {
            background-color: #ffffff;
            border: 1px solid #e5e7eb;
            border-radius: 1rem;
            box-shadow: 0 1px 2px rgba(15, 23, 42, 0.05);
        }
        .badge-mudah {
            background-color: #ecfdf5;
            color: #047857;
            font-size: 0.75rem;
            font-weight: 600;
            padding: 0.125rem 0.5rem;
            border-radius: 9999px;
            display: inline-flex;
            align-items: center;
        }
        .badge-sedang {
            background-color: #fef3c7;
            color: #b45309;
            font-size: 0.75rem;
            font-weight: 600;
            padding: 0.125rem 0.5rem;
            border-radius: 9999px;
            display: inline-flex;
            align-items: center;
        }
        .badge-sulit {
            background-color: #fee2e2;
            color: #b91c1c;
            font-size: 0.75rem;
            font-weight: 600;
            padding: 0.125rem 0.5rem;
            border-radius: 9999px;
            display: inline-flex;
            align-items: center;
        }
    </style>
</head>
<body class="bg-gray-50 min-h-screen">

<div class="flex min-h-screen">
    {{-- Overlay for mobile --}}
    <div id="sidebar-overlay" class="fixed inset-0 bg-black bg-opacity-50 z-40 hidden md:hidden" onclick="toggleSidebar()"></div>

    {{-- Sidebar --}}
    <aside id="sidebar" class="w-64 bg-white border-r border-gray-200 flex flex-col fixed h-screen z-50 transform -translate-x-full md:translate-x-0 transition-transform duration-300 ease-in-out">
        {{-- Header with collapse button --}}
        <div class="p-5 border-b border-gray-100 flex items-center justify-between">
            <a href="{{ route('dashboard') }}" class="flex items-center gap-2">
                <img src="{{ asset('images/logo (2).png') }}" alt="ALSYCODE Logo" class="h-9 w-auto">
                <div class="sidebar-logo-text">
                    <p class="font-bold text-gray-900 text-sm">ALSYCODE</p>
                    <p class="text-xs text-gray-400">Algoritma Pemrograman</p>
                </div>
            </a>
            <button id="sidebar-collapse-btn" class="hidden md:block p-1 rounded-md hover:bg-gray-100 transition-colors" onclick="toggleCollapse()">
                <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
            </button>
        </div>

        {{-- Nav --}}
        <nav class="flex-1 p-4 space-y-1 overflow-y-auto">
            @if(auth()->user()->isAdmin())
                <a href="{{ route('admin.dashboard') }}" class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7h18M3 12h18M3 17h18"/></svg>
                    <span class="link-text">Dashboard Admin</span>
                </a>
                <a href="{{ route('admin.users') }}" class="sidebar-link {{ request()->routeIs('admin.users*') ? 'active' : '' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0"/></svg>
                    <span class="link-text">Manajemen User</span>
                </a>
            @else
                <a href="{{ route('dashboard') }}" class="sidebar-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                    <span class="link-text">Dashboard</span>
                </a>
                <a href="{{ route('materi.index') }}" class="sidebar-link {{ request()->routeIs('materi*') ? 'active' : '' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                    <span class="link-text">Materi</span>
                </a>
                <a href="{{ route('pbl.index') }}" class="sidebar-link {{ request()->routeIs('pbl*') ? 'active' : '' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                    <span class="link-text">Aktivitas PBL</span>
                </a>
                <a href="{{ route('nilai.index') }}" class="sidebar-link {{ request()->routeIs('nilai*') ? 'active' : '' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                    <span class="link-text">Nilai & Evaluasi</span>
                </a>
                <a href="{{ route('compiler') }}" class="sidebar-link {{ request()->routeIs('compiler*') ? 'active' : '' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/></svg>
                    <span class="link-text">Mini Compiler</span>
                </a>
            @endif
        </nav>

        {{-- User Info --}}
        <div class="border-t border-gray-100" id="user-info-wrap">
            {{-- Normal (expanded) --}}
            <div id="user-info-expanded" class="p-4">
                <div class="flex items-center gap-3 mb-3">
                    <div class="h-9 w-9 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600 font-bold text-sm flex-shrink-0">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-semibold text-gray-900 truncate">{{ auth()->user()->name }}</p>
                        <p class="text-xs text-gray-400 capitalize">{{ auth()->user()->role }}</p>
                    </div>
                </div>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full text-left text-sm text-red-500 hover:text-red-700 font-medium py-1">
                        ← Keluar
                    </button>
                </form>
            </div>
            {{-- Collapsed: hanya avatar + logout icon --}}
            <div id="user-info-collapsed" class="hidden flex-col items-center py-4 gap-3">
                <div class="h-9 w-9 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600 font-bold text-sm">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" title="Keluar" class="text-red-400 hover:text-red-600 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                        </svg>
                    </button>
                </form>
            </div>
        </div>
    </aside>

    {{-- Main --}}
    <main class="flex-1 ml-0 md:ml-64 transition-all duration-300 ease-in-out min-w-0" id="main-content">
        {{-- Top Navbar for Mobile --}}
        <nav class="bg-white border-b border-gray-200 px-4 py-3 md:hidden flex items-center justify-between sticky top-0 z-20">
            <button id="mobile-menu-btn" class="p-2 rounded-md hover:bg-gray-100 transition-colors" onclick="toggleSidebar()">
                <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>
            <div class="flex items-center gap-2">
                <img src="{{ asset('images/logo (2).png') }}" alt="ALSYCODE Logo" class="h-6 w-auto">
                <span class="font-bold text-gray-900 text-sm">ALSYCODE</span>
            </div>
            <div class="w-10"></div>
        </nav>

        <div class="w-full px-4 sm:px-6 lg:px-8 py-6 md:py-8">
            {{-- Flash Messages --}}
            @if(session('success'))
                <div class="mb-4 p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-lg text-sm flex items-center gap-2">
                    <svg class="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                    {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="mb-4 p-4 bg-red-50 border border-red-200 text-red-700 rounded-lg text-sm flex items-center gap-2">
                    <svg class="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                    {{ session('error') }}
                </div>
            @endif
            @if($errors->any())
                <div class="mb-4 p-4 bg-red-50 border border-red-200 text-red-700 rounded-lg text-sm">
                    <ul class="list-disc list-inside space-y-1">
                        @foreach($errors->all() as $err)
                            <li>{{ $err }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @yield('content')
        </div>
    </main>
</div>

<script>
    let sidebarCollapsed = false;
    let sidebarOpen = false;

    function toggleCollapse() {
        const sidebar = document.getElementById('sidebar');
        const main = document.getElementById('main-content');
        const collapseBtn = document.getElementById('sidebar-collapse-btn');
        const links = document.querySelectorAll('.sidebar-link');
        const logoText = document.querySelector('.sidebar-logo-text');

        sidebarCollapsed = !sidebarCollapsed;

        if (sidebarCollapsed) {
            sidebar.classList.remove('md:w-64');
            sidebar.classList.add('md:w-16');
            main.classList.remove('md:ml-64');
            main.classList.add('md:ml-16');
            collapseBtn.innerHTML = '<svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>';
            links.forEach(link => link.classList.add('collapsed'));
            logoText.style.display = 'none';
            document.getElementById('user-info-expanded').classList.add('hidden');
            document.getElementById('user-info-collapsed').classList.remove('hidden');
            document.getElementById('user-info-collapsed').classList.add('flex');
        } else {
            sidebar.classList.remove('md:w-16');
            sidebar.classList.add('md:w-64');
            main.classList.remove('md:ml-16');
            main.classList.add('md:ml-64');
            collapseBtn.innerHTML = '<svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>';
            links.forEach(link => link.classList.remove('collapsed'));
            logoText.style.display = 'block';
            document.getElementById('user-info-expanded').classList.remove('hidden');
            document.getElementById('user-info-collapsed').classList.add('hidden');
            document.getElementById('user-info-collapsed').classList.remove('flex');
        }
    }

    function toggleSidebar() {
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('sidebar-overlay');

        sidebarOpen = !sidebarOpen;

        if (sidebarOpen) {
            sidebar.classList.remove('-translate-x-full');
            overlay.classList.remove('hidden');
        } else {
            sidebar.classList.add('-translate-x-full');
            overlay.classList.add('hidden');
        }
    }

    window.addEventListener('resize', function() {
        if (window.innerWidth >= 768) {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebar-overlay');
            sidebar.classList.remove('-translate-x-full');
            overlay.classList.add('hidden');
            sidebarOpen = false;
        }
    });
</script>

@stack('scripts')
</body>
</html>