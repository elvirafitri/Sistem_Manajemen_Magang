<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PembimbingProfile;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class PembimbingController extends Controller
{
    public function index(): View
    {
        $pembimbing = PembimbingProfile::query()
            ->with('user')
            ->withCount('pesertaBimbingan')
            ->orderBy('id')
            ->paginate(15);

        return view('admin.pembimbing.index', compact('pembimbing'));
    }

    public function create(): View
    {
        return view('admin.pembimbing.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'min:8', 'confirmed'],
            'nip' => ['nullable', 'string', 'max:64'],
            'phone' => ['nullable', 'string', 'max:32'],
            'avatar' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:5120'],
        ]);

        $avatarPath = null;
        if ($request->hasFile('avatar')) {
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
        }

        DB::transaction(function () use ($data, $avatarPath): void {
            $user = User::query()->create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => $data['password'],
                'role' => User::ROLE_PEMBIMBING,
                'avatar_path' => $avatarPath,
            ]);

            PembimbingProfile::query()->create([
                'user_id' => $user->id,
                'nip' => $data['nip'] ?? null,
                'phone' => $data['phone'] ?? null,
            ]);
        });

        return redirect()->route('admin.pembimbing.index')->with('success', 'Akun pembimbing dibuat.');
    }

    public function edit(PembimbingProfile $pembimbing): View
    {
        $pembimbing->load('user');

        return view('admin.pembimbing.edit', compact('pembimbing'));
    }

    public function update(Request $request, PembimbingProfile $pembimbing): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', Rule::unique('users', 'email')->ignore($pembimbing->user_id)],
            'password' => ['nullable', 'min:8', 'confirmed'],
            'nip' => ['nullable', 'string', 'max:64'],
            'phone' => ['nullable', 'string', 'max:32'],
            'avatar' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:5120'],
        ]);

        $avatarPath = $pembimbing->user->avatar_path;
        if ($request->hasFile('avatar')) {
            if ($avatarPath) {
                Storage::disk('public')->delete($avatarPath);
            }
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
        }

        DB::transaction(function () use ($data, $pembimbing, $avatarPath): void {
            $u = [
                'name' => $data['name'],
                'email' => $data['email'],
                'avatar_path' => $avatarPath,
            ];
            if (! empty($data['password'])) {
                $u['password'] = $data['password'];
            }
            $pembimbing->user->update($u);
            $pembimbing->update([
                'nip' => $data['nip'] ?? null,
                'phone' => $data['phone'] ?? null,
            ]);
        });

        return redirect()->route('admin.pembimbing.index')->with('success', 'Data pembimbing diperbarui.');
    }

    public function destroy(PembimbingProfile $pembimbing): RedirectResponse
    {
        $pembimbing->delete();

        return redirect()->route('admin.pembimbing.index')->with('success', 'Pembimbing dinonaktifkan (soft delete). Data peserta tetap aman.');
    }
}
