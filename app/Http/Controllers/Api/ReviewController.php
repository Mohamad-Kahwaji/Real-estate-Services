<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    // Submits or updates a review for a completed and approved order belonging to the current user.
    public function store(Request $request, $orderid){
        $user = auth('users')->user();

        $order = Order::where('id', $orderid)
            ->where('user_id', $user->id)
            ->where('status', 'approved')
            ->first();

        if (!$order) {
            return back()->with('error', 'Order not found or not eligible for review.');
        }

        $request->validate([
            'rating'  => ['required', 'integer', 'min:1', 'max:5'],
            'comment' => ['nullable', 'string'],
        ]);

        Review::updateOrCreate(
            ['order_id' => $order->id],
            [
                'user_id'    => $user->id,
                'service_id' => $order->service_id,
                'rating'     => $request->rating,
                'comment'    => $request->comment,
            ]
        );

        return back()->with('success', 'send review successfully');
    }

    // Returns all reviews and aggregate rating stats for the specified service as JSON.
    public function index($id = null){
        $reviews = Review::with(['user', 'service.business'])->get();
        return response()->json(['status' => true, 'data' => $reviews]);
    }

    public function byService($serviceId){
        $reviews = Review::with(['user', 'service.business'])->where('service_id', $serviceId)->get();
        return response()->json([
            'status'         => true,
            'service_id'     => $serviceId,
            'average_rating' => round($reviews->avg('rating'), 1),
            'reviews_count'  => $reviews->count(),
            'data'           => $reviews,
        ]);
    }
}
