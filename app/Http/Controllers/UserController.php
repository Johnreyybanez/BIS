<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    private function authUserId(): int
    {
        return (int) Auth::user()->id;
    }

    private function audit(string $action, string $module, ?int $recordId, string $ip): void
    {
        try {
            AuditLog::create([
                'user_id'    => $this->authUserId(),
                'action'     => $action,
                'module'     => $module,
                'record_id'  => $recordId,
                'ip_address' => $ip,
            ]);
        } catch (\Throwable $e) {
            Log::error('AuditLog failed: ' . $e->getMessage());
        }
    }

    /* ─── INDEX ─── */
    public function index()
    {
        $users         = User::with('role')->latest()->paginate(10);
        $roles         = Role::all();
        $totalUsers    = User::count();
        $activeCount   = User::where('status', 'active')->count();
        $inactiveCount = User::where('status', 'inactive')->count();
        $lockedCount   = User::where('status', 'locked')->count();

        return view('users.index', compact(
            'users', 'roles', 'totalUsers', 'activeCount', 'inactiveCount', 'lockedCount'
        ));
    }

    /* ─── STORE ─── */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'full_name'               => 'required|string|max:255',
            'username'                => 'required|string|max:255|unique:users',
            'email'                   => 'nullable|email|max:255|unique:users',
            'role_id'                 => 'required|exists:roles,id',
            'password'                => 'required|string|min:6|confirmed',
            'password_confirmation'   => 'required',
            'contact_number'          => 'nullable|string|max:20',
            'status'                  => 'required|in:active,inactive,locked',
            'profile_photo'           => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            $data = [
                'full_name'      => $request->full_name,
                'username'       => $request->username,
                'email'          => $request->email,
                'role_id'        => (int) $request->role_id,
                'password'       => Hash::make($request->password),
                'contact_number' => $request->contact_number,
                'status'         => $request->status,
            ];

            if ($request->hasFile('profile_photo')) {
                $data['profile_photo'] = $request->file('profile_photo')->store('profile-photos', 'public');
            }

            $user = User::create($data);

            $this->audit('Created user: ' . $user->username, 'Users', $user->id, $request->ip());

            return response()->json([
                'success' => true,
                'message' => 'User created successfully.',
                'data'    => $user->load('role'),
            ]);

        } catch (\Throwable $e) {
            Log::error('User store error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Server error: ' . $e->getMessage()], 500);
        }
    }

    /* ─── SHOW ─── */
    public function show(User $user)
    {
        $user->load('role');
        if (request()->wantsJson() || request()->ajax()) {
            return response()->json(['success' => true, 'data' => $user]);
        }
        return view('users.show', compact('user'));
    }

    /* ─── EDIT ─── */
    public function edit(User $user)
    {
        $user->load('role');
        return response()->json(['success' => true, 'data' => $user]);
    }

    /* ─── UPDATE ─── */
    public function update(Request $request, User $user)
    {
        $validator = Validator::make($request->all(), [
            'full_name'      => 'required|string|max:255',
            'username'       => 'required|string|max:255|unique:users,username,' . $user->id,
            'email'          => 'nullable|email|max:255|unique:users,email,' . $user->id,
            'role_id'        => 'required|exists:roles,id',
            'password'       => 'nullable|string|min:6|confirmed',
            'contact_number' => 'nullable|string|max:20',
            'status'         => 'required|in:active,inactive,locked',
            'profile_photo'  => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            $data = [
                'full_name'      => $request->full_name,
                'username'       => $request->username,
                'email'          => $request->email,
                'role_id'        => (int) $request->role_id,
                'contact_number' => $request->contact_number,
                'status'         => $request->status,
            ];

            if ($request->filled('password')) {
                $data['password'] = Hash::make($request->password);
            }

            if ($request->hasFile('profile_photo')) {
                if ($user->profile_photo) {
                    Storage::disk('public')->delete($user->profile_photo);
                }
                $data['profile_photo'] = $request->file('profile_photo')->store('profile-photos', 'public');
            }

            $user->update($data);

            $this->audit('Updated user: ' . $user->username, 'Users', $user->id, $request->ip());

            return response()->json([
                'success' => true,
                'message' => 'User updated successfully.',
                'data'    => $user->load('role'),
            ]);

        } catch (\Throwable $e) {
            Log::error('User update error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Server error: ' . $e->getMessage()], 500);
        }
    }

    /* ─── DESTROY ─── */
    public function destroy(User $user)
    {
        if ($user->id === Auth::id()) {
            return response()->json(['success' => false, 'message' => 'You cannot delete your own account.'], 403);
        }

        try {
            $username = $user->username;
            if ($user->profile_photo) {
                Storage::disk('public')->delete($user->profile_photo);
            }
            $user->delete();

            $this->audit('Deleted user: ' . $username, 'Users', null, request()->ip());

            return response()->json(['success' => true, 'message' => 'User deleted successfully.']);

        } catch (\Throwable $e) {
            Log::error('User destroy error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Server error: ' . $e->getMessage()], 500);
        }
    }

    /* ─── TOGGLE STATUS ─── */
    public function toggleStatus(Request $request, User $user)
    {
        if ($user->id === Auth::id()) {
            return response()->json(['success' => false, 'message' => 'Cannot change your own status.'], 403);
        }

        $validator = Validator::make($request->all(), [
            'status' => 'required|in:active,inactive,locked',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            $user->update(['status' => $request->status]);
            $this->audit('Changed status of ' . $user->username . ' to ' . $request->status, 'Users', $user->id, $request->ip());

            return response()->json(['success' => true, 'message' => 'Status updated to ' . $request->status . '.']);

        } catch (\Throwable $e) {
            return response()->json(['success' => false, 'message' => 'Server error: ' . $e->getMessage()], 500);
        }
    }
}