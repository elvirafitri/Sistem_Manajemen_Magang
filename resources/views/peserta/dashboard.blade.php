@extends('layouts.panel')

@section('title', 'Beranda — Peserta')
@section('page_title', 'Ringkasan')

@section('content')
<div class="mb-6 flex flex-col gap-1 sm:flex-row sm:items-end sm:justify-between">
    <div>
        <h1 class="text-xl font-semibold tracking-tight text-slate-900 md:text-2xl">Selamat datang, {{ auth()->user()->name }} 👋</h1>
        <p class="mt-1 flex items-center gap-2 text-sm text-slate-500">
            <span id="realtime-clock" class="font-mono text-indigo-600 bg-indigo-50 px-2 py-0.5 rounded">{{ now()->translatedFormat('l, d F Y · H:i:s') }} WIB</span>
            <span>· Pantau kehadiran dan aktivitas Anda.</span>
        </p>
    </div>
</div>

@if($profile)
{{-- Alerts Area --}}
@if($pendingLeaves && $pendingLeaves->count() > 0)
<div class="mb-5 flex items-start gap-3 rounded-[14px] border border-amber-200 bg-amber-50 p-4 shadow-sm">
    <div class="mt-0.5 flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-amber-100 text-amber-600">
        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
        </svg>
    </div>
    <div>
        <h3 class="text-sm font-semibold text-amber-800">Menunggu persetujuan izin</h3>
        <p class="mt-1 text-sm text-amber-700">Ada {{ $pendingLeaves->count() }} pengajuan izin/sakit Anda yang masih menunggu validasi dari pembimbing.</p>
    </div>
</div>
@endif

