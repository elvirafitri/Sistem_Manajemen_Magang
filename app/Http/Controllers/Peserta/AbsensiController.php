<?php

namespace App\Http\Controllers\Peserta;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Setting;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AbsensiController extends Controller
{
    /**
     * Hitung jarak (meter) antara dua koordinat menggunakan Haversine formula.
     */
    private function haversineDistance(float $lat1, float $lng1, float $lat2, float $lng2): float
    {
        $earthRadius = 6371000; // meter
        $dLat = deg2rad($lat2 - $lat1);
        $dLng = deg2rad($lng2 - $lng1);

        $a = sin($dLat / 2) ** 2
            + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * sin($dLng / 2) ** 2;

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return $earthRadius * $c;
    }

    /**
     * Validasi bahwa koordinat dalam radius lokasi kantor.
     * Kembalikan array ['ok' => bool, 'distance' => float, 'message' => string].
     */
    private function validateLocation(float $lat, float $lng): array
    {
        $officeLat = (float) Setting::get('office_lat');
        $officeLng = (float) Setting::get('office_lng');
        $radius    = (int)   Setting::get('allowed_radius_meters', 100);

        // Jika koordinat kantor belum diset admin, bypass validasi
        if ($officeLat == 0.0 && $officeLng == 0.0) {
            return ['ok' => true, 'distance' => 0, 'message' => 'Lokasi kantor belum dikonfigurasi.'];
        }

        $distance = $this->haversineDistance($lat, $lng, $officeLat, $officeLng);

        if ($distance > $radius) {
            return [
                'ok'       => false,
                'distance' => round($distance),
                'message'  => 'Absensi tidak dapat dilakukan karena Anda berada di luar area yang ditentukan (RS Awal Bros Botania)',
            ];
        }

        return ['ok' => true, 'distance' => round($distance), 'message' => 'Lokasi valid.'];
    }

    public function checkIn(Request $request): JsonResponse
    {
        $profile = Auth::user()->pesertaProfile;
        if (! $profile) {
            return response()->json(['success' => false, 'message' => 'Profil peserta tidak ditemukan.'], 404);
        }

        // --- PROTEKSI MASA MAGANG SELESAI ---
        if ($profile->periode_selesai && Carbon::today()->greaterThan(Carbon::parse($profile->periode_selesai))) {
            return response()->json([
                'success' => false,
                'message' => 'Masa magang Anda telah selesai. Anda tidak dapat melakukan absensi lagi.'
            ]);
        }

        if (! Carbon::today()->isWeekday()) {
            return response()->json(['success' => false, 'message' => 'Absensi hanya pada hari kerja (Senin–Jumat).']);
        }

        $now = now();

        $jamMulai = Carbon::today()->setTime(7, 0);
        $batasCheckIn = Carbon::today()->setTime(16, 0);

        if ($now->lt($jamMulai) || $now->gt($batasCheckIn)) {
            return response()->json([
                'success' => false,
                'message' => 'Absensi hanya dapat dilakukan mulai pukul 07.00 WIB'
            ]);
        }

        // Validasi input koordinat
        $request->validate([
            'latitude'  => 'required|numeric',
            'longitude' => 'required|numeric',
        ]);

        $lat = (float) $request->latitude;
        $lng = (float) $request->longitude;

        // Validasi jarak
        $locationCheck = $this->validateLocation($lat, $lng);
        if (! $locationCheck['ok']) {
            return response()->json([
                'success'  => false,
                'message'  => $locationCheck['message'],
                'distance' => $locationCheck['distance'],
            ]);
        }

        $att = Attendance::query()->firstOrCreate(
            [
                'peserta_profile_id' => $profile->id,
                'tanggal'            => Carbon::today()->toDateString(),
            ],
            ['status' => 'alpa']
        );

        if ($att->status === 'izin' || $att->status === 'sakit') {
            return response()->json(['success' => false, 'message' => 'Hari ini tercatat ' . $att->status . '.']);
        }

        if ($att->check_in_at) {
            return response()->json(['success' => false, 'message' => 'Anda sudah check-in hari ini.']);
        }

        $checkIn = now();

        $batasTerlambat = Carbon::today()->setTime(8, 10);

        $att->update([
            'check_in_at'        => $checkIn,
            'check_in_lat'       => $lat,
            'check_in_lng'       => $lng,
            'status'             => 'hadir',
            'is_terlambat'       => $checkIn->gt($batasTerlambat),
        ]);

        return response()->json([
            'success'  => true,
            'message'  => 'Check-in berhasil! Selamat bekerja 💪',
            'time'     => now()->format('H:i'),
            'distance' => $locationCheck['distance'],
        ]);
    }

    public function checkOut(Request $request): JsonResponse
    {
        $profile = Auth::user()->pesertaProfile;
        if (! $profile) {
            return response()->json(['success' => false, 'message' => 'Profil peserta tidak ditemukan.'], 404);
        }

        // --- PROTEKSI MASA MAGANG SELESAI ---
        if ($profile->periode_selesai && Carbon::today()->greaterThan(Carbon::parse($profile->periode_selesai))) {
            return response()->json([
                'success' => false,
                'message' => 'Masa magang Anda telah selesai. Anda tidak dapat melakukan absensi lagi.'
            ]);
        }

        // Validasi input koordinat
        $request->validate([
            'latitude'  => 'required|numeric',
            'longitude' => 'required|numeric',
        ]);

        $lat = (float) $request->latitude;
        $lng = (float) $request->longitude;

        // Validasi jarak
        $locationCheck = $this->validateLocation($lat, $lng);
        if (! $locationCheck['ok']) {
            return response()->json([
                'success'  => false,
                'message'  => $locationCheck['message'],
                'distance' => $locationCheck['distance'],
            ]);
        }

        $att = Attendance::query()
            ->where('peserta_profile_id', $profile->id)
            ->whereDate('tanggal', Carbon::today())
            ->first();

        if (! $att || ! $att->check_in_at) {
            return response()->json(['success' => false, 'message' => 'Lakukan check-in terlebih dahulu.']);
        }

        if ($att->check_out_at) {
            return response()->json(['success' => false, 'message' => 'Anda sudah check-out hari ini.']);
        }

        $checkOut = now();

        $durasiKerja = $att->check_in_at->diffInMinutes($checkOut);

        $att->update([
            'check_out_at'          => $checkOut,
            'check_out_lat'         => $lat,
            'check_out_lng'         => $lng,
            'durasi_kerja'          => $durasiKerja,
            'is_checkout_otomatis'  => false,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Check-out berhasil. Sampai jumpa besok! 👋',
            'time'    => now()->format('H:i'),
        ]);
    }
}
