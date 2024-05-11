<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Laravel\Reverb\Protocols\Pusher\Channels\Channel;

class GotMessage implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(public array $message)
    {
    }

    public function broadcastOn(): array
    {
        // $this->message is available here
        return [
            new PrivateChannel("channel_for_everyone"),
//            new Channel('channel_for_everyone'),
        ];
    }
}
