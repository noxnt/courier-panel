<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Setting;
use Illuminate\Support\Collection;

class SettingRepository
{
    public function getAll(): Collection
    {
        return Setting::all();
    }

    public function update(array $settings): void
    {
        foreach ($settings as $setting) {
            Setting::updateOrCreate(
                ['key' => $setting['key']],    // find by
                ['value' => $setting['value']] // update
            );
        }
    }
}
