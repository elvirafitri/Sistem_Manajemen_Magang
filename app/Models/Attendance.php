<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Attendance extends Model
{
    protected $fillable = [
        'peserta_profile_id',
        'tanggal',
        'check_in_at',
        'check_in_lat',
        'check_in_lng',
        'check_out_at',
        'check_out_lat',
        'check_out_lng',
        'status',
        'keterangan',
    ];

    protected function casts(): array
    {
        return [
            'tanggal'       => 'date',
            'check_in_at'   => 'datetime',
            'check_out_at'  => 'datetime',
            'check_in_lat'  => 'float',
            'check_in_lng'  => 'float',
            'check_out_lat' => 'float',
            'check_out_lng' => 'float',
        ];
    }

    public function pesertaProfile(): BelongsTo
    {
        return $this->belongsTo(PesertaProfile::class);
    }
}
