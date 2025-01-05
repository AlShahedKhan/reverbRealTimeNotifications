<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});


Broadcast::channel('posts', function ($user) {
    return $user->is_admin; // Only allow users with 'is_admin' to listen to the channel
});
