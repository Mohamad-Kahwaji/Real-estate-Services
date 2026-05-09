<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index()
    {
        $user = request()->user();

        return response()->json([
            'status' => true,
            'data' => $user->notifications,
        ]);
    }

    public function unread()
    {
        $user = request()->user();

        return response()->json([
            'status' => true,
            'data' => $user->unreadNotifications,
            'count' => $user->unreadNotifications->count(),
        ]);
    }

    public function markAsRead()
    {
        $user = request()->user();

        $user->unreadNotifications()->update(['read_at' => now()]);
        return response()->json([
            'status' => true,
            'message' => 'Notifications marked as read',
        ]);
    }
}
