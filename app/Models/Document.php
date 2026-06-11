<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Document extends Model
{
    protected $fillable = [
        'peserta_profile_id',
        'nama',
        'path',
    ];

    public function pesertaProfile(): BelongsTo
    {
        return $this->belongsTo(PesertaProfile::class);
    }
}
