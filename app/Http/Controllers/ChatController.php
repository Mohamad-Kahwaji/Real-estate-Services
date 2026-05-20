<?php

namespace App\Http\Controllers;

use App\Events\MessageSent;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    // Builds a list of conversation partners with their last message and unread count for the given user.
    private function getConversations($authUser)
    {
        $partnerIds = Message::where('sender_id', $authUser->id)
            ->orWhere('receiver_id', $authUser->id)
            ->get(['sender_id', 'receiver_id'])
            ->map(fn($m) => $m->sender_id === $authUser->id ? $m->receiver_id : $m->sender_id)
            ->unique()->values();

        return $partnerIds->map(function ($partnerId) use ($authUser) {
            $partner = User::find($partnerId);
            if (!$partner) return null;

            $last = Message::where(function ($q) use ($authUser, $partnerId) {
                            $q->where('sender_id', $authUser->id)->where('receiver_id', $partnerId);
                        })->orWhere(function ($q) use ($authUser, $partnerId) {
                            $q->where('sender_id', $partnerId)->where('receiver_id', $authUser->id);
                        })->latest('created_at')->first();

            $unread = Message::where('sender_id', $partnerId)
                ->where('receiver_id', $authUser->id)
                ->whereNull('read_at')->count();

            return (object)[
                'user'         => $partner,
                'last_message' => $last,
                'unread'       => $unread,
            ];
        })->filter()->sortByDesc(fn($c) => optional($c->last_message)->created_at)->values();
    }

    // Loads the chat inbox view with all conversations for the authenticated user.
    public function index()
    {
        $authUser      = Auth::guard('users')->user();
        $conversations = $this->getConversations($authUser);
        $isMenu        = false;

        return view('chat', compact('authUser', 'conversations', 'isMenu'));
    }

    // Opens a conversation thread with a specific user and marks all their incoming messages as read.
    public function show($userId)
    {
        $authUser = Auth::guard('users')->user();
        $receiver = User::findOrFail($userId);
        $isMenu   = false;

        Message::where('sender_id', $receiver->id)
               ->where('receiver_id', $authUser->id)
               ->whereNull('read_at')
               ->update(['read_at' => now()]);

        $messages      = Message::between($authUser->id, $receiver->id)->get();
        $conversations = $this->getConversations($authUser);

        return view('chat', compact('authUser', 'receiver', 'messages', 'conversations', 'isMenu'));
    }

    // Validates and sends a text or file message to the specified user, broadcasting the MessageSent event.
    public function store(Request $request, $userId)
    {
        $request->validate([
            'body' => 'nullable|string|max:2000',
            'file' => 'nullable|file|max:20480|mimes:jpeg,png,jpg,gif,webp,pdf,doc,docx,xlsx,zip,txt',
        ]);

        if (! $request->filled('body') && ! $request->hasFile('file')) {
            return response()->json(['error' => 'Message or file required.'], 422);
        }

        $authUser = Auth::guard('users')->user();
        $receiver = User::findOrFail($userId);

        $filePath = null;
        $fileType = null;
        $fileName = null;

        if ($request->hasFile('file')) {
            $file     = $request->file('file');
            $fileName = $file->getClientOriginalName();
            $filePath = $file->store('chat-files', 'public');
            $fileType = in_array($file->extension(), ['jpeg', 'png', 'jpg', 'gif', 'webp'])
                ? 'image' : 'file';
        }

        $message = Message::create([
            'sender_id'   => $authUser->id,
            'receiver_id' => $receiver->id,
            'body'        => $request->body ?? '',
            'file_path'   => $filePath,
            'file_type'   => $fileType,
            'file_name'   => $fileName,
        ]);

        $message->load('sender');
        event(new MessageSent($message));

        return response()->json([
            'id'          => $message->id,
            'sender_id'   => $message->sender_id,
            'receiver_id' => $message->receiver_id,
            'body'        => $message->body,
            'file_url'    => $message->fileUrl(),
            'file_type'   => $message->file_type,
            'file_name'   => $message->file_name,
            'sender_name' => $authUser->name,
            'created_at'  => $message->created_at->format('H:i'),
        ]);
    }
}
