<?php

namespace App\Http\Controllers\Peserta;

use App\Http\Controllers\Controller;
use App\Models\Certificate;
use App\Services\CertificateService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class SertifikatController extends Controller
{
    public function show(CertificateService $cert): View|RedirectResponse
    {
        $profile = Auth::user()->pesertaProfile;
        if (! $profile) {
            abort(404);
        }

        $certificate = Certificate::query()->where('peserta_profile_id', $profile->id)->first();

        $eval = $profile->evaluations()->with('rubricScores.rubric')->where('is_final', true)->latest()->first();
        if (! $eval) {
            return view('peserta.certificate', [
                'certificate' => null,
                'profile' => $profile,
                'eval' => null,
                'message' => 'Penilaian akhir dari pembimbing belum tersedia.',
            ]);
        }

        if (! $certificate) {
            return view('peserta.certificate', [
                'certificate' => null,
                'profile' => $profile,
                'eval' => $eval,
                'message' => 'Sertifikat Anda belum diterbitkan oleh Admin. Silakan hubungi admin untuk menerbitkan sertifikat Anda.',
            ]);
        }

        return view('peserta.certificate', [
            'certificate' => $certificate,
            'profile' => $profile,
            'eval' => $eval,
            'message' => null,
        ]);
    }

    public function download(): BinaryFileResponse|RedirectResponse
    {
        $profile = Auth::user()->pesertaProfile;
        $certificate = Certificate::query()->where('peserta_profile_id', $profile?->id)->first();

        if (! $certificate || ! $certificate->file_path || ! Storage::disk('public')->exists($certificate->file_path)) {
            return redirect()->route('peserta.certificate')->with('error', 'File sertifikat belum tersedia.');
        }

        return response()->download(
            Storage::disk('public')->path($certificate->file_path),
            'sertifikat-magang-'.$profile->nim.'-BARU.pdf'
        );
    }

    public function regenerate(CertificateService $cert): RedirectResponse
    {
        $profile = Auth::user()->pesertaProfile;
        $certificate = Certificate::query()->where('peserta_profile_id', $profile?->id)->first();
        
        if (!$certificate) {
            return redirect()->route('peserta.certificate')->with('error', 'Sertifikat belum diterbitkan oleh Admin.');
        }

        $cert->generateOrRefresh($profile);

        return redirect()->route('peserta.certificate')->with('success', 'Sertifikat diperbarui.');
    }
}
