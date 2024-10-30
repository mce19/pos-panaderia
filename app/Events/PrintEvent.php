<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PrintEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $infoResponse;


    public function __construct($infoResponse)
    {
        $this->infoResponse = $infoResponse;
    }


    public function broadcastOn()
    {
        //asi sugiere pusher
        //return ['my-channel'];

        //asi lo crea por defecto laravel
        //return new PrivateChannel('channel-name');


        return new Channel('my-channel');
    }

    public function broadcastAs()
    {
        return 'my-event';
    }
}
