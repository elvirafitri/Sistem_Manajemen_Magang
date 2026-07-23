@extends('layouts.panel')

@section('title', 'Riwayat Absensi — Peserta')
@section('page_title', 'Riwayat Absensi')

@section('content')
<div class="mb-6 flex flex-wrap items-center justify-between gap-3">
    <div>
        <h1 class="text-2xl font-bold tracking-tight text-slate-900">Riwayat Absensi</h1>
        <p class="mt-1 text-sm text-slate-500">Rekap seluruh catatan absensi Anda selama periode magang.</p>
    </div>

    {{-- Month/Year Filter --}}
    <form action="{{ route('peserta.history') }}" method="get" class="flex items-center gap-2">
        <select name="month" onchange="this.form.submit()" class="rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm font-medium text-slate-700 focus:border-blue-500 focus:outline-none focus:ring-4 focus:ring-blue-500/10">
            @foreach(range(1, 12) as $m)
            <option value="{{ $m }}" @selected($month==$m)>{{ \Carbon\Carbon::create(2000, $m, 1)->translatedFormat('F') }}</option>
            @endforeach
        </select>
        <select name="year" onchange="this.form.submit()" class="rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm font-medium text-slate-700 focus:border-blue-500 focus:outline-none focus:ring-4 focus:ring-blue-500/10">
            @foreach(range(now()->year - 2, now()->year + 1) as $y)
            <option value="{{ $y }}" @selected($year==$y)>{{ $y }}</option>
            @endforeach
        </select>
    </form>
</div>

{{-- Recap Cards --}}
<div class="mb-8 grid grid-cols-2 md:grid-cols-4 gap-4">
    <div class="rounded-2xl border border-emerald-100 bg-emerald-50/50 p-4 shadow-sm">
        <div class="flex items-center gap-3">
            <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-emerald-100 text-emerald-600">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div>
                <p class="text-[10px] font-bold uppercase tracking-wider text-emerald-600/70">Hadir</p>
                <p class="text-xl font-bold text-emerald-700">{{ $recap['hadir'] }} <span class="text-xs font-medium">Hari</span></p>
            </div>
        </div>
    </div>
    <div class="rounded-2xl border border-blue-100 bg-blue-50/50 p-4 shadow-sm">
        <div class="flex items-center gap-3">
            <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-blue-100 text-blue-600">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div>
                <p class="text-[10px] font-bold uppercase tracking-wider text-blue-600/70">Izin</p>
                <p class="text-xl font-bold text-blue-700">{{ $recap['izin'] }} <span class="text-xs font-medium">Hari</span></p>
            </div>
        </div>
    </div>
    <div class="rounded-2xl border border-amber-100 bg-amber-50/50 p-4 shadow-sm">
        <div class="flex items-center gap-3">
            <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-amber-100 text-amber-600">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
            </div>
            <div>
                <p class="text-[10px] font-bold uppercase tracking-wider text-amber-600/70">Sakit</p>
                <p class="text-xl font-bold text-amber-700">{{ $recap['sakit'] }} <span class="text-xs font-medium">Hari</span></p>
            </div>
        </div>
    </div>
    <div class="rounded-2xl border border-rose-100 bg-rose-50/50 p-4 shadow-sm">
        <div class="flex items-center gap-3">
            <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-rose-100 text-rose-600">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div>
                <p class="text-[10px] font-bold uppercase tracking-wider text-rose-600/70">Alpa</p>
                <p class="text-xl font-bold text-rose-700">{{ $recap['alpa'] }} <span class="text-xs font-medium">Hari</span></p>
            </div>
        </div>
    </div>
</div>

