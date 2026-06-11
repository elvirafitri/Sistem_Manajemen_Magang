<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\View\View;

class LupaPasswordController extends Controller
{
    public function showForm(): View
    {
        return view('auth.forgot-password');
    }

    public function sendEmail(Request $request): RedirectResponse
    {
        $request->validate(['email' => ['required', 'email']]);

        // Kirim link reset via Laravel Password Broker (email SMTP)
        $status = Password::sendResetLink(
            $request->only('email')
        );

        if ($status === Password::RESET_LINK_SENT) {
            return back()->with('success', __($status));
        }

        // Email tidak ditemukan di database
        if ($status === Password::INVALID_USER) {
            return back()->withErrors(['email' => 'Email tidak ditemukan di sistem.'])->onlyInput('email');
        }

        return back()->withErrors(['email' => __($status)])->onlyInput('email');
    }
}
