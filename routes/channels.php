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

/*Broadcast::channel('App.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});*/

use App\Models\UserType;

Broadcast::channel('notifications', function () {
    return true;
});

Broadcast::channel('user-{parameter}', function ($user, $userId) {
    return $user->id == $userId;
});

Broadcast::channel('admin', function ($user) {
    return $user->user_types_id == 1;
});

Broadcast::channel('office', function ($user) {
    return $user->user_types_id == 2 || $user->user_types_id == 1;
});
