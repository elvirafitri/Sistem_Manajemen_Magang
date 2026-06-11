<?php

namespace App\Providers;

use App\Models\LeaveRequest;
use App\Models\PembimbingProfile;
use App\Models\PesertaProfile;
use Carbon\Carbon;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Carbon::setLocale('id');

        Route::model('peserta', PesertaProfile::class);
        Route::model('pembimbing', PembimbingProfile::class);
        Route::model('leave', LeaveRequest::class);

        View::composer('layouts.panel', function ($view): void {
            $user = auth()->user();
            if (! $user) {
                return;
            }

            $panelHome = match (true) {
                $user->isAdmin() => route('admin.dashboard'),
                $user->isPembimbing() => route('pembimbing.dashboard'),
                default => route('peserta.dashboard'),
            };

            $panelNotifCount = 0;
            $panelNotifUrl = null;

            if ($user->isAdmin()) {
                $panelNotifCount = LeaveRequest::query()->where('status', 'pending')->count();
                $panelNotifUrl = $panelNotifCount > 0 ? route('admin.dashboard') : null;
            } elseif ($user->isPembimbing() && $user->pembimbingProfile) {
                $panelNotifCount = LeaveRequest::query()
                    ->where('status', 'pending')
                    ->whereHas('pesertaProfile', fn ($q) => $q->where('pembimbing_id', $user->pembimbingProfile->id))
                    ->count();
                $panelNotifUrl = $panelNotifCount > 0 ? route('pembimbing.leaves.index') : null;
            } elseif ($user->pesertaProfile) {
                $panelNotifCount = LeaveRequest::query()
                    ->where('peserta_profile_id', $user->pesertaProfile->id)
                    ->where('status', 'pending')
                    ->count();
                $panelNotifUrl = $panelNotifCount > 0 ? route('peserta.dashboard') : null;
            }

            $view->with(compact('panelHome', 'panelNotifCount', 'panelNotifUrl'));
        });
    }
}
