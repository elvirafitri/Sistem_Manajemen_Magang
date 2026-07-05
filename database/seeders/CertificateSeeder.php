<?php

namespace Database\Seeders;

use App\Models\Attendance;
use App\Models\Certificate;
use App\Models\Evaluation;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class CertificateSeeder extends Seeder
{
    public function run(): void
    {
        // Sertifikat hanya dibuat untuk peserta yang penilaiannya sudah final
        // dan periode magangnya sudah selesai
        Evaluation::where('is_final', true)
            ->with('pesertaProfile')
            ->get()
            ->each(function (Evaluation $evaluation) {
                $peserta = $evaluation->pesertaProfile;

                if (!$peserta) {
                    return;
                }

                $selesai = Carbon::parse($peserta->periode_selesai);
                if ($selesai->isFuture()) {
                    // Belum selesai magang, belum saatnya terbit sertifikat
                    return;
                }

                if (Certificate::where('peserta_profile_id', $peserta->id)->exists()) {
                    return; // sudah ada sertifikatnya
                }

                $kehadiranPersen = $this->hitungPersentaseKehadiran($peserta->id);

                Certificate::create([
                    'peserta_profile_id' => $peserta->id,
                    'nomor_sertifikat'   => $this->generateNomorSertifikat($peserta->id, $selesai),
                    'file_path'          => null, // di-generate belakangan lewat proses PDF, bukan di seeder
                    'kehadiran_persen'   => $kehadiranPersen,
                    'nilai_akhir'        => $evaluation->total_nilai,
                    'generated_at'       => $selesai->copy()->addDay(),
                ]);
            });
    }

    protected function hitungPersentaseKehadiran(int $pesertaProfileId): float
    {
        $total = Attendance::where('peserta_profile_id', $pesertaProfileId)->count();

        if ($total === 0) {
            return 0;
        }

        $hadir = Attendance::where('peserta_profile_id', $pesertaProfileId)
            ->where('status', 'hadir')
            ->count();

        return round(($hadir / $total) * 100, 2);
    }

    protected function generateNomorSertifikat(int $pesertaProfileId, Carbon $tanggalSelesai): string
    {
        $romawiBulan = [
            'I',
            'II',
            'III',
            'IV',
            'V',
            'VI',
            'VII',
            'VIII',
            'IX',
            'X',
            'XI',
            'XII',
        ][$tanggalSelesai->month - 1];

        $nomorUrut = str_pad((string) $pesertaProfileId, 3, '0', STR_PAD_LEFT);

        return "{$nomorUrut}/SM/{$romawiBulan}/{$tanggalSelesai->year}";
    }
}
