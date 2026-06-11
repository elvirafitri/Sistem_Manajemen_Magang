<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use App\Models\Attendance;
use Carbon\Carbon;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::call(function () {
    $today = Carbon::today();
    
    // Auto-checkout for those who checked in today but forgot to checkout
    Attendance::whereDate('tanggal', $today)
        ->whereNotNull('check_in_at')
        ->whereNull('check_out_at')
        ->update([
            'check_out_at' => $today->copy()->setTime(20, 0, 0),
            'keterangan' => \Illuminate\Support\Facades\DB::raw("CONCAT(COALESCE(keterangan, ''), ' (Auto-checkout pada 20:00)')")
        ]);
})->dailyAt('20:00');
