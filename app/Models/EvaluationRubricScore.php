<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EvaluationRubricScore extends Model
{
    protected $fillable = [
        'evaluation_id',
        'rubric_id',
        'nilai',
    ];

    protected function casts(): array
    {
        return [
            'nilai' => 'decimal:2',
        ];
    }

    public function evaluation(): BelongsTo
    {
        return $this->belongsTo(Evaluation::class);
    }

    public function rubric(): BelongsTo
    {
        return $this->belongsTo(Rubric::class);
    }

    public function getPredikatAttribute(): string
    {
        $nilai = $this->nilai;
        if ($nilai === null) {
            return '-';
        }
        
        if ($nilai >= 85) return 'A';
        if ($nilai >= 70) return 'B';
        if ($nilai >= 55) return 'C';
        if ($nilai >= 40) return 'D';
        return 'E';
    }
}
