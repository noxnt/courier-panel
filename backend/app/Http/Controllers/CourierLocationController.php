<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Events\CourierMoved;
use App\Http\Requests\CourierLocation\UpdateCourierLocationRequest;
use App\Repository\CourierLocationRedisRepository;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;

// TODO move business logic to Service layer, DB queries to Repository layer (saving locations from cache to DB)

class CourierLocationController extends Controller
{
    public function __construct(
        private readonly CourierLocationRedisRepository $CourierLocationRedisRepository,
    ) {
        //
    }

    public function store(UpdateCourierLocationRequest $request): JsonResponse
    {
        $data = $request->validated();

        $this->CourierLocationRedisRepository->add($data['courier_id'], $data['lat'], $data['lng']);

        event(new CourierMoved($data['courier_id']));

        return ApiResponse::success('Courier location stored successfully.');
    }
}
