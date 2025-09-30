<?php

declare(strict_types=1);

use App\Enums\SettingEnum;
use App\Models\Setting;
use Illuminate\Support\Facades\Schema;

function setting(SettingEnum $setting): mixed
{
    if (! Schema::hasTable('settings')) {
        return null;
    }

    return match($setting) {
        SettingEnum::EMULATOR_ENABLE => Setting::getBool(SettingEnum::EMULATOR_ENABLE->value),
    };
}
