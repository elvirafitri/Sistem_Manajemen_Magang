@extends('layouts.panel')

@section('title', 'Izin / sakit — Peserta')
@section('page_title', 'Pengajuan izin atau sakit')

@section('content')
<div class="mb-6 flex flex-wrap items-center justify-between gap-3">
    <div>
        <h1 class="text-2xl font-bold tracking-tight text-slate-900">Formulir Pengajuan</h1>
        <p class="mt-1 text-sm text-slate-500">Ajukan izin atau sakit beserta bukti lampiran untuk dikonfirmasi pembimbing.</p>
    </div>

</div>

<div class="rounded-[14px] border border-slate-200 bg-white p-6 shadow-sm md:p-8 transition-all hover:shadow-md hover:border-slate-300">
    <form action="{{ route('peserta.leave.store') }}" method="post" enctype="multipart/form-data" class="space-y-6">
        @csrf

        {{-- Jenis Pengajuan dulu (di atas) --}}
        <div>
            <label class="mb-3 block text-sm font-semibold text-slate-700">Jenis Pengajuan <span class="text-rose-500">*</span></label>
            <div class="grid grid-cols-2 gap-4">
                <label id="card-izin" class="group relative flex cursor-pointer rounded-[14px] border-2 p-4 shadow-sm transition-all hover:shadow {{ (old('jenis')==='izin' || empty(old('jenis'))) ? 'border-blue-500 bg-blue-50/50' : 'border-slate-200 bg-white hover:bg-slate-50' }}">
                    <input type="radio" name="jenis" value="izin" class="sr-only" required @checked(old('jenis')==='izin' || empty(old('jenis')))>
                    <div class="flex w-full items-center gap-3">
                        <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-amber-50 text-amber-600 ring-1 ring-inset ring-amber-500/20 transition-colors group-hover:bg-amber-100">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <div class="flex-1">
                            <p class="font-bold text-slate-900">Izin</p>
                            <p class="text-xs text-slate-500 mt-0.5">Keperluan pribadi, dll.</p>
                        </div>
                        <div class="h-5 w-5 shrink-0 rounded-full border-2 flex items-center justify-center transition-colors {{ (old('jenis')==='izin' || empty(old('jenis'))) ? 'border-blue-500 bg-blue-500' : 'border-slate-300' }} check-circle">
                            <svg class="h-3 w-3 text-white transition-opacity {{ (old('jenis')==='izin' || empty(old('jenis'))) ? 'opacity-100' : 'opacity-0' }}" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                        </div>
                    </div>
                </label>

                <label id="card-sakit" class="group relative flex cursor-pointer rounded-[14px] border-2 p-4 shadow-sm transition-all hover:shadow {{ old('jenis')==='sakit' ? 'border-blue-500 bg-blue-50/50' : 'border-slate-200 bg-white hover:bg-slate-50' }}">
                    <input type="radio" name="jenis" value="sakit" class="sr-only" required @checked(old('jenis')==='sakit')>
                    <div class="flex w-full items-center gap-3">
                        <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-rose-50 text-rose-600 ring-1 ring-inset ring-rose-500/20 transition-colors group-hover:bg-rose-100">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                        </div>
                        <div class="flex-1">
                            <p class="font-bold text-slate-900">Sakit</p>
                            <p class="text-xs text-slate-500 mt-0.5">Sertakan surat dokter.</p>
                        </div>
                        <div class="h-5 w-5 shrink-0 rounded-full border-2 flex items-center justify-center transition-colors {{ old('jenis')==='sakit' ? 'border-blue-500 bg-blue-500' : 'border-slate-300' }} check-circle">
                            <svg class="h-3 w-3 text-white transition-opacity {{ old('jenis')==='sakit' ? 'opacity-100' : 'opacity-0' }}" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                        </div>
                    </div>
                </label>
            </div>
        </div>

        {{-- Tanggal --}}
        <div class="grid gap-5 sm:grid-cols-2">
            <div>
                <label class="mb-1.5 block text-sm font-semibold text-slate-700">Tanggal Mulai <span class="text-rose-500">*</span></label>
                <input type="date" name="tanggal_mulai" value="{{ old('tanggal_mulai') }}" required class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm outline-none transition-all focus:border-blue-500 focus:ring-1 focus:ring-blue-500 bg-slate-50 focus:bg-white">
                @error('tanggal_mulai')<p class="mt-1.5 text-xs font-medium text-rose-500">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="mb-1.5 block text-sm font-semibold text-slate-700">Tanggal Selesai <span class="text-rose-500">*</span></label>
                <input type="date" name="tanggal_selesai" value="{{ old('tanggal_selesai') }}" required class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm outline-none transition-all focus:border-blue-500 focus:ring-1 focus:ring-blue-500 bg-slate-50 focus:bg-white">
                @error('tanggal_selesai')<p class="mt-1.5 text-xs font-medium text-rose-500">{{ $message }}</p>@enderror
            </div>
        </div>

        {{-- Alasan --}}
        <div>
            <label class="mb-1.5 block text-sm font-semibold text-slate-700">Keterangan / Alasan <span class="text-rose-500">*</span></label>
            <textarea name="alasan" rows="4" required placeholder="Tuliskan alasan lengkap mengapa Anda mengajukan izin/sakit..." class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm outline-none transition-all placeholder:text-slate-400 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 bg-slate-50 focus:bg-white">{{ old('alasan') }}</textarea>
            @error('alasan')<p class="mt-1.5 text-xs font-medium text-rose-500">{{ $message }}</p>@enderror
        </div>

        {{-- File Upload --}}
        <div>
            <label class="mb-1.5 block text-sm font-semibold text-slate-700">
                Bukti Lampiran <span id="lampiran-required" class="{{ old('jenis') === 'sakit' ? '' : 'hidden' }} text-rose-500">*</span>
                <span class="ml-1 text-xs font-normal text-slate-400">(Surat Dokter, Wajib jika "Sakit" — PDF, JPG, PNG)</span>
            </label>
            <div class="flex items-center gap-4 rounded-xl border-2 border-dashed border-slate-300 bg-slate-50 p-5 transition-all focus-within:border-blue-500 focus-within:bg-blue-50/30 hover:border-slate-400">
                <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-white border border-slate-200 shadow-sm text-slate-400">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                </div>
                <div class="flex-1">
                    <input type="file" name="bukti" accept=".pdf,.jpg,.jpeg,.png" class="w-full text-sm text-slate-500 file:mr-3 file:rounded-xl file:border-0 file:bg-blue-600 file:px-4 file:py-2 file:text-sm file:font-semibold file:text-white hover:file:bg-blue-700 file:transition-colors file:shadow-sm cursor-pointer">
                    <p class="mt-1.5 text-xs text-slate-400">Maks. ukuran 5MB</p>
                </div>
            </div>
            @error('bukti')<p class="mt-1.5 text-xs font-medium text-rose-500">{{ $message }}</p>@enderror
        </div>

        <div class="flex items-center justify-end gap-3 border-t border-slate-100 pt-6">
            <a href="{{ route('peserta.dashboard') }}" class="rounded-xl px-5 py-2.5 text-sm font-semibold text-slate-600 hover:bg-slate-100 hover:text-slate-900 transition-colors">Batal</a>
            <button type="submit" class="inline-flex items-center gap-2 rounded-xl bg-blue-600 px-6 py-2.5 text-sm font-bold text-white shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-all">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                Kirim Pengajuan
            </button>
        </div>
    </form>
