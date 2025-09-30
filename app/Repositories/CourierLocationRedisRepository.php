<?php

declare(strict_types=1);

namespace App\Repositories;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Redis;

class CourierLocationRedisRepository
{
    private const KEY_PREFIX = 'courier:locations:'; // Key prefix for specific courier

    /**
     * Add a new point for a courier
     */
    public function add(int $courierId, float $lat, float $lng): void
    {
        $key = self::KEY_PREFIX . $courierId;

        $point = json_encode([
            'courier_id' => $courierId,
            'lat' => $lat,
            'lng' => $lng,
            'created_at' => now(),
        ]);

        Redis::rpush($key, $point);

        Redis::expire($key, config('courier.cache_ttl_minutes') * 60);
        Redis::ltrim($key, -config('courier.max_cache_points'), -1);
    }

    /**
     * Get the last point of a courier
     */
    public function getLast(int $courierId): ?array
    {
        $last = Redis::lindex(self::KEY_PREFIX . $courierId, -1);

        return $last ? json_decode($last, true) : null;
    }

    /**
     * Pull all cached points for a specific courier except the last one.
     * Returns an array of points and removes them from Redis, keeping only the latest point.
     */
    public function pullAllExceptLast(int $courierId): Collection
    {
        $key = self::KEY_PREFIX . $courierId;

        $points = collect(Redis::transaction(function ($pipe) use ($key) {
            $points =$pipe->lrange($key, 0, -2); // fetch all except the last one
            $pipe->ltrim($key, -1, -1); // keep in cache only the last one

            return $points;
        })[0]);

        return $points->map(fn($point) => json_decode($point, true));
    }
}
