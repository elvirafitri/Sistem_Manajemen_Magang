<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class PesertaProfile extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'pembimbing_id',
        'jenis_program',
        'nim',
        'jurusan',
        'institusi',
        'phone',
        'periode_mulai',
        'periode_selesai',
        'alamat',
    ];

    protected function casts(): array
    {
        return [
            'periode_mulai' => 'date',
            'periode_selesai' => 'date',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function pembimbing(): BelongsTo
    {
        return $this->belongsTo(PembimbingProfile::class, 'pembimbing_id');
    }

    public function attendances(): HasMany
    {
        return $this->hasMany(Attendance::class);
    }

    public function leaveRequests(): HasMany
    {
        return $this->hasMany(LeaveRequest::class);
    }

    public function evaluations(): HasMany
    {
        return $this->hasMany(Evaluation::class);
    }

    public function documents(): HasMany
    {
        return $this->hasMany(Document::class);
    }

    public function certificate(): HasOne
    {
        return $this->hasOne(Certificate::class);
    }

    public function latestEvaluation(): HasOne
    {
        return $this->hasOne(Evaluation::class)->latestOfMany();
    }
}
