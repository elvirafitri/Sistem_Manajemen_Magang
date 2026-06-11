<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class PembimbingProfile extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'nip',
        'phone',
    ];

    protected static function booted(): void
    {
        static::deleting(function (PembimbingProfile $p): void {
            if (! $p->isForceDeleting()) {
                PesertaProfile::where('pembimbing_id', $p->id)->update(['pembimbing_id' => null]);
            }
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function pesertaBimbingan(): HasMany
    {
        return $this->hasMany(PesertaProfile::class, 'pembimbing_id');
    }

    public function leaveVerifications(): HasMany
    {
        return $this->hasMany(LeaveRequest::class, 'verified_by_pembimbing_id');
    }
}
