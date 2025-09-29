<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Courier cache and throttling configuration
    |--------------------------------------------------------------------------
    |
    | These values control how courier locations are cached and how frequently
    | they can be updated.
    |
    | - cache_ttl_minutes: how long location points are kept in Redis (minutes)
    | - throttle_limit: maximum allowed location updates per courier per minute
    | - max_cache_points: derived maximum number of cached points per courier
    |
    */

    'cache_ttl_minutes' => $cacheTtl = env('COURIER_CACHE_TTL', 10),
    'throttle_limit' => $throttleLimit = env('COURIER_THROTTLE_LIMIT', 30),
    'max_cache_points' => $cacheTtl * $throttleLimit,

];
