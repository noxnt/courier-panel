<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\CourierLocation;
use Clickbar\Magellan\Data\Geometries\Point;
use Illuminate\Support\Collection;

class CourierLocationRepository
{
    public function insert(Collection $data): void
    {
        $data = $data->map(function ($point) {
                return [
                    'courier_id' => $point['courier_id'],
                    'location' => Point::make($point['lat'], $point['lng']),
                    'created_at' => $point['created_at'],
                ];
            })
            ->sortBy('courier_id')
            ->sortBy('created_at') // Sort by newest last (newest point has a higher id)
            ->toArray();

        CourierLocation::insert($data);
    }
}
