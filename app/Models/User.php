<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Notifications\Auth\ResetPasswordNotification;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    public const ROLE_ADMIN = 'admin';

    public const ROLE_PEMBIMBING = 'pembimbing';

    public const ROLE_PESERTA = 'peserta';

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'avatar_path',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function pembimbingProfile(): HasOne
    {
        return $this->hasOne(PembimbingProfile::class);
    }

    public function pesertaProfile(): HasOne
    {
        return $this->hasOne(PesertaProfile::class);
    }

    public function isAdmin(): bool
    {
        return $this->role === self::ROLE_ADMIN;
    }

    public function isPembimbing(): bool
    {
        return $this->role === self::ROLE_PEMBIMBING;
    }

    public function isPeserta(): bool
    {
        return $this->role === self::ROLE_PESERTA;
    }

    public function sendPasswordResetNotification($token): void
    {
        $this->notify(new ResetPasswordNotification($token));
    }

    public function dashboardRoute(): string
    {
        return match ($this->role) {
            self::ROLE_ADMIN => route('admin.dashboard'),
            self::ROLE_PEMBIMBING => route('pembimbing.dashboard'),
            default => route('peserta.dashboard'),
        };
    }
}
