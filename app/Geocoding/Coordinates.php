<?php

namespace App\Geocoding;

class Coordinates
{
    public function __construct(public string $latitude, public string $longitude)
    {
    }
}
