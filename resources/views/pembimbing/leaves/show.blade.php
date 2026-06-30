@extends('layouts.panel')

@section('title', 'Detail Pengajuan — Pembimbing')
@section('page_title', 'Detail izin / sakit')

@section('content')
<div class="mb-6 flex flex-wrap items-center justify-between gap-3">
    <div>
        <h1 class="text-2xl font-bold tracking-tight text-slate-900">Detail Pengajuan</h1>
        <p class="mt-1 text-sm text-slate-500">Tinjau informasi perizinan/sakit peserta sebelum menyetujui.</p>
    </div>
</div>

<div class="overflow-hidden rounded-[14px] border border-slate-200/80 bg-white shadow-sm">
    <div class="p-6 sm:p-8">
        {{-- Header Info --}}
        <div class="flex items-start justify-between border-b border-slate-100 pb-6 mb-6">
            <div class="flex items-center gap-4">
                @if($leave->pesertaProfile->user->avatar_path)
                    <img src="{{ route('storage.file', $leave->pesertaProfile->user->avatar_path) }}" alt="Avatar" class="h-16 w-16 rounded-full object-cover border border-slate-200 shadow-sm ring-4 ring-slate-50">
                @else
                    <div class="flex h-16 w-16 items-center justify-center rounded-full bg-blue-100 text-xl font-bold tracking-wider text-blue-700 shadow-sm ring-4 ring-white">
                        {{ strtoupper(substr($leave->pesertaProfile->user->name, 0, 1)) }}
                    </div>
                @endif
                <div>
                    <h3 class="text-lg font-bold text-slate-800">{{ $leave->pesertaProfile->user->name }}</h3>
                    <p class="text-sm font-medium text-slate-500 font-mono">{{ $leave->pesertaProfile->nim ?? 'NIM Belum Diisi' }}</p>
                </div>
            </div>
            <div>
                <span class="inline-flex items-center gap-1.5 rounded-full px-3 py-1.5 text-sm font-semibold ring-1 ring-inset
                    @if($leave->status === 'approved') bg-emerald-50 text-emerald-700 ring-emerald-600/20
                    @elseif($leave->status === 'rejected') bg-rose-50 text-rose-700 ring-rose-600/20
                    @else bg-slate-100 text-slate-700 ring-slate-500/10 @endif">
                    
                    @if($leave->status === 'approved') <span class="h-2 w-2 rounded-full bg-emerald-500"></span> Disetujui
                    @elseif($leave->status === 'rejected') <span class="h-2 w-2 rounded-full bg-rose-500"></span> Ditolak
                    @else <span class="h-2 w-2 rounded-full bg-slate-400"></span> Menunggu
                    @endif
                </span>
            </div>
        </div>

        {{-- Details Detail --}}
        <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-6">
            <div>
                <dt class="text-xs font-semibold uppercase tracking-wide text-slate-500 mb-1">Jenis Pengajuan</dt>
                <dd class="text-sm font-medium text-slate-800">
                    <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold ring-1 ring-inset {{ $leave->jenis === 'sakit' ? 'bg-blue-50 text-blue-700 ring-blue-600/20' : 'bg-amber-50 text-amber-700 ring-amber-600/20' }}">
                        {{ ucfirst($leave->jenis) }}
                    </span>
                </dd>
            </div>
            <div>
                <dt class="text-xs font-semibold uppercase tracking-wide text-slate-500 mb-1">Periode Tanggal</dt>
                <dd class="text-sm font-medium text-slate-800 tracking-tight">{{ $leave->tanggal_mulai->format('d M Y') }} – {{ $leave->tanggal_selesai->format('d M Y') }}</dd>
            </div>
            
            <div class="sm:col-span-2">
                <dt class="text-xs font-semibold uppercase tracking-wide text-slate-500 mb-2">Alasan</dt>
                <dd class="text-sm text-slate-700 bg-slate-50 p-4 rounded-xl border border-slate-100 leading-relaxed">{{ $leave->alasan }}</dd>
            </div>

            @if($leave->bukti_path)
            <div class="sm:col-span-2">
                <dt class="text-xs font-semibold uppercase tracking-wide text-slate-500 mb-2">Bukti Lampiran</dt>
                <dd>
                    <a href="{{ route('storage.file', $leave->bukti_path) }}" target="_blank" class="inline-flex items-center gap-2 rounded-lg border border-slate-200 bg-white px-4 py-2 text-sm font-medium text-indigo-600 shadow-sm hover:bg-slate-50 hover:text-indigo-700 transition-colors">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/></svg>
                        Lihat File Lampiran
                    </a>
                </dd>
            </div>
            @endif
        </dl>
    </div>

    @if($leave->status === 'pending')
    <div class="border-t border-slate-100 bg-slate-50/50 p-6 sm:p-8">
        <h4 class="mb-4 text-sm font-bold text-slate-800">Aksi Verifikasi</h4>
        
        <div class="flex flex-col gap-6 sm:flex-row sm:items-start">
            <form action="{{ route('pembimbing.leaves.approve', $leave) }}" method="post" class="flex-shrink-0">
                @csrf
                <button type="submit" class="inline-flex items-center justify-center gap-2 w-full sm:w-auto rounded-xl bg-emerald-600 px-6 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 transition-all">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    Setujui Izin
                </button>
            </form>

            <div class="h-px w-full bg-slate-200 sm:h-12 sm:w-px"></div>

            <form action="{{ route('pembimbing.leaves.reject', $leave) }}" method="post" class="flex-1 w-full">
                @csrf
                <div class="mb-3">
                    <label class="mb-1.5 block text-xs font-semibold uppercase tracking-wider text-slate-500" for="catatan_pembimbing">Catatan Penolakan <span class="normal-case tracking-normal font-normal text-slate-400 ml-1">(Opsional)</span></label>
                    <input type="text" name="catatan_pembimbing" id="catatan_pembimbing" placeholder="Tuliskan alasan penolakan di sini..." class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm outline-none transition-all focus:border-rose-500 focus:ring-1 focus:ring-rose-500 bg-white">
                </div>
                <button type="submit" class="inline-flex w-full sm:w-auto items-center justify-center gap-2 rounded-xl bg-white border border-rose-600 px-6 py-2.5 text-sm font-semibold text-rose-600 shadow-sm hover:bg-rose-50 focus:outline-none focus:ring-2 focus:ring-rose-500 focus:ring-offset-2 transition-all">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    Tolak Pengajuan
                </button>
            </form>
        </div>
    </div>
    @endif
</div>
@endsection
