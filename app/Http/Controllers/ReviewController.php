<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\ServiceRequest;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
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

    // Admin: list all reviews
    public function index()
    {
        $reviews = Review::with(['user', 'service.business'])
            ->latest()
            ->get();

        return view('admin.reviews', compact('reviews'));
    }

    // Admin: delete a review
    public function destroy(Review $review)
    {
        $review->delete();
        return back()->with('success', 'Review deleted.');
    }

    // API: reviews for a specific service
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
