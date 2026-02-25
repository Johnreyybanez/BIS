<?php

namespace App\Http\Controllers;

use App\Models\CertificateType;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class CertificateTypeController extends Controller
{
    /**
     * Returns the authenticated user's numeric ID safely.
     * Fixes: SQLSTATE[22007] 'admin' for column audit_logs.user_id
     */
    private function authUserId(): int
    {
        return (int) Auth::user()->id;
    }

    /* ═══════════════════════════════════════════════════
       INDEX
    ═══════════════════════════════════════════════════ */
    public function index()
    {
        $certificateTypes = CertificateType::withCount('certificateRequests')
            ->orderBy('certificate_name')
            ->paginate(10);

        $totalTypes = CertificateType::count();
        $totalFee   = CertificateType::sum('fee');
        $avgFee     = CertificateType::avg('fee');
        $mostUsed   = CertificateType::withCount('certificateRequests')
            ->orderBy('certificate_requests_count', 'desc')
            ->first();

        return view('certificate-types.index', compact(
            'certificateTypes',
            'totalTypes',
            'totalFee',
            'avgFee',
            'mostUsed'
        ));
    }

    /* ═══════════════════════════════════════════════════
       STORE
    ═══════════════════════════════════════════════════ */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'certificate_name' => 'required|string|max:255|unique:certificate_types',
            'description'      => 'nullable|string|max:1000',
            'fee'              => 'required|numeric|min:0|max:9999999.99',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $certificateType = CertificateType::create([
            'certificate_name' => $request->certificate_name,
            'description'      => $request->description,
            'fee'              => $request->fee,
        ]);

        AuditLog::create([
            'user_id'    => $this->authUserId(),   // ← FIXED
            'action'     => 'Created certificate type: ' . $certificateType->certificate_name,
            'module'     => 'Certificate Types',
            'record_id'  => $certificateType->id,
            'ip_address' => $request->ip(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Certificate type created successfully.',
            'data'    => $certificateType,
        ]);
    }

    /* ═══════════════════════════════════════════════════
       SHOW
    ═══════════════════════════════════════════════════ */
    public function show(CertificateType $certificateType)
    {
        $certificateType->load(['certificateRequests' => function ($query) {
            $query->with('resident')->latest()->limit(5);
        }]);

        $totalRequests    = $certificateType->certificateRequests()->count();
        $pendingRequests  = $certificateType->certificateRequests()->where('status', 'pending')->count();
        $approvedRequests = $certificateType->certificateRequests()->where('status', 'approved')->count();
        $releasedRequests = $certificateType->certificateRequests()->where('status', 'released')->count();
        $rejectedRequests = $certificateType->certificateRequests()->where('status', 'rejected')->count();

        $totalRevenue = $certificateType->certificateRequests()
            ->whereIn('status', ['approved', 'released'])
            ->count() * $certificateType->fee;

        if (request()->wantsJson() || request()->ajax()) {
            return response()->json([
                'success' => true,
                'data'    => $certificateType,
                'stats'   => [
                    'total_requests' => $totalRequests,
                    'pending'        => $pendingRequests,
                    'approved'       => $approvedRequests,
                    'released'       => $releasedRequests,
                    'rejected'       => $rejectedRequests,
                    'total_revenue'  => $totalRevenue,
                ],
            ]);
        }

        return view('certificate-types.show', compact('certificateType'));
    }

    /* ═══════════════════════════════════════════════════
       EDIT
    ═══════════════════════════════════════════════════ */
    public function edit(CertificateType $certificateType)
    {
        return response()->json([
            'success' => true,
            'data'    => $certificateType,
        ]);
    }

    /* ═══════════════════════════════════════════════════
       UPDATE
    ═══════════════════════════════════════════════════ */
    public function update(Request $request, CertificateType $certificateType)
    {
        $validator = Validator::make($request->all(), [
            'certificate_name' => 'required|string|max:255|unique:certificate_types,certificate_name,' . $certificateType->id,
            'description'      => 'nullable|string|max:1000',
            'fee'              => 'required|numeric|min:0|max:9999999.99',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $certificateType->update([
            'certificate_name' => $request->certificate_name,
            'description'      => $request->description,
            'fee'              => $request->fee,
        ]);

        AuditLog::create([
            'user_id'    => $this->authUserId(),   // ← FIXED
            'action'     => 'Updated certificate type: ' . $certificateType->certificate_name,
            'module'     => 'Certificate Types',
            'record_id'  => $certificateType->id,
            'ip_address' => $request->ip(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Certificate type updated successfully.',
            'data'    => $certificateType,
        ]);
    }

    /* ═══════════════════════════════════════════════════
       DESTROY
    ═══════════════════════════════════════════════════ */
    public function destroy(CertificateType $certificateType)
    {
        if ($certificateType->certificateRequests()->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot delete certificate type that has existing requests.',
            ], 422);
        }

        $name = $certificateType->certificate_name;
        $id   = $certificateType->id;
        $certificateType->delete();

        AuditLog::create([
            'user_id'    => $this->authUserId(),   // ← FIXED
            'action'     => 'Deleted certificate type: ' . $name,
            'module'     => 'Certificate Types',
            'record_id'  => null,
            'ip_address' => request()->ip(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Certificate type deleted successfully.',
        ]);
    }

    /* ═══════════════════════════════════════════════════
       GET TYPES (API / dropdowns)
    ═══════════════════════════════════════════════════ */
    public function getTypes()
    {
        $types = CertificateType::orderBy('certificate_name')
            ->select('id', 'certificate_name', 'fee')
            ->get();

        return response()->json([
            'success' => true,
            'data'    => $types,
        ]);
    }
}