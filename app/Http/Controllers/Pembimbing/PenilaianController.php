<?php

namespace App\Http\Controllers\Pembimbing;

use App\Http\Controllers\Controller;
use App\Models\Evaluation;
use App\Models\EvaluationRubricScore;
use App\Models\PesertaProfile;
use App\Models\Rubric;
use App\Services\EvaluationScoreService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Barryvdh\DomPDF\Facade\Pdf;

class PenilaianController extends Controller
{
    public function index(): View
    {
        $profile = Auth::user()->pembimbingProfile;
        $peserta = PesertaProfile::query()
            ->with(['user', 'evaluations' => fn($q) => $q->latest()])
            ->where('pembimbing_id', $profile?->id)
            ->orderBy('nim')
            ->get();

        return view('pembimbing.evaluation.index', compact('peserta', 'profile'));
    }

    public function edit(PesertaProfile $peserta, EvaluationScoreService $scoreSvc): View
    {
        $this->authorizePeserta($peserta);

        $profile = Auth::user()->pembimbingProfile;
        $peserta->load([
            'user',
            'attendances' => fn($q) => $q->latest() // Mengurutkan kehadiran dari yang terbaru
        ]);

        $rekap = [
            'hadir' => $peserta->attendances->where('status', 'hadir')->count(),
            'izin'  => $peserta->attendances->where('status', 'izin')->count(),
            'sakit' => $peserta->attendances->where('status', 'sakit')->count(),
            'alpa'  => $peserta->attendances->where('status', 'alpa')->count(),
        ];

        $rubrics = Rubric::query()->orderBy('urutan')->orderBy('id')->get();

        $evaluation = Evaluation::query()->firstOrCreate(
            ['peserta_profile_id' => $peserta->id],
            [
                'pembimbing_profile_id' => $profile?->id,
                'is_final' => false,
            ]
        );

        if ($evaluation->pembimbing_profile_id !== $profile?->id) {
            $evaluation->update(['pembimbing_profile_id' => $profile?->id]);
        }

        $evaluation->load('rubricScores');
        $scoresByRubric = $evaluation->rubricScores->keyBy('rubric_id');

        return view('pembimbing.evaluation.edit', compact(
            'peserta',
            'rubrics',
            'evaluation',
            'scoresByRubric',
            'rekap'
        ));
    }

    public function update(Request $request, PesertaProfile $peserta, EvaluationScoreService $scoreSvc): RedirectResponse
    {
        $this->authorizePeserta($peserta);

        $profile = Auth::user()->pembimbingProfile;
        $rubrics = Rubric::query()->orderBy('urutan')->get();

        $rules = [
            'komentar_final' => ['nullable', 'string', 'max:2000'],
            'is_final' => ['nullable', 'boolean'],
        ];
        foreach ($rubrics as $r) {
            $rules['nilai_' . $r->id] = ['required', 'numeric', 'min:0', 'max:100'];
        }

        $data = $request->validate($rules);

        // Cek apakah penilaian sudah difinalisasi
        $evaluation = Evaluation::where('peserta_profile_id', $peserta->id)->first();

        if ($evaluation && $evaluation->is_final) {
            return redirect()
                ->route('pembimbing.evaluation.index')
                ->with('error', 'Penilaian sudah difinalisasi dan tidak dapat diubah lagi.');
        }

        DB::transaction(function () use (
            $data,
            $peserta,
            $profile,
            $rubrics,
            $scoreSvc,
            $request
        ) {

            $evaluation = Evaluation::query()->firstOrCreate(
                [
                    'peserta_profile_id' => $peserta->id
                ],
                [
                    'pembimbing_profile_id' => $profile?->id,
                    'is_final' => false
                ]
            );

            if ($evaluation->pembimbing_profile_id !== $profile?->id) {
                $evaluation->update(['pembimbing_profile_id' => $profile?->id]);
            }

            foreach ($rubrics as $r) {
                $nilai = (float) $data['nilai_' . $r->id];
                EvaluationRubricScore::query()->updateOrCreate(
                    [
                        'evaluation_id' => $evaluation->id,
                        'rubric_id' => $r->id,
                    ],
                    ['nilai' => $nilai]
                );
            }

            $scoreSvc->recalculateTotal($evaluation->fresh());

            $evaluation->update([
                'komentar_final' => $data['komentar_final'] ?? null,
                'is_final' => $request->boolean('is_final'),
                'finalized_at' => $request->boolean('is_final') ? ($evaluation->finalized_at ?? now()) : null,
            ]);
        });

        return redirect()->route('pembimbing.evaluation.index')->with('success', 'Penilaian disimpan.');
    }

    private function authorizePeserta(PesertaProfile $peserta): void
    {
        $pid = Auth::user()->pembimbingProfile?->id;
        if ($peserta->pembimbing_id !== $pid) {
            abort(403);
        }
    }

    public function download(PesertaProfile $peserta)
    {
        $this->authorizePeserta($peserta);

        $evaluation = Evaluation::with(['pesertaProfile.user', 'pembimbingProfile.user', 'rubricScores.rubric'])
            ->where('peserta_profile_id', $peserta->id)
            ->first();

        if (!$evaluation || !$evaluation->is_final) {
            return redirect()->back()->with('error', 'Penilaian belum tersedia atau belum final.');
        }

        $profile = $peserta;

        $pdf = Pdf::loadView('pdf.evaluation', compact('evaluation', 'profile'));

        return $pdf->download('Penilaian_Magang_' . $profile->nim . '.pdf');
    }
}
