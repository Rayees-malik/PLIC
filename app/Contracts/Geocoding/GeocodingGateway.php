<?php

namespace App\Contracts\Geocoding;

use App\Geocoding\Coordinates;

interface GeocodingGateway
{
    public function getLatitudeAndLongitude(string $address): Coordinates;

    public function getApiKey(): string;
}
