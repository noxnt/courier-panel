<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Courier;
use Illuminate\Database\Seeder;

class CourierSeeder extends Seeder
{
    public function run(): void
    {
        if (! in_array(app()->environment(), ['local', 'development'])) {
            return;
        }

        $timestamp = now();
        $couriers = collect([
            [
                'name' => 'James',
                'phone' => '+12025550123',
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ],
            [
                'name' => 'Bob',
                'phone' => '+14165550123',
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ],
            [
                'name' => 'Anna',
                'phone' => '+819012345678',
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ],
            [
                'name' => 'Ivan',
                'phone' => '+380501234567',
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ],
            [
                'name' => 'Sandra',
                'phone' => '+4915123456789',
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ]
        ]);

        $existingPhones = Courier::all()->pluck('phone')->toArray();

        Courier::insert($couriers->whereNotIn('phone', $existingPhones)->toArray());
    }
}
