<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\CourierLocation\StoreCourierLocationRequest;
use App\Services\CourierLocationService;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;

class CourierLocationController extends Controller
{
    public function __construct(
        private readonly CourierLocationService $courierLocationService,
    ) {
        //
    }

    public function store(StoreCourierLocationRequest $request): JsonResponse
    {
        $this->courierLocationService->create($request->validated());

        return ApiResponse::success('Courier location stored successfully.');
    }
}
