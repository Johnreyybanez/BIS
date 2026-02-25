<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Carbon;
class AuthController extends Controller
{
    /**
     * Show the login form.
     * Redirect already-authenticated users to the dashboard.
     */
    public function showLoginForm()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }

        return view('auth.login');
    }

    /**
     * Handle a login request.
     */
    public function login(Request $request)
    {
        // Validate input
        $validator = Validator::make($request->all(), [
            'username' => 'required|string',
            'password' => 'required|string',
        ], [
            'username.required' => 'Please enter your username.',
            'password.required' => 'Please enter your password.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Check if user exists first — gives us a better error message
        $user = User::where('username', $request->username)->first();

        if (! $user) {
            return redirect()->back()
                ->with('error', 'Invalid username or password. Please try again.')
                ->withInput();
        }

        // Check account status before attempting auth
        if ($user->status === 'locked') {
            return redirect()->back()
                ->with('error', 'Your account has been locked. Please contact your administrator.')
                ->withInput();
        }

        if ($user->status !== 'active') {
            return redirect()->back()
                ->with('error', 'Your account is inactive. Please contact your administrator.')
                ->withInput();
        }

        // Attempt authentication (status check already done above, so only username + password here)
        $credentials = [
            'username' => $request->username,
            'password' => $request->password,
        ];

        if (! Auth::attempt($credentials, $request->boolean('remember'))) {
            return redirect()->back()
                ->with('error', 'Invalid username or password. Please try again.')
                ->withInput();
        }

        // Regenerate session to prevent fixation
        $request->session()->regenerate();

        // Update last login timestamp
        $user = Auth::user();
        $user->last_login = Carbon::now();
        $user->save();

        // Write audit log
        AuditLog::create([
            'user_id'    => $user->id,
            'action'     => 'User logged in',
            'module'     => 'Authentication',
            'ip_address' => $request->ip(),
        ]);

        return redirect()->route('dashboard')
            ->with('success', 'Welcome back, ' . $user->full_name . '! You are now signed in.');
    }

    /**
     * Handle a logout request.
     */
    public function logout(Request $request)
    {
        if (Auth::check()) {
            AuditLog::create([
                'user_id'    => Auth::id(),
                'action'     => 'User logged out',
                'module'     => 'Authentication',
                'ip_address' => $request->ip(),
            ]);
        }

        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')
            ->with('success', 'You have been signed out successfully.');
    }
}