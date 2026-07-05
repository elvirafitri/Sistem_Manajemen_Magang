<?php

namespace Database\Seeders;

use App\Models\Evaluation;
use App\Models\EvaluationRubricScore;
use App\Models\PesertaProfile;
use App\Models\Rubric;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class EvaluationSeeder extends Seeder
{
    protected array $komentar = [
        'Peserta menunjukkan kedisiplinan dan tanggung jawab yang baik selama magang.',
        'Peserta cukup aktif dan mampu bekerja sama dengan baik dalam tim.',
        'Peserta menunjukkan inisiatif yang baik dalam menyelesaikan tugas.',
        'Peserta sudah sangat baik dalam mengikuti kegiatan magang.',
        'Peserta perlu meningkatkan lagi kedisiplinan waktu, namun secara umum cukup baik.',
    ];

    public function run(): void
    {
        $rubrics = Rubric::all();

        if ($rubrics->isEmpty()) {
            $this->command?->warn('Tabel rubrics kosong, jalankan RubricSeeder dulu.');
            return;
        }

        PesertaProfile::whereNotNull('pembimbing_id')->get()->each(function (PesertaProfile $peserta) use ($rubrics) {
            // Hanya buat penilaian untuk peserta yang periode magangnya sudah/hampir selesai
            $selesai = Carbon::parse($peserta->periode_selesai);
            if ($selesai->isFuture() && $selesai->diffInDays(now()) > 14) {
                // Masih jauh dari selesai, skip dulu
                return;
            }

            $evaluation = Evaluation::firstOrNew([
                'peserta_profile_id' => $peserta->id,
            ]);

            if ($evaluation->exists && $evaluation->is_final) {
                // Sudah dinilai final sebelumnya, jangan diubah
                return;
            }

            $rubricScores = [];
            foreach ($rubrics as $rubric) {
                $rubricScores[$rubric->id] = fake()->randomFloat(2, 75, 98);
            }

            $totalNilai = round(array_sum($rubricScores) / count($rubricScores), 2);
            $finalizedAt = Carbon::parse($peserta->periode_selesai)->min(now());

            $evaluation->fill([
                'pembimbing_profile_id' => $peserta->pembimbing_id,
                'total_nilai'           => $totalNilai,
                'komentar_final'        => fake()->randomElement($this->komentar),
                'finalized_at'          => $finalizedAt,
                'is_final'              => true,
            ]);
            $evaluation->save();

            foreach ($rubricScores as $rubricId => $nilai) {
                EvaluationRubricScore::updateOrCreate(
                    [
                        'evaluation_id' => $evaluation->id,
                        'rubric_id'     => $rubricId,
                    ],
                    [
                        'nilai' => $nilai,
                    ]
                );
            }
        });
    }
}
