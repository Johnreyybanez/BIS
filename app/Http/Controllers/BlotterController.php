<?php

namespace App\Http\Controllers;

use App\Models\BlotterRecord;
use App\Models\Resident;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class BlotterController extends Controller
{
    /**
     * Display a listing of blotter records.
     */
    public function index()
    {
        $blotters = BlotterRecord::with(['complainant', 'respondent', 'createdBy'])
            ->latest()
            ->paginate(10);

        // Get all active residents for the modal dropdowns
        $residents = Resident::where('status', 'active')
            ->orderBy('last_name')
            ->get(['id', 'first_name', 'middle_name', 'last_name', 'suffix', 'resident_code']);

        // Get stats for cards
        $totalCases = BlotterRecord::count();
        $ongoingCases = BlotterRecord::where('status', 'ongoing')->count();
        $settledCases = BlotterRecord::where('status', 'settled')->count();
        $filedCases = BlotterRecord::where('status', 'filed')->count();
        $dismissedCases = BlotterRecord::where('status', 'dismissed')->count();

        // Get monthly data for chart (optional)
        $monthlyData = BlotterRecord::select(
                DB::raw('MONTH(created_at) as month'),
                DB::raw('COUNT(*) as total')
            )
            ->whereYear('created_at', date('Y'))
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        return view('blotters.index', compact(
            'blotters',
            'residents',
            'totalCases',
            'ongoingCases',
            'settledCases',
            'filedCases',
            'dismissedCases',
            'monthlyData'
        ));
    }

    /**
     * Show the form for creating a new blotter record.
     */
    public function create()
    {
        $residents = Resident::where('status', 'active')->get();
        return view('blotters.create', compact('residents'));
    }

    /**
     * Store a newly created blotter record.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'complainant_id' => 'required|exists:residents,id',
            'respondent_id' => 'required|exists:residents,id|different:complainant_id',
            'incident_date' => 'required|date',
            'incident_location' => 'required|string|max:255',
            'description' => 'required|string|min:10',
            'status' => 'sometimes|in:ongoing,filed'
        ], [
            'respondent_id.different' => 'Complainant and respondent cannot be the same person.'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $blotter = BlotterRecord::create([
            'complainant_id' => $request->complainant_id,
            'respondent_id' => $request->respondent_id,
            'incident_date' => $request->incident_date,
            'incident_location' => $request->incident_location,
            'description' => $request->description,
            'status' => $request->status ?? 'ongoing',
            'created_by' => Auth::id()
        ]);

        AuditLog::create([
            'user_id' => Auth::id(),
            'action' => 'Created blotter case: Case #' . $blotter->id,
            'module' => 'Blotters',
            'record_id' => $blotter->id,
            'ip_address' => $request->ip()
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Blotter case created successfully.',
            'data' => $blotter->load(['complainant', 'respondent'])
        ]);
    }

    /**
     * Display the specified blotter record.
     */
    public function show(BlotterRecord $blotter)
    {
        $blotter->load(['complainant', 'respondent', 'createdBy']);
        
        return response()->json([
            'success' => true,
            'data' => $blotter
        ]);
    }

    /**
     * Show the form for editing the specified blotter record.
     */
    public function edit(BlotterRecord $blotter)
    {
        $blotter->load(['complainant', 'respondent']);
        
        // Get all active residents for the dropdown
        $residents = Resident::where('status', 'active')
            ->orderBy('last_name')
            ->get(['id', 'first_name', 'middle_name', 'last_name', 'suffix', 'resident_code']);
        
        return response()->json([
            'success' => true,
            'data' => $blotter,
            'residents' => $residents
        ]);
    }

    /**
     * Update the specified blotter record.
     */
    public function update(Request $request, BlotterRecord $blotter)
    {
        $validator = Validator::make($request->all(), [
            'complainant_id' => 'required|exists:residents,id',
            'respondent_id' => 'required|exists:residents,id|different:complainant_id',
            'incident_date' => 'required|date',
            'incident_location' => 'required|string|max:255',
            'description' => 'required|string|min:10',
            'status' => 'required|in:ongoing,settled,filed,dismissed'
        ], [
            'respondent_id.different' => 'Complainant and respondent cannot be the same person.'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $oldStatus = $blotter->status;
        
        $blotter->update([
            'complainant_id' => $request->complainant_id,
            'respondent_id' => $request->respondent_id,
            'incident_date' => $request->incident_date,
            'incident_location' => $request->incident_location,
            'description' => $request->description,
            'status' => $request->status
        ]);

        $actionMessage = 'Updated blotter case: Case #' . $blotter->id;
        if ($oldStatus != $request->status) {
            $actionMessage .= ' (Status changed from ' . $oldStatus . ' to ' . $request->status . ')';
        }

        AuditLog::create([
            'user_id' => Auth::id(),
            'action' => $actionMessage,
            'module' => 'Blotters',
            'record_id' => $blotter->id,
            'ip_address' => $request->ip()
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Blotter case updated successfully.',
            'data' => $blotter->load(['complainant', 'respondent'])
        ]);
    }

    /**
     * Update only the status of a blotter record.
     */
    public function updateStatus(Request $request, BlotterRecord $blotter)
    {
        $validator = Validator::make($request->all(), [
            'status' => 'required|in:ongoing,settled,filed,dismissed',
            'notes' => 'nullable|string|max:500'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $oldStatus = $blotter->status;
        $blotter->update(['status' => $request->status]);

        AuditLog::create([
            'user_id' => Auth::id(),
            'action' => 'Changed blotter case status: Case #' . $blotter->id . ' from ' . $oldStatus . ' to ' . $request->status . ($request->notes ? ' - Notes: ' . $request->notes : ''),
            'module' => 'Blotters',
            'record_id' => $blotter->id,
            'ip_address' => $request->ip()
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Case status updated successfully.'
        ]);
    }

    /**
     * Remove the specified blotter record.
     */
    public function destroy(BlotterRecord $blotter)
    {
        $caseNumber = $blotter->case_number;
        $blotter->delete();

        AuditLog::create([
            'user_id' => Auth::id(),
            'action' => 'Deleted blotter case: Case ' . $caseNumber,
            'module' => 'Blotters',
            'record_id' => null,
            'ip_address' => request()->ip()
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Blotter case deleted successfully.'
        ]);
    }

    /**
     * Get all active residents for API calls.
     */
    public function getResidents()
    {
        $residents = Resident::where('status', 'active')
            ->orderBy('last_name')
            ->get(['id', 'first_name', 'middle_name', 'last_name', 'suffix', 'resident_code']);
        
        return response()->json([
            'success' => true,
            'data' => $residents
        ]);
    }

    /**
     * Generate reports.
     */
    public function reports()
    {
        $stats = [
            'total' => BlotterRecord::count(),
            'ongoing' => BlotterRecord::where('status', 'ongoing')->count(),
            'settled' => BlotterRecord::where('status', 'settled')->count(),
            'filed' => BlotterRecord::where('status', 'filed')->count(),
            'dismissed' => BlotterRecord::where('status', 'dismissed')->count(),
        ];

        $monthlyData = BlotterRecord::select(
                DB::raw('MONTH(created_at) as month'),
                DB::raw('YEAR(created_at) as year'),
                DB::raw('COUNT(*) as total')
            )
            ->whereYear('created_at', date('Y'))
            ->groupBy('year', 'month')
            ->orderBy('month')
            ->get();

        $topComplainants = BlotterRecord::with('complainant')
            ->select('complainant_id', DB::raw('COUNT(*) as total'))
            ->whereNotNull('complainant_id')
            ->groupBy('complainant_id')
            ->orderByDesc('total')
            ->limit(5)
            ->get();

        $topRespondents = BlotterRecord::with('respondent')
            ->select('respondent_id', DB::raw('COUNT(*) as total'))
            ->whereNotNull('respondent_id')
            ->groupBy('respondent_id')
            ->orderByDesc('total')
            ->limit(5)
            ->get();

        return view('blotters.reports', compact('stats', 'monthlyData', 'topComplainants', 'topRespondents'));
    }
}