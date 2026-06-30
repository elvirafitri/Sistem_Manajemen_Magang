@extends('layouts.panel')

@section('title', 'Detail Peserta — Admin')
@section('page_title', 'Detail Peserta Magang')

@section('content')
<div class="mb-6 flex items-center justify-between">
    <h1 class="text-2xl font-bold text-slate-800">Profil Peserta</h1>
    <div class="flex gap-2">
        <a href="{{ route('admin.peserta.edit', $peserta) }}" class="rounded-lg border border-slate-200 bg-white px-4 py-2 text-sm font-medium text-slate-600 hover:bg-slate-50 hover:text-slate-900 transition-colors">Edit Profil</a>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    {{-- Left Column: Avatar & Basic Info --}}
    <div class="lg:col-span-1 flex flex-col gap-6">
        <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm transition-all duration-200 hover:shadow-md">
            <div class="relative h-24 bg-gradient-to-r from-blue-500 to-indigo-600"></div>
            <div class="relative -mt-12 flex flex-col items-center px-6 pb-8">
                @if($peserta->user->avatar_path)
                    <img src="{{ route('storage.file', $peserta->user->avatar_path) }}" alt="Avatar" class="h-24 w-24 rounded-full border-4 border-white object-cover shadow-lg">
                @else
                    <div class="flex h-24 w-24 items-center justify-center rounded-full border-4 border-white bg-blue-100 text-2xl font-bold text-blue-600 shadow-lg">
                        {{ strtoupper(substr($peserta->user->name, 0, 1)) }}
                    </div>
                @endif
                <h2 class="mt-4 text-xl font-bold text-slate-900">{{ $peserta->user->name }}</h2>
                <p class="text-sm font-medium text-slate-500">{{ $peserta->nim ?? 'NIM belum diisi' }}</p>
                <div class="mt-6 w-full border-t border-slate-100 pt-6">
                    <div class="flex flex-col gap-4">
                        <div class="flex items-center gap-3 text-slate-600">
                            <svg class="h-5 w-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                            <span class="text-sm">{{ $peserta->user->email }}</span>
                        </div>
                        <div class="flex items-center gap-3 text-slate-600">
                            <svg class="h-5 w-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                            <span class="text-sm">{{ $peserta->phone ?? 'Belum ada nomor telepon' }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            <h3 class="mb-4 text-sm font-bold uppercase tracking-wider text-slate-400">Pembimbing Lapangan</h3>
            <div class="flex items-center gap-4">
                <div class="flex h-12 w-12 items-center justify-center rounded-full bg-slate-100 text-slate-500 font-bold">
                    {{ strtoupper(substr($peserta->pembimbing?->user?->name ?? '—', 0, 1)) }}
                </div>
                <div>
                    <p class="font-bold text-slate-900">{{ $peserta->pembimbing?->user?->name ?? 'Belum ditentukan' }}</p>
                    <p class="text-xs text-slate-500">{{ $peserta->pembimbing?->user?->email ?? '—' }}</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Right Column: Detailed Info --}}
    <div class="lg:col-span-2 flex flex-col gap-6">
        <div class="rounded-2xl border border-slate-200 bg-white shadow-sm overflow-hidden">
            <div class="border-b border-slate-100 bg-slate-50/50 px-8 py-5">
                <h3 class="font-bold text-slate-800 text-lg">Informasi Akademik & Program</h3>
            </div>
            <div class="p-8">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-y-8 gap-x-12">
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-slate-400 mb-1.5">Jurusan / Program Studi</label>
                        <p class="text-slate-900 font-medium">{{ $peserta->jurusan ?? '—' }}</p>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-slate-400 mb-1.5">Institusi / Universitas</label>
                        <p class="text-slate-900 font-medium">{{ $peserta->institusi ?? '—' }}</p>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-slate-400 mb-1.5">Jenis Program</label>
                        <p class="text-slate-900 font-medium capitalize">{{ $peserta->jenis_program ?? '—' }}</p>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-slate-400 mb-1.5">Periode Magang</label>
                        <div class="flex items-center gap-2 mt-1">
                            <span class="inline-flex items-center rounded-md bg-blue-50 px-2 py-1 text-xs font-bold text-blue-700 ring-1 ring-inset ring-blue-600/20">
                                {{ $peserta->periode_mulai->format('d M Y') }}
                            </span>
                            <span class="text-slate-400">—</span>
                            <span class="inline-flex items-center rounded-md bg-blue-50 px-2 py-1 text-xs font-bold text-blue-700 ring-1 ring-inset ring-blue-600/20">
                                {{ $peserta->periode_selesai->format('d M Y') }}
                            </span>
                        </div>
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-xs font-semibold uppercase tracking-wider text-slate-400 mb-1.5">Alamat Lengkap</label>
                        <p class="text-slate-900 leading-relaxed">{{ $peserta->alamat ?? '—' }}</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Documents Section --}}
        <div class="rounded-2xl border border-slate-200 bg-white shadow-sm overflow-hidden">
            <div class="border-b border-slate-100 bg-slate-50/50 px-8 py-5">
                <h3 class="font-bold text-slate-800 text-lg">Dokumen Pendukung</h3>
            </div>
            <div class="p-8">
                @forelse($peserta->documents as $doc)
                    <div class="group flex items-center justify-between p-4 rounded-xl border border-slate-100 hover:border-blue-200 hover:bg-blue-50/30 transition-all mb-3 last:mb-0">
                        <div class="flex items-center gap-4">
                            <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-slate-100 text-slate-500 group-hover:bg-blue-100 group-hover:text-blue-600 transition-colors">
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                            </div>
                            <div>
                                <p class="font-bold text-slate-900">{{ $doc->nama }}</p>
                                <p class="text-xs text-slate-500">Diupload pada {{ $doc->created_at->format('d/m/Y H:i') }}</p>
                            </div>
                        </div>
                        <a href="{{ route('storage.file', $doc->path) }}" target="_blank" class="rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs font-bold text-slate-600 hover:bg-slate-50 hover:text-blue-600 transition-all shadow-sm">
                            Buka / Unduh
                        </a>
                    </div>
                @empty
                    <div class="text-center py-12 text-slate-500">
                        <svg class="mx-auto h-12 w-12 text-slate-200 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        <p class="font-medium">Belum ada dokumen diupload.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
