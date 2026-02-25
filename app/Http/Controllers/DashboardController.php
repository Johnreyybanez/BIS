<?php

namespace App\Http\Controllers;

use App\Models\Resident;
use App\Models\CertificateRequest;
use App\Models\BlotterRecord;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $totalResidents = Resident::where('status', 'active')->count();
        $totalSenior = Resident::where('is_senior', true)->count();
        $totalPWD = Resident::where('is_pwd', true)->count();
        
        $pendingCertificates = CertificateRequest::where('status', 'pending')->count();
        $pendingBlotters = BlotterRecord::where('status', 'ongoing')->count();
        
        $recentResidents = Resident::latest()->take(5)->get();
        $recentCertificates = CertificateRequest::with(['resident', 'certificateType'])
            ->latest()
            ->take(5)
            ->get();
        
        $notifications = Notification::where('user_id', Auth::id())
            ->where('is_read', false)
            ->latest()
            ->take(10)
            ->get();

        $data = [
            'totalResidents' => $totalResidents,
            'totalSenior' => $totalSenior,
            'totalPWD' => $totalPWD,
            'pendingCertificates' => $pendingCertificates,
            'pendingBlotters' => $pendingBlotters,
            'recentResidents' => $recentResidents,
            'recentCertificates' => $recentCertificates,
            'notifications' => $notifications
        ];

        return view('dashboard', $data);
    }
}