<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PengaturanController extends Controller
{
    public function locationIndex(): View
    {
        $officeLat = Setting::get('office_lat');
        $officeLng = Setting::get('office_lng');
        $radius    = Setting::get('allowed_radius_meters', 100);

        return view('admin.settings.location', compact('officeLat', 'officeLng', 'radius'));
    }

    public function locationUpdate(Request $request): RedirectResponse
    {
        $request->validate([
            'office_lat'            => 'required|numeric|between:-90,90',
            'office_lng'            => 'required|numeric|between:-180,180',
            'allowed_radius_meters' => 'required|integer|min:10|max:10000',
        ]);

        Setting::set('office_lat',            $request->office_lat);
        Setting::set('office_lng',            $request->office_lng);
        Setting::set('allowed_radius_meters', $request->allowed_radius_meters);

        return back()->with('success', 'Pengaturan lokasi absensi berhasil disimpan.');
    }
}
