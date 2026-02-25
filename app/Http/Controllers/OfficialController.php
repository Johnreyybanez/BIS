<?php

namespace App\Http\Controllers;

use App\Models\Official;
use App\Models\Resident;
use Illuminate\Http\Request;

class OfficialController extends Controller
{
    /**
     * Display a listing of officials.
     */
    public function index()
    {
        $officials = Official::with('resident')
            ->latest()
            ->get();

        $residents = Resident::select('id', 'first_name', 'middle_name', 'last_name', 'resident_code')
            ->where('status', 'active')
            ->orderBy('last_name')
            ->get();

        $totalOfficials = $officials->count();
        $totalActive    = $officials->where('status', 'active')->count();
        $totalInactive  = $officials->where('status', 'inactive')->count();
        $totalPositions = $officials->pluck('position')->unique()->count();

        return view('officials.index', compact(
            'officials', 'residents',
            'totalOfficials', 'totalActive', 'totalInactive', 'totalPositions'
        ));
    }

    /**
     * Unused — modal-based UI.
     */
    public function create()
    {
        return redirect()->route('officials.index');
    }

    /**
     * Store a newly created official.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'resident_id' => 'required|exists:residents,id',
            'position'    => 'required|string|max:100',
            'term_start'  => 'required|date',
            'term_end'    => 'nullable|date|after_or_equal:term_start',
            'status'      => 'required|in:active,inactive',
        ]);

        $official = Official::create($validated);

        return response()->json([
            'success'  => true,
            'message'  => "{$official->resident->full_name} has been added as {$official->position}.",
            'official' => $official->load('resident'),
        ]);
    }

    /**
     * Return official details as JSON for the View modal.
     */
    public function show(string $id)
    {
        $official = Official::with('resident:id,first_name,middle_name,last_name,resident_code')
            ->findOrFail($id);

        return response()->json([
            'success' => true,
            'data'    => $official,
        ]);
    }

    /**
     * Return raw official data as JSON for the Edit modal.
     */
    public function edit(string $id)
    {
        $official = Official::findOrFail($id);

        return response()->json([
            'success' => true,
            'data'    => $official,
        ]);
    }

    /**
     * Update the specified official.
     */
    public function update(Request $request, string $id)
    {
        $official = Official::findOrFail($id);

        $validated = $request->validate([
            'resident_id' => 'required|exists:residents,id',
            'position'    => 'required|string|max:100',
            'term_start'  => 'required|date',
            'term_end'    => 'nullable|date|after_or_equal:term_start',
            'status'      => 'required|in:active,inactive',
        ]);

        $official->update($validated);

        return response()->json([
            'success'  => true,
            'message'  => "{$official->fresh()->resident->full_name} has been updated successfully.",
            'official' => $official->fresh('resident'),
        ]);
    }

    /**
     * Remove the specified official.
     */
    public function destroy(string $id)
    {
        $official = Official::with('resident')->findOrFail($id);
        $name     = $official->resident?->full_name ?? 'Official';
        $position = $official->position;

        $official->delete();

        return response()->json([
            'success' => true,
            'message' => "{$name} ({$position}) has been removed.",
        ]);
    }
}