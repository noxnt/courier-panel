<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Events\CourierMoved;
use App\Events\CourierUpdated;
use App\Repositories\CourierLocationRedisRepository;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Cache;

class BroadcastCourierCoordinates implements ShouldQueue
{
    use Queueable;

    public function handle(CourierMoved $event): void
    {
        $courierId = $event->courierId;

        // Try to acquire a lock for this courier to avoid duplicate jobs
        $lock = Cache::lock("courier:lock:$courierId", 10);

        if ($lock->get()) {
            try {
                // Get the last location from cache
                if ($courierLocation = app(CourierLocationRedisRepository::class)->getLast($courierId)) {
                    // Broadcast updated location
                    broadcast(new CourierUpdated($courierLocation));
                }
            } finally {
                // Release the lock immediately after handling
                $lock->release();
            }
        }
    }
}
