<?php

namespace App\Geocoding;

use App\Contracts\Geocoding\GeocodingGateway;
use App\Exceptions\GeocodingNoResultsException;

class FakeGoogleMapsGateway implements GeocodingGateway
{
    private const API_URL = 'https://maps.googleapis.com/maps/api/geocode/json?address={address}&key={apiKey}';

    private $successFixtures = [
        '3+Commerce+Cres%2C+Acton%2C+ON%2C+L7J+2X3' => [
            'lat' => '43.639305',
            'lng' => '-80.046878',
        ],
        '1+Blue+Jays+Way%2C+Toronto%2C+ON%2C+M5V+1J1' => [
            'lat' => '43.641804',
            'lng' => '-79.3891419',
        ],
        '3085+HIGHWAY+%237%2C+Markham%2C+ON%2C+L3R+1Y3' => [
            'lat' => '43.8492997',
            'lng' => '-79.3550921',
        ],
    ];

    private array $failureFixtures = [
        '123 Buzzard Lake Drive, Loonyville, ZZ, H3L L0A' => [
            'lat' => null,
            'lng' => null,
        ],
    ];

    public function getLatitudeAndLongitude(string $address): Coordinates
    {
        $address = urlencode($address);

        if (array_key_exists($address, $this->successFixtures)) {
            return new Coordinates(
                $this->successFixtures[$address]['lat'],
                $this->successFixtures[$address]['lng']
            );
        }

        throw_if(
            ! array_key_exists($address, $this->successFixtures),
            new GeocodingNoResultsException("No results for {$address}")
        );
    }

    public function getApiKey(): string
    {
        return 'fake-api-key';
    }
}
