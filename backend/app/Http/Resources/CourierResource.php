<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CourierResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'phone' => $this->phone,
            'location' => $this->whenLoaded('lastLocation', function () {
                // Fetch DB-stored coordinates for initial map rendering
                return [
                    'lng' => $this->lastLocation->location->getX(), // longitude ↔️
                    'lat' => $this->lastLocation->location->getY(), // latitude ↕️
                ];
            }),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
