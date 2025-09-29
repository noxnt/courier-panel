<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\Setting\UpdateSettingsRequest;
use App\Services\SettingService;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;

class SettingController extends Controller
{
    public function __construct(
        private SettingService $settingService,
    ) {
        //
    }

    public function index(): JsonResponse
    {
        return ApiResponse::success(
            'Settings list retrieved successfully.',
            $this->settingService->getAll()
        );
    }

    public function update(UpdateSettingsRequest $request): JsonResponse
    {
        $this->settingService->update($request->validated());

        return ApiResponse::success('Settings updated successfully.');
    }
}
