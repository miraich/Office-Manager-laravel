<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::routes(['middleware' => ['auth:sanctum']]);

Broadcast::channel('channel_for_everyone', function ($user) {
    return auth()->check();
});
