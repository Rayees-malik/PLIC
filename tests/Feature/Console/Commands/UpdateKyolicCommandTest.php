<?php

use App\Contracts\Geocoding\GeocodingGateway;
use App\Geocoding\FakeGoogleMapsGateway;
use App\Models\AS400\AS400ZeusRetailer;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Schema;

use function Pest\Laravel\artisan;

uses(DatabaseTransactions::class);

beforeEach(function () {
    $geocodingGateway = new FakeGoogleMapsGateway;
    $this->app->instance(GeocodingGateway::class, $geocodingGateway);

    Http::fake([
        'https://maps.googleapis.com/maps/api/geocode/json?address=3 Commerce Cres, Acton, ON, L7J 2X3&key=fake-api-key' => Http::response(
            [
                'results' => [
                    [
                        'address_components' => [
                            [
                                'long_name' => '3',
                                'short_name' => '3',
                                'types' => [
                                    'street_number',
                                ],
                            ],
                            [
                                'long_name' => 'Commerce Crescent',
                                'short_name' => 'Commerce Crescent',
                                'types' => [
                                    'route',
                                ],
                            ],
                            [
                                'long_name' => 'Halton Hills',
                                'short_name' => 'Halton Hills',
                                'types' => [
                                    'locality',
                                    'political',
                                ],
                            ],
                            [
                                'long_name' => 'Regional Municipality of Halton',
                                'short_name' => 'Regional Municipality of Halton',
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
                                'long_name' => 'L0P',
                                'short_name' => 'L0P',
                                'types' => [
                                    'postal_code',
                                    'postal_code_prefix',
                                ],
                            ],
                        ],
                        'formatted_address' => '3 Commerce Crescent, Halton Hills, ON L0P, Canada',
                        'geometry' => [
                            'location' => [
                                'lat' => 43.639305,
                                'lng' => -80.046878,
                            ],
                            'location_type' => 'RANGE_INTERPOLATED',
                            'viewport' => [
                                'northeast' => [
                                    'lat' => 43.640653980292,
                                    'lng' => -80.045529019708,
                                ],
                                'southwest' => [
                                    'lat' => 43.637956019708,
                                    'lng' => -80.048226980292,
                                ],
                            ],
                        ],
                        'place_id' => 'EjEzIENvbW1lcmNlIENyZXNjZW50LCBIYWx0b24gSGlsbHMsIE9OIEwwUCwgQ2FuYWRhIjASLgoUChIJPThD1-B0K4gRyK7GqzyXxPEQAyoUChIJB1nzJOF0K4gRu_ZXlGEPdjM',
                        'types' => [
                            'street_address',
                        ],
                    ],
                ],
                'status' => 'OK',
            ],
            200
        ),
        'https://maps.googleapis.com/maps/api/geocode/json?address=1 Blue Jays Way, Toronto, ON, M5V 1J1&key=fake-api-key' => Http::response(
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
        'https://maps.googleapis.com/maps/api/geocode/json?address=blah blah blah&key=fake-api-key' => Http::response([
            'results' => [],
            'status' => 'ZERO_RESULTS',
        ], 200),
    ]);

    Config::set('database.connections.kyolic', config('database.connections.mysql'));

    Schema::connection('kyolic')->dropIfExists('wp_whwwupoupw_asl_stores');

    Schema::connection('kyolic')
        ->create('wp_whwwupoupw_asl_stores', function (Blueprint $table) {
            $table->string('title');
            $table->string('street');
            $table->string('city');
            $table->string('state');
            $table->string('postal_code');
            $table->unsignedInteger('country');
            $table->string('email');
            $table->string('phone');
            $table->string('lat');
            $table->string('lng');
            $table->unsignedInteger('logo_id');
            $table->datetime('updated_on')->nullable();
        });
});

it('truncates the store locator table', function () {
    DB::connection('kyolic')
        ->table('wp_whwwupoupw_asl_stores')->insert([
            'title' => 'test',
            'street' => 'test',
            'city' => 'test',
            'state' => 'test',
            'postal_code' => 'test',
            'country' => 38,
            'email' => 'test',
            'phone' => 'test',
            'lat' => 'test',
            'lng' => 'test',
            'logo_id' => 2,
            'updated_on' => '2021-01-01',
        ]);

    $retailer = AS400ZeusRetailer::factory()
        ->kyolic()
        ->count(2)
        ->state(new Sequence(
            [
                'name' => 'Purity Life',
                'address' => '3 Commerce Cres',
                'city' => 'Acton',
                'province' => 'ON',
                'postal_code' => 'L7J 2X3',
            ],
            [
                'name' => 'Jim Deli',
                'address' => '1 Blue Jays Way',
                'city' => 'Toronto',
                'province' => 'ON',
                'postal_code' => 'M5V 1J1',
            ],
        ))
        ->create();

    expect(DB::connection('kyolic')->table('wp_whwwupoupw_asl_stores')->count())->toBe(1);
    expect(DB::connection('kyolic')->table('wp_whwwupoupw_asl_stores')->first()->title)->toBe('test');

    artisan('update:kyolic')->assertSuccessful();

    expect(DB::connection('kyolic')->table('wp_whwwupoupw_asl_stores')->count())->toBe(2);
    expect(DB::connection('kyolic')->table('wp_whwwupoupw_asl_stores')->first()->title)->toBe($retailer[0]->name);
    expect(DB::connection('kyolic')->table('wp_whwwupoupw_asl_stores')->skip(1)->first()->title)->toBe($retailer[1]->name);
});

it('ensures address is urlencoded before making API request', function () {
    $retailer = AS400ZeusRetailer::factory()
        ->kyolic()
        ->create([
            'name' => 'ACME Corp.',
            'address' => '3085 HIGHWAY #7',
            'city' => 'Markham',
            'province' => 'ON',
            'postal_code' => 'L3R 1Y3',
            'contact_email' => 'alpha@example.com',
            'contact_phone' => '519-123-4567',
        ]);

    artisan('update:kyolic')->assertSuccessful();

    expect(DB::connection('kyolic')->table('wp_whwwupoupw_asl_stores')->count())->toBe(1);
});

it('updates kyolic website table', function () {
    $retailer = AS400ZeusRetailer::factory()
        ->kyolic()
        ->count(2)
        ->state(new Sequence(
            [
                'name' => 'Purity Life',
                'address' => '3 Commerce Cres',
                'city' => 'Acton',
                'province' => 'ON',
                'postal_code' => 'L7J 2X3',
                'contact_email' => 'alpha@example.com',
                'contact_phone' => '519-123-4567',
            ],
            [
                'name' => 'Jim Deli',
                'address' => '1 Blue Jays Way',
                'city' => 'Toronto',
                'province' => 'ON',
                'postal_code' => 'M5V 1J1',
                'contact_email' => 'beta@example.com',
                'contact_phone' => '519-765-4321',
            ],
        ))
        ->create();

    artisan('update:kyolic')->assertSuccessful();

    expect(DB::connection('kyolic')->table('wp_whwwupoupw_asl_stores')->count())->toBe(2);

    expect(DB::connection('kyolic')->table('wp_whwwupoupw_asl_stores')->first()->title)->toBe($retailer[0]->name);
    expect(DB::connection('kyolic')->table('wp_whwwupoupw_asl_stores')->first()->street)->toBe($retailer[0]->address);
    expect(DB::connection('kyolic')->table('wp_whwwupoupw_asl_stores')->first()->city)->toBe($retailer[0]->city);
    expect(DB::connection('kyolic')->table('wp_whwwupoupw_asl_stores')->first()->state)->toBe($retailer[0]->province);
    expect(DB::connection('kyolic')->table('wp_whwwupoupw_asl_stores')->first()->postal_code)->toBe($retailer[0]->postal_code);
    expect(DB::connection('kyolic')->table('wp_whwwupoupw_asl_stores')->first()->email)->toBe($retailer[0]->contact_email);
    expect(DB::connection('kyolic')->table('wp_whwwupoupw_asl_stores')->first()->phone)->toBe($retailer[0]->contact_phone);
    expect(DB::connection('kyolic')->table('wp_whwwupoupw_asl_stores')->first()->lat)->toBe('43.639305');
    expect(DB::connection('kyolic')->table('wp_whwwupoupw_asl_stores')->first()->lng)->toBe('-80.046878');

    expect(DB::connection('kyolic')->table('wp_whwwupoupw_asl_stores')->skip(1)->first()->title)->toBe($retailer[1]->name);
    expect(DB::connection('kyolic')->table('wp_whwwupoupw_asl_stores')->skip(1)->first()->street)->toBe($retailer[1]->address);
    expect(DB::connection('kyolic')->table('wp_whwwupoupw_asl_stores')->skip(1)->first()->city)->toBe($retailer[1]->city);
    expect(DB::connection('kyolic')->table('wp_whwwupoupw_asl_stores')->skip(1)->first()->state)->toBe($retailer[1]->province);
    expect(DB::connection('kyolic')->table('wp_whwwupoupw_asl_stores')->skip(1)->first()->postal_code)->toBe($retailer[1]->postal_code);
    expect(DB::connection('kyolic')->table('wp_whwwupoupw_asl_stores')->skip(1)->first()->email)->toBe($retailer[1]->contact_email);
    expect(DB::connection('kyolic')->table('wp_whwwupoupw_asl_stores')->skip(1)->first()->phone)->toBe($retailer[1]->contact_phone);
    expect(DB::connection('kyolic')->table('wp_whwwupoupw_asl_stores')->skip(1)->first()->lat)->toBe('43.641804');
    expect(DB::connection('kyolic')->table('wp_whwwupoupw_asl_stores')->skip(1)->first()->lng)->toBe('-79.3891419');
});

it('sets the updated datetime for all the records to the same value', function () {
    $retailer = AS400ZeusRetailer::factory()
        ->kyolic()
        ->count(2)
        ->state(new Sequence(
            [
                'name' => 'Purity Life',
                'address' => '3 Commerce Cres',
                'city' => 'Acton',
                'province' => 'ON',
                'postal_code' => 'L7J 2X3',
            ],
            [
                'name' => 'Jim Deli',
                'address' => '1 Blue Jays Way',
                'city' => 'Toronto',
                'province' => 'ON',
                'postal_code' => 'M5V 1J1',
            ],
        ))
        ->create();

    artisan('update:kyolic')->assertSuccessful();

    expect(
        DB::connection('kyolic')->table('wp_whwwupoupw_asl_stores')->first()->updated_on
    )->toBe(
        DB::connection('kyolic')->table('wp_whwwupoupw_asl_stores')->skip(1)->first()->updated_on
    );
});

it('sets the logo_id to 2', function () {
    $retailer = AS400ZeusRetailer::factory()
        ->kyolic()
        ->state(new Sequence(
            [
                'name' => 'Purity Life',
                'address' => '3 Commerce Cres',
                'city' => 'Acton',
                'province' => 'ON',
                'postal_code' => 'L7J 2X3',
            ],
            [
                'name' => 'Jim Deli',
                'address' => '1 Blue Jays Way',
                'city' => 'Toronto',
                'province' => 'ON',
                'postal_code' => 'M5V 1J1',
            ],
        ))
        ->create();

    artisan('update:kyolic')->assertSuccessful();
    expect(DB::connection('kyolic')->table('wp_whwwupoupw_asl_stores')->value('logo_id'))->toBe(2);
});

it('always uses Canada as the country', function () {
    $retailer = AS400ZeusRetailer::factory()
        ->kyolic()
        ->state(new Sequence(
            [
                'name' => 'Purity Life',
                'address' => '3 Commerce Cres',
                'city' => 'Acton',
                'province' => 'ON',
                'postal_code' => 'L7J 2X3',
            ],
            [
                'name' => 'Jim Deli',
                'address' => '1 Blue Jays Way',
                'city' => 'Toronto',
                'province' => 'ON',
                'postal_code' => 'M5V 1J1',
            ],
        ))
        ->create();

    artisan('update:kyolic')->assertSuccessful();
    expect(DB::connection('kyolic')->table('wp_whwwupoupw_asl_stores')->value('country'))->toBe(38);
});

it('only inserts kyolic retailers', function () {
    AS400ZeusRetailer::factory()
        ->create([
            'category' => 'ANOTHER RETAILER',
            'name' => 'ACME Corp.',
            'address' => '123 Buzzard Lake Drive',
            'city' => 'Loonyville',
            'province' => 'ZZ',
            'postal_code' => 'H3L L0A',
        ]);

    $kyolicRetailer = AS400ZeusRetailer::factory()
        ->kyolic()
        ->create([
            'name' => 'Purity Life',
            'address' => '3 Commerce Cres',
            'city' => 'Acton',
            'province' => 'ON',
            'postal_code' => 'L7J 2X3',
        ]);

    artisan('update:kyolic')->assertSuccessful();

    expect(DB::connection('kyolic')->table('wp_whwwupoupw_asl_stores')->count())->toBe(1);
    expect(DB::connection('kyolic')->table('wp_whwwupoupw_asl_stores')->first()->title)->toBe($kyolicRetailer->name);
});

it('only inserts distinct retailers', function () {
    $kyolicRetailers = AS400ZeusRetailer::factory()
        ->kyolic()
        ->count(2)
        ->state(new Sequence(
            ['invoice_date' => '2022-04-01'],
            ['invoice_date' => '2022-04-05'],
        ))
        ->create([
            'name' => 'Purity Life',
            'address' => '3 Commerce Cres',
            'city' => 'Acton',
            'province' => 'ON',
            'postal_code' => 'L7J 2X3',
            'contact_email' => 'sales@exmaple.com',
            'contact_phone' => '416-555-1234',
        ]);

    artisan('update:kyolic')->assertSuccessful();

    expect(DB::connection('kyolic')->table('wp_whwwupoupw_asl_stores')->count())->toBe(1);
    expect(DB::connection('kyolic')->table('wp_whwwupoupw_asl_stores')->first()->title)->toBe($kyolicRetailers->first()->name);
});
