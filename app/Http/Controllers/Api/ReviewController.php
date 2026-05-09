<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function store(Request $request,$orderid){
      $user = auth('users')->user();

    $order = Order::where('id', $orderid)
        ->where('user_id', $user->id)
        ->where('status', 'approved')
        ->first();

    if (!$order) {
        return back()->with('error', 'Order not found or not eligible for review.');
    }

    $request->validate([
        'rating' => ['required', 'integer', 'min:1', 'max:5'],
        'comment' => ['nullable', 'string'],
    ]);

    Review::updateOrCreate(
        ['order_id' => $order->id],
        [
            'user_id' => $user->id,
            'service_id' => $order->service_id,
            'rating' => $request->rating,
            'comment' => $request->comment,
        ]
    );

    return back()->with('success','send review successfully');
    }

    public function index($id){
      $reviews = Review::with(['user','service.business'])->where('service_id',$id)->get();
      return response()->json([
        'status' => 'approved',
        'service_id' => $id,
        'average_rating' => $reviews->avg('rating'),
        'reviews_count' => $reviews->count(),
        'data' => $reviews,
        'message' => 'Reviews retrieved successfully',
      ]);
    }

}
