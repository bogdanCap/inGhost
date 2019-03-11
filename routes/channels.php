<?php

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

Broadcast::channel('App.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

//chat broadcast routes
Broadcast::channel('chat', function ($user) {
    //return Auth::check();
    return [
        'user' => $user
    ];
});

/*
Broadcast::channel('Chat.{session}', function ($user, Session $session) {
    if ($user->id == $session->user1_id || $user->id == $session->user2_id) {
        return true;
    }
    return false;
});
*/

