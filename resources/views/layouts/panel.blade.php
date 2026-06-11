<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Panel') — {{ config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('head')
</head><body class="min-h-screen bg-slate-50 font-sans text-slate-800 antialiased">
    <style>
        #panel-sidebar-nav::-webkit-scrollbar { display: none; }
        #panel-sidebar-nav { -ms-overflow-style: none; scrollbar-width: none; }
        ::selection { background-color: #d1fae5; color: #065f46; }
        @media (min-width: 768px) {
            .desktop-pl-64 { padding-left: 16rem; }
        }
        .active-menu-blue {
            background-color: rgba(255, 255, 255, 0.2) !important;
            color: white !important;
        }
    </style>

    <div id="panel-sidebar-backdrop" class="fixed inset-0 z-40 hidden bg-slate-900/40 md:hidden" aria-hidden="true" style="transition: opacity 300ms;"></div>

    <aside id="panel-sidebar"
        class="fixed inset-y-0 left-0 z-50 flex w-64 -translate-x-full flex-col border-r border-[#00B1C0] bg-[#00B1C0] shadow-sm transition-transform duration-300 md:translate-x-0">
        
        {{-- Header Sidebar (Sejajar dengan Navbar h-16) --}}
        <div class="flex h-16 w-full items-center justify-between border-b border-white/20 px-4 shrink-0">
            <a href="{{ $panelHome ?? route('admin.dashboard') }}" class="flex items-center gap-2 overflow-hidden mr-2">
                <span class="truncate text-sm font-medium text-white">Sistem Magang</span>
            </a>


            {{-- Desktop Sidebar Toggle (Di baris Navbar) --}}
            <button type="button" id="desktop-sidebar-close" class="hidden shrink-0 text-white/80 hover:text-white hover:bg-white/10 p-1.5 rounded-md focus:outline-none md:flex transition-colors" title="Tutup Menu">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <rect x="3" y="3" width="18" height="18" rx="2" ry="2" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></rect>
                    <line x1="9" y1="3" x2="9" y2="21" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></line>
                </svg>
            </button>
        </div>

        {{-- User Profile Block --}}
        <div class="flex flex-col items-center border-b border-white/20 py-6 px-4 shrink-0">
            @if(auth()->user()->avatar_path)
                <img src="{{ route('storage.file', auth()->user()->avatar_path) }}" alt="Avatar" class="mb-2 h-12 w-12 shrink-0 rounded-full object-cover shadow-sm ring-4 ring-white/20">
            @else
                <div class="mb-2 flex h-12 w-12 items-center justify-center rounded-full bg-white/20 text-lg font-bold text-white shadow-sm ring-4 ring-white/10">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>
            @endif
            <span class="text-center text-sm font-bold tracking-tight text-white w-full truncate px-2">{{ auth()->user()->name }}</span>
            <span class="mt-0.5 text-center text-sm font-medium text-white/80">
                @if(auth()->user()->isAdmin()) Admin
                @elseif(auth()->user()->isPembimbing()) Pembimbing
                @else Peserta magang
                @endif
            </span>
        </div>

        {{-- Navigation --}}
        <nav id="panel-sidebar-nav" class="flex flex-1 flex-col gap-0.5 overflow-y-auto py-4">
            @include('layouts.partials.panel-sidebar')
        </nav>

        {{-- Bottom info/logout --}}
        <div class="p-4 shrink-0 border-t border-white/10">
            <form action="{{ route('logout') }}" method="post">
                @csrf
                <button type="submit" class="flex w-full items-center justify-center gap-2 rounded-lg border border-white/20 bg-white/10 px-4 py-2.5 text-sm font-semibold text-white transition hover:border-white/40 hover:bg-white/20">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                    Keluar
                </button>
            </form>
        </div>
    </aside>

    <div id="main-content" class="min-h-screen flex flex-col transition-all duration-300 desktop-pl-64">
        <header class="sticky top-0 z-30 flex h-16 w-full shrink-0 shadow-md bg-white border-b border-slate-200">

            {{-- Kiri: Area Logo (sejajar sidebar w-64) --}}
            <div class="hidden md:flex h-full w-64 shrink-0 items-center bg-white border-b border-slate-200 px-4">
                <a href="{{ $panelHome ?? '#' }}" class="flex items-center">
                    <img src="{{ asset('images/logo-rs-awalbros.png') }}"
                         alt="RS Awal Bros"
                         class="h-10 w-auto object-contain">
                </a>
            </div>

            {{-- Kanan: Area Putih --}}
            <div class="flex flex-1 h-full items-center justify-between bg-white border-b border-slate-200 px-4">

                {{-- Mobile Toggle + Logo --}}
                <div class="flex items-center gap-3 md:gap-0">
                    <button type="button" id="panel-sidebar-toggle" class="inline-flex h-10 w-10 shrink-0 items-center justify-center rounded-lg text-slate-500 hover:bg-slate-100 hover:text-slate-700 focus:outline-none md:hidden transition-colors">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <rect x="3" y="3" width="18" height="18" rx="2" ry="2" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></rect>
                            <line x1="9" y1="3" x2="9" y2="21" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></line>
                        </svg>
                    </button>
                    {{-- Desktop Open Sidebar Toggle (saat sidebar closed) --}}
                    <button type="button" id="desktop-sidebar-open" class="hidden h-10 w-10 shrink-0 items-center justify-center rounded-lg text-slate-500 hover:bg-slate-100 hover:text-slate-700 focus:outline-none transition-colors" style="display: none;">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <rect x="3" y="3" width="18" height="18" rx="2" ry="2" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></rect>
                            <line x1="9" y1="3" x2="9" y2="21" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></line>
                        </svg>
                    </button>
                    {{-- Logo mobile (di tengah) --}}
                    <div class="md:hidden flex items-center">
                        <img src="{{ asset('images/logo-rs-awalbros.png') }}" alt="RS Awal Bros" class="h-8 w-auto object-contain">
                    </div>
                </div>

                {{-- Kanan: User info + Avatar --}}
                <div class="flex items-center gap-3">
                    <div class="hidden text-right md:block">
                        <div class="text-sm font-bold text-slate-800">{{ auth()->user()->name }}</div>
                        <div class="text-[10px] font-semibold uppercase tracking-wider text-slate-500">
                            @if(auth()->user()->isAdmin()) Admin
                            @elseif(auth()->user()->isPembimbing()) Pembimbing
                            @else Peserta Magang
                            @endif
                        </div>
                    </div>
                    @if(auth()->user()->avatar_path)
                        <img src="{{ route('storage.file', auth()->user()->avatar_path) }}" alt="Avatar"
                             class="h-9 w-9 shrink-0 rounded-full object-cover ring-2 ring-slate-100 ring-offset-1">
                    @else
                        <div class="flex h-9 w-9 items-center justify-center rounded-full bg-slate-100 text-sm font-bold text-slate-600 ring-2 ring-slate-100 ring-offset-1">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </div>
                    @endif
                </div>

            </div>
        </header>

        <div class="flex-1 px-4 py-5 md:px-6">
            <div class="mb-4 flex flex-wrap items-center justify-between gap-2">
                <nav class="flex items-center gap-2 text-sm text-slate-500" aria-label="Breadcrumb">
                    <a href="{{ $panelHome ?? '#' }}" class="hover:text-blue-600 transition-colors">Beranda</a>
                    <span class="text-slate-300">/</span>
                    <span class="font-medium text-slate-700">@yield('page_title', 'Dashboard')</span>
                </nav>
                @if(!request()->routeIs('*.dashboard'))
                <button type="button" onclick="history.back()" class="inline-flex items-center gap-1.5 rounded-lg border border-slate-200 bg-white px-3 py-1.5 text-sm font-medium text-slate-600 shadow-sm transition-colors cursor-pointer hover:bg-slate-50 hover:text-slate-900 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                    Kembali
                </button>
                @endif
            </div>

            @if(session('success'))
                <div class="mb-4 rounded-[10px] border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="mb-4 rounded-[10px] border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800">{{ session('error') }}</div>
            @endif
            @if ($errors->any())
                <div class="mb-4 rounded-[10px] border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800">
                    <ul class="list-inside list-disc">
                        @foreach ($errors->all() as $e)
                            <li>{{ $e }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            @yield('content')
        </div>
    </div>

    <script>
        (function () {
            var sidebar = document.getElementById('panel-sidebar');
            var backdrop = document.getElementById('panel-sidebar-backdrop');
            var mainContent = document.getElementById('main-content');
            
            // Mobile toggle elements
            var toggle = document.getElementById('panel-sidebar-toggle');
            
            // Desktop toggle elements
            var deskClose = document.getElementById('desktop-sidebar-close');
            var deskOpen = document.getElementById('desktop-sidebar-open');

            // Toggle Mobile
            function openNav() {
                sidebar.classList.remove('-translate-x-full');
                backdrop.classList.remove('hidden');
                document.body.classList.add('overflow-hidden');
            }
            function closeNav() {
                sidebar.classList.add('-translate-x-full');
                backdrop.classList.add('hidden');
                document.body.classList.remove('overflow-hidden');
            }
            toggle && toggle.addEventListener('click', function () {
                if (sidebar.classList.contains('-translate-x-full')) openNav(); else closeNav();
            });
            backdrop && backdrop.addEventListener('click', closeNav);
            
            // Toggle Desktop
            function hideDesktopNav() {
                sidebar.classList.remove('md:translate-x-0');
                sidebar.classList.add('md:-translate-x-full');
                mainContent.classList.remove('desktop-pl-64');
                deskOpen.style.display = 'inline-flex';
            }
            function showDesktopNav() {
                mainContent.classList.add('desktop-pl-64');
                sidebar.classList.add('md:translate-x-0');
                sidebar.classList.remove('md:-translate-x-full');
                deskOpen.style.display = 'none';
            }

            deskClose && deskClose.addEventListener('click', hideDesktopNav);
            deskOpen && deskOpen.addEventListener('click', showDesktopNav);

            window.addEventListener('resize', function () {
                if (window.matchMedia('(min-width: 768px)').matches) {
                    closeNav(); // ensure mobile overlay is closed if resized to desktop
                }
            });
        })();
    </script>
    @stack('scripts')
</body>
</html>
