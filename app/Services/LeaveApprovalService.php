<?php

namespace App\Services;

use App\Models\Attendance;
use App\Models\LeaveRequest;
use Carbon\CarbonPeriod;

class LeaveApprovalService
{
    /**
     * Setelah izin/sakit disetujui, catat status per hari kerja di rentang tersebut.
     */
    public function syncAttendanceForApprovedLeave(LeaveRequest $leave): void
    {
        $status = $leave->jenis === 'sakit' ? 'sakit' : 'izin';

        foreach (CarbonPeriod::create($leave->tanggal_mulai, $leave->tanggal_selesai) as $date) {
            if (! $date->isWeekday()) {
                continue;
            }

            Attendance::query()->updateOrCreate(
                [
                    'peserta_profile_id' => $leave->peserta_profile_id,
                    'tanggal' => $date->toDateString(),
                ],
                [
                    'status' => $status,
                    'check_in_at' => null,
                    'check_out_at' => null,
                    'keterangan' => 'Disetujui dari pengajuan #'.$leave->id,
                ]
            );
        }
    }

    /**
     * Batalkan status izin/sakit di kalender jika ditolak (kembali alpa kecuali sudah check-in manual).
     */
    public function revertAttendanceForRejectedLeave(LeaveRequest $leave): void
    {
        foreach (CarbonPeriod::create($leave->tanggal_mulai, $leave->tanggal_selesai) as $date) {
            if (! $date->isWeekday()) {
                continue;
            }

            $att = Attendance::query()
                ->where('peserta_profile_id', $leave->peserta_profile_id)
                ->where('tanggal', $date->toDateString())
                ->first();

            if (! $att) {
                continue;
            }

            if (in_array($att->status, ['izin', 'sakit'], true)
                && str_contains((string) $att->keterangan, 'Disetujui dari pengajuan #'.$leave->id)) {
                $att->update([
                    'status' => $att->check_in_at ? 'hadir' : 'alpa',
                    'keterangan' => null,
                ]);
            }
        }
    }
}
