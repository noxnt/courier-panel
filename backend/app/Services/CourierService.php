<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Courier;
use App\Repositories\CourierRepository;
use Illuminate\Support\Collection;

readonly class CourierService
{
    public function __construct(
        private CourierRepository $courierRepository,
    ) {
        //
    }

    public function getAll(): Collection
    {
        return $this->courierRepository->getAll();
    }

    public function get(Courier $courier): Courier
    {
        return $courier->load('lastLocation');
    }

    public function create(array $data): Courier
    {
        return $this->courierRepository->create($data);
    }

    public function update(Courier $courier, array $data): Courier
    {
        return $this->courierRepository->update($courier, $data);
    }

    public function delete(Courier $courier): void
    {
        $this->courierRepository->delete($courier);
    }
}
