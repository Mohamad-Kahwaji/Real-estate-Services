<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NotificationController extends Controller
{
    // Returns all notifications (read and unread) for the authenticated user.
    public function index()
    {
        $user = request()->user();

        return response()->json([
            'status' => true,
            'data'   => $user->notifications,
        ]);
    }

    // Returns only unread notifications and their count for the authenticated user.
    public function unread()
    {
        $user = request()->user();

        return response()->json([
            'status' => true,
            'data'   => $user->unreadNotifications,
            'count'  => $user->unreadNotifications->count(),
        ]);
    }

    // Marks all unread notifications as read for the authenticated user.
    public function markAsRead()
    {
        $user = request()->user();

        $user->unreadNotifications()->update(['read_at' => now()]);

        return response()->json([
            'status'  => true,
            'message' => 'Notifications marked as read',
        ]);
    }
}
