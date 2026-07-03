<?php

namespace App\Http\Controllers\Peserta;

use App\Http\Controllers\Controller;
use App\Models\Evaluation;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class PenilaianController extends Controller
{
    public function index(): View
    {
        $profile = Auth::user()->pesertaProfile;

        if (!$profile) {
            abort(404, 'Profil peserta tidak ditemukan.');
        }

        $evaluation = Evaluation::with([
            'pembimbingProfile.user',
            'rubricScores.rubric'
        ])
            ->where('peserta_profile_id', $profile->id)
            ->where('is_final', true)
            ->first();

        return view('peserta.evaluation.index', compact('evaluation', 'profile'));
    }

    public function download()
    {
        $profile = Auth::user()->pesertaProfile;

        if (!$profile) {
            abort(404);
        }

        $evaluation = Evaluation::with(['pembimbingProfile.user', 'rubricScores.rubric'])
            ->where('peserta_profile_id', $profile->id)
            ->where('is_final', true)
            ->first();

        if (!$evaluation || !$evaluation->is_final) {
            return redirect()->back()->with('error', 'Penilaian belum tersedia atau belum final.');
        }

        $pdf = Pdf::loadView('pdf.evaluation', compact('evaluation', 'profile'));

        return $pdf->download('Penilaian_Magang_' . $profile->nim . '.pdf');
    }
}
