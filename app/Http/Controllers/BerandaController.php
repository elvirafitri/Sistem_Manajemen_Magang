<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class BerandaController extends Controller
{
    public function __invoke(): RedirectResponse
    {
        if (Auth::check()) {
            return redirect()->intended(Auth::user()->dashboardRoute());
        }

        return redirect()->route('login');
    }
}
