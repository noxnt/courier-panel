<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\SettingRepository;
use Illuminate\Support\Collection;

readonly class SettingService
{
    public function __construct(
        private SettingRepository $settingRepository,
    ) {
        //
    }

    public function getAll(): Collection
    {
        return $this->settingRepository->getAll();
    }

    public function update(array $data): void
    {
        $this->settingRepository->update($data);
    }
}
