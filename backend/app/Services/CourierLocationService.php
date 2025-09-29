<?php

declare(strict_types=1);

namespace App\Services;

use App\Events\CourierMoved;
use App\Repositories\CourierLocationRedisRepository;

readonly class CourierLocationService
{
    public function __construct(
        private CourierLocationRedisRepository $courierLocationRedisRepository,
    ) {
        //
    }

    /**
     *  Write-back cache:
     *  1. Save courier location in Redis cache first for fast write.
     *  2. Trigger CourierMoved event to broadcast via sockets.
     *  3. Later, a scheduled command/job will flush cached locations into the database.
     */
    public function create(array $data): void
    {
        $this->courierLocationRedisRepository->add($data['courier_id'], $data['lat'], $data['lng']);

        event(new CourierMoved($data['courier_id']));
    }
}
