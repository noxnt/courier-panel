<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\Courier;
use App\Repositories\CourierLocationRedisRepository;
use App\Repositories\CourierLocationRepository;
use Illuminate\Console\Command;

class CourierLocationSync extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'courier:location-sync {--bulk-limit=5000}';

    /**
     * The console command description.
     */
    protected $description = 'Sync courier locations from Redis cache to database';

    public function __construct(
        private readonly CourierLocationRedisRepository $courierLocationRedisRepository,
        private readonly CourierLocationRepository $courierLocationRepository,
    ) {
        parent::__construct();
    }

    /**
     * Pull points from Redis (except last), merge into batch, insert if bulk limit reached
     */
    public function handle(): int
    {
        $bulkLimit = (int) $this->option('bulk-limit');

        $courierIds = Courier::select('id')->get()->pluck('id');

        $data = collect();
        foreach ($courierIds as $courierId) {
            $data->push(...$this->courierLocationRedisRepository->pullAllExceptLast($courierId));

            if (count($data) >= $bulkLimit) {
                $this->courierLocationRepository->insert($data);
                $data = [];
            }
        }

        // Insert remaining points
        if (!empty($data)) {
            $this->courierLocationRepository->insert($data);
        }

        return self::SUCCESS;
    }
}
