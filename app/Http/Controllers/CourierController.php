<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\Courier\StoreCourierRequest;
use App\Http\Requests\Courier\UpdateCourierRequest;
use App\Http\Resources\CourierResource;
use App\Models\Courier;
use App\Services\CourierService;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class CourierController extends Controller
{
    public function __construct(
        private readonly CourierService $courierService,
    ) {
        //
    }

    public function index(): JsonResponse
    {
        return ApiResponse::success(
            'Couriers list retrieved successfully.',
            CourierResource::collection($this->courierService->getAll())
        );
    }

    public function show(Courier $courier): JsonResponse
    {
        return ApiResponse::success(
            'Courier retrieved successfully.',
            CourierResource::make($this->courierService->get($courier)),
        );
    }

    public function store(StoreCourierRequest $request): JsonResponse
    {
        $courier = $this->courierService->create($request->validated());

        return ApiResponse::success(
            'Courier created successfully.',
            CourierResource::make($courier),
            Response::HTTP_CREATED,
        );
    }

    public function update(Courier $courier, UpdateCourierRequest $request): JsonResponse
    {
        $courier = $this->courierService->update($courier, $request->validated());

        return ApiResponse::success(
            'Courier updated successfully.',
            CourierResource::make($courier)
        );
    }

    public function destroy(Courier $courier): JsonResponse
    {
        $this->courierService->delete($courier);

        return ApiResponse::success(
            'Courier deleted successfully.',
        );
    }
}
