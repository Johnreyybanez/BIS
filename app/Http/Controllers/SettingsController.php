<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SettingsController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $settings = $user->settings ?? [
            'notifications' => true,
            'email_notifications' => true,
            'theme' => 'light',
        ];
        
        return view('settings.index', compact('settings'));
    }

    public function updateNotifications(Request $request)
    {
        $user = Auth::user();
        
        $settings = $user->settings ?? [];
        $settings['notifications'] = $request->has('notifications');
        $settings['email_notifications'] = $request->has('email_notifications');
        
        $user->update(['settings' => $settings]);

        return response()->json([
            'success' => true,
            'message' => 'Notification settings updated successfully.'
        ]);
    }

    public function updateAppearance(Request $request)
    {
        $validator = validator($request->all(), [
            'theme' => 'required|in:light,dark,system',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user = Auth::user();
        
        $settings = $user->settings ?? [];
        $settings['theme'] = $request->theme;
        
        $user->update(['settings' => $settings]);

        return response()->json([
            'success' => true,
            'message' => 'Appearance settings updated successfully.'
        ]);
    }
}