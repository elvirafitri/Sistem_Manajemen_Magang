@extends('layouts.panel')

@section('title', 'Penilaian Magang — Pembimbing')
@section('page_title', 'Penilaian magang')

@section('content')
<div class="mb-6 flex flex-wrap items-center justify-between gap-3">
    <div>
        <h1 class="text-2xl font-bold tracking-tight text-slate-900">Peserta Bimbingan</h1>
        <p class="mt-1 text-sm text-slate-500">Pilih peserta magang untuk mengisi nilai rubrik dan memberikan komentar evaluasi.</p>
    </div>
</div>

<div class="overflow-hidden rounded-[14px] border border-slate-200/80 bg-white shadow-sm transition-all">
    <div class="overflow-x-auto">
        <table class="min-w-full text-left text-sm whitespace-nowrap">
            <thead class="border-b border-slate-200 bg-slate-50/70">
                <tr>
                    <th class="px-5 py-4 font-semibold text-slate-800">Peserta</th>
                    <th class="px-5 py-4 font-semibold text-slate-800 text-center">Total Nilai</th>
                    <th class="px-5 py-4 font-semibold text-slate-800 text-center">Status Final</th>
                    <th class="px-5 py-4 text-right font-semibold text-slate-800">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($peserta as $p)
                    @php $ev = $p->evaluations->sortByDesc('id')->first(); @endphp
                    <tr class="transition-colors hover:bg-slate-50/70 group">
                        <td class="px-5 py-4 font-medium text-slate-900 group-hover:text-blue-700">
                            <div class="flex items-center gap-3">
                                @if($p->user->avatar_path)
                                    <img src="{{ route('storage.file', $p->user->avatar_path) }}" alt="Avatar" class="h-9 w-9 rounded-full object-cover border border-slate-200 shadow-sm">
                                @else
                                    <div class="flex h-9 w-9 items-center justify-center rounded-full bg-blue-100 text-xs font-bold tracking-wider text-blue-700 shadow-sm ring-1 ring-white">
                                        {{ strtoupper(substr($p->user->name, 0, 1)) }}
                                    </div>
                                @endif
                                <div>
                                    <p class="font-semibold text-slate-800">{{ $p->user->name }}</p>
                                    <p class="text-xs text-slate-500 font-mono mt-0.5">{{ $p->nim ?? 'NIM Belum Diisi' }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-5 py-4 text-center">
                            @if($ev?->total_nilai !== null)
                                <span class="inline-block rounded-xl bg-slate-50 border border-slate-200/60 px-3 py-1.5 font-mono text-sm font-bold text-slate-800 shadow-sm">
                                    {{ rtrim(rtrim(number_format($ev->total_nilai, 2, '.', ''), '0'), '.') }}
                                </span>
                            @else
                                <span class="text-slate-400 font-medium tracking-wide">Belum dinilai</span>
                            @endif
                        </td>
                        <td class="px-5 py-4 text-center">
                            @if($ev?->is_final)
                                <span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-50 px-2.5 py-1 text-xs font-semibold text-emerald-700 ring-1 ring-inset ring-emerald-600/20">
                                    <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span> Final
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 rounded-full bg-amber-50 px-2.5 py-1 text-xs font-semibold text-amber-700 ring-1 ring-inset ring-amber-600/20">
                                    <span class="h-1.5 w-1.5 rounded-full bg-amber-500"></span> Draft
                                </span>
                            @endif
                        </td>
                        <td class="px-5 py-4 text-right font-medium">
                            <div class="flex items-center justify-end gap-3">

                                <a class="inline-flex items-center gap-1.5 text-blue-600 hover:text-blue-800 font-semibold transition-colors" href="{{ route('pembimbing.evaluation.edit', $p) }}">
                                    Isi Penilaian <svg class="h-4 w-4 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                                </a>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-5 py-16 text-center text-slate-500">
                            <div class="flex flex-col items-center justify-center">
                                <svg class="h-10 w-10 text-slate-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                                Belum ada peserta magang bimbingan Anda.
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
