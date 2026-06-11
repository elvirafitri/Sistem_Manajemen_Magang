@extends('layouts.panel')

@section('title', 'Sertifikat — Admin')
@section('page_title', '')

@section('content')
<div class="mt-4">
    <div class="rounded-[14px] border border-slate-200/80 bg-white shadow-sm transition-all duration-200">
        {{-- Card Header Hierarchy --}}
        <div class="border-b border-slate-100 px-8 py-6">
            <h2 class="text-xl font-bold tracking-tight text-slate-900">Terbitkan Sertifikat</h2>
            <p class="mt-1.5 text-sm text-slate-500">Buat sertifikat PDF berdasarkan kehadiran dan penilaian akhir peserta secara otomatis.</p>
        </div>

        {{-- Form Content --}}
        <div class="px-8 py-7">
            <form action="{{ route('admin.sertifikat.generate') }}" method="post" class="flex flex-col gap-6">
                @csrf
                
                {{-- Pembimbing Dropdown --}}
                <div>
                    <label class="mb-2 block text-sm font-semibold text-slate-700" for="pembimbing_id">Filter Pembimbing</label>
                    <div class="relative transition-all duration-200">
                        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        </div>
                        <select id="pembimbing_id" class="block w-full appearance-none rounded-xl border border-slate-200 bg-slate-50/50 py-3.5 pl-11 pr-10 text-sm text-slate-900 transition-all duration-200 hover:border-slate-300 focus:border-blue-500 focus:bg-white focus:outline-none focus:ring-4 focus:ring-blue-500/10 cursor-pointer">
                            <option value="">-- Semua Pembimbing --</option>
                            @foreach($pembimbingList as $pembimbing)
                                <option value="{{ $pembimbing->id }}">{{ $pembimbing->user->name }}</option>
                            @endforeach
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-4 text-slate-400">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </div>
                    </div>
                </div>

                {{-- Peserta Dropdown --}}
                <div>
                    <label class="mb-2 block text-sm font-semibold text-slate-700" for="peserta_profile_id">Pilih Peserta Magang</label>
                    <div class="relative transition-all duration-200">
                        {{-- User Icon inside dropdown --}}
                        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                        </div>
                        
                        {{-- Select Input --}}
                        <select name="peserta_profile_id" id="peserta_profile_id" required disabled
                            class="block w-full appearance-none rounded-xl border border-slate-200 bg-slate-50/50 py-3.5 pl-11 pr-10 text-sm text-slate-900 transition-all duration-200 hover:border-slate-300 focus:border-blue-500 focus:bg-white focus:outline-none focus:ring-4 focus:ring-blue-500/10 cursor-pointer disabled:cursor-not-allowed disabled:bg-slate-100 disabled:text-slate-400 disabled:opacity-70 disabled:hover:border-slate-200">
                            <option value="" disabled selected>-- Pilih Pembimbing Terlebih Dahulu --</option>
                            @foreach($pesertaList as $p)
                                @php
                                    $hasFinalEvaluation = $p->evaluations->isNotEmpty();
                                @endphp
                                <option value="{{ $p->id }}" 
                                    data-pembimbing-id="{{ $p->pembimbing_id }}"
                                    data-has-evaluation="{{ $hasFinalEvaluation ? '1' : '0' }}"
                                    {{ !$hasFinalEvaluation ? 'disabled class=text-slate-400' : '' }}>
                                    {{ $p->nim }} — {{ $p->user->name }}{{ !$hasFinalEvaluation ? ' (Belum Ada Nilai Final)' : '' }}
                                </option>
                            @endforeach
                        </select>

                        {{-- Custom Arrow Icon --}}
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-4 text-slate-400">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </div>
                    </div>
                </div>

                {{-- Action Button --}}
                <div class="mt-2 text-right sm:text-left">
                    <button type="submit" 
                        class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-blue-600 px-6 py-3.5 text-sm font-semibold text-white shadow-sm transition-all duration-200 hover:-translate-y-0.5 hover:bg-blue-700 hover:shadow shadow-blue-500/30 focus:outline-none focus:ring-4 focus:ring-blue-500/20 sm:w-auto">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        Terbitkan Sertifikat
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Daftar Sertifikat --}}
<div class="mt-8 mb-6">
    <div class="mb-5 flex flex-wrap items-center justify-between gap-3">
        <div>
            <h3 class="text-lg font-bold tracking-tight text-slate-800">Daftar Sertifikat</h3>
            <p class="mt-1 text-sm text-slate-500">Sertifikat milik peserta magang yang telah berhasil diterbitkan.</p>
        </div>
    </div>

    <div class="overflow-hidden rounded-[14px] border border-slate-200/80 bg-white shadow-sm transition-all">
        <div class="overflow-x-auto">
            <table class="min-w-full text-left text-sm whitespace-nowrap">
                <thead class="border-b border-slate-200 bg-slate-50/70">
                    <tr>
                        <th class="px-5 py-4 font-semibold text-slate-800">Peserta</th>
                        <th class="px-5 py-4 font-semibold text-slate-800">NIM / NIK</th>
                        <th class="px-5 py-4 font-semibold text-slate-800">Tanggal Terbit</th>
                        <th class="px-5 py-4 font-semibold text-slate-800 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($certificates as $cert)
                        <tr class="transition-colors hover:bg-slate-50/70 group">
                            <td class="px-5 py-4 font-medium text-slate-900 group-hover:text-blue-700">{{ $cert->pesertaProfile->user->name }}</td>
                            <td class="px-5 py-4 text-slate-600 font-mono">{{ $cert->pesertaProfile->nim }}</td>
                            <td class="px-5 py-4 text-slate-500 tabular-nums">{{ $cert->created_at->format('d/m/Y H:i') }}</td>
                            <td class="px-5 py-4 text-center">
                                <a href="{{ route('admin.sertifikat.download', $cert->id) }}" class="inline-flex items-center gap-1.5 rounded-lg border border-emerald-200 bg-emerald-50 px-3 py-1.5 text-xs font-semibold text-emerald-700 transition-colors hover:bg-emerald-100 hover:text-emerald-800 focus:outline-none focus:ring-2 focus:ring-emerald-500">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                                    Unduh
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-5 py-12 text-center text-slate-500">
                                <div class="flex flex-col items-center justify-center">
                                    <svg class="h-10 w-10 text-slate-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                    Belum ada sertifikat yang diterbitkan.
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const pembimbingSelect = document.getElementById('pembimbing_id');
        const pesertaSelect = document.getElementById('peserta_profile_id');
        const pesertaOptions = Array.from(pesertaSelect.querySelectorAll('option[data-pembimbing-id]'));
        const defaultOption = pesertaSelect.querySelector('option[value=""]');

        pembimbingSelect.addEventListener('change', function () {
            const selectedPembimbing = this.value;
            
            // Bersihkan dropdown
            pesertaSelect.innerHTML = '';
            pesertaSelect.appendChild(defaultOption);
            defaultOption.selected = true;

            if (!selectedPembimbing) {
                // Kunci dropdown peserta jika tidak ada pembimbing dipilih
                pesertaSelect.disabled = true;
                defaultOption.textContent = '-- Pilih Pembimbing Terlebih Dahulu --';
                return;
            }
            
            // Buka dropdown peserta
            pesertaSelect.disabled = false;
            defaultOption.textContent = '-- Pilih Peserta --';
            
            let hasMatch = false;

            // Masukkan kembali opsi peserta yang cocok
            pesertaOptions.forEach(option => {
                if (selectedPembimbing === option.getAttribute('data-pembimbing-id')) {
                    pesertaSelect.appendChild(option);
                    hasMatch = true;
                }
            });

            // Jika tidak ada peserta untuk pembimbing tersebut
            if (!hasMatch) {
                const emptyOpt = document.createElement('option');
                emptyOpt.disabled = true;
                emptyOpt.selected = true;
                emptyOpt.textContent = 'Tidak ada peserta bimbingan';
                pesertaSelect.appendChild(emptyOpt);
            }
        });
    });
</script>
@endpush

