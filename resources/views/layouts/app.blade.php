<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Masuk') — {{ config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-slate-50 font-sans text-slate-800 antialiased">
    <header class="border-b border-slate-200/80 bg-white shadow-sm">
        <div class="mx-auto flex h-16 max-w-6xl items-center justify-between px-4 md:px-6">
            <a href="{{ route('login') }}" class="flex items-center gap-3">
                <span class="flex h-9 w-9 items-center justify-center rounded-lg bg-blue-600 text-sm font-bold text-white shadow-sm">SM</span>
                <span class="font-semibold tracking-tight text-slate-900">{{ config('app.name') }}</span>
            </a>
        </div>
    </header>

    <main class="mx-auto max-w-6xl px-4 py-8 md:px-6">
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
    </main>
</body>
</html>
