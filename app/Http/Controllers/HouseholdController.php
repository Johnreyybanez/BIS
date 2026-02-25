<?php

namespace App\Http\Controllers;

use App\Models\Household;
use App\Models\Resident;
use Illuminate\Http\Request;

class HouseholdController extends Controller
{
    /**
     * Display a listing of households.
     * Uses ->get() so DataTables handles all pagination/search client-side.
     */
    public function index()
    {
        $households = Household::with(['headResident', 'residents'])
            ->withCount('residents')
            ->latest()
            ->get();

        $residents = Resident::select('id', 'first_name', 'middle_name', 'last_name', 'resident_code')
            ->where('status', 'active')
            ->orderBy('last_name')
            ->get();

        $puroks = Household::whereNotNull('purok')
            ->distinct()
            ->orderBy('purok')
            ->pluck('purok');

        // Summary counts
        $totalHouseholds   = $households->count();
        $withHead          = $households->whereNotNull('head_resident_id')->count();
        $totalPuroks       = $households->pluck('purok')->filter()->unique()->count();
        $noHead            = $households->whereNull('head_resident_id')->count();

        return view('households.index', compact(
            'households', 'residents', 'puroks',
            'totalHouseholds', 'withHead', 'totalPuroks', 'noHead'
        ));
    }

    /**
     * Unused — modal-based UI.
     */
    public function create()
    {
        return redirect()->route('households.index');
    }

    /**
     * Store a newly created household.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'household_number' => 'required|string|unique:households,household_number|max:50',
            'purok'            => 'nullable|string|max:50',
            'address'          => 'nullable|string|max:500',
            'head_resident_id' => 'nullable|exists:residents,id',
        ]);

        $household = Household::create($validated);

        return response()->json([
            'success'   => true,
            'message'   => "Household {$household->household_number} added successfully.",
            'household' => $household->load('headResident'),
        ]);
    }

    /**
     * Return household details as JSON for the View modal.
     */
    public function show(string $id)
    {
        $household = Household::with([
            'headResident:id,first_name,middle_name,last_name,resident_code,status',
            'residents:id,household_id,first_name,middle_name,last_name,resident_code,status',
        ])->findOrFail($id);

        return response()->json([
            'success' => true,
            'data'    => $household,
        ]);
    }

    /**
     * Return raw household data as JSON for the Edit modal.
     */
    public function edit(string $id)
    {
        $household = Household::findOrFail($id);

        return response()->json([
            'success' => true,
            'data'    => $household,
        ]);
    }

    /**
     * Update the specified household.
     */
    public function update(Request $request, string $id)
    {
        $household = Household::findOrFail($id);

        $validated = $request->validate([
            'household_number' => "required|string|unique:households,household_number,{$id}|max:50",
            'purok'            => 'nullable|string|max:50',
            'address'          => 'nullable|string|max:500',
            'head_resident_id' => 'nullable|exists:residents,id',
        ]);

        $household->update($validated);

        return response()->json([
            'success'   => true,
            'message'   => "Household {$household->household_number} updated successfully.",
            'household' => $household->fresh('headResident'),
        ]);
    }

    /**
     * Delete a household and detach its members.
     */
    public function destroy(string $id)
    {
        $household = Household::findOrFail($id);

        // Safely detach members before deleting
        $household->residents()->update(['household_id' => null]);

        $number = $household->household_number;
        $household->delete();

        return response()->json([
            'success' => true,
            'message' => "Household {$number} has been deleted.",
        ]);
    }
}