<?php

namespace App\Http\Controllers;

use App\Events\MessageSent;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    public function index()
    {
        $authUser = Auth::guard('users')->user();

        // Get all users except the current one, with last message and unread count
        $users = User::where('id', '!=', $authUser->id)->get()->map(function ($user) use ($authUser) {
            $lastMessage = Message::between($authUser->id, $user->id)->latest('created_at')->first();
            $unread = Message::where('sender_id', $user->id)
                             ->where('receiver_id', $authUser->id)
                             ->whereNull('read_at')
                             ->count();
            $user->last_message = $lastMessage;
            $user->unread_count = $unread;
            return $user;
        })->sortByDesc(fn($u) => optional($u->last_message)->created_at)->values();

        return view('chat', compact('authUser', 'users'));
    }

    public function show($userId)
    {
        $authUser = Auth::guard('users')->user();
        $receiver = User::findOrFail($userId);

        // Mark messages from receiver as read
        Message::where('sender_id', $receiver->id)
               ->where('receiver_id', $authUser->id)
               ->whereNull('read_at')
               ->update(['read_at' => now()]);

        $messages = Message::between($authUser->id, $receiver->id)->get();

        // Build users list for sidebar
        $users = User::where('id', '!=', $authUser->id)->get()->map(function ($user) use ($authUser) {
            $lastMessage = Message::between($authUser->id, $user->id)->latest('created_at')->first();
            $unread = Message::where('sender_id', $user->id)
                             ->where('receiver_id', $authUser->id)
                             ->whereNull('read_at')
                             ->count();
            $user->last_message = $lastMessage;
            $user->unread_count = $unread;
            return $user;
        })->sortByDesc(fn($u) => optional($u->last_message)->created_at)->values();

        return view('chat', compact('authUser', 'receiver', 'messages', 'users'));
    }

    public function store(Request $request, $userId)
    {
        $request->validate(['body' => 'required|string|max:1000']);

        $authUser = Auth::guard('users')->user();
        $receiver = User::findOrFail($userId);

        $message = Message::create([
            'sender_id'   => $authUser->id,
            'receiver_id' => $receiver->id,
            'body'        => $request->body,
        ]);

        $message->load('sender');

        event(new MessageSent($message));

        return response()->json([
            'id'          => $message->id,
            'sender_id'   => $message->sender_id,
            'receiver_id' => $message->receiver_id,
            'body'        => $message->body,
            'sender_name' => $authUser->name,
            'created_at'  => $message->created_at->format('H:i'),
        ]);
    }
}
