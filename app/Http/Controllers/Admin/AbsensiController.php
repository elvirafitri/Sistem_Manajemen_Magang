<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\PesertaProfile;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AbsensiController extends Controller
{
    public function index(Request $request): View
    {
        Attendance::autoCheckout();
        $pesertaId = $request->integer('peserta_id') ?: null;
        $pembimbingId = $request->integer('pembimbing_id') ?: null;

        $q = Attendance::query()
            ->with(['pesertaProfile.user'])
            ->orderByDesc('tanggal')
            ->orderByDesc('check_in_at');

        // ... baris code filter pembimbing / peserta sebelumnya ...

        if ($pesertaId) {
            $q->where('peserta_profile_id', $pesertaId);
        } elseif ($pembimbingId) {
            $q->whereHas('pesertaProfile', function ($query) use ($pembimbingId) {
                $query->where('pembimbing_id', $pembimbingId);
            });
        }

        // 1. PINDAHKAN CALCULATION SUMMARY KE SINI (Sebelum Paginate)
        $summary = [
            'hadir' => (clone $q)->where('status', 'hadir')->count(),
            'izin'  => (clone $q)->where('status', 'izin')->count(),
            'sakit' => (clone $q)->where('status', 'sakit')->count(),
            'alpha' => (clone $q)->where('status', 'alpa')->count(), // Pastikan key-nya 'alpha' sesuai dengan Blade view
        ];

        // 2. JALANKAN PAGINASI SETELAH SUMMARY DIHITUNG
        $rows = $q->paginate(30)->withQueryString();

        $pesertaListQuery = PesertaProfile::query()->with(['user', 'pembimbing'])->orderBy('nim');
        // ... sisa code ke bawah tetap sama ...

        if ($pembimbingId) {
            $pesertaListQuery->where('pembimbing_id', $pembimbingId);
        }
        $pesertaList = $pesertaListQuery->get();

        $pembimbingList = \App\Models\PembimbingProfile::query()->with('user')->orderBy('id')->get();

        return view('admin.attendance.index', compact('rows', 'pesertaList', 'pesertaId', 'pembimbingList', 'pembimbingId', 'summary'));
    }
}
