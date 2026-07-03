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
                                <input type="number" step="0.01" min="0" max="100" name="nilai_{{ $r->id }}" id="nilai_{{ $r->id }}"
                                    value="{{ old('nilai_'.$r->id, $sc?->nilai ?? null) }}" required placeholder="0 - 100"
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
            <textarea name="komentar_final" rows="4" placeholder="Tuliskan umpan balik atau komentar evaluasi kinerja peserta di sini..." class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm outline-none transition-all placeholder:text-slate-300 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 bg-slate-50/50 focus:bg-white">{{ old('komentar_final', $evaluation->komentar_final) }}</textarea>
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
            <a href="{{ route('pembimbing.evaluation.index') }}" class="rounded-xl px-5 py-2.5 text-sm font-medium text-slate-600 hover:bg-slate-100 transition-colors">Batal</a>
            <button type="submit" class="inline-flex items-center justify-center gap-2 rounded-xl bg-blue-600 px-6 py-2.5 text-sm font-semibold text-white shadow-sm hover:focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 hover:bg-blue-700 transition-all">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                Simpan Penilaian
            </button>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
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
            // Initial update
            updateHuruf(input);
            // On change/keyup update
            input.addEventListener('input', function() {
                updateHuruf(this);
            });
        });
    });
</script>
@endpush