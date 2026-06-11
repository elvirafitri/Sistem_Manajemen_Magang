<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class ResetSandiController extends Controller
{
    /**
     * Admin langsung ganti password user (tanpa email).
     */
    public function reset(Request $request, User $user): RedirectResponse
    {
        $data = $request->validate([
            'password' => ['required', 'confirmed', Password::min(8)],
        ]);

        $user->update(['password' => Hash::make($data['password'])]);

        return back()->with('success', "Password {$user->name} berhasil direset.");
    }
}
