<?php

namespace App\Geocoding;

use App\Contracts\Geocoding\GeocodingGateway;
use App\Exceptions\GeocodingNoResultsException;
use App\Exceptions\GeocodingRequestDeniedException;
use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class GoogleMapsGateway implements GeocodingGateway
{
    private const API_URL = 'https://maps.googleapis.com/maps/api/geocode/json?sensor=false&address={address}&key={apiKey}';

    public function getLatitudeAndLongitude(string $address): Coordinates
    {
        $url = $this->buildUrl($address);
        $response = Http::get($url);

        throw_if(
            $response->failed(),
            new Exception("Retrieval of coordinates for {$address} failed [{$url}]")
        );

        if ($response->successful()) {
            $data = $response->json();

            throw_if(
                $data['status'] == 'REQUEST_DENIED',
                new GeocodingRequestDeniedException("Request denied for {$address} [{$url}]")
            );

            throw_if(
                $data['status'] == 'ZERO_RESULTS',
                new GeocodingNoResultsException("No results for {$address} [{$url}]")
            );

            return new Coordinates(
                $data['results'][0]['geometry']['location']['lat'],
                $data['results'][0]['geometry']['location']['lng']
            );
        }
    }

    public function getApiKey(): string
    {
        return config('geocoding.google_maps_api_key');
    }

    private function buildUrl(string $address): string
    {
        return Str::of(self::API_URL)
            ->replace('{address}', urlencode($address))
            ->replace('{apiKey}', $this->getApiKey())
            ->__toString();
    }
}
