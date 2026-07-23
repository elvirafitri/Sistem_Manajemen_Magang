<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Casts\Attribute;

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
        'is_terlambat',
        'durasi_kerja',
        'is_checkout_otomatis',
        'keterangan',
    ];

    protected function casts(): array
    {
        return [
            'tanggal' => 'date',
            'check_in_at' => 'datetime',
            'check_out_at' => 'datetime',
            'check_in_lat' => 'float',
            'check_in_lng' => 'float',
            'check_out_lat' => 'float',
            'check_out_lng' => 'float',

            'is_terlambat' => 'boolean',
            'durasi_kerja' => 'integer',
            'is_checkout_otomatis' => 'boolean',
        ];
    }

    protected function durasiKerjaMenit(): Attribute
    {
        return Attribute::make(
            get: function () {
                // Jika di database kolom 'durasi_kerja' sudah ada stands nilainya, pakai itu
                if ($this->attributes['durasi_kerja'] ?? null) {
                    return $this->attributes['durasi_kerja'];
                }

                // Jika kolom 'durasi_kerja' di DB kosong/null tapi check-in & check-out ada isinya, 
                // hitung selisihnya secara otomatis secara real-time
                if ($this->check_in_at && $this->check_out_at) {
                    return $this->check_in_at->diffInMinutes($this->check_out_at);
                }

                return 0;
            }
        );
    }
    public function pesertaProfile(): BelongsTo
    {
        return $this->belongsTo(PesertaProfile::class);
    }

    public static function autoCheckout(): void
    {
        // Belum waktunya checkout otomatis
        if (now()->lt(today()->setTime(17, 0))) {
            return;
        }

        self::query()
            ->whereDate('tanggal', today())
            ->whereNotNull('check_in_at')
            ->whereNull('check_out_at')
            ->get()
            ->each(function ($attendance) {

                $checkout = today()->setTime(17, 0);

                $attendance->update([
                    'check_out_at' => $checkout,
                    'durasi_kerja' => $attendance->check_in_at->diffInMinutes($checkout),
                    'is_checkout_otomatis' => true,
                ]);
            });
    }
}
