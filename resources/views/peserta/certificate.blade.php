@extends('layouts.panel')

@section('title', 'Sertifikat — Peserta')
@section('page_title', 'Hasil & Sertifikat')

@section('content')
<div class="mb-8">
    <h1 class="text-2xl font-bold tracking-tight text-slate-900">Hasil & Sertifikat</h1>
    <p class="mt-1 text-sm text-slate-500">Unduh sertifikat resmi penyelesaian program magang Anda.</p>
</div>

@if($message)
    <div class="max-w-lg space-y-4">
        <div class="rounded-[14px] border border-amber-200 bg-amber-50 p-5 flex items-start gap-3">
            <svg class="h-5 w-5 shrink-0 text-amber-500 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <div>
                <p class="text-sm font-bold text-amber-800">Sertifikat Belum Tersedia</p>
                <p class="mt-0.5 text-sm text-amber-700">{{ $message }}</p>
            </div>
        </div>

        <div class="rounded-[14px] border border-slate-200 bg-white p-5 shadow-sm">
            <p class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-3">Syarat Mendapatkan Sertifikat</p>
            <ul class="space-y-3">
                @php
                    $evalExists = !is_null($eval);
                    $syaratList = [
                        ['text' => 'Data profil magang sudah dilengkapi (NIM, dll.)', 'status' => !empty($profile->nim)],
                        ['text' => 'Penilaian akhir telah diberikan oleh pembimbing', 'status' => $evalExists],
                        ['text' => 'Sertifikat telah diterbitkan oleh Admin', 'status' => !is_null($certificate)],
                    ];
                @endphp
                @foreach($syaratList as $syarat)
                <li class="flex items-center gap-3 text-sm">
                    <span class="flex h-5 w-5 shrink-0 items-center justify-center rounded-full border {{ $syarat['status'] ? 'border-emerald-200 bg-emerald-50 text-emerald-600' : 'border-slate-200 bg-slate-50 text-slate-400' }}">
                        @if($syarat['status'])
                            <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3.5" d="M5 13l4 4L19 7"/></svg>
                        @else
                            <svg class="h-2.5 w-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M9 12l2 2 4-4"/></svg>
                        @endif
                    </span>
                    <span class="{{ $syarat['status'] ? 'text-slate-500 line-through decoration-slate-300' : 'text-slate-700 font-medium' }}">
                        {{ $syarat['text'] }}
                    </span>
                </li>
                @endforeach
            </ul>
        </div>
    </div>

@elseif($certificate)
    <div class="max-w-4xl mx-auto space-y-6">
        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8">
            <h2 class="mb-6 text-xl font-bold uppercase tracking-wide text-slate-800 border-b border-slate-100 pb-4">Sertifikat Peserta Magang</h2>
            
            <div class="mb-6 space-y-1 text-sm text-slate-700">
                <p><span class="font-semibold w-36 inline-block">Nama Lengkap</span> : {{ $profile->user->name }}</p>
                <p><span class="font-semibold w-36 inline-block">Periode Magang</span> : {{ $profile->periode_mulai->format('d-m-Y') }} s/d {{ $profile->periode_selesai->format('d-m-Y') }}</p>
            </div>

            <div class="overflow-hidden rounded-xl border border-slate-200 shadow-sm">
                <div class="overflow-x-auto">
                    <table class="min-w-full text-left text-sm whitespace-nowrap">
                        <thead class="bg-[#00B1C0] text-white">
                            <tr>
                                <th class="px-5 py-4 font-bold text-center w-16">No</th>
                                <th class="px-5 py-4 font-bold">Nama Peserta</th>
                                <th class="px-5 py-4 font-bold">File Sertifikat</th>
                                <th class="px-5 py-4 font-bold">Tanggal Terbit</th>
                                <th class="px-5 py-4 font-bold">Status</th>
                                <th class="px-5 py-4 font-bold text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 bg-white">
                            <tr class="transition-colors hover:bg-slate-50">
                                <td class="px-5 py-4 text-center font-medium text-slate-700">1</td>
                                <td class="px-5 py-4 font-semibold text-slate-800">{{ $profile->user->name }}</td>
                                <td class="px-5 py-4 text-blue-600 font-medium">sertifikat-magang.pdf</td>
                                <td class="px-5 py-4 text-slate-600">{{ $certificate->created_at->format('d-m-Y') }}</td>
                                <td class="px-5 py-4">
                                    <span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-50 px-2.5 py-1 text-xs font-semibold text-emerald-700 ring-1 ring-inset ring-emerald-600/20">
                                        Selesai
                                    </span>
                                </td>
                                <td class="px-5 py-4 text-center">
                                    <a href="{{ route('peserta.certificate.download') }}" class="inline-flex items-center justify-center rounded-lg bg-[#00B1C0] p-2 text-white shadow-sm transition hover:bg-[#00929e] focus:outline-none focus:ring-2 focus:ring-[#00B1C0]" title="Unduh Sertifikat">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                                    </a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <p class="mt-4 text-xs italic text-slate-500">
                *Tekan tombol <span class="inline-flex items-center justify-center rounded bg-[#00B1C0] p-1 mx-0.5"><svg class="h-3 w-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg></span> untuk mengunduh sertifikat Anda.
            </p>
            

        </div>
    </div>
@endif
@endsection
