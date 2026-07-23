<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use App\Models\Attendance;
use App\Models\PesertaProfile;
use Carbon\Carbon;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');


/*
|--------------------------------------------------------------------------
| AUTO CHECK OUT
|--------------------------------------------------------------------------
| Pukul 17.00 peserta yang lupa checkout akan otomatis checkout.
|--------------------------------------------------------------------------
*/

Schedule::call(function () {

    $today = Carbon::today();
    $jamCheckout = $today->copy()->setTime(17, 0);

    Attendance::whereDate('tanggal', $today)
        ->whereNotNull('check_in_at')
        ->whereNull('check_out_at')
        ->get()
        ->each(function ($attendance) use ($jamCheckout) {

            $attendance->update([
                'check_out_at' => $jamCheckout,
                'durasi_kerja' => $attendance->check_in_at->diffInMinutes($jamCheckout),
                'is_checkout_otomatis' => true,
            ]);
        });
})->weekdays()->dailyAt('17:00');


/*
|--------------------------------------------------------------------------
| AUTO ALPA
|--------------------------------------------------------------------------
| Pukul 17.05 peserta yang tidak check in otomatis menjadi alpa.
|--------------------------------------------------------------------------
*/

Schedule::call(function () {

    $today = Carbon::today();

    foreach (PesertaProfile::all() as $peserta) {

        Attendance::firstOrCreate(

            [
                'peserta_profile_id' => $peserta->id,
                'tanggal' => $today->toDateString(),
            ],

            [
                'status' => 'alpa',
            ]

        );
    }
})->weekdays()->dailyAt('17:05');
