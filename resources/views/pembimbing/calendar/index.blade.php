@extends('layouts.panel')

@section('title', 'Kalender Absensi — Pembimbing')
@section('page_title', 'Kalender Absensi')

@section('content')
<div class="mb-6 flex flex-col gap-1 sm:flex-row sm:items-end sm:justify-between">
    <div>
        <h1 class="text-xl font-semibold tracking-tight text-slate-900 md:text-2xl">Kalender Kehadiran</h1>
        <p class="mt-1 text-sm text-slate-500">Pilih tanggal untuk melihat detail absensi peserta bimbingan Anda.</p>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    {{-- Bagian Kalender --}}
    <div class="lg:col-span-2 rounded-[14px] border border-slate-200/80 bg-white p-5 shadow-sm">
        <div id="calendar" class="w-full"></div>
    </div>

    {{-- Detail Absensi Harian --}}
    <div class="lg:col-span-1 rounded-[14px] border border-slate-200/80 bg-white p-5 shadow-sm flex flex-col">
        <div class="mb-5 flex flex-col pb-4 border-b border-slate-100 gap-4">
            <div>
                <h2 class="text-lg font-bold text-slate-800">Detail Kehadiran</h2>
                <p class="text-sm text-slate-500">{{ \Carbon\Carbon::parse($selectedDate)->translatedFormat('l, d F Y') }}</p>
            </div>
            
            <div class="grid grid-cols-3 gap-2">
                <div class="flex flex-col items-center justify-center bg-emerald-50 rounded-lg py-1.5 border border-emerald-100 text-center">
                    <span class="text-lg font-bold text-emerald-700">{{ $summary['hadir'] }}</span>
                    <span class="text-[10px] uppercase font-semibold text-emerald-600">Hadir</span>
                </div>
                <div class="flex flex-col items-center justify-center bg-amber-50 rounded-lg py-1.5 border border-amber-100 text-center">
                    <span class="text-lg font-bold text-amber-700">{{ $summary['izin_sakit'] }}</span>
                    <span class="text-[10px] uppercase font-semibold text-amber-600">Izin/Sakit</span>
                </div>
                <div class="flex flex-col items-center justify-center bg-rose-50 rounded-lg py-1.5 border border-rose-100 text-center">
                    <span class="text-lg font-bold text-rose-700">{{ $summary['alpa_belum'] }}</span>
                    <span class="text-[10px] uppercase font-semibold text-rose-600">Alpa/Belum</span>
                </div>
            </div>
        </div>

        <div class="flex-1 overflow-x-auto">
            <table class="min-w-full text-left text-sm whitespace-nowrap">
                <thead>
                    <tr class="bg-slate-50/70 text-xs font-semibold uppercase tracking-wide text-slate-500 border-y border-slate-100">
                        <th class="px-5 py-3">Nama Peserta</th>
                        <th class="px-5 py-3">Instansi</th>
                        <th class="px-5 py-3">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($pesertaList as $p)
                        <tr class="hover:bg-slate-50/50 transition-colors group">
                            <td class="px-5 py-4">
                                <div class="flex items-center gap-3">
                                    @if($p->user->avatar_path)
                                        <img src="{{ route('storage.file', $p->user->avatar_path) }}" alt="Avatar" class="h-8 w-8 rounded-full object-cover border border-slate-200 shadow-sm">
                                    @else
                                        <div class="flex h-8 w-8 items-center justify-center rounded-full bg-blue-100 text-xs font-bold tracking-wider text-blue-700 shadow-sm ring-1 ring-white">
                                            {{ strtoupper(substr($p->user->name, 0, 1)) }}
                                        </div>
                                    @endif
                                    <div>
                                        <p class="font-semibold text-slate-900 group-hover:text-blue-700 transition">{{ $p->user->name }}</p>
                                        <p class="mt-0.5 font-mono text-xs text-slate-500">{{ $p->nim ?? '-' }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-5 py-4 text-slate-600 text-xs">
                                {{ $p->instansi ?? '-' }}
                            </td>
                            <td class="px-5 py-4">
                                @if($p->status_hari_ini === 'hadir')
                                    <div class="flex flex-col gap-1 items-start">
                                        <span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-50 px-2.5 py-1 text-xs font-semibold text-emerald-700 ring-1 ring-inset ring-emerald-600/20">
                                            <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span> Hadir
                                        </span>
                                        @if($p->check_in_at)
                                        <span class="text-[10px] text-slate-500 font-mono">Masuk: {{ \Carbon\Carbon::parse($p->check_in_at)->format('H:i') }}</span>
                                        @endif
                                        @if($p->check_out_at)
                                        <span class="text-[10px] text-slate-500 font-mono">Pulang: {{ \Carbon\Carbon::parse($p->check_out_at)->format('H:i') }}</span>
                                        @endif
                                    </div>
                                @elseif(in_array($p->status_hari_ini, ['izin', 'sakit']))
                                    <span class="inline-flex items-center gap-1.5 rounded-full bg-amber-50 px-2.5 py-1 text-xs font-semibold text-amber-700 ring-1 ring-inset ring-amber-600/20">
                                        <span class="h-1.5 w-1.5 rounded-full bg-amber-500"></span> {{ ucfirst($p->status_hari_ini) }}
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 rounded-full bg-rose-50 px-2.5 py-1 text-xs font-semibold text-rose-700 ring-1 ring-inset ring-rose-600/20">
                                        <span class="h-1.5 w-1.5 rounded-full bg-rose-500"></span> Belum Absen / Alpa
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="px-5 py-10 text-center text-slate-500">
                                Belum ada peserta bimbingan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Rekapitulasi Absensi Keseluruhan --}}
