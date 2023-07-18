<?php

namespace Database\Seeders\Development\iseed;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AddressesTableSeeder extends Seeder
{
    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        DB::table('addresses')->delete();

        DB::table('addresses')->insert([

            [
                'address' => '320 Don Hillock Drive',
                'address2' => '',
                'addressable_id' => 1,
                'addressable_type' => 'App\\Models\\Retailer',
                'city' => 'Aurora',
                'cloned_from_id' => null,
                'country_id' => 40,
                'created_at' => '2020-08-19 11:30:38',
                'deleted_at' => null,
                'postal_code' => 'L4G 0G9',
                'province' => 'Ontario',
                'updated_at' => '2020-08-19 11:30:38',
            ],

            [
                'address' => '6120 - 1A Street S.W',
                'address2' => '',
                'addressable_id' => 2,
                'addressable_type' => 'App\\Models\\Retailer',
                'city' => 'Calgary',
                'cloned_from_id' => null,
                'country_id' => 40,
                'created_at' => '2020-08-19 11:30:38',
                'deleted_at' => null,
                'postal_code' => 'T2H 0G3',
                'province' => 'AB',
                'updated_at' => '2020-08-19 11:30:38',
            ],

            [
                'address' => '85 Ellesmere Road',
                'address2' => '',
                'addressable_id' => 3,
                'addressable_type' => 'App\\Models\\Retailer',
                'city' => 'Scarborough',
                'cloned_from_id' => null,
                'country_id' => 40,
                'created_at' => '2020-08-19 11:30:38',
                'deleted_at' => null,
                'postal_code' => 'M1R 4B7',
                'province' => 'ON',
                'updated_at' => '2020-08-19 11:30:38',
            ],

            [
                'address' => '58 Antares Drive',
                'address2' => 'Unit #1',
                'addressable_id' => 4,
                'addressable_type' => 'App\\Models\\Retailer',
                'city' => 'Nepean',
                'cloned_from_id' => null,
                'country_id' => 40,
                'created_at' => '2020-08-19 11:30:38',
                'deleted_at' => null,
                'postal_code' => 'K2E 7W6',
                'province' => 'ON',
                'updated_at' => '2020-08-19 11:30:38',
            ],

            [
                'address' => '1 President',
                'address2' => '',
                'addressable_id' => 6,
                'addressable_type' => 'App\\Models\\Retailer',
                'city' => 'Brampton',
                'cloned_from_id' => null,
                'country_id' => 40,
                'created_at' => '2020-08-19 11:30:38',
                'deleted_at' => null,
                'postal_code' => 'L6Y 5S5',
                'province' => 'Ontario',
                'updated_at' => '2020-08-19 11:30:38',
            ],

            [
                'address' => '1 President',
                'address2' => '',
                'addressable_id' => 7,
                'addressable_type' => 'App\\Models\\Retailer',
                'city' => 'Brampton',
                'cloned_from_id' => null,
                'country_id' => 40,
                'created_at' => '2020-08-19 11:30:38',
                'deleted_at' => null,
                'postal_code' => 'L6Y 5S5',
                'province' => 'Ontario',
                'updated_at' => '2020-08-19 11:30:38',
            ],

            [
                'address' => '243 Consumers Rd.',
                'address2' => '',
                'addressable_id' => 12,
                'addressable_type' => 'App\\Models\\Retailer',
                'city' => 'Toronto',
                'cloned_from_id' => null,
                'country_id' => 40,
                'created_at' => '2020-08-19 11:30:38',
                'deleted_at' => null,
                'postal_code' => 'M2J 4W8',
                'province' => 'Ontario',
                'updated_at' => '2020-08-19 11:30:38',
            ],

            [
                'address' => '1 Wilkinson Road',
                'address2' => '',
                'addressable_id' => 13,
                'addressable_type' => 'App\\Models\\Retailer',
                'city' => 'Brampton',
                'cloned_from_id' => null,
                'country_id' => 40,
                'created_at' => '2020-08-19 11:30:38',
                'deleted_at' => null,
                'postal_code' => 'L6T 4M6',
                'province' => 'Ontario',
                'updated_at' => '2020-08-19 11:30:38',
            ],

            [
                'address' => '2101 91st Street',
                'address2' => '',
                'addressable_id' => 16,
                'addressable_type' => 'App\\Models\\Retailer',
                'city' => 'North Bergen',
                'cloned_from_id' => null,
                'country_id' => 237,
                'created_at' => '2020-08-19 11:30:38',
                'deleted_at' => null,
                'postal_code' => '07047',
                'province' => 'NJ',
                'updated_at' => '2020-08-19 11:30:38',
            ],

            [
                'address' => '1940 Argentia Road',
                'address2' => '',
                'addressable_id' => 17,
                'addressable_type' => 'App\\Models\\Retailer',
                'city' => 'Mississauga',
                'cloned_from_id' => null,
                'country_id' => 40,
                'created_at' => '2020-08-19 11:30:38',
                'deleted_at' => null,
                'postal_code' => 'L5N 1P9',
                'province' => 'Ontario',
                'updated_at' => '2020-08-19 11:30:38',
            ],

            [
                'address' => '904 The East Mall',
                'address2' => '2nd floor',
                'addressable_id' => 19,
                'addressable_type' => 'App\\Models\\Retailer',
                'city' => 'Toronto',
                'cloned_from_id' => null,
                'country_id' => 40,
                'created_at' => '2020-08-19 11:30:38',
                'deleted_at' => null,
                'postal_code' => 'M9B 6K2',
                'province' => 'Ontario',
                'updated_at' => '2020-08-19 11:30:38',
            ],

            [
                'address' => '2285 West 4th Avenue',
                'address2' => '2nd Level',
                'addressable_id' => 20,
                'addressable_type' => 'App\\Models\\Retailer',
                'city' => 'Vancouver',
                'cloned_from_id' => null,
                'country_id' => 40,
                'created_at' => '2020-08-19 11:30:38',
                'deleted_at' => null,
                'postal_code' => 'V6K 1N9',
                'province' => 'British Columbia',
                'updated_at' => '2020-08-19 11:30:38',
            ],

            [
                'address' => '1850 SE 17th Street, Suite 106 A',
                'address2' => '',
                'addressable_id' => 43,
                'addressable_type' => 'App\\Models\\Vendor',
                'city' => 'Fort Lauderdale',
                'cloned_from_id' => null,
                'country_id' => 237,
                'created_at' => '2020-11-15 08:09:37',
                'deleted_at' => null,
                'postal_code' => '33316',
                'province' => 'Florida',
                'updated_at' => '2020-11-15 08:09:37',
            ],

            [
                'address' => '5605, DE GASPÉ, SUITE 401, MONTREAL, H2T 2A4 CANADA',
                'address2' => '',
                'addressable_id' => 64,
                'addressable_type' => 'App\\Models\\Vendor',
                'city' => 'Montreal',
                'cloned_from_id' => null,
                'country_id' => 40,
                'created_at' => '2020-11-15 08:09:37',
                'deleted_at' => null,
                'postal_code' => 'H2T 2A4',
                'province' => 'Quebec',
                'updated_at' => '2020-11-15 08:09:37',
            ],

            [
                'address' => '1750 112th Avenue NE Suite C242',
                'address2' => '',
                'addressable_id' => 94,
                'addressable_type' => 'App\\Models\\Vendor',
                'city' => 'Bellevue',
                'cloned_from_id' => null,
                'country_id' => 237,
                'created_at' => '2020-11-15 08:09:37',
                'deleted_at' => null,
                'postal_code' => '98004',
                'province' => 'WA',
                'updated_at' => '2020-11-15 08:09:37',
            ],

            [
                'address' => 'P.O. Box 444, Mt-Royal Station',
                'address2' => '',
                'addressable_id' => 122,
                'addressable_type' => 'App\\Models\\Vendor',
                'city' => 'Mont-Royal',
                'cloned_from_id' => null,
                'country_id' => 40,
                'created_at' => '2020-11-15 08:09:37',
                'deleted_at' => null,
                'postal_code' => 'H3P 3C6',
                'province' => 'Québec',
                'updated_at' => '2020-11-15 08:09:37',
            ],

            [
                'address' => '8770 W. Bryn Mawr Ave. Suite 1100',
                'address2' => '',
                'addressable_id' => 147,
                'addressable_type' => 'App\\Models\\Vendor',
                'city' => 'Chicago',
                'cloned_from_id' => null,
                'country_id' => 237,
                'created_at' => '2020-11-15 08:09:37',
                'deleted_at' => null,
                'postal_code' => '60631',
                'province' => 'Illonois',
                'updated_at' => '2020-11-15 08:09:37',
            ],

            [
                'address' => '#3-11191 Horseshoe Way',
                'address2' => '',
                'addressable_id' => 196,
                'addressable_type' => 'App\\Models\\Vendor',
                'city' => 'Richmond',
                'cloned_from_id' => null,
                'country_id' => 40,
                'created_at' => '2020-11-15 08:09:37',
                'deleted_at' => null,
                'postal_code' => 'V7A 4S5',
                'province' => 'BC',
                'updated_at' => '2020-11-15 08:09:37',
            ],

            [
                'address' => '2334 Marie-Victorin, CP 87',
                'address2' => '',
                'addressable_id' => 215,
                'addressable_type' => 'App\\Models\\Vendor',
                'city' => 'Varennes',
                'cloned_from_id' => null,
                'country_id' => 40,
                'created_at' => '2020-11-15 08:09:37',
                'deleted_at' => null,
                'postal_code' => 'J3X1R4',
                'province' => 'Quebec',
                'updated_at' => '2020-11-15 08:09:37',
            ],
        ]);
    }
}
