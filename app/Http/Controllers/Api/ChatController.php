<?php

namespace App\Http\Controllers\Api;

use App\Events\MessageSent;
use App\Http\Controllers\Controller;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    // ─── GET /api/chat/conversations ─────────────────────────────────────────
    // List all conversations with last message + unread count
    public function conversations(Request $request)
    {
        $user = $request->user();

        $userId = (int) $user->id;

        // Get all unique partner IDs using pluck on sender+receiver then filter self
        $senderIds   = Message::where('receiver_id', $userId)->pluck('sender_id');
        $receiverIds = Message::where('sender_id', $userId)->pluck('receiver_id');
        $partnerIds  = $senderIds->merge($receiverIds)->unique()->values();

        $conversations = $partnerIds->map(function ($partnerId) use ($userId) {
            $pid     = (int) $partnerId;
            $partner = User::find($pid);
            if (!$partner) return null;

            $last = Message::where(function ($q) use ($userId, $pid) {
                $q->where('sender_id', $userId)->where('receiver_id', $pid);
            })->orWhere(function ($q) use ($userId, $pid) {
                $q->where('sender_id', $pid)->where('receiver_id', $userId);
            })->latest('created_at')->first();

            $unread = Message::where('sender_id', $pid)
                ->where('receiver_id', $userId)
                ->whereNull('read_at')
                ->count();

            return [
                'user' => [
                    'id'        => $partner->id,
                    'name'      => $partner->name,
                    'avatar'    => $partner->profile_photo_url ?? null,
                    'is_online' => $partner->isOnline(),
                    'last_seen' => $partner->lastSeenLabel(),
                ],
                'last_message' => $last ? [
                    'body'       => $last->body,
                    'sent_by_me' => $last->sender_id === $user->id,
                    'created_at' => $last->created_at->format('Y-m-d H:i'),
                ] : null,
                'unread_count' => $unread,
            ];
        })->filter()->sortByDesc(fn ($c) => $c['last_message']['created_at'] ?? '')->values();

        return response()->json([
            'status' => true,
            'data'   => $conversations,
        ]);
    }

    // ─── GET /api/chat/{userId} ───────────────────────────────────────────────
    // Get messages with a specific user — also marks incoming as read
    public function show(Request $request, $userId)
    {
        $user     = $request->user();
        $receiver = User::findOrFail($userId);

        // Mark messages from receiver as read
        Message::where('sender_id', $receiver->id)
            ->where('receiver_id', $user->id)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        $messages = Message::between($user->id, $receiver->id)
            ->get()
            ->map(fn ($m) => [
                'id'         => $m->id,
                'body'       => $m->body,
                'sent_by_me' => $m->sender_id === $user->id,
                'read_at'    => $m->read_at?->format('Y-m-d H:i'),
                'created_at' => $m->created_at->format('Y-m-d H:i'),
            ]);

        return response()->json([
            'status'   => true,
            'receiver' => [
                'id'        => $receiver->id,
                'name'      => $receiver->name,
                'avatar'    => $receiver->profile_photo_url ?? null,
                'is_online' => $receiver->isOnline(),
                'last_seen' => $receiver->lastSeenLabel(),
            ],
            'messages' => $messages,
        ]);
    }

    // ─── POST /api/chat/{userId} ──────────────────────────────────────────────
    // Send a message
    public function store(Request $request, $userId)
    {
        $request->validate(['body' => 'required|string|max:1000']);

        $user     = $request->user();
        $receiver = User::findOrFail($userId);

        if ($user->id === $receiver->id) {
            return response()->json([
                'status'  => false,
                'message' => 'You cannot send a message to yourself.',
            ], 422);
        }

        $message = Message::create([
            'sender_id'   => $user->id,
            'receiver_id' => $receiver->id,
            'body'        => $request->body,
        ]);

        $message->load('sender');
        event(new MessageSent($message));

        return response()->json([
            'status'  => true,
            'message' => [
                'id'         => $message->id,
                'body'       => $message->body,
                'sent_by_me' => true,
                'read_at'    => null,
                'created_at' => $message->created_at->format('Y-m-d H:i'),
            ],
        ], 201);
    }

    // ─── GET /api/chat/unread ─────────────────────────────────────────────────
    // Total unread messages count (for badge in app)
    public function unreadCount(Request $request)
    {
        $user  = $request->user();
        $count = Message::where('receiver_id', $user->id)
            ->whereNull('read_at')
            ->count();

        return response()->json([
            'status'       => true,
            'unread_count' => $count,
        ]);
    }

    // ─── DELETE /api/chat/{messageId} ────────────────────────────────────────
    // Delete own message
    public function destroy(Request $request, $messageId)
    {
        $user    = $request->user();
        $message = Message::where('id', $messageId)
            ->where('sender_id', $user->id)
            ->firstOrFail();

        $message->delete();

        return response()->json([
            'status'  => true,
            'message' => 'Message deleted.',
        ]);
    }
}
