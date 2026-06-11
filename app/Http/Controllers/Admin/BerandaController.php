<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\LeaveRequest;
use App\Models\PembimbingProfile;
use App\Models\PesertaProfile;
use Carbon\Carbon;
use Illuminate\View\View;

class BerandaController extends Controller
{
    public function __invoke(): View
    {
        $today = Carbon::today();

        $pesertaAktif = PesertaProfile::query()
            ->whereDate('periode_mulai', '<=', $today)
            ->whereDate('periode_selesai', '>=', $today)
            ->count();

        $prevMonthDay = Carbon::today()->subMonth();
        $pesertaAktifBulanLalu = PesertaProfile::query()
            ->whereDate('periode_mulai', '<=', $prevMonthDay)
            ->whereDate('periode_selesai', '>=', $prevMonthDay)
            ->count();

        $pembimbingCount = PembimbingProfile::query()->count();

        $totalKehadiranHariIni = Attendance::query()
            ->whereDate('tanggal', $today)
            ->where('status', 'hadir')
            ->count();

        $hadirKemarin = Attendance::query()
            ->whereDate('tanggal', $today->copy()->subDay())
            ->where('status', 'hadir')
            ->count();

        $pendingVerifikasi = LeaveRequest::query()->where('status', 'pending')->count();

        $trendPeserta = $this->trendPct($pesertaAktif, $pesertaAktifBulanLalu);
        $trendHadir = $this->trendPct($totalKehadiranHariIni, $hadirKemarin);

        $chartLabels = [];
        $chartHadir = [];
        $chartIzinSakit = [];
        for ($i = 5; $i >= 0; $i--) {
            $m = Carbon::now()->subMonths($i);
            $chartLabels[] = $m->translatedFormat('M');
            $chartHadir[] = Attendance::query()
                ->whereYear('tanggal', $m->year)
                ->whereMonth('tanggal', $m->month)
                ->where('status', 'hadir')
                ->count();
            $chartIzinSakit[] = Attendance::query()
                ->whereYear('tanggal', $m->year)
                ->whereMonth('tanggal', $m->month)
                ->whereIn('status', ['izin', 'sakit'])
                ->count();
        }

        $aktivitasTerbaru = Attendance::query()
            ->with(['pesertaProfile.user'])
            ->whereDate('tanggal', $today)
            ->orderByDesc('check_in_at')
            ->orderByDesc('id')
            ->limit(8)
            ->get();

        return view('admin.dashboard', compact(
            'pesertaAktif',
            'pembimbingCount',
            'totalKehadiranHariIni',
            'pendingVerifikasi',
            'trendPeserta',
            'trendHadir',
            'chartLabels',
            'chartHadir',
            'chartIzinSakit',
            'aktivitasTerbaru'
        ));
    }

    /**
     * @return array{pct: float, up: bool}|null
     */
    private function trendPct(int $current, int $previous): ?array
    {
        if ($previous === 0 && $current === 0) {
            return null;
        }
        if ($previous === 0) {
            return ['pct' => 100.0, 'up' => true];
        }

        $pct = (($current - $previous) / $previous) * 100;

        return [
            'pct' => round(abs($pct), 1),
            'up' => $pct >= 0,
        ];
    }
}
