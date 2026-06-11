<?php

namespace App\Services;

use App\Models\Evaluation;
use App\Models\Rubric;

class EvaluationScoreService
{
    public function recalculateTotal(Evaluation $evaluation): void
    {
        $evaluation->load('rubricScores');
        $sumBobot = (float) Rubric::query()->sum('bobot_maks');
        if ($sumBobot <= 0) {
            return;
        }

        $sum = 0.0;
        $count = 0;
        foreach ($evaluation->rubricScores as $score) {
            $sum += (float) $score->nilai;
            $count++;
        }

        $average = $count > 0 ? $sum / $count : 0;

        $evaluation->update(['total_nilai' => round($average, 2)]);
    }
}
