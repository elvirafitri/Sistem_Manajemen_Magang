<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Evaluation;
use App\Models\PesertaProfile;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Barryvdh\DomPDF\Facade\Pdf;

class LaporanPenilaianController extends Controller
{
    public function index(Request $request): View
    {
        $pesertaId = $request->integer('peserta_id') ?: null;
        $pembimbingId = $request->integer('pembimbing_id') ?: null;
        $search = $request->string('search')->trim();

        $q = Evaluation::query()
            ->with(['pesertaProfile.user', 'pembimbingProfile.user', 'rubricScores.rubric'])
            ->where('is_final', true) // hanya tampilkan yang final
            ->orderByDesc('updated_at');

        if ($pembimbingId) {
            $q->where('pembimbing_profile_id', $pembimbingId);
        }

        if ($pesertaId) {
            $q->where('peserta_profile_id', $pesertaId);
        }

        if ($search) {
            $q->whereHas('pesertaProfile.user', function ($query) use ($search) {
                $query->where('name', 'like', "%{$search}%");
            });
        }

        $rows = $q->paginate(20)->withQueryString();

        $pembimbingList = \App\Models\PembimbingProfile::query()->with('user')->get();

        $pesertaListQuery = PesertaProfile::query()->with('user')->orderBy('nim');
        if ($pembimbingId) {
            $pesertaListQuery->where('pembimbing_id', $pembimbingId);
        }
        $pesertaList = $pesertaListQuery->get();

        return view('admin.evaluation.index', compact('rows', 'pesertaList', 'pembimbingList', 'pesertaId', 'pembimbingId', 'search'));
    }

    public function exportCsv(Request $request): StreamedResponse
    {
        $pesertaId = $request->integer('peserta_id') ?: null;
        $pembimbingId = $request->integer('pembimbing_id') ?: null;

        $q = Evaluation::query()
            ->with(['pesertaProfile.user', 'pembimbingProfile.user'])
            ->where('is_final', true);

        if ($pembimbingId) {
            $q->where('pembimbing_profile_id', $pembimbingId);
        }

        if ($pesertaId) {
            $q->where('peserta_profile_id', $pesertaId);
        }

        $filename = 'penilaian-magang-' . now()->format('Y-m-d-His') . '.csv';

        return response()->streamDownload(function () use ($q): void {
            $out = fopen('php://output', 'w');
            fwrite($out, "\xEF\xBB\xBF");
            fputcsv($out, ['NIM', 'Nama', 'Pembimbing', 'Total Nilai', 'Final', 'Diperbarui'], ';');

            foreach ($q->orderBy('id')->cursor() as $ev) {
                $p = $ev->pesertaProfile;
                $pb = $ev->pembimbingProfile;
                fputcsv($out, [
                    $p?->nim,
                    $p?->user?->name,
                    $pb?->user?->name ?? '-',
                    $ev->total_nilai,
                    $ev->is_final ? 'Ya' : 'Tidak',
                    $ev->updated_at?->format('Y-m-d H:i'),
                ], ';');
            }

            fclose($out);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }

    public function download(Evaluation $evaluation)
    {
        $evaluation->load(['pesertaProfile.user', 'pembimbingProfile.user', 'rubricScores.rubric']);

        if (!$evaluation->is_final) {
            return redirect()->back()->with('error', 'Penilaian belum final.');
        }

        $profile = $evaluation->pesertaProfile;

        $pdf = Pdf::loadView('pdf.evaluation', compact('evaluation', 'profile'));

        return $pdf->download('Penilaian_Magang_' . $profile->nim . '.pdf');
    }
}
