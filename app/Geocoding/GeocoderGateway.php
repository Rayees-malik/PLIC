<?php

namespace App\Geocoding;

use App\Contracts\Geocoding\GeocodingGateway;
use App\Exceptions\GeocodingNoResultsException;
use Exception;
use Illuminate\Support\Facades\Http;

class GeocoderGateway implements GeocodingGateway
{
    private const API_URL = 'https://geocoder.ca/?geoit=xml&json=1&locate=';

    public function getLatitudeAndLongitude(string $address): Coordinates
    {
        $response = Http::get(self::API_URL . $address);

        throw_if(
            $response->failed(),
            new Exception("Retrieval of coordinates for {$address} failed")
        );

        if ($response->successful()) {
            $geocoderResponse = $response->json();

            if (array_key_exists('error', $geocoderResponse)) {
                throw new GeocodingNoResultsException("No results for {$address}");
            }

            return new Coordinates(
                $geocoderResponse['latt'],
                $geocoderResponse['longt']
            );
        }
    }

    public function getApiKey(): string
    {
        return config('GEOCODER_CA_API_KEY');
    }
}
