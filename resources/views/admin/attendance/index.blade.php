@extends('layouts.panel')

@section('title', 'Absensi — Admin')
@section('page_title', 'Absensi')

@section('content')
<h1 class="mb-6 text-xl font-bold text-slate-800">Monitoring absensi</h1>

<div class="mb-6 rounded-[14px] bg-white p-5 shadow-sm border border-slate-200/80">
    <form method="get" class="m-0 flex flex-col sm:flex-row gap-4">
        {{-- Pembimbing Dropdown --}}
        <div class="flex-1">
            <label class="mb-2 block text-xs font-semibold uppercase tracking-wider text-slate-500" for="pembimbing_id">Filter Pembimbing</label>
            <div class="relative">
                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5">
                    <svg class="h-4 w-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                </div>
                <select name="pembimbing_id" id="pembimbing_id" onchange="document.getElementById('peserta_id').value=''; this.form.submit()"
                    class="block w-full appearance-none rounded-xl border border-slate-200 bg-slate-50 py-2.5 pl-10 pr-10 text-sm text-slate-900 transition-colors focus:border-blue-500 focus:bg-white focus:outline-none focus:ring-4 focus:ring-blue-500/10 cursor-pointer">
                    <option value="">-- Semua Pembimbing --</option>
                    @foreach($pembimbingList as $pb)
                    <option value="{{ $pb->id }}" @selected($pembimbingId==$pb->id)>{{ $pb->user->name }}</option>
                    @endforeach
                </select>
                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3.5 text-slate-400">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </div>
            </div>
        </div>

        {{-- Peserta Dropdown --}}
        <div class="flex-1">
            <label class="mb-2 block text-xs font-semibold uppercase tracking-wider text-slate-500" for="peserta_id">Filter Peserta</label>
            <div class="relative">
                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5">
                    <svg class="h-4 w-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                    </svg>
                </div>
                <select name="peserta_id" id="peserta_id" onchange="this.form.submit()" {{ empty($pembimbingId) ? 'disabled' : '' }}
                    class="block w-full appearance-none rounded-xl border border-slate-200 bg-slate-50 py-2.5 pl-10 pr-10 text-sm text-slate-900 transition-colors focus:border-blue-500 focus:bg-white focus:outline-none focus:ring-4 focus:ring-blue-500/10 cursor-pointer disabled:cursor-not-allowed disabled:bg-slate-100 disabled:text-slate-400 disabled:opacity-70 disabled:hover:border-slate-200">
                    <option value="">{{ empty($pembimbingId) ? '-- Pilih Pembimbing Terlebih Dahulu --' : '-- Semua Peserta --' }}</option>
                    @foreach($pesertaList as $p)
                    <option value="{{ $p->id }}" @selected($pesertaId==$p->id)>{{ $p->nim }} — {{ $p->user->name }}</option>
                    @endforeach
                </select>
                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3.5 text-slate-400">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </div>
            </div>
        </div>
    </form>
</div>

{{-- Summary Cards --}}
<div class="mb-8 grid grid-cols-2 md:grid-cols-4 gap-4">
    <div class="rounded-2xl border border-emerald-100 bg-white p-4 shadow-sm">
        <p class="text-[10px] font-bold uppercase tracking-wider text-emerald-600/70 mb-1">Total Hadir</p>
        <div class="flex items-end justify-between">
            <p class="text-2xl font-bold text-slate-800">{{ $summary['hadir'] }}</p>
            <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-emerald-50 text-emerald-600">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
        </div>
    </div>
    <div class="rounded-2xl border border-amber-100 bg-white p-4 shadow-sm">
        <p class="text-[10px] font-bold uppercase tracking-wider text-amber-600/70 mb-1">Total Izin</p>
        <div class="flex items-end justify-between">
            <p class="text-2xl font-bold text-slate-800">{{ $summary['izin'] }}</p>
            <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-amber-50 text-amber-600">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
        </div>
    </div>
    <div class="rounded-2xl border border-blue-100 bg-white p-4 shadow-sm">
        <p class="text-[10px] font-bold uppercase tracking-wider text-blue-600/70 mb-1">Total Sakit</p>
        <div class="flex items-end justify-between">
            <p class="text-2xl font-bold text-slate-800">{{ $summary['sakit'] }}</p>
            <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-blue-50 text-blue-600">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
            </div>
        </div>
    </div>
    <div class="rounded-2xl border border-rose-100 bg-white p-4 shadow-sm">
        <p class="text-[10px] font-bold uppercase tracking-wider text-rose-600/70 mb-1">Total Alpa</p>
        <div class="flex items-end justify-between">
            <p class="text-2xl font-bold text-slate-800">{{ $summary['alpha'] }}</p>
            <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-rose-50 text-rose-600">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
        </div>
    </div>
</div>

