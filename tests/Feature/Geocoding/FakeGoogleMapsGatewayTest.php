<?php

use App\Exceptions\GeocodingNoResultsException;
use App\Geocoding\FakeGoogleMapsGateway;
use Illuminate\Support\Facades\Http;

it('can retrieve longitude and latitude from Google Maps API', function () {
    Http::fake([
        'https://maps.googleapis.com/maps/api/geocode/json?address=1+Blue+Jays+Way%2C+Toronto%2C+ON%2C+M5V+1J1&key=fake-api-key' => Http::response(
            [
                'results' => [
                    [
                        'address_components' => [
                            [
                                'long_name' => '1',
                                'short_name' => '1',
                                'types' => [
                                    'street_number',
                                ],
                            ],
                            [
                                'long_name' => 'Blue Jays Way',
                                'short_name' => 'Blue Jays Way',
                                'types' => [
                                    'route',
                                ],
                            ],
                            [
                                'long_name' => 'Old Toronto',
                                'short_name' => 'Old Toronto',
                                'types' => [
                                    'political',
                                    'sublocality',
                                    'sublocality_level_1',
                                ],
                            ],
                            [
                                'long_name' => 'Toronto',
                                'short_name' => 'Toronto',
                                'types' => [
                                    'locality',
                                    'political',
                                ],
                            ],
                            [
                                'long_name' => 'Toronto',
                                'short_name' => 'Toronto',
                                'types' => [
                                    'administrative_area_level_2',
                                    'political',
                                ],
                            ],
                            [
                                'long_name' => 'Ontario',
                                'short_name' => 'ON',
                                'types' => [
                                    'administrative_area_level_1',
                                    'political',
                                ],
                            ],
                            [
                                'long_name' => 'Canada',
                                'short_name' => 'CA',
                                'types' => [
                                    'country',
                                    'political',
                                ],
                            ],
                            [
                                'long_name' => 'M5V 1J1',
                                'short_name' => 'M5V 1J1',
                                'types' => [
                                    'postal_code',
                                ],
                            ],
                        ],
                        'formatted_address' => '1 Blue Jays Way, Toronto, ON M5V 1J1, Canada',
                        'geometry' => [
                            'bounds' => [
                                'northeast' => [
                                    'lat' => 43.6427346,
                                    'lng' => -79.3876486,
                                ],
                                'southwest' => [
                                    'lat' => 43.6405272,
                                    'lng' => -79.390885,
                                ],
                            ],
                            'location' => [
                                'lat' => 43.641804,
                                'lng' => -79.3891419,
                            ],
                            'location_type' => 'ROOFTOP',
                            'viewport' => [
                                'northeast' => [
                                    'lat' => 43.642945430291,
                                    'lng' => -79.3874151,
                                ],
                                'southwest' => [
                                    'lat' => 43.640247469708,
                                    'lng' => -79.3909404,
                                ],
                            ],
                        ],
                        'place_id' => 'ChIJo3X0ydc0K4gRunfoB_z2558',
                        'types' => [
                            'premise',
                        ],
                    ],
                ],
                'status' => 'OK',
            ],
            200
        ),
        'https://maps.googleapis.com/maps/api/geocode/json?address=3085+HIGHWAY+%237%2C+Markham%2C+ON%2C+L3R+1Y3&key=fake-api-key' => Http::response(
            [
                'results' => [
                    [
                        'address_components' => [
                            [
                                'long_name' => '3085',
                                'short_name' => '3085',
                                'types' => [
                                    'street_number',
                                ],
                            ],
                            [
                                'long_name' => 'Highway 7',
                                'short_name' => 'Hwy 7',
                                'types' => [
                                    'route',
                                ],
                            ],
                            [
                                'long_name' => 'Markham',
                                'short_name' => 'Markham',
                                'types' => [
                                    'locality',
                                    'political',
                                ],
                            ],
                            [
                                'long_name' => 'Regional Municipality of York',
                                'short_name' => 'Regional Municipality of York',
                                'types' => [
                                    'administrative_area_level_2',
                                    'political',
                                ],
                            ],
                            [
                                'long_name' => 'Ontario',
                                'short_name' => 'ON',
                                'types' => [
                                    'administrative_area_level_1',
                                    'political',
                                ],
                            ],
                            [
                                'long_name' => 'Canada',
                                'short_name' => 'CA',
                                'types' => [
                                    'country',
                                    'political',
                                ],
                            ],
                            [
                                'long_name' => 'L3R 0J5',
                                'short_name' => 'L3R 0J5',
                                'types' => [
                                    'postal_code',
                                ],
                            ],
                        ],
                        'formatted_address' => '3085 Hwy 7, Markham, ON L3R 0J5, Canada',
                        'geometry' => [
                            'bounds' => [
                                'northeast' => [
                                    'lat' => 43.8498688,
                                    'lng' => -79.3545338,
                                ],
                                'southwest' => [
                                    'lat' => 43.8487076,
                                    'lng' => -79.35545739999999,
                                ],
                            ],
                            'location' => [
                                'lat' => 43.8492997,
                                'lng' => -79.35509209999999,
                            ],
                            'location_type' => 'ROOFTOP',
                            'viewport' => [
                                'northeast' => [
                                    'lat' => 43.8506371802915,
                                    'lng' => -79.35364661970848,
                                ],
                                'southwest' => [
                                    'lat' => 43.8479392197085,
                                    'lng' => -79.35634458029149,
                                ],
                            ],
                        ],
                        'place_id' => 'ChIJ2fk0y-vU1IkRz3g6KPV2P2E',
                        'types' => [
                            'premise',
                        ],
                    ],
                ],
                'status' => 'OK',
            ],
            200
        ),
    ]);

    $gateway = new FakeGoogleMapsGateway;

    $coordinates = $gateway->getLatitudeAndLongitude('1 Blue Jays Way, Toronto, ON, M5V 1J1');

    expect($coordinates->latitude)->toBe('43.641804');
    expect($coordinates->longitude)->toBe('-79.3891419');
});

