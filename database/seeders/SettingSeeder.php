<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\SettingEnum;
use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        $settings = collect([
            [
                'key' => SettingEnum::EMULATOR_ENABLE->value,
                'value' => 0
            ],
        ]);

        $existingKeys = Setting::all()->pluck('key')->toArray();

        Setting::insert($settings->whereNotIn('key', $existingKeys)->toArray());
    }
}
