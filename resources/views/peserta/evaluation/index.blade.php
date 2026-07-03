@extends('layouts.panel')

@section('title', 'Penilaian Magang — Peserta')
@section('page_title', 'Penilaian Magang')

@section('content')
<div class="mb-6 flex flex-wrap items-center justify-between gap-4">
    <div>
        <h1 class="text-2xl font-bold tracking-tight text-slate-900">Penilaian Magang</h1>
        <p class="mt-1 text-sm text-slate-500">Lihat hasil evaluasi kinerja magang dari pembimbing Anda.</p>
    </div>
    @if($evaluation && $evaluation->is_final)
    <a href="{{ route('peserta.evaluation.download') }}" class="inline-flex items-center gap-2 rounded-xl bg-emerald-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2">
        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
        </svg>
        Unduh PDF
    </a>
    @endif
</div>

@if(!$evaluation)
<div class="flex flex-col items-center justify-center rounded-[14px] border border-slate-200/80 bg-white p-12 text-center shadow-sm">
    <svg class="mb-4 h-12 w-12 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
    </svg>
    <h3 class="text-lg font-bold text-slate-800">Belum Ada Penilaian</h3>
    <p class="mt-2 text-sm text-slate-500 max-w-md">Pembimbing Anda belum memasukkan penilaian untuk kinerja magang Anda.</p>
</div>
@else
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2 space-y-6">
        <div class="rounded-2xl border border-slate-200/80 bg-white shadow-sm overflow-hidden">
            <div class="border-b border-slate-100 bg-slate-50/50 px-6 py-4">
                <h2 class="text-base font-bold text-slate-800">Rincian Nilai</h2>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-slate-700">
                    <thead class="bg-slate-50 text-slate-900 border-b border-slate-200">
                        <tr>
                            <th class="px-5 py-3 font-semibold text-center w-12">No</th>
                            <th class="px-5 py-3 font-semibold">Aspek Penilaian</th>
                            <th class="px-5 py-3 font-semibold text-right w-32">Nilai Angka</th>
                            <th class="px-5 py-3 font-semibold text-center w-32">Nilai Huruf</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($evaluation->rubricScores as $index => $score)
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="px-5 py-3 text-center font-medium text-slate-500">{{ $index + 1 }}</td>
                            <td class="px-5 py-3 font-medium text-slate-800">{{ $score->rubric->nama }}</td>
                            <td class="px-5 py-3 text-right font-mono font-semibold {{ $score->nilai >= 70 ? 'text-blue-600' : ($score->nilai >= 55 ? 'text-amber-600' : 'text-red-600') }}">
                                {{ $score->nilai }}
                            </td>
                            <td class="px-5 py-3 text-center">
                                <span class="inline-flex items-center justify-center rounded-md px-2.5 py-1 text-xs font-bold ring-1 ring-inset {{ $score->predikat == 'A' ? 'bg-emerald-100 text-emerald-700 ring-emerald-600/20' : ($score->predikat == 'B' ? 'bg-blue-100 text-blue-700 ring-blue-600/20' : ($score->predikat == 'C' ? 'bg-amber-100 text-amber-700 ring-amber-600/20' : 'bg-red-100 text-red-700 ring-red-600/20')) }}">
                                    {{ $score->predikat }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-5 py-8 text-center text-slate-500 italic">Rincian nilai belum dimasukkan.</td>
                        </tr>
                        @endforelse
                    </tbody>
                    <tfoot class="bg-slate-50 border-t border-slate-200">
                        <tr>
                            <td colspan="2" class="px-5 py-4 text-right font-bold text-slate-800">NILAI AKHIR (RATA-RATA) :</td>
                            <td class="px-5 py-4 text-right font-mono text-xl font-bold text-blue-600">{{ $evaluation->total_nilai ?? '-' }}</td>
                            <td class="px-5 py-4 text-center">
                                @if($evaluation->total_nilai !== null)
                                <span class="inline-flex items-center justify-center rounded-md px-3 py-1.5 text-base font-bold ring-1 ring-inset {{ $evaluation->predikat == 'A' ? 'bg-emerald-100 text-emerald-700 ring-emerald-600/20' : ($evaluation->predikat == 'B' ? 'bg-blue-100 text-blue-700 ring-blue-600/20' : ($evaluation->predikat == 'C' ? 'bg-amber-100 text-amber-700 ring-amber-600/20' : 'bg-red-100 text-red-700 ring-red-600/20')) }}">
                                    {{ $evaluation->predikat }}
                                </span>
                                @else
                                -
                                @endif
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

        @if($evaluation->komentar_final)
        <div class="rounded-2xl border border-blue-200 bg-blue-50/50 p-6 shadow-sm">
            <h3 class="mb-2 text-sm font-bold uppercase tracking-wider text-blue-800 flex items-center gap-2">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                </svg>
                Komentar Pembimbing
            </h3>
            <p class="text-sm leading-relaxed text-blue-900">{{ $evaluation->komentar_final }}</p>
        </div>
        @endif
    </div>

    <div class="space-y-6">
        <div class="rounded-2xl border border-slate-200/80 bg-white p-6 shadow-sm flex flex-col items-center justify-center text-center">
            <div class="mb-2 text-sm font-bold text-slate-500 uppercase tracking-widest">Predikat Penilaian</div>

            @php
            $predikat = $evaluation->predikat;
            $kategoriColor = match($predikat) {
            'A' => 'bg-emerald-100 text-emerald-700 ring-emerald-500/20',
            'B' => 'bg-blue-100 text-blue-700 ring-blue-500/20',
            'C' => 'bg-amber-100 text-amber-700 ring-amber-500/20',
            'D', 'E' => 'bg-red-100 text-red-700 ring-red-500/20',
            default => 'bg-slate-100 text-slate-700 ring-slate-500/20',
            };
            @endphp

            <div class="inline-flex items-center rounded-full px-6 py-2 text-2xl font-black ring-1 ring-inset {{ $kategoriColor }}">
                {{ $predikat }}
            </div>

            <div class="mt-6 w-full space-y-2 text-left text-xs text-slate-500">
                <div class="flex justify-between border-b border-slate-100 pb-1"><span>A (Sangat Baik)</span> <span class="font-semibold text-slate-700">85 - 100</span></div>
                <div class="flex justify-between border-b border-slate-100 pb-1"><span>B (Baik)</span> <span class="font-semibold text-slate-700">70 - 84.99</span></div>
                <div class="flex justify-between border-b border-slate-100 pb-1"><span>C (Cukup)</span> <span class="font-semibold text-slate-700">55 - 69.99</span></div>
                <div class="flex justify-between border-b border-slate-100 pb-1"><span>D (Kurang)</span> <span class="font-semibold text-slate-700">40 - 54.99</span></div>
                <div class="flex justify-between"><span>E (Gagal)</span> <span class="font-semibold text-slate-700">0 - 39.99</span></div>
            </div>
        </div>
    </div>
</div>
@endif
@endsection