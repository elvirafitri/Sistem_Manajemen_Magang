<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LeaveRequest extends Model
{
    protected $fillable = [
        'peserta_profile_id',
        'tanggal_mulai',
        'tanggal_selesai',
        'jenis',
        'alasan',
        'bukti_path',
        'status',
        'verified_by_pembimbing_id',
        'verified_at',
        'catatan_pembimbing',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_mulai' => 'date',
            'tanggal_selesai' => 'date',
            'verified_at' => 'datetime',
        ];
    }

    public function pesertaProfile(): BelongsTo
    {
        return $this->belongsTo(PesertaProfile::class);
    }

    public function verifiedBy(): BelongsTo
    {
        return $this->belongsTo(PembimbingProfile::class, 'verified_by_pembimbing_id');
    }
}