it('can retrieve longitude and latitude when address contains special characters', function () {
    Http::fake([
        'https://maps.googleapis.com/maps/api/geocode/json?address=1+Blue+Jays+Way%2C+Toronto%2C+ON%2C+M5V+1J1&key=fake-api-key' => Http::response(
            [
                'results' => [
                    [
                        'address_components' => [
                            [
                                'long_name' => '1',
                                'short_name' => '1',
                                'types' => [
                                    'street_number',
                                ],
                            ],
                            [
                                'long_name' => 'Blue Jays Way',
                                'short_name' => 'Blue Jays Way',
                                'types' => [
                                    'route',
                                ],
                            ],
                            [
                                'long_name' => 'Old Toronto',
                                'short_name' => 'Old Toronto',
                                'types' => [
                                    'political',
                                    'sublocality',
                                    'sublocality_level_1',
                                ],
                            ],
                            [
                                'long_name' => 'Toronto',
                                'short_name' => 'Toronto',
                                'types' => [
                                    'locality',
                                    'political',
                                ],
                            ],
                            [
                                'long_name' => 'Toronto',
                                'short_name' => 'Toronto',
                                'types' => [
                                    'administrative_area_level_2',
                                    'political',
                                ],
                            ],
                            [
                                'long_name' => 'Ontario',
                                'short_name' => 'ON',
                                'types' => [
                                    'administrative_area_level_1',
                                    'political',
                                ],
                            ],
                            [
                                'long_name' => 'Canada',
                                'short_name' => 'CA',
                                'types' => [
                                    'country',
                                    'political',
                                ],
                            ],
                            [
                                'long_name' => 'M5V 1J1',
                                'short_name' => 'M5V 1J1',
                                'types' => [
                                    'postal_code',
                                ],
                            ],
                        ],
                        'formatted_address' => '1 Blue Jays Way, Toronto, ON M5V 1J1, Canada',
                        'geometry' => [
                            'bounds' => [
                                'northeast' => [
                                    'lat' => 43.6427346,
                                    'lng' => -79.3876486,
                                ],
                                'southwest' => [
                                    'lat' => 43.6405272,
                                    'lng' => -79.390885,
                                ],
                            ],
                            'location' => [
                                'lat' => 43.641804,
                                'lng' => -79.3891419,
                            ],
                            'location_type' => 'ROOFTOP',
                            'viewport' => [
                                'northeast' => [
                                    'lat' => 43.642945430291,
                                    'lng' => -79.3874151,
                                ],
                                'southwest' => [
                                    'lat' => 43.640247469708,
                                    'lng' => -79.3909404,
                                ],
                            ],
                        ],
                        'place_id' => 'ChIJo3X0ydc0K4gRunfoB_z2558',
                        'types' => [
                            'premise',
                        ],
                    ],
                ],
                'status' => 'OK',
            ],
            200
        ),
        'https://maps.googleapis.com/maps/api/geocode/json?address=3085+HIGHWAY+%237%2C+Markham%2C+ON%2C+L3R+1Y3&key=fake-api-key' => Http::response(
            [
                'results' => [
                    [
                        'address_components' => [
                            [
                                'long_name' => '3085',
                                'short_name' => '3085',
                                'types' => [
                                    'street_number',
                                ],
                            ],
                            [
                                'long_name' => 'Highway 7',
                                'short_name' => 'Hwy 7',
                                'types' => [
                                    'route',
                                ],
                            ],
                            [
                                'long_name' => 'Markham',
                                'short_name' => 'Markham',
                                'types' => [
                                    'locality',
                                    'political',
                                ],
                            ],
                            [
                                'long_name' => 'Regional Municipality of York',
                                'short_name' => 'Regional Municipality of York',
                                'types' => [
                                    'administrative_area_level_2',
                                    'political',
                                ],
                            ],
                            [
                                'long_name' => 'Ontario',
                                'short_name' => 'ON',
                                'types' => [
                                    'administrative_area_level_1',
                                    'political',
                                ],
                            ],
                            [
                                'long_name' => 'Canada',
                                'short_name' => 'CA',
                                'types' => [
                                    'country',
                                    'political',
                                ],
                            ],
                            [
                                'long_name' => 'L3R 0J5',
                                'short_name' => 'L3R 0J5',
                                'types' => [
                                    'postal_code',
                                ],
                            ],
                        ],
                        'formatted_address' => '3085 Hwy 7, Markham, ON L3R 0J5, Canada',
                        'geometry' => [
                            'bounds' => [
                                'northeast' => [
                                    'lat' => 43.8498688,
                                    'lng' => -79.3545338,
                                ],
                                'southwest' => [
                                    'lat' => 43.8487076,
                                    'lng' => -79.35545739999999,
                                ],
                            ],
                            'location' => [
                                'lat' => 43.8492997,
                                'lng' => -79.35509209999999,
                            ],
                            'location_type' => 'ROOFTOP',
                            'viewport' => [
                                'northeast' => [
                                    'lat' => 43.8506371802915,
                                    'lng' => -79.35364661970848,
                                ],
                                'southwest' => [
                                    'lat' => 43.8479392197085,
                                    'lng' => -79.35634458029149,
                                ],
                            ],
                        ],
                        'place_id' => 'ChIJ2fk0y-vU1IkRz3g6KPV2P2E',
                        'types' => [
                            'premise',
                        ],
                    ],
                ],
                'status' => 'OK',
            ],
            200
        ),
    ]);

    $gateway = new FakeGoogleMapsGateway;

    $coordinates = $gateway->getLatitudeAndLongitude('3085 HIGHWAY #7, Markham, ON, L3R 1Y3');

    expect($coordinates->latitude)->toBe('43.8492997');
    expect($coordinates->longitude)->toBe('-79.3550921');
});

it('throws an exception if there are no results', function () {
    Http::fake([
        'https:/maps.googleapis.com/maps/api/geocode/json?address=blah+blah+blah&key=fake-api-key' => Http::response([
            'results' => [],
            'status' => 'ZERO_RESULTS',
        ], 200),
    ]);

    $gateway = new FakeGoogleMapsGateway;

    $gateway->getLatitudeAndLongitude('blah blah blah');
})->throws(GeocodingNoResultsException::class);
