<?php

use App\Http\Controllers\Admin\AbsensiController as AdminAbsensiController;
use App\Http\Controllers\Admin\BerandaController as AdminBerandaController;
use App\Http\Controllers\Admin\LaporanPenilaianController;
use App\Http\Controllers\Admin\PembimbingController as AdminPembimbingController;
use App\Http\Controllers\Admin\PesertaController as AdminPesertaController;
use App\Http\Controllers\Auth\LupaPasswordController;
use App\Http\Controllers\Auth\MasukController;
use App\Http\Controllers\Auth\AturUlangPasswordController;
use App\Http\Controllers\BerandaController;
use App\Http\Controllers\Admin\SertifikatController as AdminSertifikatController;
use App\Http\Controllers\Pembimbing\BerandaController as PembimbingBerandaController;
use App\Http\Controllers\Pembimbing\PenilaianController as PembimbingPenilaianController;
use App\Http\Controllers\Pembimbing\PermohonanIzinController as PembimbingPermohonanIzinController;
use App\Http\Controllers\Pembimbing\KalenderAbsensiController as PembimbingKalenderAbsensiController;
use App\Http\Controllers\Admin\SertifikatPageController as AdminSertifikatPageController;
use App\Http\Controllers\Admin\PengaturanController as AdminPengaturanController;
use App\Http\Controllers\Peserta\AbsensiController as PesertaAbsensiController;
use App\Http\Controllers\Peserta\SertifikatController as PesertaSertifikatController;
use App\Http\Controllers\Peserta\BerandaController as PesertaBerandaController;
use App\Http\Controllers\Peserta\RiwayatController as PesertaRiwayatController;
use App\Http\Controllers\Peserta\IzinController as PesertaIzinController;
use App\Http\Controllers\Peserta\ProfilController as PesertaProfilController;
use App\Http\Controllers\Peserta\PenilaianController as PesertaPenilaianController;
use Illuminate\Support\Facades\Route;

Route::get('/', BerandaController::class)->name('home');

// Route untuk serve file storage melalui Laravel (mengatasi forbidden di XAMPP)
Route::get('/berkas/{path}', function (string $path) {
    $disk = \Illuminate\Support\Facades\Storage::disk('public');
    
    if (! $disk->exists($path)) {
        abort(404, 'File tidak ditemukan.');
    }
    
    $pathOnDisk = $disk->path($path);
    
    return response()->file($pathOnDisk, [
        'Content-Disposition' => 'inline; filename="' . basename($path) . '"'
    ]);
})->where('path', '.*')->name('storage.file');

Route::get('/lang/{lang}', function ($lang) {
    if (in_array($lang, ['id', 'en'])) {
        session(['locale' => $lang]);
    }
    return back();
})->name('lang.switch');

Route::middleware('guest')->group(function (): void {
    Route::get('/login', [MasukController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [MasukController::class, 'login']);
    Route::get('/lupa-password', [LupaPasswordController::class, 'showForm'])->name('password.request');
    Route::post('/lupa-password', [LupaPasswordController::class, 'sendEmail'])->name('password.email');
    Route::get('/reset-password/{token}', [AturUlangPasswordController::class, 'showResetForm'])->name('password.reset');
    Route::post('/reset-password', [AturUlangPasswordController::class, 'reset'])->name('password.update');
});

Route::post('/logout', [MasukController::class, 'logout'])->middleware('auth')->name('logout');

Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function (): void {
    Route::get('/dashboard', AdminBerandaController::class)->name('dashboard');

    Route::get('peserta', [AdminPesertaController::class, 'index'])->name('peserta.index');
    Route::get('peserta/create', [AdminPesertaController::class, 'create'])->name('peserta.create');
    Route::get('peserta/{peserta}', [AdminPesertaController::class, 'show'])->name('peserta.show');
    Route::post('peserta', [AdminPesertaController::class, 'store'])->name('peserta.store');
    Route::get('peserta/{peserta}/edit', [AdminPesertaController::class, 'edit'])->name('peserta.edit');
    Route::put('peserta/{peserta}', [AdminPesertaController::class, 'update'])->name('peserta.update');
    Route::delete('peserta/{peserta}', [AdminPesertaController::class, 'destroy'])->name('peserta.destroy');
    Route::resource('pembimbing', AdminPembimbingController::class)->except(['show']);

    Route::get('/absensi', [AdminAbsensiController::class, 'index'])->name('absensi.index');
    Route::get('/penilaian', [LaporanPenilaianController::class, 'index'])->name('penilaian.index');
    Route::get('/penilaian/export', [LaporanPenilaianController::class, 'exportCsv'])->name('penilaian.export');
    Route::get('/penilaian/download/{evaluation}', [LaporanPenilaianController::class, 'download'])->name('penilaian.download');
    Route::get('/sertifikat', AdminSertifikatPageController::class)->name('sertifikat.page');
    Route::post('/sertifikat/generate', [AdminSertifikatController::class, 'generate'])->name('sertifikat.generate');
    Route::get('/sertifikat/download/{certificate}', [AdminSertifikatController::class, 'download'])->name('sertifikat.download');

    Route::get('/pengaturan/lokasi', [AdminPengaturanController::class, 'locationIndex'])->name('setting.location');
    Route::post('/pengaturan/lokasi', [AdminPengaturanController::class, 'locationUpdate'])->name('setting.location.update');
});

