<?php

namespace App\Http\Controllers\Peserta;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\LeaveRequest;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class RiwayatController extends Controller
{
    public function __invoke(Request $request): View
    {
        Attendance::autoCheckout();
        $profile = Auth::user()->pesertaProfile;

        if ($profile) {
            // 1. AUTO ALPA OTOMATIS (Mempersiapkan Batas Akhir Magang)
            // =========================================================================
            // Awal pengecekan: Tanggal mulai magang / pendaftaran akun
            $tanggalCek = Carbon::parse($profile->periode_mulai)->startOfDay();

            // Batas akhir pengecekan (Batas Atas):
            // Jika punya tgl_selesai magang dan sudah lewat, kunci batas di tgl_selesai tersebut.
            // Jika masih aktif magang, batasnya adalah hari ini.
            $batasAkhir = Carbon::today();
            if ($profile->periode_selesai) {
                $tglSelesaiMagang = Carbon::parse($profile->periode_selesai)->endOfDay();

                if ($batasAkhir->greaterThan($tglSelesaiMagang)) {
                    $batasAkhir = $tglSelesaiMagang;
                }
            }

            // A. Perulangan untuk HARI-HARI KERJA selama MASA MAGANG
            while ($tanggalCek->lt($batasAkhir->startOfDay())) {
                if ($tanggalCek->isWeekday()) {
                    $attendance = Attendance::where(
                        'peserta_profile_id',
                        $profile->id
                    )
                        ->whereDate('tanggal', $tanggalCek)
                        ->first();

                    if (!$attendance) {
                        Attendance::create([
                            'peserta_profile_id' => $profile->id,
                            'tanggal'            => $tanggalCek,
                            'status'             => 'alpa',
                            'is_terlambat'       => false,
                            'durasi_kerja'       => 0,
                        ]);
                    }
                }
                $tanggalCek->addDay();
            }

            // B. Khusus HARI INI (Hanya diproses jika MASIH MASA MAGANG & lewat jam 18.00 sore)
            $masihDalamMasaMagang =
                !$profile->periode_selesai ||
                Carbon::today()->lte(Carbon::parse($profile->periode_selesai));

            if (
                $masihDalamMasaMagang &&
                now()->isWeekday() &&
                now()->greaterThanOrEqualTo(Carbon::today()->setTime(17, 5))
            ) {
                Attendance::firstOrCreate(
                    [
                        'peserta_profile_id' => $profile->id,
                        'tanggal'            => Carbon::today()->toDateString(),
                    ],
                    [
                        'status'       => 'alpa',
                        'is_terlambat' => false,
                        'durasi_kerja' => 0,
                    ]
                );
            }
        }

        // 2. TAMPILKAN DATA KE VIEW RIWAYAT ABSENSI
        $month = $request->integer('month') ?: now()->month;
        $year = $request->integer('year') ?: now()->year;

        $query = Attendance::query()
            ->where('peserta_profile_id', $profile?->id);

        $recap = [
            'hadir' => (clone $query)->whereYear('tanggal', $year)->whereMonth('tanggal', $month)->where('status', 'hadir')->count(),
            'izin'  => (clone $query)->whereYear('tanggal', $year)->whereMonth('tanggal', $month)->where('status', 'izin')->count(),
            'sakit' => (clone $query)->whereYear('tanggal', $year)->whereMonth('tanggal', $month)->where('status', 'sakit')->count(),
            'alpa' => (clone $query)->whereYear('tanggal', $year)->whereMonth('tanggal', $month)->where('status', 'alpa')->count(),
        ];

        $rows = $query
            ->whereYear('tanggal', $year)
            ->whereMonth('tanggal', $month)
            ->orderByDesc('tanggal')
            ->paginate(31)
            ->withQueryString();

        return view('peserta.history', compact('rows', 'profile', 'recap', 'month', 'year'));
    }
}