<div class="overflow-hidden rounded-[14px] border border-slate-200/80 bg-white shadow-sm transition-all">
    <div class="overflow-x-auto">
        <table class="min-w-full text-left text-sm whitespace-nowrap">
            <thead class="border-b border-slate-200 bg-slate-50/70">
                <tr>
                    <th class="px-5 py-4 text-center font-semibold text-slate-800">Tanggal</th>
                    <th class="px-5 py-4 text-center font-semibold text-slate-800">Peserta</th>
                    <th class="px-5 py-4 text-center font-semibold text-slate-800">Status</th>
                    <th class="px-5 py-4 text-center font-semibold text-slate-800">Masuk</th>
                    <th class="px-5 py-4 text-center font-semibold text-slate-800">Keluar</th>
                    <th class="px-5 py-4 text-center font-semibold text-slate-800">Terlambat</th>
                    <th class="px-5 py-4 text-center font-semibold text-slate-800">Durasi</th>
                    <th class="px-5 py-4 text-center font-semibold text-slate-800">Jam Kerja</th>
                    <th class="px-5 py-4 text-center font-semibold text-slate-800">Lokasi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($rows as $r)
                <tr class="transition-colors hover:bg-slate-50/70 group">
                    <td class="px-5 py-4 text-center font-mono font-medium text-slate-600">{{ $r->tanggal->format('d/m/Y') }}</td>
                    <td class="px-5 py-4 font-medium text-slate-900 group-hover:text-blue-700">{{ $r->pesertaProfile->user->name }}</td>
                    <td class="px-5 py-4 text-center">
                        <span class="inline-flex items-center gap-1.5 rounded-full px-2.5 py-1 text-xs font-semibold ring-1 ring-inset
                                @if($r->status==='hadir') bg-emerald-50 text-emerald-700 ring-emerald-600/20
                                @elseif($r->status==='izin') bg-amber-50 text-amber-700 ring-amber-600/20
                                @elseif($r->status==='sakit') bg-blue-50 text-blue-700 ring-blue-600/20
                                @else bg-slate-100 text-slate-600 ring-slate-500/10 @endif">

                            @if($r->status==='hadir') <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
                            @elseif($r->status==='izin') <span class="h-1.5 w-1.5 rounded-full bg-amber-500"></span>
                            @elseif($r->status==='sakit') <span class="h-1.5 w-1.5 rounded-full bg-blue-500"></span>
                            @endif

                            {{ ucfirst($r->status) }}
                        </span>
                    </td>
                    <td class="w-24 px-5 py-4 text-center">

                        @if($r->check_in_at)

                        <span class="inline-block w-12 text-center font-mono text-slate-700">
                            {{ $r->check_in_at->format('H:i') }}
                        </span>

                        @else

                        <span class="inline-block w-12 text-center text-slate-400">
                            -
                        </span>

                        @endif

                    </td>
                    <td class="w-28 px-5 py-4 text-center">

                        @if($r->check_out_at)

                        <div class="flex flex-col items-center">

                            <span class="inline-block w-12 text-center font-mono text-slate-700">
                                {{ $r->check_out_at->format('H:i') }}
                            </span>

                            @if($r->is_checkout_otomatis)
                            <span class="mt-1 rounded-full bg-blue-100 px-2 py-0.5 text-[10px] font-semibold text-blue-700">
                                Auto
                            </span>
                            @endif

                        </div>

                        @else

                        <span class="inline-block w-12 text-center text-slate-400">
                            -
                        </span>

                        @endif

                    </td>

                    <td class="px-5 py-4 text-center">
                        @if($r->status == 'hadir')
                        @if($r->is_terlambat)
                        <span class="rounded-full bg-red-100 px-2 py-1 text-xs font-semibold text-red-700">
                            Ya
                        </span>
                        @else
                        <span class="rounded-full bg-green-100 px-2 py-1 text-xs font-semibold text-green-700">
                            Tidak
                        </span>
                        @endif
                        @else
                        <span class="inline-block w-5 text-center text-slate-400">-</span>
                        @endif
                    </td>

                    <td class="px-5 py-4 text-center">
                        @if($r->check_in_at && $r->check_out_at)
                        <span class="rounded-full bg-slate-100 px-2 py-1 text-xs font-semibold text-slate-700">
                            {{ floor($r->durasi_kerja_menit / 60) }}j
                            {{ $r->durasi_kerja_menit % 60 }}m
                        </span>
                        @else
                        <span class="inline-block w-5 text-center text-slate-400">-</span>
                        @endif
                    </td>

                    <td class="px-5 py-4 text-center">
                        @if($r->status == 'hadir')

                        @if($r->durasi_kerja_menit >= 420)
                        <span class="rounded-full bg-green-100 px-2 py-1 text-xs font-semibold text-green-700">
                            Memenuhi
                        </span>
                        @else
                        <span class="rounded-full bg-yellow-100 px-2 py-1 text-xs font-semibold text-yellow-700">
                            Kurang
                        </span>
                        @endif
                        @else
                        <span class="inline-block w-5 text-center text-slate-400">-</span>
                        @endif
                    </td>

                    <td class="px-5 py-4 text-center">
                        @if($r->check_in_lat && $r->check_in_lng)
                        <a href="https://www.openstreetmap.org/?mlat={{ $r->check_in_lat }}&mlon={{ $r->check_in_lng }}&zoom=18"
                            target="_blank"
                            title="Buka lokasi absen masuk di OpenStreetMap"
                            class="inline-flex items-center gap-1.5 rounded-full bg-blue-50 px-3 py-1 text-xs font-semibold text-blue-700 ring-1 ring-blue-600/20 hover:bg-blue-100 transition">
                            <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            Lihat
                        </a>
                        @else
                        <span class="inline-block w-5 text-center text-slate-400">-</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-5 py-16 text-center text-slate-500">
                        <div class="flex flex-col items-center justify-center">
                            <svg class="h-10 w-10 text-slate-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            Belum ada data absensi.
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
<div class="mt-6">{{ $rows->links() }}</div>
@endsection