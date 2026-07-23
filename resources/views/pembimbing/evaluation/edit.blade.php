@extends('layouts.panel')

@section('title', 'Rubrik Penilaian — Pembimbing')
@section('page_title', 'Isi rubrik penilaian')

@section('content')
<div class="mb-6 flex flex-wrap items-center justify-between gap-3">
    <div class="flex items-center gap-4">
        @if($peserta->user->avatar_path)
        <img src="{{ route('storage.file', $peserta->user->avatar_path) }}" alt="Avatar" class="h-14 w-14 rounded-full object-cover border border-slate-200 shadow-sm ring-4 ring-slate-50">
        @else
        <div class="flex h-14 w-14 items-center justify-center rounded-full bg-blue-100 text-lg font-bold tracking-wider text-blue-700 shadow-sm ring-4 ring-white">
            {{ strtoupper(substr($peserta->user->name, 0, 1)) }}
        </div>
        @endif
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-slate-900">{{ $peserta->user->name }}</h1>
            <p class="mt-0.5 text-sm font-medium text-slate-500 font-mono">NIM {{ $peserta->nim ?? '-' }}</p>
        </div>
    </div>
</div>

{{-- Portofolio & Data Pendukung Penilaian --}}
<div class="mb-6 rounded-2xl border border-slate-200 bg-white shadow-sm overflow-hidden">

    <div class="bg-slate-50 border-b border-slate-200 px-6 py-4">
        <h2 class="text-lg font-bold text-slate-800">
            Portofolio & Data Pendukung Penilaian
        </h2>

        <p class="text-sm text-slate-500 mt-1">
            Informasi rekap dan riwayat aktivitas ini digunakan sebagai data pendukung portofolio bagi pembimbing sebelum memberikan penilaian.
        </p>
    </div>

    <div class="p-6">

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div>
                <span class="text-slate-500 text-sm">Program Magang</span>
                <p class="font-semibold text-slate-800">{{ $peserta->jenis_program }}</p>
            </div>

            <div>
                <span class="text-slate-500 text-sm">Periode Magang</span>
                <p class="font-semibold text-slate-800">
                    {{ \Carbon\Carbon::parse($peserta->periode_mulai)->translatedFormat('d F Y') }}
                    -
                    {{ \Carbon\Carbon::parse($peserta->periode_selesai)->translatedFormat('d F Y') }}
                </p>
            </div>
        </div>

        <!-- Tab Navigation untuk Portofolio/Rekap -->
        <div class="border-b border-slate-200 mb-6">
            <nav class="-mb-px flex justify-center space-x-8" aria-label="Tabs">
                <button type="button" id="tab-rekap-btn" onclick="switchTab('rekap')"
                    class="border-blue-600 text-blue-600 whitespace-nowrap py-3 px-1 border-b-2 font-semibold text-sm flex items-center gap-2">
                    📊 Rekap Kehadiran
                </button>
                <button type="button" id="tab-riwayat-btn" onclick="switchTab('riwayat')"
                    class="border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300 whitespace-nowrap py-3 px-1 border-b-2 font-semibold text-sm flex items-center gap-2">
                    📋 Riwayat Presensi Detail (Portofolio)
                </button>
            </nav>
        </div>

        <!-- TAB CONTENT 1: Rekap Kehadiran -->
        <div id="tab-rekap-content">
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                <div class="rounded-lg bg-green-50 border border-green-200 p-4 text-center">
                    <div class="text-sm text-slate-500">Hadir</div>
                    <div class="text-2xl font-bold text-green-600">{{ $rekap['hadir'] }}</div>
                </div>

                <div class="rounded-lg bg-blue-50 border border-blue-200 p-4 text-center">
                    <div class="text-sm text-slate-500">Izin</div>
                    <div class="text-2xl font-bold text-blue-600">{{ $rekap['izin'] }}</div>
                </div>

                <div class="rounded-lg bg-yellow-50 border border-yellow-200 p-4 text-center">
                    <div class="text-sm text-slate-500">Sakit</div>
                    <div class="text-2xl font-bold text-yellow-600">{{ $rekap['sakit'] }}</div>
                </div>

                <div class="rounded-lg bg-red-50 border border-red-200 p-4 text-center">
                    <div class="text-sm text-slate-500">Alpa</div>
                    <div class="text-2xl font-bold text-red-600">{{ $rekap['alpa'] }}</div>
                </div>
            </div>
        </div>

        <!-- TAB CONTENT 2: Riwayat Presensi Detail -->
        <div id="tab-riwayat-content" class="hidden">

            <!-- Informasi Jumlah Data -->
            <div class="mb-3 flex items-center justify-between text-xs text-slate-500 px-1">
                <span>Menampilkan seluruh riwayat presensi peserta</span>
                <span class="font-semibold text-slate-700">Total: {{ $peserta->attendances->count() }} Hari Presensi</span>
            </div>

            <!-- Container dengan Max Height & Sticky Header -->
            <div class="overflow-x-auto overflow-y-auto max-h-[380px] rounded-xl border border-slate-200 shadow-inner">
                <table class="w-full text-left text-sm text-slate-700">
                    <thead class="bg-slate-100 text-slate-900 border-b border-slate-200 sticky top-0 z-10 shadow-sm">
                        <tr>
                            <th class="px-4 py-3 font-semibold w-12 text-center bg-slate-100">No</th>
                            <th class="px-4 py-3 font-semibold bg-slate-100">Tanggal</th>
                            <th class="px-4 py-3 font-semibold text-center bg-slate-100">Jam Masuk</th>
                            <th class="px-4 py-3 font-semibold text-center bg-slate-100">Jam Keluar</th>
                            <th class="px-4 py-3 font-semibold text-center bg-slate-100">Status</th>
                            <th class="px-4 py-3 font-semibold bg-slate-100">Keterangan / Catatan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 bg-white">
                        @forelse($peserta->attendances as $idx => $att)
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="px-4 py-3 text-center text-slate-400 font-mono">{{ $idx + 1 }}</td>
                            <td class="px-4 py-3 font-medium text-slate-800">
                                {{ \Carbon\Carbon::parse($att->tanggal ?? $att->check_in_at ?? $att->date ?? $att->created_at)->translatedFormat('d F Y') }}
                            </td>
                            <td class="px-4 py-3 text-center font-mono text-slate-600">
                                {{ $att->check_in_at ? \Carbon\Carbon::parse($att->check_in_at)->format('H:i') : '-' }}
                            </td>
                            <td class="px-4 py-3 text-center font-mono text-slate-600">
                                {{ $att->check_out_at ? \Carbon\Carbon::parse($att->check_out_at)->format('H:i') : '-' }}
                            </td>
                            <td class="px-4 py-3 text-center">
                                @php
                                $statusClass = match(strtolower($att->status)) {
                                'hadir' => 'bg-emerald-100 text-emerald-700 ring-emerald-600/20',
                                'izin' => 'bg-blue-100 text-blue-700 ring-blue-600/20',
                                'sakit' => 'bg-amber-100 text-amber-700 ring-amber-600/20',
                                default => 'bg-rose-100 text-rose-700 ring-rose-600/20',
                                };
                                @endphp
                                <span class="inline-flex items-center rounded-md px-2.5 py-1 text-xs font-semibold ring-1 ring-inset capitalize {{ $statusClass }}">
                                    {{ $att->status }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-xs">
                                @php
                                $isLate = false;
                                $lateMinutes = 0;
                                $jamMasukStandar = '08:00:00';

                                if (strtolower($att->status) === 'hadir' && $att->check_in_at) {
                                $checkInTime = \Carbon\Carbon::parse($att->check_in_at);
                                $targetTime = \Carbon\Carbon::parse($checkInTime->format('Y-m-d') . ' ' . $jamMasukStandar);

                                if ($checkInTime->gt($targetTime)) {
                                $isLate = true;
                                $lateMinutes = $checkInTime->diffInMinutes($targetTime);
                                }
                                }
                                @endphp

                                @if($isLate)
                                <span class="inline-flex items-center gap-1 font-semibold text-amber-600 bg-amber-50 px-2 py-0.5 rounded border border-amber-200">
                                    ⚠️ Terlambat ({{ $lateMinutes }} mnt)
                                </span>
                                @if($att->keterangan || $att->notes)
                                <p class="text-slate-500 mt-1">{{ $att->keterangan ?? $att->notes }}</p>
                                @endif
                                @elseif(strtolower($att->status) === 'hadir')
                                <span class="text-emerald-600 font-medium">Tepat Waktu</span>
                                @else
                                <span class="text-slate-500">{{ $att->keterangan ?? $att->notes ?? '-' }}</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-4 py-6 text-center text-slate-400">
                                Belum ada catatan riwayat presensi.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        
        <div class="mt-6 rounded-xl bg-blue-50 border border-blue-200 p-4">
            <h4 class="font-semibold text-blue-900 mb-1">
                Catatan Penilaian
            </h4>
            <p class="text-sm text-blue-800 leading-relaxed">
                Penilaian diberikan berdasarkan hasil observasi pembimbing terhadap kinerja peserta selama melaksanakan kegiatan magang sesuai dengan rubrik penilaian yang telah ditentukan. Portofolio & rekap kehadiran ditampilkan sebagai informasi pendukung untuk memberikan gambaran mengenai tingkat kedisiplinan dan keaktifan peserta selama periode magang.
            </p>
        </div>

    </div>

</div>

<div class="rounded-2xl border border-slate-200 bg-white shadow-sm overflow-hidden w-full">
    <div class="bg-slate-50/50 border-b border-slate-100 px-6 py-4 sm:px-8">
        <h2 class="text-lg font-bold text-slate-800">Formulir Rubrik Penilaian</h2>
        <p class="mt-1 text-sm text-slate-500">Isi nilai untuk setiap komponen sesuai dengan bobot maksimal yang ditentukan.</p>
    </div>

    <form action="{{ route('pembimbing.evaluation.update', $peserta) }}" method="post" class="p-6 sm:p-8 space-y-8">
        @csrf @method('PUT')

        <div class="overflow-x-auto rounded-xl border border-slate-200">
            <table class="w-full text-left text-sm text-slate-700">
                <thead class="bg-slate-50 text-slate-900 border-b border-slate-200">
                    <tr>
                        <th class="px-4 py-3 font-semibold text-center w-12">No</th>
                        <th class="px-4 py-3 font-semibold">Aspek Penilaian</th>
                        <th class="px-4 py-3 font-semibold text-center w-40">Nilai Angka (0 - 100)</th>
                        <th class="px-4 py-3 font-semibold text-center w-32">Nilai Huruf</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 bg-white">
                    @forelse($rubrics as $index => $r)
                    @php $sc = $scoresByRubric->get($r->id); @endphp
                    <tr class="hover:bg-slate-50/50 transition-colors">
                        <td class="px-4 py-3 text-center font-medium text-slate-500">{{ $index + 1 }}</td>
                        <td class="px-4 py-3">
                            <label for="nilai_{{ $r->id }}" class="block font-medium text-slate-800 cursor-pointer">
                                {{ $r->nama }}
                            </label>
                        </td>
                        <td class="px-4 py-3 text-center">
                            <div class="relative">
                                <input type="number"
                                    step="0.01"
                                    min="0"
                                    max="100"
                                    name="nilai_{{ $r->id }}"
                                    id="nilai_{{ $r->id }}"
                                    value="{{ old('nilai_'.$r->id, $sc?->nilai ?? null) }}"
                                    required
                                    placeholder="0 - 100"
                                    {{ $evaluation->is_final ? 'readonly' : '' }}
                                    class="nilai-input block w-full rounded-lg border border-slate-300 py-2 pl-3 pr-10 text-right font-mono text-sm text-slate-900 focus:border-blue-500 focus:ring-blue-500 placeholder:text-slate-300">
                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3">
                                    <span class="text-xs font-medium text-slate-400">pt</span>
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-3 text-center">
                            <span id="huruf_{{ $r->id }}" class="inline-flex items-center justify-center rounded-md bg-slate-100 px-3 py-1.5 text-sm font-bold text-slate-700 ring-1 ring-inset ring-slate-500/20 w-12">
                                -
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-4 py-8 text-center text-red-500 font-bold bg-red-50">
                            DATA RUBRIK KOSONG! (Database: {{ DB::connection()->getDatabaseName() }} | Count: {{ count($rubrics) }})
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="border-t border-slate-100 pt-8">
            <label class="mb-2 block text-sm font-bold text-slate-800">Komentar Akhir <span class="text-xs font-normal text-slate-400 ml-1">(Catatan evaluasi untuk peserta)</span></label>
            <textarea
                name="komentar_final"
                rows="4"
                {{ $evaluation->is_final ? 'readonly' : '' }}
                placeholder="Tuliskan umpan balik..."
                class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm outline-none transition-all placeholder:text-slate-300 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 bg-slate-50/50 focus:bg-white">{{ old('komentar_final', $evaluation->komentar_final) }}</textarea>
        </div>

        <div class="rounded-xl border border-blue-200 bg-blue-50 p-4 relative overflow-hidden">
            <h3 class="text-sm font-bold text-blue-900 mb-2">Keterangan Nilai (Predikat):</h3>
            <ul class="text-xs text-blue-800 space-y-1 grid grid-cols-2 sm:grid-cols-4 gap-2">
                <li><span class="font-bold">A</span> = 86 - 100 (Sangat Baik)</li>
                <li><span class="font-bold">B</span> = 76 - 85.99 (Baik)</li>
                <li><span class="font-bold">C</span> = 65 - 75.99 (Cukup)</li>
                <li><span class="font-bold">D</span> = 0 - 64.99 (Kurang)</li>
            </ul>
        </div>

        <div class="rounded-xl border border-amber-200 bg-amber-50 p-4">
            <label class="flex items-start gap-3 cursor-pointer">
                <input
                    type="checkbox"
                    name="is_final"
                    value="1"
                    {{ old('is_final', $evaluation->is_final) ? 'checked' : '' }}
                    {{ $evaluation->is_final ? 'disabled' : '' }}
                    class="mt-1 h-5 w-5 rounded border-slate-300 text-blue-600 focus:ring-blue-500">
                <div>
                    <p class="font-semibold text-slate-800">
                        Finalisasi Penilaian
                    </p>

                    <p class="text-sm text-slate-500">
                        Setelah dicentang dan disimpan, penilaian tidak dapat diubah lagi dan akan langsung terlihat oleh Admin serta Peserta.
                    </p>
                </div>
            </label>
        </div>

        <div class="flex items-center justify-end gap-3 pt-2">

            <a href="{{ route('pembimbing.evaluation.index') }}"
                class="rounded-xl px-5 py-2.5 text-sm font-medium text-slate-600 hover:bg-slate-100 transition-colors">
                Kembali
            </a>

            @unless($evaluation->is_final)
            <button type="submit"
                class="inline-flex items-center justify-center gap-2 rounded-xl bg-blue-600 px-6 py-2.5 text-sm font-semibold text-white">
                Simpan Penilaian
            </button>
            @endunless

        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
    function switchTab(tab) {
        const rekapBtn = document.getElementById('tab-rekap-btn');
        const riwayatBtn = document.getElementById('tab-riwayat-btn');
        const rekapContent = document.getElementById('tab-rekap-content');
        const riwayatContent = document.getElementById('tab-riwayat-content');

        if (tab === 'rekap') {
            rekapContent.classList.remove('hidden');
            riwayatContent.classList.add('hidden');

            rekapBtn.className = "border-blue-600 text-blue-600 whitespace-nowrap py-3 px-1 border-b-2 font-semibold text-sm flex items-center gap-2";
            riwayatBtn.className = "border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300 whitespace-nowrap py-3 px-1 border-b-2 font-semibold text-sm flex items-center gap-2";
        } else {
            riwayatContent.classList.remove('hidden');
            rekapContent.classList.add('hidden');

            riwayatBtn.className = "border-blue-600 text-blue-600 whitespace-nowrap py-3 px-1 border-b-2 font-semibold text-sm flex items-center gap-2";
            rekapBtn.className = "border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300 whitespace-nowrap py-3 px-1 border-b-2 font-semibold text-sm flex items-center gap-2";
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        const inputs = document.querySelectorAll('.nilai-input');

        function updateHuruf(input) {
            const val = parseFloat(input.value);
            const id = input.id.split('_')[1];
            const hurufSpan = document.getElementById('huruf_' + id);

            if (isNaN(val)) {
                hurufSpan.textContent = '-';
                hurufSpan.className = 'inline-flex items-center justify-center rounded-md bg-slate-100 px-3 py-1.5 text-sm font-bold text-slate-700 ring-1 ring-inset ring-slate-500/20 w-12';
                return;
            }

            let huruf = 'D';
            let bgClass = 'bg-orange-100 text-orange-700 ring-orange-600/20';

            if (val >= 86) {
                huruf = 'A';
                bgClass = 'bg-emerald-100 text-emerald-700 ring-emerald-600/20';
            } else if (val >= 76) {
                huruf = 'B';
                bgClass = 'bg-blue-100 text-blue-700 ring-blue-600/20';
            } else if (val >= 65) {
                huruf = 'C';
                bgClass = 'bg-amber-100 text-amber-700 ring-amber-600/20';
            }

            hurufSpan.textContent = huruf;
            hurufSpan.className = `inline-flex items-center justify-center rounded-md px-3 py-1.5 text-sm font-bold ring-1 ring-inset w-12 ${bgClass}`;
        }

        inputs.forEach(input => {
            updateHuruf(input);
            input.addEventListener('input', function() {
                updateHuruf(this);
            });
        });
    });
</script>
@endpush