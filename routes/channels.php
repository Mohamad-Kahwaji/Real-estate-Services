<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

// Private chat between two users — only participants can authorize
Broadcast::channel('chat.{user1}.{user2}', function ($user, $user1, $user2) {
    return (int) $user->id === (int) $user1 || (int) $user->id === (int) $user2;
});
