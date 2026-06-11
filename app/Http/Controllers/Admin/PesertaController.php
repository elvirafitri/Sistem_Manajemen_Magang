<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PembimbingProfile;
use App\Models\PesertaProfile;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class PesertaController extends Controller
{
    public function index(): View
    {
        $peserta = PesertaProfile::query()
            ->with(['user', 'pembimbing.user'])
            ->orderBy('nim')
            ->paginate(15);

        return view('admin.peserta.index', compact('peserta'));
    }

    public function show(PesertaProfile $peserta): View
    {
        $peserta->load(['user', 'pembimbing.user', 'documents']);
        return view('admin.peserta.show', compact('peserta'));
    }

    public function create(): View
    {
        $pembimbing = PembimbingProfile::query()->with('user')->orderBy('id')->get();

        return view('admin.peserta.create', compact('pembimbing'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'min:8', 'confirmed'],
            'periode_mulai' => ['required', 'date'],
            'periode_selesai' => ['required', 'date', 'after_or_equal:periode_mulai'],
            'pembimbing_id' => ['nullable', 'exists:pembimbing_profiles,id'],
            'avatar' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:5120'],
            'nim' => ['nullable', 'string', 'max:32'],
            'phone' => ['nullable', 'string', 'max:20'],
            'jenis_program' => ['nullable', 'string', 'max:255'],
            'jurusan' => ['nullable', 'string', 'max:255'],
            'institusi' => ['nullable', 'string', 'max:255'],
            'alamat' => ['nullable', 'string'],
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
                'role' => User::ROLE_PESERTA,
                'avatar_path' => $avatarPath,
            ]);

            PesertaProfile::query()->create([
                'user_id' => $user->id,
                'pembimbing_id' => $data['pembimbing_id'] ?? null,
                'nim' => $data['nim'] ?? null,
                'jenis_program' => $data['jenis_program'] ?? null,
                'jurusan' => $data['jurusan'] ?? null,
                'institusi' => $data['institusi'] ?? null,
                'phone' => $data['phone'] ?? null,
                'periode_mulai' => $data['periode_mulai'],
                'periode_selesai' => $data['periode_selesai'],
                'alamat' => $data['alamat'] ?? null,
            ]);
        });

        return redirect()->route('admin.peserta.index')->with('success', 'Peserta magang berhasil ditambahkan.');
    }

    public function edit(PesertaProfile $peserta): View
    {
        $peserta->load(['user', 'pembimbing.user']);
        $pembimbing = PembimbingProfile::query()->with('user')->orderBy('id')->get();

        return view('admin.peserta.edit', compact('peserta', 'pembimbing'));
    }

    public function update(Request $request, PesertaProfile $peserta): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', Rule::unique('users', 'email')->ignore($peserta->user_id)],
            'password' => ['nullable', 'min:8', 'confirmed'],
            'periode_mulai' => ['required', 'date'],
            'periode_selesai' => ['required', 'date', 'after_or_equal:periode_mulai'],
            'pembimbing_id' => ['nullable', 'exists:pembimbing_profiles,id'],
            'avatar' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:5120'],
            'nim' => ['nullable', 'string', 'max:32'],
            'phone' => ['nullable', 'string', 'max:20'],
            'jenis_program' => ['nullable', 'string', 'max:255'],
            'jurusan' => ['nullable', 'string', 'max:255'],
            'institusi' => ['nullable', 'string', 'max:255'],
            'alamat' => ['nullable', 'string'],
        ]);

        $avatarPath = $peserta->user->avatar_path;
        if ($request->hasFile('avatar')) {
            if ($avatarPath) {
                Storage::disk('public')->delete($avatarPath);
            }
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
        }

        DB::transaction(function () use ($data, $peserta, $avatarPath): void {
            $u = [
                'name' => $data['name'],
                'email' => $data['email'],
                'avatar_path' => $avatarPath,
            ];
            if (! empty($data['password'])) {
                $u['password'] = $data['password'];
            }
            $peserta->user->update($u);

            $peserta->update([
                'pembimbing_id' => $data['pembimbing_id'] ?? null,
                'nim' => $data['nim'] ?? null,
                'jenis_program' => $data['jenis_program'] ?? null,
                'jurusan' => $data['jurusan'] ?? null,
                'institusi' => $data['institusi'] ?? null,
                'phone' => $data['phone'] ?? null,
                'periode_mulai' => $data['periode_mulai'],
                'periode_selesai' => $data['periode_selesai'],
                'alamat' => $data['alamat'] ?? null,
            ]);
        });

        return redirect()->route('admin.peserta.index')->with('success', 'Data peserta diperbarui.');
    }

    public function destroy(PesertaProfile $peserta): RedirectResponse
    {
        DB::transaction(function () use ($peserta): void {
            $peserta->user()->delete();
        });

        return redirect()->route('admin.peserta.index')->with('success', 'Peserta dihapus.');
    }
}
