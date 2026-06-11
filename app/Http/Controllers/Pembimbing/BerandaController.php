<?php

namespace App\Http\Controllers\Pembimbing;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\LeaveRequest;
use App\Models\PesertaProfile;
use App\Services\AttendanceStatsService;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class BerandaController extends Controller
{
    public function __invoke(AttendanceStatsService $stats): View
    {
        $profile = Auth::user()->pembimbingProfile;
        $peserta = PesertaProfile::query()
            ->with(['user', 'latestEvaluation'])
            ->where('pembimbing_id', $profile?->id)
            ->orderBy('nim')
            ->get();

        $pesertaCount = $peserta->count();
        $belumDinilaiCount = $peserta->filter(fn ($p) => ! $p->latestEvaluation?->is_final)->count();
        $rataRataNilai = $peserta->filter(fn ($p) => $p->latestEvaluation?->is_final)->avg(fn ($p) => $p->latestEvaluation->total_nilai) ?? 0;

        $today = now()->toDateString();

        $peserta->each(function ($p) use ($stats, $today) {
            $p->is_active = now()->between($p->periode_mulai, $p->periode_selesai);
            $p->progress_percent = $stats->attendancePercent($p);
            $p->is_dinilai = $p->latestEvaluation?->is_final;

            // Fetch attendances for today
            $atts = Attendance::query()
                ->where('peserta_profile_id', $p->id)
                ->whereDate('tanggal', $today)
                ->first();

            // Set today's status
            $p->today_status = $atts ? $atts->status : 'belum';
        });

        $pendingLeaves = LeaveRequest::query()
            ->with(['pesertaProfile.user'])
            ->whereHas('pesertaProfile', fn ($q) => $q->where('pembimbing_id', $profile?->id))
            ->where('status', 'pending')
            ->orderByDesc('created_at')
            ->limit(10)
            ->get();

        $pendingLeavesCount = $pendingLeaves->count();

        $chartLabels = [];
        $chartHadir = [];
        $chartIzinSakit = [];

        if ($profile) {
            $attendances = Attendance::query()
                ->whereHas('pesertaProfile', fn ($q) => $q->where('pembimbing_id', $profile->id))
                ->where('created_at', '>=', now()->subMonths(5)->startOfMonth())
                ->get();

            $grouped = $attendances->groupBy(fn ($a) => $a->created_at->format('M Y'));
            
            for ($i = 5; $i >= 0; $i--) {
                $month = now()->subMonths($i)->format('M Y');
                $chartLabels[] = $month;
                $monthAtts = $grouped->get($month, collect());
                $chartHadir[] = $monthAtts->where('status', 'hadir')->count();
                $chartIzinSakit[] = $monthAtts->whereIn('status', ['izin', 'sakit'])->count();
            }
        }

        return view('pembimbing.dashboard', compact(
            'peserta', 'pesertaCount', 'belumDinilaiCount', 'rataRataNilai', 
            'pendingLeaves', 'pendingLeavesCount', 'profile',
            'chartLabels', 'chartHadir', 'chartIzinSakit'
        ));
    }
}