<div class="overflow-hidden rounded-[14px] border border-slate-200 bg-white shadow-sm transition-all hover:shadow-md hover:border-slate-300">
    <div class="border-b border-slate-100 bg-slate-50/50 px-6 py-4 flex items-center justify-between gap-3">
        <h2 class="font-semibold text-slate-800">Daftar Riwayat Absensi</h2>
        <span class="inline-flex items-center gap-1.5 rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-600">
            <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
            </svg>
            Total: {{ $rows->total() }} catatan
        </span>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full text-left text-sm">
            <thead>
                <tr class="border-b border-slate-100 bg-slate-50/30">
                    <th class="px-6 py-3.5 text-xs font-bold uppercase tracking-wider text-slate-500">Tanggal</th>
                    <th class="px-6 py-3.5 text-xs font-bold uppercase tracking-wider text-slate-500">Status</th>
                    <th class="px-6 py-3.5 text-xs font-bold uppercase tracking-wider text-slate-500">Masuk</th>
                    <th class="px-6 py-3.5 text-xs font-bold uppercase tracking-wider text-slate-500">keluar</th>
                    <th class="px-6 py-3.5 text-xs font-bold uppercase tracking-wider text-slate-500">Terlambat</th>
                    <th class="px-6 py-3.5 text-xs font-bold uppercase tracking-wider text-slate-500">Durasi</th>
                    <th class="px-6 py-3.5 text-xs font-bold uppercase tracking-wider text-slate-500">jam Kerja</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($rows as $r)
                <tr class="transition-colors hover:bg-slate-50/60">
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-2.5">
                            <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-slate-100 text-slate-500">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <div>
                                <p class="font-semibold text-slate-900">{{ $r->tanggal->translatedFormat('d M Y') }}</p>
                                <p class="text-xs text-slate-400 font-mono">{{ $r->tanggal->translatedFormat('l') }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        @php
                        $statusConfig = match($r->status) {
                        'hadir' => ['bg-emerald-50 text-emerald-700 ring-emerald-600/20', 'Hadir'],
                        'izin' => ['bg-amber-50 text-amber-700 ring-amber-600/20', 'Izin'],
                        'sakit' => ['bg-rose-50 text-rose-700 ring-rose-600/20', 'Sakit'],
                        'alpa' => ['bg-slate-100 text-slate-600 ring-slate-500/20', 'Alpa'],
                        default => ['bg-slate-100 text-slate-600 ring-slate-500/20', ucfirst($r->status)],
                        };
                        @endphp
                        <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold ring-1 ring-inset {{ $statusConfig[0] }}">
                            {{ $statusConfig[1] }}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        @if($r->check_in_at)
                        <span class="font-mono text-sm font-semibold text-slate-800">{{ $r->check_in_at->format('H:i') }}</span>
                        @else
                        <span class="text-slate-300 font-mono">—</span>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        @if($r->check_out_at)
                        <div class="flex flex-col">
                            <span class="font-mono text-sm font-semibold text-slate-800">
                                {{ $r->check_out_at->format('H:i') }}
                            </span>

                            @if($r->is_checkout_otomatis)
                            <span class="mt-1 inline-flex w-fit rounded-full bg-blue-100 px-2 py-0.5 text-[10px] font-semibold text-blue-700">
                                Auto 
                            </span>
                            @endif
                        </div>
                        @else
                        <span class="text-slate-300 font-mono">—</span>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        @if($r->status == 'hadir')
                        @if($r->is_terlambat)
                        <span class="inline-flex rounded-full bg-red-100 px-2 py-1 text-xs font-semibold text-red-700">
                            Ya
                        </span>
                        @else
                        <span class="inline-flex rounded-full bg-green-100 px-2 py-1 text-xs font-semibold text-green-700">
                            Tidak
                        </span>
                        @endif
                        @else
                        -
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        @if($r->check_in_at && $r->check_out_at)
                        <span class="text-xs font-semibold text-slate-600 bg-slate-100 px-2.5 py-1 rounded-full font-mono">
                            {{ floor($r->durasi_kerja_menit / 60) }}j {{ $r->durasi_kerja_menit % 60 }}m
                        </span>
                        @else
                        <span class="text-slate-300 text-xs font-mono">—</span>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        @if($r->status == 'hadir')
                        {{-- Menggunakan durasi_kerja_menit yang sudah kita buat di model --}}
                        @if($r->durasi_kerja_menit >= 420)
                        <span class="inline-flex rounded-full bg-green-100 px-2 py-1 text-xs font-semibold text-green-700">
                            Memenuhi
                        </span>
                        @else
                        <span class="inline-flex rounded-full bg-yellow-100 px-2 py-1 text-xs font-semibold text-yellow-700">
                            Kurang
                        </span>
                        @endif
                        @else
                        -
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-16 text-center">
                        <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-slate-100 text-slate-400 mb-4">
                            <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                        </div>
                        <p class="text-sm font-semibold text-slate-700">Belum Ada Riwayat</p>
                        <p class="text-xs text-slate-500 mt-1">Catatan kehadiran Anda akan muncul di sini setelah melakukan absen masuk.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($rows->hasPages())
    <div class="border-t border-slate-100 bg-slate-50/30 px-6 py-4">
        {{ $rows->links() }}
    </div>
    @endif
</div>
@endsection