@extends('layouts.panel')

@section('title', 'Izin & Sakit — Pembimbing')
@section('page_title', 'Verifikasi izin & sakit')

@section('content')
<div class="mb-6 flex flex-wrap items-center justify-between gap-3">
    <div>
        <h1 class="text-2xl font-bold tracking-tight text-slate-900">Verifikasi Izin & Sakit</h1>
        <p class="mt-1 text-sm text-slate-500">Kelola pengajuan izin dan sakit dari peserta magang bimbingan Anda.</p>
    </div>
</div>

<div class="overflow-hidden rounded-[14px] border border-slate-200/80 bg-white shadow-sm transition-all">
    <div class="overflow-x-auto">
        <table class="min-w-full text-left text-sm whitespace-nowrap">
            <thead class="border-b border-slate-200 bg-slate-50/70">
                <tr>
                    <th class="px-5 py-4 font-semibold text-slate-800">Peserta</th>
                    <th class="px-5 py-4 font-semibold text-slate-800">Rentang Waktu</th>
                    <th class="px-5 py-4 font-semibold text-slate-800">Jenis</th>
                    <th class="px-5 py-4 font-semibold text-slate-800">Status</th>
                    <th class="px-5 py-4 text-right font-semibold text-slate-800">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($rows as $lr)
                    <tr class="transition-colors hover:bg-slate-50/70 group">
                        <td class="px-5 py-4 font-medium text-slate-900 group-hover:text-blue-700">
                            <div class="flex items-center gap-3">
                                @if($lr->pesertaProfile->user->avatar_path)
                                    <img src="{{ route('storage.file', $lr->pesertaProfile->user->avatar_path) }}" alt="Avatar" class="h-8 w-8 rounded-full object-cover border border-slate-200 shadow-sm">
                                @else
                                    <div class="flex h-8 w-8 items-center justify-center rounded-full bg-blue-100 text-[11px] font-bold tracking-wider text-blue-700 shadow-sm ring-1 ring-white">
                                        {{ strtoupper(substr($lr->pesertaProfile->user->name, 0, 1)) }}
                                    </div>
                                @endif
                                <div>
                                    <p class="font-medium text-slate-800">{{ $lr->pesertaProfile->user->name }}</p>
                                    <p class="text-xs text-slate-500 font-mono">{{ $lr->pesertaProfile->nim ?? 'NIM Belum Diisi' }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-5 py-4 text-slate-600 tracking-tight">{{ $lr->tanggal_mulai->format('d M Y') }} <span class="mx-1 text-slate-400">s/d</span> {{ $lr->tanggal_selesai->format('d M Y') }}</td>
                        <td class="px-5 py-4">
                            <span class="inline-flex items-center rounded-full px-2.5 py-1 text-xs font-semibold ring-1 ring-inset {{ $lr->jenis === 'sakit' ? 'bg-blue-50 text-blue-700 ring-blue-600/20' : 'bg-amber-50 text-amber-700 ring-amber-600/20' }}">
                                {{ ucfirst($lr->jenis) }}
                            </span>
                        </td>
                        <td class="px-5 py-4">
                            <span class="inline-flex items-center gap-1.5 rounded-full px-2.5 py-1 text-xs font-semibold ring-1 ring-inset
                                @if($lr->status === 'approved') bg-emerald-50 text-emerald-700 ring-emerald-600/20
                                @elseif($lr->status === 'rejected') bg-rose-50 text-rose-700 ring-rose-600/20
                                @else bg-slate-100 text-slate-700 ring-slate-500/10 @endif">
                                
                                @if($lr->status === 'approved') <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span> Disetujui
                                @elseif($lr->status === 'rejected') <span class="h-1.5 w-1.5 rounded-full bg-rose-500"></span> Ditolak
                                @else <span class="h-1.5 w-1.5 rounded-full bg-slate-400"></span> Menunggu
                                @endif
                            </span>
                        </td>
                        <td class="px-5 py-4 text-right font-medium">
                            <a class="inline-flex items-center gap-1.5 text-indigo-600 hover:text-indigo-900 transition-colors" href="{{ route('pembimbing.leaves.show', $lr) }}">
                                Detail <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-5 py-16 text-center text-slate-500">
                            <div class="flex flex-col items-center justify-center">
                                <svg class="h-10 w-10 text-slate-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                                Belum ada pengajuan izin atau sakit.
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