<div class="mb-6 grid gap-6 lg:grid-cols-3">

    {{-- Left Column: Absensi Hari Ini & Progress --}}
    <div class="flex flex-col gap-6 lg:col-span-2">

        {{-- Contextual Attendance Card --}}
        <div class="flex flex-col overflow-hidden rounded-[14px] border border-slate-200/80 bg-white shadow-sm transition-all hover:shadow-md hover:border-slate-300">
            <div class="border-b border-slate-100 bg-slate-50/50 px-6 py-4">
                <h2 class="font-semibold text-slate-800">Absensi Hari Ini</h2>
            </div>
            <div class="p-6">
                @if($leaveToday)
                <div class="flex flex-col items-center justify-center rounded-xl border border-amber-200 bg-amber-50 py-8 text-center shadow-sm">
                    <div class="mb-3 flex h-14 w-14 items-center justify-center rounded-full bg-amber-100 text-amber-600 ring-1 ring-amber-500/20">
                        <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-amber-900">Anda sedang Izin / Sakit</h3>
                    <p class="mt-1 text-sm text-amber-700">Status: {{ ucfirst($leaveToday->status) }} · {{ ucfirst($leaveToday->jenis) }}</p>
                </div>
                @else
                @if(!$attendanceToday)
                {{-- Not Checked In --}}
                <div class="flex flex-col items-center justify-center rounded-xl bg-slate-50 py-8 text-center border border-rose-100 shadow-sm" id="checkin-panel">
                    <h3 class="mb-4 flex items-center gap-2 text-xl font-bold text-rose-600">
                        <span class="relative flex h-4 w-4">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-rose-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-4 w-4 bg-rose-500"></span>
                        </span>
                        Belum absen masuk
                    </h3>

                    {{-- Location Status Badge --}}
                    <div id="location-status-badge" class="mb-4 flex items-center gap-2 rounded-full bg-slate-100 px-4 py-1.5 text-xs font-medium text-slate-500">
                        <span class="relative flex h-2 w-2"><span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-slate-400 opacity-75"></span><span class="relative inline-flex rounded-full h-2 w-2 bg-slate-400"></span></span>
                        Mendeteksi lokasi...
                    </div>

                    @if(empty($profile->nim))
                    <div class="mb-4 rounded-lg bg-red-100 p-3 text-red-700 text-sm border border-red-200 shadow-sm">
                        Anda belum dapat melakukan absensi. Harap melengkapi <strong>Data Personal & Dokumen (NIM/NIS, dll)</strong> terlebih dahulu melalui halaman <a href="{{ route('peserta.profile') }}" class="underline hover:text-red-900">Profil</a>.
                    </div>
                    <a href="{{ route('peserta.profile') }}" class="w-full sm:w-auto mt-2 inline-flex items-center justify-center gap-2 rounded-xl bg-indigo-600 px-8 py-4 text-lg font-bold text-white shadow-lg shadow-indigo-500/30 transition-all hover:-translate-y-1 hover:bg-indigo-700">
                        Lengkapi Profil Sekarang
                    </a>
                    @else
                    <button id="btn-checkin" type="button"
                        class="w-full sm:w-auto flex items-center justify-center gap-2 rounded-xl bg-green-500 px-8 py-4 text-lg font-bold text-white shadow-lg shadow-green-500/30 transition-all hover:-translate-y-1 hover:bg-green-600 hover:shadow-green-500/40 disabled:opacity-60 disabled:cursor-not-allowed disabled:hover:translate-y-0"
                        data-url="{{ route('peserta.absensi.checkin') }}"
                        data-csrf="{{ csrf_token() }}"
                        disabled>
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                        </svg>
                        <span id="btn-checkin-label">Mendapatkan Lokasi...</span>
                    </button>
                    @endif
                </div>
                @elseif($attendanceToday->check_in_at && !$attendanceToday->check_out_at)
                {{-- Checked in, needs Check-out --}}
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-6 rounded-xl border border-emerald-200 bg-emerald-50 p-6 shadow-sm" id="checkout-panel">
                    <div>
                        <h3 class="flex items-center gap-2 text-lg font-bold text-emerald-800">
                            ✅ Sudah absen masuk jam {{ $attendanceToday->check_in_at->format('H:i') }}
                        </h3>
                        <p class="mt-1 text-sm text-emerald-700">Selamat bekerja! Jangan lupa check-out saat pulang.</p>

                        {{-- Location Status Badge --}}
                        <div id="location-status-badge" class="mt-2 inline-flex items-center gap-2 rounded-full bg-emerald-100 px-3 py-1 text-xs font-medium text-emerald-600">
                            <span class="relative flex h-2 w-2"><span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span><span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-400"></span></span>
                            Mendeteksi lokasi...
                        </div>
                    </div>

                    <button id="btn-checkout" type="button"
                        class="w-full sm:w-auto shrink-0 flex items-center justify-center gap-2 rounded-xl bg-slate-800 px-8 py-3.5 text-base font-bold text-white shadow-lg shadow-slate-800/20 transition-all hover:-translate-y-0.5 hover:bg-slate-900 hover:shadow-slate-800/30 disabled:opacity-60 disabled:cursor-not-allowed disabled:hover:translate-y-0"
                        data-url="{{ route('peserta.absensi.checkout') }}"
                        data-csrf="{{ csrf_token() }}"
                        disabled>
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                        <span id="btn-checkout-label">Mendapatkan Lokasi...</span>
                    </button>
                </div>
                @else
                {{-- Finished for the day --}}
                <div class="flex flex-col items-center justify-center rounded-xl bg-blue-50 py-8 text-center border border-blue-200 shadow-sm">
                    <h3 class="text-xl font-bold text-blue-900 mb-2">🎉 Absensi hari ini selesai</h3>
                    <p class="text-blue-700">
                        <strong>Masuk:</strong> <span class="font-mono">{{ $attendanceToday->check_in_at->format('H:i') }}</span> WIB &nbsp;·&nbsp;
                        <strong>Pulang:</strong> <span class="font-mono">{{ $attendanceToday->check_out_at->format('H:i') }}</span> WIB
                    </p>
                </div>
                @endif
                @endif
            </div>
        </div>

        {{-- Progress Kehadiran Canggih --}}
        <div class="flex flex-col overflow-hidden rounded-[14px] border border-slate-200/80 bg-white p-6 shadow-sm transition-all hover:shadow-md hover:border-slate-300">
            <div class="mb-4 flex items-end justify-between">
                <div>
                    <h2 class="font-semibold text-slate-800">Progres Kehadiran</h2>
                    <p class="text-sm text-slate-500">Kumulatif kehadiran vs hari kerja</p>
                </div>
                <div class="text-right">
                    <span class="text-3xl font-bold tracking-tight text-indigo-700">{{ $progress->persentase }}%</span>
                </div>
            </div>

            <div class="mb-2 flex items-center justify-between text-xs font-medium text-slate-500">
                <span>{{ $progress->hari_hadir }} hari hadir / izin</span>
                <span>Target: {{ $progress->total_hari }} hari</span>
            </div>

            <div class="h-4 w-full overflow-hidden rounded-full bg-slate-100 shadow-inner ring-1 ring-inset ring-slate-200">
                <div class="h-full rounded-full bg-gradient-to-r from-indigo-500 to-blue-500 transition-all duration-1000 ease-out" @style(['width: ' . $progress->persentase . ' %'])></div>
            </div>

            @if($progress->persentase >= 80)
            <div class="mt-4 rounded-lg bg-emerald-50 border border-emerald-100 px-3 py-2 text-sm text-emerald-700 shadow-sm">
                ✨ <strong>Catatan:</strong> pastikan anda selalu absen dan absen tepat waktu.
            </div>
            @elseif($progress->persentase > 0 && $progress->persentase < 50)
                <div class="mt-4 rounded-lg bg-rose-50 border border-rose-100 px-3 py-2 text-sm text-rose-700 shadow-sm">
                ⚠️ <strong>Catatan:</strong> pastikan anda selalu absen dan absen tepat waktu.
        </div>
        @endif
    </div>

    {{-- Quick Actions --}}
    <div class="flex gap-4 overflow-x-auto pb-2 border-t border-slate-100 pt-2 lg:border-none lg:pt-0">
        <a href="{{ route('peserta.leave.create') }}" class="flex shrink-0 items-center justify-center gap-2 rounded-xl border border-slate-200 bg-white px-5 py-3 text-sm font-semibold text-slate-700 shadow-sm transition hover:bg-slate-50 hover:shadow">
            <svg class="h-5 w-5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            Ajukan Izin / Sakit
        </a>
        <a href="{{ route('peserta.history') }}" class="flex shrink-0 items-center justify-center gap-2 rounded-xl border border-slate-200 bg-white px-5 py-3 text-sm font-semibold text-slate-700 shadow-sm transition hover:bg-slate-50 hover:shadow">
            <svg class="h-5 w-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            Riwayat Kehadiran
        </a>
    </div>
