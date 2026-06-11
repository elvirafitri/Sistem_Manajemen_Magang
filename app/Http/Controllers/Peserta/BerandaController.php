<?php

namespace App\Http\Controllers\Peserta;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\LeaveRequest;
use App\Services\AttendanceStatsService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class BerandaController extends Controller
{
    public function __invoke(AttendanceStatsService $stats): View
    {
        $profile = Auth::user()->pesertaProfile;
        
        if (! $profile) {
            return view('peserta.dashboard', ['profile' => null]);
        }

        // 1. Progress Kehadiran
        $progress = $stats->attendanceProgressDetails($profile);

        // 2. Absensi Hari Ini
        $today = Carbon::today();
        $attendanceToday = Attendance::query()
            ->where('peserta_profile_id', $profile->id)
            ->whereDate('tanggal', $today)
            ->first();

        // 3. Status Penting: Cek Izin Menunggu
        $pendingLeaves = LeaveRequest::query()
            ->where('peserta_profile_id', $profile->id)
            ->where('status', 'pending')
            ->get();
            
        // Check Izin Hari Ini (hanya yang sudah Approved)
        $leaveToday = LeaveRequest::query()
            ->where('peserta_profile_id', $profile->id)
            ->where('status', 'approved')
            ->whereDate('tanggal_mulai', '<=', $today)
            ->whereDate('tanggal_selesai', '>=', $today)
            ->first();

        // 4. Aktivitas Terakhir (Gabungan absensi & izin terakhir, disederhanakan pakai absensi 5 record terakhir)
        $recentActivities = Attendance::query()
            ->where('peserta_profile_id', $profile->id)
            ->whereDate('tanggal', '!=', $today)
            ->orderByDesc('tanggal')
            ->limit(3)
            ->get();

        // 5. Pembimbing Details
        $profile->load('pembimbing.user');

        return view('peserta.dashboard', compact(
            'profile', 'progress', 'attendanceToday', 'pendingLeaves', 'leaveToday', 'recentActivities'
        ));
    }
}
