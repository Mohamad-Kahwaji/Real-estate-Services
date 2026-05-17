<?php

namespace App\Http\Controllers\dashboard;

use App\Http\Controllers\Controller;
use App\Models\Business;
use App\Models\Service;
use App\Models\ServiceRequest;
use App\Models\User;

class Analytics extends Controller
{
    public function index()
    {
        $stats = [
            'services_total'    => Service::count(),
            'services_pending'  => Service::where('status', 'pending')->count(),
            'services_approved' => Service::where('status', 'approved')->count(),
            'services_rejected' => Service::where('status', 'rejected')->count(),
            'users_total'       => User::count(),
            'businesses_total'  => Business::count(),
            'businesses_pending'=> Business::where('status', 'pending')->count(),
            'requests_total'    => ServiceRequest::count(),
            'requests_pending'  => ServiceRequest::where('status', 'pending')->count(),
        ];

        $recentServices = Service::with(['business', 'category'])
            ->latest()->limit(8)->get();

        $recentUsers = User::latest()->limit(6)->get();

        return view('content.dashboard.dashboards-analytics',
            compact('stats', 'recentServices', 'recentUsers'));
    }
}
