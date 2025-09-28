<?php

declare(strict_types=1);

use App\Console\Commands\EmulateCourierPush;
use Illuminate\Support\Facades\Schedule;

Schedule::command(EmulateCourierPush::class)->everyTenSeconds()->environments(['local', 'development']);
