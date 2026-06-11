<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Rubric extends Model
{
    protected $fillable = [
        'nama',
        'urutan',
        'bobot_maks',
    ];

    protected function casts(): array
    {
        return [
            'bobot_maks' => 'decimal:2',
        ];
    }

    public function evaluationScores(): HasMany
    {
        return $this->hasMany(EvaluationRubricScore::class);
    }
}