Route::middleware(['auth', 'role:pembimbing'])->prefix('pembimbing')->name('pembimbing.')->group(function (): void {
    Route::get('/dashboard', PembimbingBerandaController::class)->name('dashboard');

    Route::get('/izin-sakit', [PembimbingPermohonanIzinController::class, 'index'])->name('leaves.index');
    Route::get('/izin-sakit/{leave}', [PembimbingPermohonanIzinController::class, 'show'])->name('leaves.show');
    Route::post('/izin-sakit/{leave}/setujui', [PembimbingPermohonanIzinController::class, 'approve'])->name('leaves.approve');
    Route::post('/izin-sakit/{leave}/tolak', [PembimbingPermohonanIzinController::class, 'reject'])->name('leaves.reject');

    Route::get('/penilaian', [PembimbingPenilaianController::class, 'index'])->name('evaluation.index');
    Route::get('/penilaian/{peserta}/edit', [PembimbingPenilaianController::class, 'edit'])->name('evaluation.edit');
    Route::put('/penilaian/{peserta}', [PembimbingPenilaianController::class, 'update'])->name('evaluation.update');
    Route::get('/penilaian/{peserta}/download', [PembimbingPenilaianController::class, 'download'])->name('evaluation.download');

    Route::get('/peserta', [\App\Http\Controllers\Pembimbing\PesertaController::class, 'index'])->name('peserta.index');
    Route::get('/peserta/{peserta}', [\App\Http\Controllers\Pembimbing\PesertaController::class, 'show'])->name('peserta.show');

    Route::get('/kalender', [PembimbingKalenderAbsensiController::class, 'index'])->name('calendar.index');
});

Route::middleware(['auth', 'role:peserta'])->prefix('peserta')->name('peserta.')->group(function (): void {
    Route::get('/dashboard', PesertaBerandaController::class)->name('dashboard');

    Route::get('/profil', [PesertaProfilController::class, 'edit'])->name('profile');
    Route::put('/profil', [PesertaProfilController::class, 'update'])->name('profile.update');
    Route::delete('/profil/dokumen/{document}', [PesertaProfilController::class, 'destroyDocument'])->name('profile.document.destroy');

    Route::post('/absensi/check-in', [PesertaAbsensiController::class, 'checkIn'])->name('absensi.checkin');
    Route::post('/absensi/check-out', [PesertaAbsensiController::class, 'checkOut'])->name('absensi.checkout');

    Route::get('/izin/baru', [PesertaIzinController::class, 'create'])->name('leave.create');
    Route::post('/izin', [PesertaIzinController::class, 'store'])->name('leave.store');

    Route::get('/penilaian', [PesertaPenilaianController::class, 'index'])->name('evaluation.index');
    Route::get('/penilaian/download', [PesertaPenilaianController::class, 'download'])->name('evaluation.download');

    Route::get('/riwayat-absensi', PesertaRiwayatController::class)->name('history');

    Route::get('/sertifikat', [PesertaSertifikatController::class, 'show'])->name('certificate');
    Route::get('/sertifikat/download', [PesertaSertifikatController::class, 'download'])->name('certificate.download');
    Route::post('/sertifikat/refresh', [PesertaSertifikatController::class, 'regenerate'])->name('certificate.refresh');
});

