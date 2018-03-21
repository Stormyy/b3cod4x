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
use Stormyy\B3\Models\Penalty;
use Stormyy\B3\Models\Screenshot;

class PlayerHasBeenBanned implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $penalty;

    /**
     * Create a new event instance.
     *
     * @param Screenshot $screenshot
     */
    public function __construct(Penalty $penalty)
    {
        $this->penalty = $penalty;
    }
}
