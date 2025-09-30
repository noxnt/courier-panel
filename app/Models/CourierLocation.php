<?php

declare(strict_types=1);

namespace App\Models;

use Clickbar\Magellan\Data\Geometries\Point;
use Illuminate\Database\Eloquent\Model;

class CourierLocation extends Model
{
    protected $table = 'courier_locations';

    public $timestamps = false;

    protected $fillable = [
        'courier_id',
        'location',
        'created_at',
    ];

    protected $casts = [
        'location' => Point::class
    ];
}
