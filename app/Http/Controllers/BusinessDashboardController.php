<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\ServiceRequest;
use Illuminate\Support\Facades\DB;

class BusinessDashboardController extends Controller
{
    // Loads the business dashboard with service stats, request counts, favorites, review averages, and recent activity.
    public function index()
    {
        $user     = auth('users')->user();
        $business = $user->businesses()->where('status', 'approved')->first();

        if (!$business) {
            return redirect()->route('allservices.user')
                ->with('info', 'You need an approved business account to access the business dashboard.');
        }

        $serviceIds = $business->service()->pluck('id');

        $stats = [
            'services'          => $serviceIds->count(),
            'requests_total'    => ServiceRequest::where('business_id', $business->id)->count(),
            'requests_pending'  => ServiceRequest::where('business_id', $business->id)->where('status', 'pending')->count(),
            'requests_approved' => ServiceRequest::where('business_id', $business->id)->where('status', 'approved')->count(),
            'requests_rejected' => ServiceRequest::where('business_id', $business->id)->where('status', 'rejected')->count(),
            'favorites'         => DB::table('favorites')->whereIn('service_id', $serviceIds)->count(),
            'reviews_count'     => Review::whereIn('service_id', $serviceIds)->count(),
            'reviews_avg'       => round(Review::whereIn('service_id', $serviceIds)->avg('rating') ?? 0, 1),
        ];

        $recentRequests = ServiceRequest::with(['user', 'service'])
            ->where('business_id', $business->id)
            ->latest()
            ->take(8)
            ->get();

        $services = $business->service()
            ->withCount('reviews')
            ->with(['category', 'subcategory'])
            ->latest()
            ->get();

        return view('users.business-dashboard', compact(
            'business', 'stats', 'recentRequests', 'services'
        ));
    }
}
