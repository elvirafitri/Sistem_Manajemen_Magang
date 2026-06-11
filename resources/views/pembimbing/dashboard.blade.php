@extends('layouts.panel')

@section('title', 'Beranda — Pembimbing')
@section('page_title', 'Ringkasan')

@section('content')
<div class="mb-6 flex flex-col gap-1 sm:flex-row sm:items-end sm:justify-between">
    <div>
        <h1 class="text-xl font-semibold tracking-tight text-slate-900 md:text-2xl">Selamat datang, {{ auth()->user()->name }} 👋</h1>
        <p class="mt-1 text-sm text-slate-500">{{ now()->translatedFormat('l, d F Y') }} · Ringkasan aktivitas peserta bimbingan Anda.</p>
    </div>
    <div class="mt-2 flex flex-wrap gap-2 sm:mt-0">
        <a href="{{ route('pembimbing.evaluation.index') }}" class="inline-flex shrink-0 items-center justify-center rounded-[10px] bg-indigo-600 px-3 py-2 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 transition">
            + Tambah Penilaian
        </a>
        <a href="{{ route('pembimbing.leaves.index') }}" class="inline-flex shrink-0 items-center justify-center rounded-[10px] border border-slate-300 bg-white px-3 py-2 text-sm font-medium text-slate-700 shadow-sm hover:bg-slate-50 transition">
            Verifikasi Izin
        </a>
    </div>
</div>


{{-- Metric cards --}}
<div class="mb-6 grid gap-4 sm:grid-cols-2 xl:grid-cols-3">
    <div class="flex items-start justify-between rounded-[14px] border border-slate-200/80 bg-white p-5 shadow-sm transition-all hover:shadow-md hover:border-slate-300">
        <div>
            <p class="text-sm font-medium text-slate-500">Total Bimbingan</p>
            <p class="mt-2 text-2xl font-bold tracking-tight text-slate-900">{{ $pesertaCount }}</p>
            <p class="mt-2 text-xs text-slate-400">Peserta magang aktif</p>
        </div>
        <div class="flex h-12 w-12 items-center justify-center rounded-[10px] bg-blue-50 text-blue-600 ring-1 ring-inset ring-blue-500/10">
            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
        </div>
    </div>

    <div class="flex items-start justify-between rounded-[14px] border border-slate-200/80 bg-white p-5 shadow-sm transition-all hover:shadow-md hover:border-slate-300">
        <div>
            <p class="text-sm font-medium text-slate-500">Menunggu verifikasi izin</p>
            <p class="mt-2 text-2xl font-bold tracking-tight text-slate-900">{{ $pendingLeavesCount }}</p>
            <p class="mt-2 text-xs text-slate-400">Butuh verifikasi</p>
        </div>
        <div class="flex h-12 w-12 items-center justify-center rounded-[10px] bg-amber-50 text-amber-600 ring-1 ring-inset ring-amber-500/10">
            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        </div>
    </div>

    <div class="flex items-start justify-between rounded-[14px] border border-slate-200/80 bg-white p-5 shadow-sm transition-all hover:shadow-md hover:border-slate-300">
        <div>
            <p class="text-sm font-medium text-slate-500">Nilai Final</p>
            <p class="mt-2 text-2xl font-bold tracking-tight text-slate-900">{{ $belumDinilaiCount }}</p>
            <p class="mt-2 text-xs text-slate-400">Belum mendapat nilai final</p>
        </div>
        <div class="flex h-12 w-12 items-center justify-center rounded-[10px] bg-rose-50 text-rose-600 ring-1 ring-inset ring-rose-500/10">
            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
        </div>
    </div>
</div>

