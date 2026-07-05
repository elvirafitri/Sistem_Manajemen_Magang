<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Certificate;
use App\Models\PesertaProfile;
use App\Services\CertificateService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class SertifikatController extends Controller
{
    public function generate(Request $request, CertificateService $cert): RedirectResponse
    {
        $data = $request->validate([
            'peserta_profile_id' => ['required', 'exists:peserta_profiles,id'],
        ]);

        $peserta = PesertaProfile::query()
            ->findOrFail($data['peserta_profile_id']);

        $hasFinalEvaluation = $peserta->evaluations()->where('is_final', true)->exists();
        if (!$hasFinalEvaluation) {
            return redirect()
                ->route('admin.sertifikat.page')
                ->with('error', 'Gagal menerbitkan sertifikat. Pembimbing belum memberikan penilaian final untuk peserta ini.');
        }

        $cert->generateOrRefresh($peserta);

        return redirect()
            ->route('admin.sertifikat.page')
            ->with('success', 'Sertifikat PDF berhasil diterbitkan.');
    }

    public function download(Certificate $certificate): BinaryFileResponse|RedirectResponse
    {
        if (! $certificate->file_path || ! Storage::disk('public')->exists($certificate->file_path)) {
            return back()->with('error', 'File sertifikat belum tersedia atau tidak ditemukan.');
        }

        return response()->download(
            Storage::disk('public')->path($certificate->file_path),
            'sertifikat-magang-'.$certificate->pesertaProfile->nim.'.pdf'
        );
    }
}
