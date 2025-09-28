<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\Courier;
use App\Repository\CourierLocationRedisRepository;
use Illuminate\Console\Command;
use Illuminate\Http\Client\Pool;
use Illuminate\Support\Facades\Http;
use Throwable;

class EmulateCourierPush extends Command
{
    private const CENTER_COORDINATES = ['lat' => 45.5017, 'lng' => -73.5673]; // Center of Montreal
    private const RADIUS_DEG = 0.05; // ~5km - Radius around the center that serves as the boundary for movement points

    /**
     * The name and signature of the console command.
     */
    protected $signature = 'courier:emulate-push {--batch=50} {--max-delta=0.0008} {--timeout=10}';

    /**
     * The console command description.
     */
    protected $description = 'Emulate courier devices: generate small coordinate changes and POST them to the public push endpoint';

    private int $errors = 0;
    private int $sent = 0;

    private float $maxDelta;
    private int $batchSize;
    private int $timeout;
    private string $endpoint;

    public function __construct(
        private readonly CourierLocationRedisRepository $CourierLocationRedisRepository,
    ) {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->batchSize = (int) $this->option('batch');
        $this->maxDelta = (float) $this->option('max-delta'); // degrees ~111km per deg lat, small value. 0.0008 = ~90m
        $this->timeout = (int) $this->option('timeout');
        $this->endpoint = 'http://courier_nginx' . route('courier-locations.store', false, false);
        dump($this->endpoint);

        $this->info("Emulator starting. Batch size: {$this->batchSize}. Max delta: {$this->maxDelta}");

        $batch = [];

        // Stream couriers to avoid memory spikes
        foreach (Courier::cursor() as $courier) {
            // Get coordinates from cache
            $courierLocation = $this->CourierLocationRedisRepository->getLast($courier->id);

            if (! $courierLocation) {
                // For new couriers
                $courierLocation = self::CENTER_COORDINATES;
            }

            $newCoordinates = $this->calculateNewCoordinates($courierLocation); // ['lat' => int, 'lng' => int]

            $payload = [
                'courier_id' => $courier->id,
                ...$newCoordinates,
            ];

            $batch[] = $payload;

            // When batch is full, flush it (send concurrent requests)
            if (count($batch) >= $this->batchSize) {
                $this->flushBatch($batch);
                $batch = [];
            }
        }

        // Flush remaining
        if (count($batch) > 0) {
            $this->flushBatch($batch);
        }

        $this->info("Emulation completed. Sent: {$this->sent}. Errors: {$this->errors}.");

        return $this->errors > 0 ? self::FAILURE : self::SUCCESS;
    }

    /**
     * Send a batch of payloads concurrently using Http::pool().
     * Returns [successCount, errorCount].
     */
    protected function flushBatch(array $batch): void
    {
        try {
            // Use the pool to send POST for each payload
            $responses = Http::withOptions(['timeout' => $this->timeout])
                ->pool(function (Pool $pool) use ($batch) {
                    $requests = [];
                    foreach ($batch as $payload) {
                        $requests[] = $pool
                            ->withHeaders(['Accept' => 'application/json'])
                            ->post($this->endpoint, $payload);
                    }

                    return $requests;
                });

            foreach ($responses as $response) {
                if ($response->successful()) {
                    $this->sent++;
                } else {
                    $this->errors++;
                    $this->error("Request failed: HTTP {$response->status()} - {$response->body()}");
                }
            }
        } catch (Throwable $e) {
            // Network/other error for the whole pool
            $this->errors += count($batch);
            $this->error('Batch HTTP error: ' . $e->getMessage());
        }
    }

    private function calculateNewCoordinates(array $coordinates): array
    {
        // Small random delta in range [-maxDelta, +maxDelta]
        $newLat = $coordinates['lat'] + $this->randomDelta();
        $newLng = $coordinates['lng'] + $this->randomDelta();

        // Ensure that the new coordinates ($newLat, $newLng) stay within a defined square boundary
        // around the center point (CENTER_COORDINATES). This prevents the courier from "moving"
        // outside the allowed area by clamping the latitude and longitude between calculated
        // lower and upper bounds. Latitude and longitude are adjusted independently.
        $lowerBoundLat = self::CENTER_COORDINATES['lat'] - self::RADIUS_DEG; // lower bound: minimum allowed latitude
        $upperBoundLat = self::CENTER_COORDINATES['lat'] + self::RADIUS_DEG; // upper bound: maximum allowed latitude
        $lowerBoundLng = self::CENTER_COORDINATES['lng'] - self::RADIUS_DEG; // lower bound: minimum allowed longitude
        $upperBoundLng = self::CENTER_COORDINATES['lng'] + self::RADIUS_DEG; // upper bound: maximum allowed longitude

        return [
            'lat' => max($lowerBoundLat, min($upperBoundLat, $newLat)),
            'lng' => max($lowerBoundLng, min($upperBoundLng, $newLng)),
        ];
    }

    /**
     * Random float between -maxDelta and +maxDelta
     */
    private function randomDelta(): float
    {
        return (rand(-1000, 1000) / 1000) * $this->maxDelta;
    }
}
