<?php

namespace App\Http\Controllers\Peserta;

use App\Http\Controllers\Controller;
use App\Models\Document;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ProfilController extends Controller
{
    public function edit(): View
    {
        $profile = Auth::user()->pesertaProfile;
        $profile?->load('documents');

        return view('peserta.profil-peserta', compact('profile'));
    }

    public function update(Request $request): RedirectResponse
    {
        $profile = Auth::user()->pesertaProfile;
        if (! $profile) {
            abort(404);
        }

        $data = $request->validate([
            'nim' => ['required', 'string', 'max:32'],
            'jurusan' => ['required', 'string', 'max:128'],
            'institusi' => ['required', 'string', 'max:128'],
            'phone' => ['required', 'string', 'max:32'],
            'alamat' => ['required', 'string'],
            'avatar' => ['nullable', 'image', 'max:5120', 'mimes:jpg,jpeg,png'],
            'dokumen' => ['nullable', 'file', 'max:5120', 'mimes:pdf'],
            'nama_dokumen' => ['nullable', 'string', 'max:128'],
            'kategori_dokumen' => ['nullable', 'string', 'in:Surat Pengantar Magang,Surat Balasan Instansi,Laporan Magang,Lainnya'],
        ], [
            'nim.required'               => 'NIM/NIS wajib diisi.',
            'nim.max'                    => 'NIM/NIS maksimal 32 karakter.',
            'jurusan.required'           => 'Program studi wajib diisi.',
            'institusi.required'         => 'Institusi/Kampus wajib diisi.',
            'phone.required'             => 'Nomor telepon wajib diisi.',
            'alamat.required'            => 'Alamat lengkap wajib diisi.',
            'avatar.image'              => 'Foto profil harus berupa gambar.',
            'avatar.mimes'              => 'Format foto hanya boleh JPG, JPEG, atau PNG.',
            'avatar.max'                => 'Ukuran foto profil maksimal 5MB.',
            'dokumen.file'              => 'File dokumen tidak valid.',
            'dokumen.max'               => 'Ukuran dokumen terlalu besar. Maksimal 5MB.',
            'dokumen.mimes'             => 'Dokumen hanya boleh berformat PDF.',
        ]);

        // 1. Update Peserta Profiles
        $profile->update([
            'nim' => $data['nim'],
            'jurusan' => $data['jurusan'],
            'institusi' => $data['institusi'],
            'phone' => $data['phone'],
            'alamat' => $data['alamat'],
        ]);

        // 2. Update Avatar in Users table
        $avatarUploaded = false;
        if ($request->hasFile('avatar')) {
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
            /** @var \App\Models\User $user */
            $user = Auth::user();
            $user->update([
                'avatar_path' => $avatarPath
            ]);
            $avatarUploaded = true;
        }

        // 3. Document Upload
        if ($request->hasFile('dokumen')) {
            $kategori = $data['kategori_dokumen'] ?? 'Lainnya';
            $nama = $data['nama_dokumen'] ?: ($kategori !== 'Lainnya' ? $kategori : $request->file('dokumen')->getClientOriginalName());
            
            $path = $request->file('dokumen')->store('dokumen/'.$profile->id, 'public');
            Document::query()->create([
                'peserta_profile_id' => $profile->id,
                'nama' => $nama,
                'path' => $path,
            ]);
        }

        $message = $avatarUploaded
            ? 'Profil diperbarui dan foto profil berhasil diupload! 🎉'
            : 'Profil berhasil diperbarui.';

        return redirect()->route('peserta.profile')->with('success', $message);
    }

    public function destroyDocument(Document $document): RedirectResponse
    {
        $profile = Auth::user()->pesertaProfile;

        // Verify the document belongs to the logged-in user
        if ($document->peserta_profile_id !== $profile?->id) {
            abort(403, 'Unauthorized action.');
        }

        // Delete from storage
        if (\Illuminate\Support\Facades\Storage::disk('public')->exists($document->path)) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($document->path);
        }

        // Delete record
        $document->delete();

        return redirect()->route('peserta.profile')->with('success', 'Dokumen berhasil dihapus.');
    }
}
