<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\ServiceRequest;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    // Submit or update a review for a completed and paid service request.
    public function store(Request $request, $requestId)
    {
        $user = auth('users')->user();

        $serviceRequest = ServiceRequest::where('id', $requestId)
            ->where('user_id', $user->id)
            ->where('status', 'approved')
            ->where('payment_status', 'paid')
            ->firstOrFail();

        $request->validate([
            'rating'  => ['required', 'integer', 'min:1', 'max:5'],
            'comment' => ['nullable', 'string', 'max:1000'],
        ]);

        Review::updateOrCreate(
            ['user_id' => $user->id, 'service_id' => $serviceRequest->service_id],
            [
                'order_id' => null,
                'rating'   => $request->rating,
                'comment'  => $request->comment,
            ]
        );

        return back()->with('success', 'Your review has been submitted. Thank you!');
    }

    // List all reviews with user and service details for the admin panel.
    public function index(Request $request)
    {
        $term = $request->search ? '%' . $request->search . '%' : null;

        $reviews = Review::with(['user', 'service.business'])
            ->when($term, fn($q) => $q->where(fn($q2) =>
                $q2->whereHas('service', fn($s) => $s->where('title', 'like', $term))
                   ->orWhereHas('user',    fn($u) => $u->where('name',  'like', $term))
            ))
            ->when($request->rating, fn($q) => $q->where('rating', $request->rating))
            ->latest()
            ->paginate(12)
            ->withQueryString();

        return view('admin.reviews', compact('reviews'));
    }

    // Delete a review from the system.
    public function destroy(Review $review)
    {
        $review->delete();
        return back()->with('success', 'Review deleted.');
    }

    // Return all reviews and aggregate rating stats for a given service (API).
    public function byService($serviceId)
    {
        $reviews = Review::with('user')
            ->where('service_id', $serviceId)
            ->latest()
            ->get();

        return response()->json([
            'service_id'     => $serviceId,
            'average_rating' => round($reviews->avg('rating') ?? 0, 1),
            'reviews_count'  => $reviews->count(),
            'data'           => $reviews,
        ]);
    }
}
