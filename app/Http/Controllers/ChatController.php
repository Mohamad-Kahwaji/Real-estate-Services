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
        $isMenu   = false;

        return view('chat', compact('authUser', 'isMenu'));
    }

    public function show($userId)
    {
        $authUser = Auth::guard('users')->user();
        $receiver = User::findOrFail($userId);
        $isMenu   = false;

        // Only the two participants can view this conversation
        Message::where('sender_id', $receiver->id)
               ->where('receiver_id', $authUser->id)
               ->whereNull('read_at')
               ->update(['read_at' => now()]);

        $messages = Message::between($authUser->id, $receiver->id)->get();

        return view('chat', compact('authUser', 'receiver', 'messages', 'isMenu'));
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
