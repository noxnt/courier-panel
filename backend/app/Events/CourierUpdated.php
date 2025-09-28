<?php

declare(strict_types=1);

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

/**
 *  Event triggered after the backend has updated the courier's coordinates.
 *
 *  Purpose:
 *  - Broadcast the updated coordinates to the frontend via WebSocket.
 *  - Frontend listens to this event to update the courier's position on the map in real time.
 * /
 */
class CourierUpdated implements ShouldBroadcast
{
    public function __construct(
        public readonly array $courierLocation,
    ) {
        //
    }

    public function broadcastOn(): Channel|array
    {
        return new PrivateChannel('couriers');
    }
}
