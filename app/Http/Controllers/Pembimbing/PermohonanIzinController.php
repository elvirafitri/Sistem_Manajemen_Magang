<?php

namespace App\Http\Controllers\Pembimbing;

use App\Http\Controllers\Controller;
use App\Models\LeaveRequest;
use App\Services\LeaveApprovalService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class PermohonanIzinController extends Controller
{
    public function index(): View
    {
        $profile = Auth::user()->pembimbingProfile;
        $rows = LeaveRequest::query()
            ->with(['pesertaProfile.user'])
            ->whereHas('pesertaProfile', fn ($q) => $q->where('pembimbing_id', $profile?->id))
            ->orderByDesc('created_at')
            ->paginate(20);

        return view('pembimbing.leaves.index', compact('rows'));
    }

    public function show(LeaveRequest $leave): View
    {
        $this->authorizePembimbing($leave);
        $leave->load(['pesertaProfile.user', 'verifiedBy.user']);

        return view('pembimbing.leaves.show', compact('leave'));
    }

    public function approve(LeaveRequest $leave, LeaveApprovalService $svc): RedirectResponse
    {
        $this->authorizePembimbing($leave);

        $profile = Auth::user()->pembimbingProfile;
        $leave->update([
            'status' => 'approved',
            'verified_by_pembimbing_id' => $profile?->id,
            'verified_at' => now(),
        ]);

        $svc->syncAttendanceForApprovedLeave($leave);

        return redirect()->route('pembimbing.leaves.index')->with('success', 'Pengajuan disetujui.');
    }

    public function reject(Request $request, LeaveRequest $leave, LeaveApprovalService $svc): RedirectResponse
    {
        $this->authorizePembimbing($leave);

        $request->validate([
            'catatan_pembimbing' => ['nullable', 'string', 'max:1000'],
        ]);

        if ($leave->status === 'approved') {
            $svc->revertAttendanceForRejectedLeave($leave);
        }

        $profile = Auth::user()->pembimbingProfile;
        $leave->update([
            'status' => 'rejected',
            'verified_by_pembimbing_id' => $profile?->id,
            'verified_at' => now(),
            'catatan_pembimbing' => $request->input('catatan_pembimbing'),
        ]);

        return redirect()->route('pembimbing.leaves.index')->with('success', 'Pengajuan ditolak.');
    }

    private function authorizePembimbing(LeaveRequest $leave): void
    {
        $pid = Auth::user()->pembimbingProfile?->id;
        if ($leave->pesertaProfile->pembimbing_id !== $pid) {
            abort(403);
        }
    }
}