</div>

{{-- Riwayat Izin/Sakit --}}
<div class="mt-8 rounded-[14px] border border-slate-200 bg-white shadow-sm md:p-8 p-6">
    <h3 class="mb-4 text-lg font-bold text-slate-800">Riwayat Pengajuan</h3>
    <div class="space-y-4">
        @forelse($history as $item)
            <div class="flex flex-col gap-2 rounded-xl border {{ $item->status === 'approved' ? 'border-emerald-200 bg-emerald-50/50' : ($item->status === 'rejected' ? 'border-rose-200 bg-rose-50/50' : 'border-slate-200 bg-slate-50') }} p-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <div class="flex items-center gap-2">
                        <span class="font-bold uppercase tracking-wide text-xs {{ $item->jenis === 'sakit' ? 'text-rose-600' : 'text-amber-600' }}">
                            {{ $item->jenis }}
                        </span>
                        <span class="text-slate-400 text-xs">•</span>
                        <span class="text-slate-600 text-sm font-medium">
                            {{ \Carbon\Carbon::parse($item->tanggal_mulai)->format('d M Y') }} — {{ \Carbon\Carbon::parse($item->tanggal_selesai)->format('d M Y') }}
                        </span>
                    </div>
                    <p class="mt-1 text-sm text-slate-700 font-medium">{{ $item->alasan }}</p>
                </div>
                <div>
                    @if($item->status === 'approved')
                        <span class="inline-flex items-center gap-1.5 rounded-lg bg-emerald-100 px-2.5 py-1 text-xs font-semibold text-emerald-800">Disetujui</span>
                    @elseif($item->status === 'rejected')
                        <span class="inline-flex items-center gap-1.5 rounded-lg bg-rose-100 px-2.5 py-1 text-xs font-semibold text-rose-800">Ditolak</span>
                    @else
                        <span class="inline-flex items-center gap-1.5 rounded-lg bg-slate-200 px-2.5 py-1 text-xs font-semibold text-slate-700">Menunggu</span>
                    @endif
                </div>
            </div>
        @empty
            <div class="rounded-xl border border-dashed border-slate-300 p-6 text-center text-sm text-slate-500">
                Belum ada riwayat pengajuan izin atau sakit.
            </div>
        @endforelse
    </div>
</div>
@endsection

@push('scripts')
<script>
    const radioCards = {
        'izin': document.getElementById('card-izin'),
        'sakit': document.getElementById('card-sakit'),
    };

    function updateCardStyles(selectedValue) {
        Object.entries(radioCards).forEach(([value, card]) => {
            const circle = card.querySelector('.check-circle');
            const checkSvg = circle.querySelector('svg');
            const isSelected = value === selectedValue;

            if (isSelected) {
                card.classList.add('border-blue-500', 'bg-blue-50/50');
                card.classList.remove('border-slate-200', 'bg-white');
                circle.classList.add('border-blue-500', 'bg-blue-500');
                circle.classList.remove('border-slate-300');
                checkSvg.classList.add('opacity-100');
                checkSvg.classList.remove('opacity-0');

                if (value === 'sakit') {
                    document.getElementById('lampiran-required').classList.remove('hidden');
                } else {
                    document.getElementById('lampiran-required').classList.add('hidden');
                }
            } else {
                card.classList.remove('border-blue-500', 'bg-blue-50/50');
                card.classList.add('border-slate-200', 'bg-white');
                circle.classList.remove('border-blue-500', 'bg-blue-500');
                circle.classList.add('border-slate-300');
                checkSvg.classList.remove('opacity-100');
                checkSvg.classList.add('opacity-0');
            }
        });
    }

    document.querySelectorAll('input[type="radio"][name="jenis"]').forEach(radio => {
        radio.addEventListener('change', function() {
            updateCardStyles(this.value);
        });
    });
</script>
@endpush
