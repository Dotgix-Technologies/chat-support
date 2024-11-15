<?php

use Dotgix\Chatsupport\Models\conversations;
use Illuminate\Support\Facades\Broadcast;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});
Broadcast::channel('conversations.{conversationId}', function ($user, $conversationId) {
    dd('bro');
    return conversations::where(function ($query) use ($user, $conversationId) {
        $query->where('id', $conversationId)
              ->where(function ($q) use ($user) {
                  $q->where('sender_id', $user->id)
                    ->orWhere('receiver_id', $user->id);
              });
    })->exists();
});
Broadcast::channel('conversations.{conversationId}', function ($user, $conversationId) {
    // Here, return true if the user is authenticated, or any other logic to validate the access
    return true; // You may add checks to ensure only authorized users can listen
});