<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Courier;
use Illuminate\Support\Collection;

class CourierRepository
{
    public function getAll(): Collection
    {
        return Courier::with('lastLocation')->get();
    }

    public function create(array $data): Courier
    {
        return Courier::create($data);
    }

    public function update(Courier $courier, array $data): Courier
    {
        $courier->update($data);

        return $courier;
    }

    public function delete(Courier $courier): void
    {
        $courier->delete();
    }
}
