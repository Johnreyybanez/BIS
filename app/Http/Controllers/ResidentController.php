<?php

namespace App\Http\Controllers;

use App\Models\Resident;
use App\Models\Household;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ResidentController extends Controller
{
    public function index()
    {
        $residents  = Resident::with('household')->latest()->paginate(15);
        $households = Household::orderBy('household_number')->get();
        return view('residents.index', compact('residents', 'households'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name'     => 'required|string|max:100',
            'last_name'      => 'required|string|max:100',
            'gender'         => 'required|in:male,female',
            'birthdate'      => 'required|date|before:today',
            'civil_status'   => 'required|in:single,married,widowed,separated',
            'contact_number' => 'nullable|string|max:20',
            'email'          => 'nullable|email|max:150|unique:residents,email',
            'household_id'   => 'nullable|exists:households,id',
            'photo'          => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $year  = date('Y');
        $count = Resident::whereYear('created_at', $year)->count() + 1;
        $residentCode = 'RES-' . $year . '-' . str_pad($count, 5, '0', STR_PAD_LEFT);
        while (Resident::where('resident_code', $residentCode)->exists()) {
            $count++;
            $residentCode = 'RES-' . $year . '-' . str_pad($count, 5, '0', STR_PAD_LEFT);
        }

        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('residents/photos', 'public');
        }

        $resident = Resident::create([
            'resident_code'  => $residentCode,
            'first_name'     => $request->first_name,
            'middle_name'    => $request->middle_name,
            'last_name'      => $request->last_name,
            'suffix'         => $request->suffix,
            'gender'         => $request->gender,
            'birthdate'      => $request->birthdate,
            'civil_status'   => $request->civil_status,
            'nationality'    => $request->nationality ?? 'Filipino',
            'voter_status'   => $request->boolean('voter_status'),
            'occupation'     => $request->occupation,
            'contact_number' => $request->contact_number,
            'email'          => $request->email,
            'household_id'   => $request->household_id ?: null,
            'is_pwd'         => $request->boolean('is_pwd'),
            'is_senior'      => false,
            'status'         => 'active',
            'photo'          => $photoPath,
        ]);

        if ($resident->age >= 60) {
            $resident->update(['is_senior' => true]);
        }

        $this->auditLog('Created resident: ' . $resident->full_name, $resident->id, $request);

        return response()->json(['success' => true, 'message' => 'Resident created successfully.', 'resident' => $resident]);
    }

    public function show(Request $request, Resident $resident)
    {
        $resident->load('household');
        $data              = $resident->toArray();
        $data['photo_url'] = $resident->photo ? asset('storage/' . $resident->photo) : null;

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json(['success' => true, 'resident' => $data]);
        }
        return redirect()->route('residents.index');
    }

    public function edit(Request $request, Resident $resident)
    {
        $data              = $resident->toArray();
        $data['photo_url'] = $resident->photo ? asset('storage/' . $resident->photo) : null;

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json(['success' => true, 'resident' => $data]);
        }
        return redirect()->route('residents.index');
    }

    public function update(Request $request, Resident $resident)
    {
        $validator = Validator::make($request->all(), [
            'first_name'     => 'required|string|max:100',
            'last_name'      => 'required|string|max:100',
            'gender'         => 'required|in:male,female',
            'birthdate'      => 'required|date|before:today',
            'civil_status'   => 'required|in:single,married,widowed,separated',
            'contact_number' => 'nullable|string|max:20',
            'email'          => 'nullable|email|max:150|unique:residents,email,' . $resident->id,
            'household_id'   => 'nullable|exists:households,id',
            'photo'          => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $photoPath = $resident->photo;

        if ($request->hasFile('photo')) {
            if ($resident->photo) Storage::disk('public')->delete($resident->photo);
            $photoPath = $request->file('photo')->store('residents/photos', 'public');
        }

        if ($request->input('remove_photo') == '1') {
            if ($resident->photo) Storage::disk('public')->delete($resident->photo);
            $photoPath = null;
        }

        $resident->update([
            'first_name'     => $request->first_name,
            'middle_name'    => $request->middle_name,
            'last_name'      => $request->last_name,
            'suffix'         => $request->suffix,
            'gender'         => $request->gender,
            'birthdate'      => $request->birthdate,
            'civil_status'   => $request->civil_status,
            'nationality'    => $request->nationality ?? 'Filipino',
            'voter_status'   => $request->boolean('voter_status'),
            'occupation'     => $request->occupation,
            'contact_number' => $request->contact_number,
            'email'          => $request->email,
            'household_id'   => $request->household_id ?: null,
            'is_pwd'         => $request->boolean('is_pwd'),
            'is_senior'      => $resident->fresh()->age >= 60,
            'status'         => $request->status ?? $resident->status,
            'photo'          => $photoPath,
        ]);

        $this->auditLog('Updated resident: ' . $resident->full_name, $resident->id, $request);

        $fresh              = $resident->fresh()->toArray();
        $fresh['photo_url'] = $resident->fresh()->photo ? asset('storage/' . $resident->fresh()->photo) : null;

        return response()->json(['success' => true, 'message' => 'Resident updated successfully.', 'resident' => $fresh]);
    }

    public function destroy(Resident $resident)
    {
        $name = $resident->full_name;
        $id   = $resident->id;
        if ($resident->photo) Storage::disk('public')->delete($resident->photo);
        $resident->delete();
        $this->auditLog('Deleted resident: ' . $name, $id, request());
        return response()->json(['success' => true, 'message' => 'Resident deleted successfully.']);
    }

    public function search(Request $request)
    {
        $search    = $request->get('q', '');
        $residents = Resident::where('status', 'active')
            ->where(function ($q) use ($search) {
                $q->where('first_name',    'like', "%{$search}%")
                  ->orWhere('last_name',   'like', "%{$search}%")
                  ->orWhere('resident_code','like', "%{$search}%");
            })
            ->select('id', 'resident_code', 'first_name', 'middle_name', 'last_name')
            ->limit(10)->get()
            ->map(function ($r) {
                $r->full_name = trim($r->first_name . ' ' . $r->middle_name . ' ' . $r->last_name);
                return $r;
            });
        return response()->json($residents);
    }

    private function auditLog(string $action, int $recordId, $request): void
    {
        try {
            AuditLog::create([
                'user_id'    => Auth::id(),
                'action'     => $action,
                'module'     => 'Residents',
                'record_id'  => $recordId,
                'ip_address' => is_object($request) && method_exists($request, 'ip') ? $request->ip() : request()->ip(),
            ]);
        } catch (\Throwable $e) {
            logger()->error('AuditLog error: ' . $e->getMessage());
        }
    }
}