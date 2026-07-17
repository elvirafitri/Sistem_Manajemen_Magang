<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PesertaProfile;
use App\Models\PembimbingProfile;
use Illuminate\View\View;

class SertifikatPageController extends Controller
{
    public function __invoke(): View
    {
        $pembimbingList = PembimbingProfile::query()
            ->with('user')
            ->get();

        $pesertaList = PesertaProfile::query()
            ->with([
                'user',
                'attendances',
                'evaluations' => function ($query) {
                    $query->where('is_final', true);
                }
            ])
            ->orderBy('nim')
            ->get();

        $certificates = \App\Models\Certificate::query()
            ->with('pesertaProfile.user')
            ->latest()
            ->get();

        return view('admin.sertifikat', compact('pembimbingList', 'pesertaList', 'certificates'));
    }
}
