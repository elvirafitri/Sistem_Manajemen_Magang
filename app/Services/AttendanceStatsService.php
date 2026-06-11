<?php

namespace App\Services;

use App\Models\Attendance;
use App\Models\PesertaProfile;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Support\Collection;

class AttendanceStatsService
{
    /**
     * Persentase kehadiran dalam periode magang (hari kerja Sen–Jum yang ada dalam rentang).
     */
    public function attendancePercent(PesertaProfile $peserta): float
    {
        $start = $peserta->periode_mulai->copy()->startOfDay();
        $end = min(Carbon::today(), $peserta->periode_selesai->copy()->endOfDay());

        $workingDays = $this->workingDaysInRange($start, $end);
        if ($workingDays === 0) {
            return 0.0;
        }

        $hadir = Attendance::query()
            ->where('peserta_profile_id', $peserta->id)
            ->whereBetween('tanggal', [$start->toDateString(), $end->toDateString()])
            ->where('status', 'hadir')
            ->count();

        $izinSakit = Attendance::query()
            ->where('peserta_profile_id', $peserta->id)
            ->whereBetween('tanggal', [$start->toDateString(), $end->toDateString()])
            ->whereIn('status', ['izin', 'sakit'])
            ->count();

        $effective = $hadir + $izinSakit;

        return round(min(100, ($effective / $workingDays) * 100), 2);
    }

    /**
     * Detail progress kehadiran termasuk jumlah hari hadir dan total hari kerja
     */
    public function attendanceProgressDetails(PesertaProfile $peserta): object
    {
        $start = $peserta->periode_mulai->copy()->startOfDay();
        $end   = $peserta->periode_selesai->copy()->endOfDay();

        // Total hari kerja dalam seluruh periode magang
        $workingDays = $this->workingDaysInRange($start, $end);

        // Batas atas query = hari ini ATAU akhir periode, mana yang lebih kecil
        $upTo = Carbon::today()->lte($end) ? Carbon::today()->toDateString() : $end->toDateString();

        $hadir = Attendance::query()
            ->where('peserta_profile_id', $peserta->id)
            ->whereBetween('tanggal', [$start->toDateString(), $upTo])
            ->where('status', 'hadir')
            ->count();

        $izinSakit = Attendance::query()
            ->where('peserta_profile_id', $peserta->id)
            ->whereBetween('tanggal', [$start->toDateString(), $upTo])
            ->whereIn('status', ['izin', 'sakit'])
            ->count();

        $effective = $hadir + $izinSakit;
        $percent   = $workingDays > 0 ? round(min(100, ($effective / $workingDays) * 100), 2) : 0;

        return (object) [
            'total_hari' => $workingDays,
            'hari_hadir' => $effective,
            'persentase' => $percent,
        ];
    }

    public function workingDaysInRange(Carbon $start, Carbon $end): int
    {
        if ($start->gt($end)) {
            return 0;
        }

        $count = 0;
        foreach (CarbonPeriod::create($start, $end) as $date) {
            if ($date->isWeekday()) {
                $count++;
            }
        }

        return $count;
    }

    /**
     * @return Collection<int, Attendance>
     */
    public function attendanceForReport(?int $pesertaId = null): Collection
    {
        $q = Attendance::query()->with('pesertaProfile.user')->orderByDesc('tanggal');

        if ($pesertaId) {
            $q->where('peserta_profile_id', $pesertaId);
        }

        return $q->limit(5000)->get();
    }
}
