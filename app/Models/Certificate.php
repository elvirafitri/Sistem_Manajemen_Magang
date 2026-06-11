<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Certificate extends Model
{
    protected $fillable = [
        'peserta_profile_id',
        'nomor_sertifikat',
        'file_path',
        'kehadiran_persen',
        'nilai_akhir',
        'generated_at',
    ];

    protected function casts(): array
    {
        return [
            'kehadiran_persen' => 'decimal:2',
            'nilai_akhir' => 'decimal:2',
            'generated_at' => 'datetime',
        ];
    }

    public function pesertaProfile(): BelongsTo
    {
        return $this->belongsTo(PesertaProfile::class);
    }
}
