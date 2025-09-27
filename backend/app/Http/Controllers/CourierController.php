<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\Courier\StoreCourierRequest;
use App\Http\Requests\Courier\UpdateCourierRequest;
use App\Http\Resources\CourierResource;
use App\Models\Courier;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

// TODO move business logic to Service layer, DB queries to Repository layer
class CourierController extends Controller
{
    public function index(): JsonResponse
    {
        return ApiResponse::success(
            'Couriers list retrieved successfully.',
            CourierResource::collection(Courier::all())
        );
    }

    public function show(Courier $courier): JsonResponse
    {
        return ApiResponse::success(
            'Courier retrieved successfully.',
            CourierResource::make($courier),
        );
    }

    public function store(StoreCourierRequest $request): JsonResponse
    {
        $courier = Courier::create($request->validated());

        return ApiResponse::success(
            'Courier created successfully.',
            CourierResource::make($courier),
            Response::HTTP_CREATED,
        );
    }

    public function update(Courier $courier, UpdateCourierRequest $request): JsonResponse
    {
        $courier->update($request->validated());

        return ApiResponse::success(
            'Courier updated successfully.',
            CourierResource::make($courier)
        );
    }

    public function destroy(Courier $courier): JsonResponse
    {
        $courier->delete();

        return ApiResponse::success(
            'Courier deleted successfully.',
        );
    }
}