</div>

{{-- Right Column: Pembimbing & Mini Activity --}}
<div class="flex flex-col gap-6">

    {{-- Pembimbing Card --}}
    <div class="flex flex-col overflow-hidden rounded-[14px] border border-slate-200/80 bg-white shadow-sm transition-all hover:shadow-md hover:border-slate-300">
        <div class="border-b border-slate-100 bg-slate-50/50 px-6 py-4">
            <h2 class="font-semibold text-slate-800">Pembimbing Anda</h2>
        </div>
        <div class="p-6">
            @if($profile->pembimbing)
            <div class="flex items-center gap-4">
                @if($profile->pembimbing->user->avatar_path)
                <img src="{{ route('storage.file', $profile->pembimbing->user->avatar_path) }}" alt="Avatar Pembimbing" class="h-14 w-14 shrink-0 rounded-full object-cover border border-slate-200 shadow-sm ring-2 ring-slate-50">
                @else
                <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-full bg-gradient-to-br from-indigo-100 to-blue-100 text-xl font-bold text-indigo-700 ring-2 ring-white shadow-sm">
                    {{ strtoupper(substr($profile->pembimbing->user->name, 0, 1)) }}
                </div>
                @endif
                <div>
                    <h3 class="font-bold text-slate-900">{{ $profile->pembimbing->user->name }}</h3>
                    <p class="text-xs text-slate-500 tracking-tight">{{ $profile->pembimbing->user->email ?? 'Email tidak tersedia' }}</p>
                </div>
            </div>
            @else
            <div class="rounded-xl border border-amber-200 bg-amber-50 p-5">

                <div class="flex items-start gap-3">

                    <svg class="h-7 w-7 text-amber-600"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M13 16h-1v-4h-1m1-4h.01M12 21a9 9 0 100-18 9 9 0 000 18z" />
                    </svg>

                    <div>
                        <h3 class="font-semibold text-amber-800">Pembimbing Belum Ditentukan</h3>
                        <p class="mt-2 text-sm text-amber-700">Silakan hubungi Admin untuk mendapatkan pembimbing.</p>

                        <a href="https://wa.me/{{ env('ADMIN_PHONE') }}"
                            target="_blank"
                            class="mt-4 inline-flex items-center rounded-lg bg-green-600 px-4 py-2 text-sm font-semibold text-white hover:bg-green-700">
                            Hubungi Admin via WhatsApp
                        </a>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>

    {{-- Mini Activity History --}}
    <div class="flex flex-col overflow-hidden rounded-[14px] border border-slate-200/80 bg-white shadow-sm transition-all hover:shadow-md hover:border-slate-300">
        <div class="flex items-center justify-between border-b border-slate-100 bg-slate-50/50 px-6 py-4">
            <h2 class="font-semibold text-slate-800">Aktivitas Terakhir</h2>
            <a href="{{ route('peserta.history') }}" class="text-xs font-semibold text-indigo-600 hover:text-indigo-700">Lihat Semua</a>
        </div>
        <div class="p-0">
            <ul class="divide-y divide-slate-100">
                @forelse($recentActivities as $act)
                <li class="px-6 py-4 transition-colors hover:bg-slate-50/50">
                    <div class="flex items-start justify-between">
                        <div>
                            <p class="text-sm font-medium text-slate-900">
                                @if($act->status === 'hadir')
                                Tugas Absensi Selesai
                                @elseif($act->status === 'izin')
                                Pengajuan Izin
                                @elseif($act->status === 'sakit')
                                Pengajuan Sakit
                                @endif
                            </p>
                            <p class="text-xs text-slate-500">{{ $act->tanggal->format('d M Y') }}</p>
                        </div>
                        <span class="inline-flex rounded-full px-2.5 py-0.5 text-xs font-semibold ring-1 ring-inset
                                        {{ $act->status === 'hadir' ? 'bg-emerald-50 text-emerald-700 ring-emerald-600/20' : 'bg-amber-50 text-amber-700 ring-amber-600/20' }}">
                            {{ ucfirst($act->status) }}
                        </span>
                    </div>
                    @if($act->status === 'hadir' && $act->check_in_at)
                    <p class="mt-2.5 text-xs text-slate-600">
                        Masuk: <span class="font-mono">{{ $act->check_in_at->format('H:i') }}</span>
                        @if($act->check_out_at)
                        · Pulang: <span class="font-mono">{{ $act->check_out_at->format('H:i') }}</span>
                        @endif
                    </p>
                    @endif
                </li>
                @empty
                <li class="px-6 py-12 text-center text-sm text-slate-500">
                    <svg class="mx-auto h-8 w-8 text-slate-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Belum ada aktivitas yang terekam.
                </li>
                @endforelse
            </ul>
        </div>
    </div>

