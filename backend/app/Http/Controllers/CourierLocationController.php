<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\CourierLocation\UpdateCourierLocationRequest;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;

class CourierLocationController extends Controller
{
    public function store(UpdateCourierLocationRequest $request): JsonResponse
    {
        $data = $request->validated();

        //TODO Save new location row to cache (Redis)
        //TODO Dispatch CourierMoved event, broadcast it to socket

        return ApiResponse::success('Courier location stored successfully.');
    }
}
