<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::routes(['middleware' => ['web', 'auth:users']]);

Broadcast::channel('chat.{user1}.{user2}', function ($user, $user1, $user2) {
    return (int)$user->id === (int)$user1 || (int)$user->id === (int)$user2;
});
