<?php

declare(strict_types=1);

namespace App\Repository;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Redis;

class CourierLocationRedisRepository
{
    private const TTL_SECONDS = 600; // 10 minutes
    private const MAX_POINTS = 100; // Maximum number of location points to keep per courier in Redis
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

        Redis::expire($key, self::TTL_SECONDS);
        Redis::ltrim($key, -self::MAX_POINTS, -1);
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
     * Get all points for specific courier
     * Returns an array of CourierLocation[]
     */
    public function getAll(int $courierId): Collection
    {
        $points = collect(Redis::lrange(self::KEY_PREFIX . $courierId, 0, -1));

        return $points->map(fn($point) => json_decode($point, true));
    }
}
