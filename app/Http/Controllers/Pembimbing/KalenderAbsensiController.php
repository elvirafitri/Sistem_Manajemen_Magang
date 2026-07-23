<?php

namespace App\Http\Controllers\Pembimbing;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\PesertaProfile;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class KalenderAbsensiController extends Controller
{
    public function index(Request $request): View
    {
        Attendance::autoCheckout();
        $profile = Auth::user()->pembimbingProfile;

        // Determine the selected date (default is today)
        $selectedDateString = $request->input('date');
        $monthYearString = $request->input('month_year');

        try {
            if ($selectedDateString) {
                $selectedDate = Carbon::createFromFormat('Y-m-d', $selectedDateString);
            } elseif ($monthYearString) {
                $selectedDate = Carbon::createFromFormat('Y-m', $monthYearString)->startOfMonth();
            } else {
                $selectedDate = Carbon::today();
            }
        } catch (\Exception $e) {
            $selectedDate = Carbon::today();
        }

        // Fetch attendees for the selected date
        $pesertaList = collect();
        if ($profile) {
            $pesertaList = PesertaProfile::query()
                ->with(['user'])
                ->where('pembimbing_id', $profile->id)
                ->whereDate('periode_mulai', '<=', $selectedDate)
                ->whereDate('periode_selesai', '>=', $selectedDate)
                ->orderBy('nim')
                ->get();

            $attendances = Attendance::query()
                ->whereIn('peserta_profile_id', $pesertaList->pluck('id'))
                ->whereDate('tanggal', $selectedDate->format('Y-m-d'))
                ->get()
                ->keyBy('peserta_profile_id');

            $pesertaList->each(function ($p) use ($attendances) {
                if ($attendances->has($p->id)) {
                    $att = $attendances->get($p->id);
                    $p->status_hari_ini = $att->status;
                    $p->check_in_at = $att->check_in_at;
                    $p->check_out_at = $att->check_out_at;
                    $p->is_checkout_otomatis = $att->is_checkout_otomatis;
                } else {
                    $p->status_hari_ini = 'belum';
                    $p->check_in_at = null;
                    $p->check_out_at = null;
                }
            });
        }

        // Summary for selected day
        $summary = [
            'hadir' => $pesertaList->where('status_hari_ini', 'hadir')->count(),
            'izin_sakit' => $pesertaList->whereIn('status_hari_ini', ['izin', 'sakit'])->count(),
            'alpa_belum' => $pesertaList->whereIn('status_hari_ini', ['alpa', 'belum'])->count(),
            'total' => $pesertaList->count()
        ];

        // Overall summary per student for the selected month
        if ($profile) {
            $month = $selectedDate->month;
            $year = $selectedDate->year;

            $allAttendances = Attendance::query()
                ->whereIn('peserta_profile_id', $pesertaList->pluck('id'))
                ->whereMonth('tanggal', $month)
                ->whereYear('tanggal', $year)
                ->get()
                ->groupBy('peserta_profile_id');

            $pesertaList->each(function ($p) use ($allAttendances) {
                $pAtts = $allAttendances->get($p->id, collect());
                $p->count_hadir = $pAtts->where('status', 'hadir')->count();
                $p->count_izin = $pAtts->where('status', 'izin')->count();
                $p->count_sakit = $pAtts->where('status', 'sakit')->count();
                $p->count_alpa = $pAtts->where('status', 'alpa')->count();
                $p->count_terlambat = $pAtts
                    ->where('status', 'hadir')
                    ->where('is_terlambat', true)
                    ->count();
                $p->count_jam_kurang = $pAtts
                    ->where('status', 'hadir')
                    ->filter(function ($att) {
                        return $att->durasi_kerja_menit < 420;
                    })
                    ->count();
            });
        }

        return view('pembimbing.calendar.index', compact(
            'selectedDate',
            'pesertaList',
            'summary'
        ));
    }
}
