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
        $pesertaId = $request->integer('peserta_id') ?: null;
        $pembimbingId = $request->integer('pembimbing_id') ?: null;
        
        $q = Attendance::query()
            ->with(['pesertaProfile.user'])
            ->orderByDesc('tanggal')
            ->orderByDesc('id');

        if ($pesertaId) {
            $q->where('peserta_profile_id', $pesertaId);
        } elseif ($pembimbingId) {
            $q->whereHas('pesertaProfile', function($query) use ($pembimbingId) {
                $query->where('pembimbing_id', $pembimbingId);
            });
        }

        $rows = $q->paginate(30)->withQueryString();
        
        // Summary recap for the current filtered list
        $summary = [
            'hadir' => (clone $q)->where('status', 'hadir')->count(),
            'izin' => (clone $q)->where('status', 'izin')->count(),
            'sakit' => (clone $q)->where('status', 'sakit')->count(),
            'alpha' => (clone $q)->where('status', 'alpa')->count(),
        ];
        
        $pesertaListQuery = PesertaProfile::query()->with(['user', 'pembimbing'])->orderBy('nim');
        if ($pembimbingId) {
            $pesertaListQuery->where('pembimbing_id', $pembimbingId);
        }
        $pesertaList = $pesertaListQuery->get();
        
        $pembimbingList = \App\Models\PembimbingProfile::query()->with('user')->orderBy('id')->get();

        return view('admin.attendance.index', compact('rows', 'pesertaList', 'pesertaId', 'pembimbingList', 'pembimbingId', 'summary'));
    }
}
