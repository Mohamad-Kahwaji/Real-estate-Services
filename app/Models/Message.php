<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $fillable = ['sender_id', 'receiver_id', 'body', 'read_at', 'file_path', 'file_type', 'file_name'];

    public function fileUrl(): ?string
    {
        return $this->file_path ? asset('storage/' . $this->file_path) : null;
    }

    public function isImage(): bool
    {
        return $this->file_type === 'image';
    }

    protected $casts = ['read_at' => 'datetime'];

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function receiver()
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }

    // Get all messages between two users
    public static function between(int $user1, int $user2)
    {
        return static::where(function ($q) use ($user1, $user2) {
            $q->where('sender_id', $user1)->where('receiver_id', $user2);
        })->orWhere(function ($q) use ($user1, $user2) {
            $q->where('sender_id', $user2)->where('receiver_id', $user1);
        })->orderBy('created_at');
    }
}
