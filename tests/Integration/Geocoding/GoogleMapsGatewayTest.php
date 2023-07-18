<?php

use App\Exceptions\GeocodingNoResultsException;
use App\Exceptions\GeocodingRequestDeniedException;
use App\Geocoding\GoogleMapsGateway;
use Illuminate\Support\Facades\Config;

uses()->group('integration');

it('can retrieve longitude and latitude', function () {
    $gateway = new GoogleMapsGateway;

    $coordinates = $gateway->getLatitudeAndLongitude('1 Blue Jays Way, Toronto, ON, M5V 1J1');

    expect($coordinates->latitude)->toBe('43.641804');
    expect($coordinates->longitude)->toBe('-79.3891419');
});

it('can retrieve longitude and latitude when address contains special characters', function () {
    $gateway = new GoogleMapsGateway;

    $coordinates = $gateway->getLatitudeAndLongitude('3085 HIGHWAY #7, Markham, ON, L3R 1Y3');

    expect($coordinates->latitude)->toBe('43.8492997');
    expect($coordinates->longitude)->toBe('-79.3550921');
});

it('throws an exception if there are no results', function () {
    $gateway = new GoogleMapsGateway;

    $gateway->getLatitudeAndLongitude('blah blah blah');
})->throws(GeocodingNoResultsException::class);

it('throws an exception if the request was denied', function () {
    Config::set('geocoding.google_maps_api_key', 'fake-api-key');

    $gateway = new GoogleMapsGateway;

    $gateway->getLatitudeAndLongitude('blah blah blah');
})->throws(GeocodingRequestDeniedException::class);
