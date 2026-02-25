<?php

namespace App\Http\Controllers;

use App\Models\CertificateRequest;
use App\Models\CertificateType;
use App\Models\Resident;
use App\Models\Payment;
use App\Models\AuditLog;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class CertificateController extends Controller
{
    /**
     * Returns the authenticated user's numeric ID safely.
     * Fixes: SQLSTATE[22007] 'admin' for column audit_logs.user_id
     */
    private function authUserId(): int
    {
        return (int) Auth::user()->id;
    }

    /**
     * Find the admin user dynamically instead of hardcoding user_id = 1.
     * Fixes: FK constraint violation on notifications table.
     */
    private function getAdminUser()
    {
        return User::whereHas('role', function ($q) {
            $q->where('role_name', 'admin');
        })->first();
    }

    /* ═══════════════════════════════════════════════════
       INDEX
    ═══════════════════════════════════════════════════ */
    public function index()
    {
        $certificateRequests = CertificateRequest::with(['resident', 'certificateType', 'approver'])
            ->latest()
            ->paginate(10);

        $totalRequests  = CertificateRequest::count();
        $pendingCount   = CertificateRequest::where('status', 'pending')->count();
        $approvedCount  = CertificateRequest::where('status', 'approved')->count();
        $releasedCount  = CertificateRequest::where('status', 'released')->count();

        $certificateTypes = CertificateType::all();
        $residents        = Resident::where('status', 'active')->get();

        return view('certificates.index', compact(
            'certificateRequests',
            'totalRequests',
            'pendingCount',
            'approvedCount',
            'releasedCount',
            'certificateTypes',
            'residents'
        ));
    }

    /* ═══════════════════════════════════════════════════
       CREATE
    ═══════════════════════════════════════════════════ */
    public function create()
    {
        $residents        = Resident::where('status', 'active')->get();
        $certificateTypes = CertificateType::all();
        return view('certificates.create', compact('residents', 'certificateTypes'));
    }

    /* ═══════════════════════════════════════════════════
       STORE
    ═══════════════════════════════════════════════════ */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'resident_id'         => 'required|exists:residents,id',
            'certificate_type_id' => 'required|exists:certificate_types,id',
            'purpose'             => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $controlNumber = 'CTRL-' . date('Ymd') . '-' . str_pad(
            CertificateRequest::count() + 1, 5, '0', STR_PAD_LEFT
        );

        $certificateRequest = CertificateRequest::create([
            'resident_id'         => $request->resident_id,
            'certificate_type_id' => $request->certificate_type_id,
            'purpose'             => $request->purpose,
            'control_number'      => $controlNumber,
            'status'              => 'pending',
            'requested_at'        => now(),
        ]);

        // ── Notify admin dynamically (no hardcoded ID) ──
        $admin = $this->getAdminUser();
        if ($admin) {
            Notification::create([
                'user_id' => $admin->id,
                'message' => 'New certificate request from ' . $certificateRequest->resident->full_name,
                'type'    => 'certificate_request',
            ]);
        }

        AuditLog::create([
            'user_id'    => $this->authUserId(),
            'action'     => 'Created certificate request: ' . $controlNumber,
            'module'     => 'Certificates',
            'record_id'  => $certificateRequest->id,
            'ip_address' => $request->ip(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Certificate request created successfully.',
            'request' => $certificateRequest,
        ]);
    }

    /* ═══════════════════════════════════════════════════
       SHOW
    ═══════════════════════════════════════════════════ */
    public function show(CertificateRequest $certificateRequest)
    {
        $certificateRequest->load(['resident', 'certificateType', 'payment', 'approver']);

        if (request()->wantsJson() || request()->ajax()) {
            return response()->json([
                'success' => true,
                'data'    => $certificateRequest,
            ]);
        }

        return view('certificates.show', compact('certificateRequest'));
    }

    /* ═══════════════════════════════════════════════════
       EDIT
    ═══════════════════════════════════════════════════ */
    public function edit(CertificateRequest $certificateRequest)
    {
        if ($certificateRequest->status !== 'pending') {
            return redirect()->route('certificates.index')
                ->with('error', 'Only pending requests can be edited.');
        }

        $residents        = Resident::where('status', 'active')->get();
        $certificateTypes = CertificateType::all();

        return view('certificates.edit', compact('certificateRequest', 'residents', 'certificateTypes'));
    }

    /* ═══════════════════════════════════════════════════
       UPDATE
    ═══════════════════════════════════════════════════ */
    public function update(Request $request, CertificateRequest $certificateRequest)
    {
        if ($certificateRequest->status !== 'pending') {
            return response()->json(['error' => 'Only pending requests can be edited.'], 403);
        }

        $validator = Validator::make($request->all(), [
            'resident_id'         => 'required|exists:residents,id',
            'certificate_type_id' => 'required|exists:certificate_types,id',
            'purpose'             => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $certificateRequest->update([
            'resident_id'         => $request->resident_id,
            'certificate_type_id' => $request->certificate_type_id,
            'purpose'             => $request->purpose,
        ]);

        AuditLog::create([
            'user_id'    => $this->authUserId(),
            'action'     => 'Updated certificate request: ' . $certificateRequest->control_number,
            'module'     => 'Certificates',
            'record_id'  => $certificateRequest->id,
            'ip_address' => $request->ip(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Certificate request updated successfully.',
        ]);
    }

    /* ═══════════════════════════════════════════════════
       APPROVE
    ═══════════════════════════════════════════════════ */
    public function approve(Request $request, CertificateRequest $certificateRequest)
    {
        if ($certificateRequest->status !== 'pending') {
            return response()->json(['error' => 'Only pending requests can be approved.'], 403);
        }

        $certificateRequest->update([
            'status'      => 'approved',
            'approved_by' => $this->authUserId(),
            'approved_at' => now(),
        ]);

        if ($certificateRequest->resident && $certificateRequest->resident->user_id) {
            Notification::create([
                'user_id' => (int) $certificateRequest->resident->user_id,
                'message' => 'Your certificate request has been approved. Please proceed to the barangay hall to claim and pay the fee.',
                'type'    => 'certificate_approved',
                'data'    => json_encode(['request_id' => $certificateRequest->id]),
            ]);
        }

        AuditLog::create([
            'user_id'    => $this->authUserId(),
            'action'     => 'Approved certificate request: ' . $certificateRequest->control_number,
            'module'     => 'Certificates',
            'record_id'  => $certificateRequest->id,
            'ip_address' => $request->ip(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Certificate request approved successfully.',
        ]);
    }

    /* ═══════════════════════════════════════════════════
       REJECT
    ═══════════════════════════════════════════════════ */
    public function reject(Request $request, CertificateRequest $certificateRequest)
    {
        if ($certificateRequest->status !== 'pending') {
            return response()->json(['error' => 'Only pending requests can be rejected.'], 403);
        }

        $validator = Validator::make($request->all(), [
            'notes' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $certificateRequest->update(['status' => 'rejected']);

        if ($certificateRequest->resident && $certificateRequest->resident->user_id) {
            Notification::create([
                'user_id' => (int) $certificateRequest->resident->user_id,
                'message' => 'Your certificate request has been rejected.' . ($request->notes ? ' Reason: ' . $request->notes : ''),
                'type'    => 'certificate_rejected',
                'data'    => json_encode(['request_id' => $certificateRequest->id]),
            ]);
        }

        AuditLog::create([
            'user_id'    => $this->authUserId(),
            'action'     => 'Rejected certificate request: ' . $certificateRequest->control_number
                            . ($request->notes ? ' - Notes: ' . $request->notes : ''),
            'module'     => 'Certificates',
            'record_id'  => $certificateRequest->id,
            'ip_address' => $request->ip(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Certificate request rejected.',
        ]);
    }

    /* ═══════════════════════════════════════════════════
       RELEASE
    ═══════════════════════════════════════════════════ */
    public function release(Request $request, CertificateRequest $certificateRequest)
    {
        if ($certificateRequest->status !== 'approved') {
            return response()->json(['error' => 'Only approved requests can be released.'], 403);
        }

        $certificateRequest->update([
            'status'      => 'released',
            'released_at' => now(),
        ]);

        if ($certificateRequest->resident && $certificateRequest->resident->user_id) {
            Notification::create([
                'user_id' => (int) $certificateRequest->resident->user_id,
                'message' => 'Your certificate has been released and is ready for pickup.',
                'type'    => 'certificate_released',
                'data'    => json_encode(['request_id' => $certificateRequest->id]),
            ]);
        }

        AuditLog::create([
            'user_id'    => $this->authUserId(),
            'action'     => 'Released certificate: ' . $certificateRequest->control_number,
            'module'     => 'Certificates',
            'record_id'  => $certificateRequest->id,
            'ip_address' => $request->ip(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Certificate released successfully.',
        ]);
    }

    /* ═══════════════════════════════════════════════════
       DESTROY
    ═══════════════════════════════════════════════════ */
    public function destroy(CertificateRequest $certificateRequest)
    {
        if (!in_array($certificateRequest->status, ['pending', 'rejected', 'approved'])) {
            return response()->json([
                'error' => 'Only pending, rejected, or approved requests can be deleted.'
            ], 403);
        }

        $controlNumber = $certificateRequest->control_number;
        $certificateRequest->delete();

        AuditLog::create([
            'user_id'    => $this->authUserId(),
            'action'     => 'Deleted certificate request: ' . $controlNumber,
            'module'     => 'Certificates',
            'record_id'  => null,
            'ip_address' => request()->ip(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Certificate request deleted successfully.',
        ]);
    }

    /* ═══════════════════════════════════════════════════
       ADD PAYMENT
    ═══════════════════════════════════════════════════ */
    public function addPayment(Request $request, CertificateRequest $certificateRequest)
    {
        if ($certificateRequest->status !== 'approved') {
            return response()->json(['error' => 'Payment can only be added to approved requests.'], 403);
        }

        $validator = Validator::make($request->all(), [
            'amount'         => 'required|numeric|min:0',
            'payment_method' => 'required|in:cash,gcash,maya,bank',
            'or_number'      => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $payment = Payment::create([
            'certificate_request_id' => $certificateRequest->id,
            'amount'                 => $request->amount,
            'payment_method'         => $request->payment_method,
            'or_number'              => $request->or_number ?? 'OR-' . date('Ymd') . '-' . str_pad(
                Payment::count() + 1, 5, '0', STR_PAD_LEFT
            ),
            'payment_date'           => now(),
            'received_by'            => $this->authUserId(),
        ]);

        AuditLog::create([
            'user_id'    => $this->authUserId(),
            'action'     => 'Added payment for certificate: ' . $certificateRequest->control_number,
            'module'     => 'Payments',
            'record_id'  => $payment->id,
            'ip_address' => $request->ip(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Payment added successfully.',
            'payment' => $payment,
        ]);
    }

    /* ═══════════════════════════════════════════════════
       PRINT
    ═══════════════════════════════════════════════════ */
    public function print(CertificateRequest $certificateRequest)
    {
        if (!in_array($certificateRequest->status, ['approved', 'released'])) {
            return redirect()->route('certificates.index')
                ->with('error', 'Only approved or released certificates can be printed.');
        }

        $certificateRequest->load(['resident', 'certificateType', 'approver']);

        return view('certificates.print', compact('certificateRequest'));
    }
}