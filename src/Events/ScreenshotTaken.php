<?php
/**
 * Created by PhpStorm.
 * User: Bram
 * Date: 30-4-2017
 * Time: 20:20
 */

namespace Stormyy\B3\Events;


use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Stormyy\B3\Models\Screenshot;

class ScreenshotTaken implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $screenshot;

    /**
     * Create a new event instance.
     *
     * @param Screenshot $screenshot
     */
    public function __construct(Screenshot $screenshot)
    {
        $this->screenshot = $screenshot;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|array
     */
    public function broadcastOn()
    {
        return new Channel('screenshots.'.$this->screenshot->server->id);
    }
}