<div class="mb-6 flex flex-col gap-6">
    {{-- Tabel Peserta Mini --}}
    <div class="flex flex-col rounded-[14px] border border-slate-200/80 bg-white shadow-sm overflow-hidden">
        <div class="flex items-center justify-between border-b border-slate-100 px-6 py-5 bg-white">
            <div>
                <h2 class="text-lg font-bold text-slate-800">Peserta Bimbingan</h2>
                <p class="mt-0.5 text-xs text-slate-500">Daftar mahasiswa magang Anda</p>
            </div>
            <a href="{{ route('pembimbing.evaluation.index') }}" class="inline-flex items-center gap-1 text-sm font-semibold text-blue-600 hover:text-blue-800 transition-colors">Lihat Semua <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg></a>
        </div>
        <div class="flex-1 overflow-x-auto">
            <table class="min-w-full text-left text-sm whitespace-nowrap">
                <thead>
                    <tr class="border-b border-slate-100 bg-slate-50/70 text-xs font-semibold uppercase tracking-wide text-slate-500">
                        <th class="px-6 py-4">Nama Peserta</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4">Hadir Hari Ini</th>
                        <th class="px-6 py-4">Kehadiran</th>
                        <th class="px-6 py-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($peserta as $p)
                        <tr class="hover:bg-slate-50/70 transition-colors group">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    @if($p->user->avatar_path)
                                        <img src="{{ route('storage.file', $p->user->avatar_path) }}" alt="Avatar" class="h-9 w-9 rounded-full object-cover border border-slate-200 shadow-sm">
                                    @else
                                        <div class="flex h-9 w-9 items-center justify-center rounded-full bg-blue-100 text-xs font-bold tracking-wider text-blue-700 shadow-sm ring-1 ring-white">
                                            {{ strtoupper(substr($p->user->name, 0, 1)) }}
                                        </div>
                                    @endif
                                    <div>
                                        <p class="font-semibold text-slate-900 group-hover:text-blue-700 transition-colors">{{ $p->user->name }}</p>
                                        <p class="mt-0.5 font-mono text-xs text-slate-500">{{ $p->nim ?? 'NIM Belum Diisi' }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-slate-600">
                                @if($p->is_active)
                                    <span class="inline-flex rounded-full bg-emerald-50 px-2.5 py-1 text-xs font-semibold text-emerald-700 ring-1 ring-inset ring-emerald-600/20">Aktif</span>
                                @else
                                    <span class="inline-flex rounded-full bg-slate-100 px-2.5 py-1 text-xs font-semibold text-slate-700 ring-1 ring-inset ring-slate-500/10">Selesai</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-slate-600">
                                @if($p->today_status === 'hadir')
                                    <span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-50 px-2.5 py-1 text-xs font-semibold text-emerald-700 ring-1 ring-inset ring-emerald-600/20">
                                        <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span> Hadir
                                    </span>
                                @elseif(in_array($p->today_status, ['izin', 'sakit']))
                                    <span class="inline-flex items-center gap-1.5 rounded-full bg-amber-50 px-2.5 py-1 text-xs font-semibold text-amber-700 ring-1 ring-inset ring-amber-600/20">
                                        <span class="h-1.5 w-1.5 rounded-full bg-amber-500"></span> {{ ucfirst($p->today_status) }}
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 rounded-full bg-slate-100 px-2.5 py-1 text-xs font-semibold text-slate-700 ring-1 ring-inset ring-slate-500/10">
                                        <span class="h-1.5 w-1.5 rounded-full bg-slate-400"></span> {{ ucfirst($p->today_status) }}
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <span class="font-semibold text-slate-700 font-mono">{{ $p->progress_percent }}%</span>
                                    <div class="h-2 w-20 overflow-hidden rounded-full bg-slate-100 ring-1 ring-inset ring-slate-200">
                                        <div class="h-full bg-blue-500 rounded-full" @style(['width: ' . $p->progress_percent . '%'])></div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-right font-medium">
                                <a href="{{ route('pembimbing.evaluation.edit', $p) }}" class="inline-flex items-center gap-1.5 text-blue-600 hover:text-blue-800 transition-colors">
                                    @if($p->is_dinilai)
                                        Edit Nilai <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    @else
                                        Beri Nilai <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                    @endif
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-16 text-center text-slate-500">
                                <div class="flex flex-col items-center justify-center">
                                    <svg class="h-10 w-10 text-slate-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                                    Belum ada peserta yang ditugaskan.
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Chart Absensi --}}
    <div class="flex flex-col rounded-[12px] border border-slate-200/80 bg-white p-5 shadow-sm">
        <div class="mb-4">
            <h2 class="text-base font-semibold text-slate-900">Kehadiran Peserta</h2>
            <p class="text-xs text-slate-500">Hadir vs Izin (6 Bulan)</p>
        </div>
        <div class="h-64 flex-1">
            <canvas
                id="chart-bar-absensi"
                data-labels='@json($chartLabels)'
                data-hadir='@json($chartHadir)'
                data-izin-sakit='@json($chartIzinSakit)'
            ></canvas>
        </div>
    </div>
</div>


@endsection

@push('scripts')
    {{-- Instead of creating a new JS file for Vite, we inline the simple Chart code --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const ctxBar = document.getElementById('chart-bar-absensi');
            if (ctxBar) {
                const labels = JSON.parse(ctxBar.dataset.labels || '[]');
                const dataHadir = JSON.parse(ctxBar.dataset.hadir || '[]');
                const dataIzinSakit = JSON.parse(ctxBar.dataset.izinSakit || '[]');

                new Chart(ctxBar, {
                    type: 'bar',
                    data: {
                        labels: labels,
                        datasets: [
                            {
                                label: 'Hadir',
                                data: dataHadir,
                                backgroundColor: '#3b82f6', // blue-500
                                borderRadius: 4,
                            },
                            {
                                label: 'Izin / Sakit',
                                data: dataIzinSakit,
                                backgroundColor: '#f59e0b', // amber-500
                                borderRadius: 4,
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'bottom',
                                labels: {
                                    usePointStyle: true,
                                    boxWidth: 8,
                                    font: { size: 11, family: "'Inter', sans-serif" }
                                }
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: { precision: 0, font: { size: 11 } },
                                grid: { color: '#f1f5f9' },
                                border: { display: false }
                            },
                            x: {
                                grid: { display: false },
                                ticks: { font: { size: 11 } },
                                border: { display: false }
                            }
                        }
                    }
                });
            }
        });
    </script>
@endpush