<div class="mt-8 rounded-[14px] border border-slate-200/80 bg-white shadow-sm overflow-hidden">
    <div class="border-b border-slate-100 bg-white px-6 py-5 flex flex-wrap gap-4 justify-between items-center">
        <div>
            <h2 class="text-lg font-bold text-slate-800">Rekapitulasi Bulanan ({{ $selectedDate->translatedFormat('F Y') }})</h2>
            <p class="mt-0.5 text-xs text-slate-500">Akumulasi data kehadiran peserta bimbingan untuk bulan {{ $selectedDate->translatedFormat('F Y') }}.</p>
        </div>
        <form action="{{ route('pembimbing.calendar.index') }}" method="get" class="flex items-center gap-2">
            <input type="month" name="month_year" value="{{ $selectedDate->format('Y-m') }}" class="rounded-lg border-slate-300 px-3 py-2 text-sm outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500" onchange="this.form.submit()">
            <noscript><button type="submit" class="rounded-lg bg-blue-600 px-3 py-2 text-sm text-white">Filter</button></noscript>
        </form>
    </div>
    <div class="overflow-x-auto">
        <table class="min-w-full text-left text-sm whitespace-nowrap">
            <thead>
                <tr class="border-b border-slate-100 bg-slate-50/70 text-xs font-semibold uppercase tracking-wide text-slate-500">
                    <th class="px-6 py-4">Nama Peserta</th>
                    <th class="px-6 py-4 text-center">Hadir</th>
                    <th class="px-6 py-4 text-center">Izin</th>
                    <th class="px-6 py-4 text-center">Sakit</th>
                    <th class="px-6 py-4 text-center">Alpa</th>
                    <th class="px-6 py-4 text-center">Total Hari Aktif</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50">
                @forelse($pesertaList as $p)
                    <tr class="hover:bg-slate-50/50 transition-colors">
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
                                    <p class="font-semibold text-slate-900">{{ $p->user->name }}</p>
                                    <p class="mt-0.5 font-mono text-xs text-slate-500">{{ $p->nim ?? '-' }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="inline-flex min-w-[32px] items-center justify-center rounded-md bg-emerald-100 px-2 py-1 text-xs font-bold text-emerald-700 ring-1 ring-emerald-200">
                                {{ $p->count_hadir }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="inline-flex min-w-[32px] items-center justify-center rounded-md bg-blue-100 px-2 py-1 text-xs font-bold text-blue-700 ring-1 ring-blue-200">
                                {{ $p->count_izin }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="inline-flex min-w-[32px] items-center justify-center rounded-md bg-amber-100 px-2 py-1 text-xs font-bold text-amber-700 ring-1 ring-amber-200">
                                {{ $p->count_sakit }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="inline-flex min-w-[32px] items-center justify-center rounded-md bg-rose-100 px-2 py-1 text-xs font-bold text-rose-700 ring-1 ring-rose-200">
                                {{ $p->count_alpa }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="text-sm font-bold text-slate-700">
                                {{ $p->count_hadir + $p->count_izin + $p->count_sakit + $p->count_alpa }}
                            </span>
                            <span class="text-[10px] text-slate-400 font-medium">Hari</span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-10 text-center text-slate-500">
                            Data rekapitulasi belum tersedia.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var calendarEl = document.getElementById('calendar');
        var selectedDateStr = '{{ \Carbon\Carbon::parse($selectedDate)->format("Y-m-d") }}';

        var calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            initialDate: selectedDateStr,
            locale: 'id',
            headerToolbar: {
                left: 'prev,next',
                center: 'title',
                right: 'today'
            },
            buttonText: {
                today: 'Hari Ini'
            },
            height: 'auto',
            dateClick: function(info) {
                // Navigate to the clicked date
                window.location.href = "{{ route('pembimbing.calendar.index') }}?date=" + info.dateStr;
            },
            dayCellClassNames: function(arg) {
                var d = arg.date;
                var cellDateStr = '';
                
                // FullCalendar bisa mewakili hari menggunakan UTC Midnight (00:00:00Z) 
                // atau Local Midnight (00:00:00 lokal). Pendekatan ini aman untuk dua skenario.
                if (d.getUTCHours() === 0 && d.getUTCMinutes() === 0) {
                    var y = d.getUTCFullYear();
                    var m = String(d.getUTCMonth() + 1).padStart(2, '0');
                    var day = String(d.getUTCDate()).padStart(2, '0');
                    cellDateStr = y + '-' + m + '-' + day;
                } else {
                    var y = d.getFullYear();
                    var m = String(d.getMonth() + 1).padStart(2, '0');
                    var day = String(d.getDate()).padStart(2, '0');
                    cellDateStr = y + '-' + m + '-' + day;
                }

                if (cellDateStr === selectedDateStr) {
                    return ['bg-blue-50', 'border-blue-500', 'font-bold']; // highlight selected
                }
                return [];
            }
        });

        calendar.render();
    });
</script>
@endpush
