<?php

declare(strict_types=1);

use App\Console\Commands\CourierLocationSync;
use App\Console\Commands\EmulateCourierPush;
use App\Enums\SettingEnum;
use Illuminate\Support\Facades\Schedule;

// General
/**
 * Run sync 1 minute more frequently than cache TTL to prevent stale data (minimum 1 minute)
 */
$minutes = max(1, config('courier.cache_ttl_minutes') - 1);
Schedule::command(CourierLocationSync::class)->cron("*/$minutes * * * *");

// Dev
Schedule::command(EmulateCourierPush::class)
    ->everyFiveSeconds()
    ->environments(['local', 'development'])
    ->when(setting(SettingEnum::EMULATOR_ENABLE));
