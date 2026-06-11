@extends('layouts.panel')

@section('title', 'Peserta Magang — Pembimbing')
@section('page_title', 'Daftar Peserta Bimbingan')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-slate-800">Peserta Bimbingan Anda</h1>
    <p class="text-sm text-slate-500 mt-1">Daftar seluruh peserta magang yang berada di bawah bimbingan Anda.</p>
</div>

<div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
    <div class="overflow-x-auto">
        <table class="min-w-full text-left text-sm">
            <thead class="border-b border-slate-200 bg-slate-50 text-xs uppercase tracking-wider text-slate-500">
                <tr>
                    <th class="px-6 py-4 font-semibold">NIM</th>
                    <th class="px-6 py-4 font-semibold">Nama Peserta</th>
                    <th class="px-6 py-4 font-semibold">Institusi</th>
                    <th class="px-6 py-4 font-semibold">Periode</th>
                    <th class="px-6 py-4 text-right font-semibold">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($peserta as $p)
                    <tr class="hover:bg-slate-50 transition-colors duration-200">
                        <td class="px-6 py-4 font-mono text-sm text-slate-600">{{ $p->nim ?? '—' }}</td>
                        <td class="px-6 py-4 font-medium text-slate-800">
                            <div class="flex items-center gap-3">
                                @if($p->user->avatar_path)
                                    <img src="{{ route('storage.file', $p->user->avatar_path) }}" alt="Avatar" class="h-8 w-8 rounded-full object-cover border border-slate-200 shadow-sm">
                                @else
                                    <div class="flex h-8 w-8 items-center justify-center rounded-full bg-blue-100 text-[11px] font-bold tracking-wider text-blue-700 shadow-sm ring-1 ring-white">
                                        {{ strtoupper(substr($p->user->name, 0, 1)) }}
                                    </div>
                                @endif
                                <span>{{ $p->user->name }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-slate-600">{{ $p->institusi ?? '—' }}</td>
                        <td class="px-6 py-4 text-slate-600 tracking-tight whitespace-nowrap text-xs">
                            {{ $p->periode_mulai->format('d/m/Y') }} – {{ $p->periode_selesai->format('d/m/Y') }}
                        </td>
                        <td class="px-6 py-4 text-right font-medium">
                            <a class="inline-flex items-center gap-1.5 rounded-lg border border-blue-200 bg-blue-50 px-3 py-1.5 text-xs font-semibold text-blue-700 transition hover:bg-blue-100" href="{{ route('pembimbing.peserta.show', $p) }}">
                                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                Lihat Profil
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="px-6 py-12 text-center text-slate-500">Belum ada data peserta bimbingan.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
<div class="mt-4">{{ $peserta->links() }}</div>
@endsection
