<?php

namespace Database\Seeders;

use App\Models\Attendance;
use App\Models\PesertaProfile;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class AttendanceSeeder extends Seeder
{
    /**
     * Titik koordinat kantor (dari tabel settings).
     * Radius toleransi absen: 50 meter.
     */
    protected float $officeLat = 1.0416722;
    protected float $officeLng = 103.9591728;

    public function run(): void
    {
        $today = Carbon::today();

        PesertaProfile::all()->each(function (PesertaProfile $peserta) use ($today) {
            $mulai   = Carbon::parse($peserta->periode_mulai);
            $selesai = Carbon::parse($peserta->periode_selesai)->min($today);

            if ($mulai->greaterThan($selesai)) {
                // Peserta belum mulai magang / periode belum jalan
                return;
            }

            $period = Carbon::parse($mulai);

            while ($period->lessThanOrEqualTo($selesai)) {
                // Skip Sabtu & Minggu
                if ($period->isWeekend()) {
                    $period->addDay();
                    continue;
                }

                // Kalau tanggal itu sudah ada absensinya (data asli), jangan ditimpa
                $exists = Attendance::where('peserta_profile_id', $peserta->id)
                    ->whereDate('tanggal', $period->toDateString())
                    ->exists();

                if (!$exists) {
                    $this->createRandomAttendance($peserta->id, $period->copy());
                }

                $period->addDay();
            }
        });
    }

    protected function createRandomAttendance(int $pesertaProfileId, Carbon $tanggal): void
    {
        // Distribusi status: 85% hadir, 5% sakit, 5% izin, 5% alpa
        $status = fake()->randomElement([
            'hadir',
            'hadir',
            'hadir',
            'hadir',
            'hadir',
            'hadir',
            'hadir',
            'hadir',
            'hadir',
            'hadir',
            'hadir',
            'hadir',
            'hadir',
            'hadir',
            'hadir',
            'hadir',
            'hadir',
            'sakit',
            'izin',
            'alpa',
        ]);

        $checkInAt = null;
        $checkOutAt = null;
        $checkInLat = $checkInLng = $checkOutLat = $checkOutLng = null;
        $keterangan = null;

        if ($status === 'hadir') {
            $checkInAt  = $tanggal->copy()->setTime(7, fake()->numberBetween(30, 59), fake()->numberBetween(0, 59));
            $checkOutAt = $tanggal->copy()->setTime(16, fake()->numberBetween(0, 45), fake()->numberBetween(0, 59));

            [$checkInLat, $checkInLng]   = $this->randomNearbyCoordinate();
            [$checkOutLat, $checkOutLng] = $this->randomNearbyCoordinate();
        } elseif ($status === 'sakit') {
            $keterangan = fake()->randomElement(['Demam', 'Flu', 'Sakit perut', 'Pusing/migrain']);
        } elseif ($status === 'izin') {
            $keterangan = fake()->randomElement(['Ada urusan keluarga', 'Keperluan kampus', 'Acara pribadi']);
        }
        // alpa: semua null, tanpa keterangan

        Attendance::create([
            'peserta_profile_id' => $pesertaProfileId,
            'tanggal'            => $tanggal->toDateString(),
            'check_in_at'        => $checkInAt,
            'check_in_lat'       => $checkInLat,
            'check_in_lng'       => $checkInLng,
            'check_out_at'       => $checkOutAt,
            'check_out_lat'      => $checkOutLat,
            'check_out_lng'      => $checkOutLng,
            'status'             => $status,
            'keterangan'         => $keterangan,
            'created_at'         => $checkInAt ?? $tanggal,
            'updated_at'         => $checkOutAt ?? $checkInAt ?? $tanggal,
        ]);
    }

    /**
     * Generate koordinat random di sekitar kantor, masih dalam radius toleransi (~50m).
     */
    protected function randomNearbyCoordinate(): array
    {
        // ~0.00045 derajat kira-kira setara 50 meter
        $deltaLat = fake()->randomFloat(7, -0.0004, 0.0004);
        $deltaLng = fake()->randomFloat(7, -0.0004, 0.0004);

        return [
            round($this->officeLat + $deltaLat, 7),
            round($this->officeLng + $deltaLng, 7),
        ];
    }
}
