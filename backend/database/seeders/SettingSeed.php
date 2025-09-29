<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\SettingEnum;
use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeed extends Seeder
{
    public function run(): void
    {
        Setting::insert([
            [
                'key' => SettingEnum::EMULATOR_ENABLE->value,
                'value' => 0
            ],
        ]);
    }
}
