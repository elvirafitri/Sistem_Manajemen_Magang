<?php

namespace App\Services;

use App\Models\Certificate;
use App\Models\Evaluation;
use App\Models\PesertaProfile;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CertificateService
{
    public function __construct(
        protected AttendanceStatsService $attendanceStats
    ) {}

    public function generateOrRefresh(PesertaProfile $peserta): Certificate
    {
        $eval = Evaluation::query()
            ->with(['rubricScores.rubric'])
            ->where('peserta_profile_id', $peserta->id)
            ->where('is_final', true)
            ->latest()
            ->first();

        $scores = $eval ? $eval->rubricScores->map(function ($rs) {
            $rs->predicate = $this->getPredicate($rs->nilai);
            return $rs;
        }) : collect();

        $total_nilai = $scores->sum('nilai');
        $average_nilai = $scores->count() > 0 ? $total_nilai / $scores->count() : 0.0;
        $persen = $this->attendanceStats->attendancePercent($peserta);

        $nomor = 'CERT-' . now()->format('Y') . '-' . str_pad((string) $peserta->id, 5, '0', STR_PAD_LEFT);

        // Logo Path for PDF (Only process if GD extension is available)
        $logoPath = public_path('storage/avatars/logo-rs-awalbros.png');
        $logo = null;
        if (file_exists($logoPath) && extension_loaded('gd')) {
            try {
                $logo = 'data:image/png;base64,' . base64_encode(file_get_contents($logoPath));
            } catch (\Exception $e) {
                $logo = null;
            }
        }

        $programName = str_contains(strtolower($peserta->peserta_type ?? ''), 'pkl') ? 'PKL' : 'MAGANG';

        $predikat = $this->getPredicate($average_nilai);

        $pdf = Pdf::setOptions([
            'isHtml5ParserEnabled' => true,
            'isRemoteEnabled' => true
        ])->loadView('pdf.certificate', [
            'peserta' => $peserta->load(['user', 'pembimbing.user']),
            'nomor' => $nomor,
            'kehadiran' => $persen,
            'nilai' => number_format($average_nilai, 2),
            'predikat' => $predikat,
            'total' => $total_nilai,
            'scores' => $scores,
            'tanggal' => now(),
            'logo' => $logo,
            'programName' => $programName,
        ])->setPaper('a4', 'landscape');

        $dir = 'certificates';
        $filename = $dir . '/' . Str::slug($peserta->nim . '-' . $nomor) . '.pdf';
        Storage::disk('public')->put($filename, $pdf->output());

        return Certificate::query()->updateOrCreate(
            ['peserta_profile_id' => $peserta->id],
            [
                'nomor_sertifikat' => $nomor,
                'file_path' => $filename,
                'kehadiran_persen' => $persen,
                'nilai_akhir' => $average_nilai,
                'generated_at' => now(),
            ]
        );
    }

    private function getPredicate(float $score): string
    {
        if ($score >= 85) return 'A';
        if ($score >= 70) return 'B';
        if ($score >= 55) return 'C';
        if ($score >= 40) return 'D';
        return 'E';
    }
}
