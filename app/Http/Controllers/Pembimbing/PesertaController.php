<?php

namespace App\Http\Controllers\Pembimbing;

use App\Http\Controllers\Controller;
use App\Models\PesertaProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class PesertaController extends Controller
{
    public function index(): View
    {
        $pembimbing = Auth::user()->pembimbingProfile;
        
        $peserta = PesertaProfile::query()
            ->with(['user'])
            ->where('pembimbing_id', $pembimbing?->id)
            ->orderBy('nim')
            ->paginate(15);

        return view('pembimbing.peserta.index', compact('peserta'));
    }

    public function show(PesertaProfile $peserta): View
    {
        // Ensure the supervisor can only see their own interns
        $pembimbing = Auth::user()->pembimbingProfile;
        if ($peserta->pembimbing_id !== $pembimbing->id) {
            abort(403, 'Anda tidak memiliki akses ke data peserta ini.');
        }

        $peserta->load(['user', 'documents']);
        return view('pembimbing.peserta.show', compact('peserta'));
    }
}