</div>
</div>
@else
<div class="rounded-xl border border-rose-200 bg-rose-50 p-6 text-center shadow-sm">
    <h2 class="text-lg font-bold text-rose-800">Profil Peserta Tidak Ditemukan</h2>
    <p class="mt-1 text-rose-700">Akun Anda belum dilengkapi dengan profil magang. Silakan hubungi Administrator sistem.</p>
</div>
@endif
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // ============================================================
        // Real-time Clock
        // ============================================================
        const clockElem = document.getElementById('realtime-clock');
        if (clockElem) {
            const days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
            const months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
            setInterval(() => {
                const now = new Date();
                const h = now.getHours().toString().padStart(2, '0');
                const m = now.getMinutes().toString().padStart(2, '0');
                const s = now.getSeconds().toString().padStart(2, '0');
                clockElem.textContent = `${days[now.getDay()]}, ${now.getDate().toString().padStart(2,'0')} ${months[now.getMonth()]} ${now.getFullYear()} · ${h}:${m}:${s} WIB`;
            }, 1000);
        }

        // ============================================================
        // Toast Notification System
        // ============================================================
        function showToast(message, type = 'info') {
            let container = document.getElementById('toast-container');
            if (!container) {
                container = document.createElement('div');
                container.id = 'toast-container';
                container.style.cssText = 'position:fixed;top:20px;right:20px;z-index:9999;display:flex;flex-direction:column;gap:10px;';
                document.body.appendChild(container);
            }

            const colors = {
                success: {
                    bg: '#ecfdf5',
                    border: '#a7f3d0',
                    text: '#065f46',
                    icon: '✅'
                },
                error: {
                    bg: '#fef2f2',
                    border: '#fecaca',
                    text: '#991b1b',
                    icon: '❌'
                },
                info: {
                    bg: '#eff6ff',
                    border: '#bfdbfe',
                    text: '#1e40af',
                    icon: 'ℹ️'
                },
                warning: {
                    bg: '#fffbeb',
                    border: '#fde68a',
                    text: '#92400e',
                    icon: '⚠️'
                },
            };
            const c = colors[type] || colors.info;

            const toast = document.createElement('div');
            toast.style.cssText = `background:${c.bg};border:1px solid ${c.border};color:${c.text};padding:14px 18px;border-radius:14px;box-shadow:0 4px 24px rgba(0,0,0,0.10);font-size:14px;font-weight:600;max-width:360px;display:flex;align-items:flex-start;gap:10px;opacity:0;transform:translateX(40px);transition:all 0.3s;`;
            toast.innerHTML = `<span style="font-size:18px;line-height:1.2">${c.icon}</span><span style="flex:1">${message}</span>`;
            container.appendChild(toast);

            requestAnimationFrame(() => {
                toast.style.opacity = '1';
                toast.style.transform = 'translateX(0)';
            });

            setTimeout(() => {
                toast.style.opacity = '0';
                toast.style.transform = 'translateX(40px)';
                setTimeout(() => toast.remove(), 350);
            }, 4500);
        }

        // ============================================================
        // Geolocation + Absensi
        // ============================================================
        const btnCheckin = document.getElementById('btn-checkin');
        const btnCheckout = document.getElementById('btn-checkout');
        const statusBadge = document.getElementById('location-status-badge');

        let currentLat = null;
        let currentLng = null;

        function updateBadge(type, text) {
            if (!statusBadge) return;
            const styles = {
                detecting: 'background:#f1f5f9;color:#64748b;',
                ok: 'background:#d1fae5;color:#065f46;',
                error: 'background:#fee2e2;color:#991b1b;',
            };
            statusBadge.style.cssText = `display:inline-flex;align-items:center;gap:8px;border-radius:9999px;padding:4px 14px;font-size:12px;font-weight:600;${styles[type] || styles.detecting}`;
            statusBadge.innerHTML = `<span>${type === 'detecting' ? '🔍' : type === 'ok' ? '📍' : '🚫'}</span><span>${text}</span>`;
        }

        function setButtonReady(btn, labelId, labelText) {
            if (!btn) return;
            btn.disabled = false;
            const label = document.getElementById(labelId);
            if (label) label.textContent = labelText;
        }

        function setButtonLoading(btn, labelId, labelText) {
            if (!btn) return;
            btn.disabled = true;
            const label = document.getElementById(labelId);
            if (label) label.textContent = labelText;
        }

        async function doAbsensi(btn, labelId, labelText) {
            if (!currentLat || !currentLng) {
                showToast('Lokasi belum terdeteksi. Izinkan akses lokasi di browser Anda.', 'warning');
                return;
            }

            const url = btn.dataset.url;
            const csrf = btn.dataset.csrf;

            setButtonLoading(btn, labelId, '⏳ Memproses...');

            try {
                const res = await fetch(url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrf,
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({
                        latitude: currentLat,
                        longitude: currentLng
                    }),
                });
                const data = await res.json();

                if (data.success) {
                    showToast(data.message, 'success');
                    setTimeout(() => location.reload(), 1800);
                } else {
                    showToast(data.message, 'error');
                    setButtonReady(btn, labelId, labelText);
                }
            } catch (e) {
                showToast('Terjadi kesalahan jaringan. Coba lagi.', 'error');
                setButtonReady(btn, labelId, labelText);
            }
        }

        // --- Geolocation detection ---
        if ('geolocation' in navigator) {
            updateBadge('detecting', 'Mendeteksi lokasi...');

            navigator.geolocation.getCurrentPosition(
                function(position) {
                    currentLat = position.coords.latitude;
                    currentLng = position.coords.longitude;
                    const acc = Math.round(position.coords.accuracy);
                    updateBadge('ok', `Lokasi terdeteksi · Akurasi ±${acc}m`);

                    setButtonReady(btnCheckin, 'btn-checkin-label', '📍 Absen Masuk Sekarang');
                    setButtonReady(btnCheckout, 'btn-checkout-label', '📍 Absen Keluar Sekarang');
                },
                function(error) {
                    const messages = {
                        1: 'Izin lokasi ditolak. Aktifkan di pengaturan browser.',
                        2: 'Posisi tidak tersedia. Pastikan GPS aktif.',
                        3: 'Waktu deteksi lokasi habis. Coba refresh.',
                    };
                    const msg = messages[error.code] || 'Gagal mendapatkan lokasi.';
                    updateBadge('error', msg);
                    showToast(msg, 'warning');
                }, {
                    enableHighAccuracy: true,
                    timeout: 15000,
                    maximumAge: 0
                }
            );
        } else {
            updateBadge('error', 'Browser tidak mendukung GPS');
            showToast('Browser Anda tidak mendukung Geolocation API.', 'error');
        }

        if (btnCheckin) {
            btnCheckin.addEventListener('click', () => doAbsensi(btnCheckin, 'btn-checkin-label', '📍 Absen Masuk Sekarang'));
        }
        if (btnCheckout) {
            btnCheckout.addEventListener('click', () => doAbsensi(btnCheckout, 'btn-checkout-label', '📍 Absen Keluar Sekarang'));
        }
    });
</script>
@endpush