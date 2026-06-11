@extends('layouts.panel')

@section('title', 'Pembimbing — Admin')
@section('page_title', 'Manajemen akun pembimbing')

@section('content')
<div class="mb-4 flex flex-wrap items-center justify-between gap-3">
    <h1 class="text-xl font-bold text-slate-800">Daftar pembimbing</h1>
    <a href="{{ route('admin.pembimbing.create') }}" class="rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700">Tambah</a>
</div>

<div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
    <div class="overflow-x-auto">
        <table class="min-w-full text-left text-sm">
            <thead class="border-b border-slate-200 bg-slate-50 text-xs uppercase tracking-wider text-slate-500">
                <tr>
                    <th class="px-6 py-4 font-semibold">Nama</th>
                    <th class="px-6 py-4 font-semibold">Email</th>
                    <th class="px-6 py-4 font-semibold">NIP</th>
                    <th class="px-6 py-4 font-semibold text-center">Jumlah Peserta</th>
                    <th class="px-6 py-4 text-right font-semibold">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($pembimbing as $p)
                    <tr class="hover:bg-slate-50 transition-colors duration-200">
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
                        <td class="px-6 py-4 text-slate-600">{{ $p->user->email }}</td>
                        <td class="px-6 py-4 text-slate-600">{{ $p->nip ?? '—' }}</td>
                        <td class="px-6 py-4 text-center">
                            <span class="inline-flex items-center justify-center rounded-full bg-blue-50 px-2.5 py-0.5 text-xs font-semibold text-blue-700 ring-1 ring-inset ring-blue-700/10 min-w-[2rem]">{{ $p->peserta_bimbingan_count }}</span>
                        </td>
                        <td class="px-6 py-4 text-right font-medium">
                            <a class="text-indigo-600 hover:text-indigo-900 hover:underline transition-colors mr-3" href="{{ route('admin.pembimbing.edit', $p) }}">Ubah</a>
                            <form action="{{ route('admin.pembimbing.destroy', $p) }}" method="post" class="inline" onsubmit="return confirm('Nonaktifkan pembimbing? Data peserta tetap aman.');">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-rose-600 hover:text-rose-900 hover:underline transition-colors">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="px-6 py-12 text-center text-slate-500">Belum ada data pembimbing.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
<div class="mt-4">{{ $pembimbing->links() }}</div>
@endsection
