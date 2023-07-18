<?php

namespace Database\Seeders\Development\iseed;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CatalogueCategoriesTableSeeder extends Seeder
{
    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        DB::table('catalogue_categories')->delete();

        DB::table('catalogue_categories')->insert([

            [
                'brand_id' => 1,
                'created_at' => '2020-11-15 08:09:21',
                'deleted_at' => null,
                'id' => 1,
                'name' => 'General',
                'name_fr' => null,
                'sort' => null,
                'updated_at' => '2020-11-15 08:09:21',
            ],

            [
                'brand_id' => 1,
                'created_at' => '2020-11-15 08:09:23',
                'deleted_at' => null,
                'id' => 2,
                'name' => 'Body Care',
                'name_fr' => null,
                'sort' => null,
                'updated_at' => '2020-11-15 08:09:23',
            ],

            [
                'brand_id' => 1,
                'created_at' => '2020-11-15 08:09:23',
                'deleted_at' => null,
                'id' => 3,
                'name' => 'Seriously Soothing',
                'name_fr' => null,
                'sort' => null,
                'updated_at' => '2020-11-15 08:09:23',
            ],

            [
                'brand_id' => 1,
                'created_at' => '2020-11-15 08:09:23',
                'deleted_at' => null,
                'id' => 4,
                'name' => 'Radically Rejuvenating',
                'name_fr' => null,
                'sort' => null,
                'updated_at' => '2020-11-15 08:09:23',
            ],

            [
                'brand_id' => 3,
                'created_at' => '2020-11-15 08:09:23',
                'deleted_at' => null,
                'id' => 5,
                'name' => 'Hand Sanitizer',
                'name_fr' => null,
                'sort' => null,
                'updated_at' => '2020-11-15 08:09:23',
            ],

            [
                'brand_id' => 1,
                'created_at' => '2020-11-15 08:09:23',
                'deleted_at' => null,
                'id' => 6,
                'name' => 'Hair Care',
                'name_fr' => null,
                'sort' => null,
                'updated_at' => '2020-11-15 08:09:23',
            ],

            [
                'brand_id' => 3,
                'created_at' => '2020-11-15 08:09:23',
                'deleted_at' => null,
                'id' => 7,
                'name' => 'Cleaners',
                'name_fr' => null,
                'sort' => null,
                'updated_at' => '2020-11-15 08:09:23',
            ],

            [
                'brand_id' => 3,
                'created_at' => '2020-11-15 08:09:23',
                'deleted_at' => null,
                'id' => 8,
                'name' => 'Bulk To Go',
                'name_fr' => null,
                'sort' => null,
                'updated_at' => '2020-11-15 08:09:23',
            ],

            [
                'brand_id' => 1,
                'created_at' => '2020-11-15 08:09:24',
                'deleted_at' => null,
                'id' => 9,
                'name' => 'Brightening',
                'name_fr' => null,
                'sort' => null,
                'updated_at' => '2020-11-15 08:09:24',
            ],

            [
                'brand_id' => 1,
                'created_at' => '2020-11-15 08:09:24',
                'deleted_at' => null,
                'id' => 10,
                'name' => 'Ultra Hydrating',
                'name_fr' => null,
                'sort' => null,
                'updated_at' => '2020-11-15 08:09:24',
            ],

            [
                'brand_id' => 1,
                'created_at' => '2020-11-15 08:09:24',
                'deleted_at' => null,
                'id' => 11,
                'name' => 'Resurfacing',
                'name_fr' => null,
                'sort' => null,
                'updated_at' => '2020-11-15 08:09:24',
            ],

            [
                'brand_id' => 1,
                'created_at' => '2020-11-15 08:09:24',
                'deleted_at' => null,
                'id' => 12,
                'name' => 'Incredibly Clear',
                'name_fr' => null,
                'sort' => null,
                'updated_at' => '2020-11-15 08:09:24',
            ],

            [
                'brand_id' => 8,
                'created_at' => '2020-11-15 08:09:25',
                'deleted_at' => null,
                'id' => 13,
                'name' => 'Good News Gummies',
                'name_fr' => null,
                'sort' => null,
                'updated_at' => '2020-11-15 08:09:25',
            ],

            [
                'brand_id' => 3,
                'created_at' => '2020-11-15 08:09:25',
                'deleted_at' => null,
                'id' => 14,
                'name' => 'Displays',
                'name_fr' => null,
                'sort' => null,
                'updated_at' => '2020-11-15 08:09:25',
            ],

            [
                'brand_id' => 3,
                'created_at' => '2020-11-15 08:09:25',
                'deleted_at' => null,
                'id' => 15,
                'name' => 'Super Leaves - Adult Body Care',
                'name_fr' => null,
                'sort' => null,
                'updated_at' => '2020-11-15 08:09:25',
            ],

            [
                'brand_id' => 5,
                'created_at' => '2020-11-15 08:09:26',
                'deleted_at' => null,
                'id' => 16,
                'name' => 'Empty Bottles',
                'name_fr' => null,
                'sort' => null,
                'updated_at' => '2020-11-15 08:09:26',
            ],

            [
                'brand_id' => 9,
                'created_at' => '2020-11-15 08:09:27',
                'deleted_at' => null,
                'id' => 17,
                'name' => 'Aloe Vera',
                'name_fr' => null,
                'sort' => null,
                'updated_at' => '2020-11-15 08:09:27',
            ],

            [
                'brand_id' => 2,
                'created_at' => '2020-11-15 08:09:27',
                'deleted_at' => null,
                'id' => 18,
                'name' => 'Value Sizes',
                'name_fr' => null,
                'sort' => null,
                'updated_at' => '2020-11-15 08:09:27',
            ],

            [
                'brand_id' => 1,
                'created_at' => '2020-11-15 08:09:27',
                'deleted_at' => null,
                'id' => 19,
                'name' => 'Foil-time Mask',
                'name_fr' => null,
                'sort' => null,
                'updated_at' => '2020-11-15 08:09:27',
            ],

            [
                'brand_id' => 9,
                'created_at' => '2020-11-15 08:09:28',
                'deleted_at' => null,
                'id' => 20,
                'name' => 'Herbal Formulas',
                'name_fr' => null,
                'sort' => null,
                'updated_at' => '2020-11-15 08:09:28',
            ],

            [
                'brand_id' => 8,
                'created_at' => '2020-11-15 08:09:28',
                'deleted_at' => null,
                'id' => 21,
                'name' => 'Sports',
                'name_fr' => null,
                'sort' => null,
                'updated_at' => '2020-11-15 08:09:28',
            ],

            [
                'brand_id' => 3,
                'created_at' => '2020-11-15 08:09:31',
                'deleted_at' => null,
                'id' => 22,
                'name' => 'Sensitive Skin Body Care',
                'name_fr' => null,
                'sort' => null,
                'updated_at' => '2020-11-15 08:09:31',
            ],

            [
                'brand_id' => 1,
                'created_at' => '2020-11-15 08:09:32',
                'deleted_at' => null,
                'id' => 23,
                'name' => 'Deodorants',
                'name_fr' => null,
                'sort' => null,
                'updated_at' => '2020-11-15 08:09:32',
            ],

            [
                'brand_id' => 1,
                'created_at' => '2020-11-15 08:09:32',
                'deleted_at' => null,
                'id' => 24,
                'name' => 'Body Lotion',
                'name_fr' => null,
                'sort' => null,
                'updated_at' => '2020-11-15 08:09:32',
            ],

            [
                'brand_id' => 2,
                'created_at' => '2020-11-15 08:09:33',
                'deleted_at' => null,
                'id' => 25,
                'name' => 'Cannacell',
                'name_fr' => null,
                'sort' => null,
                'updated_at' => '2020-11-15 08:09:33',
            ],

            [
                'brand_id' => 5,
                'created_at' => '2020-11-15 08:09:33',
                'deleted_at' => null,
                'id' => 26,
                'name' => 'Essential Oils',
                'name_fr' => null,
                'sort' => null,
                'updated_at' => '2020-11-15 08:09:33',
            ],

            [
                'brand_id' => 2,
                'created_at' => '2020-11-15 08:09:33',
                'deleted_at' => null,
                'id' => 27,
                'name' => 'Botanical Deodorant',
                'name_fr' => null,
                'sort' => null,
                'updated_at' => '2020-11-15 08:09:33',
            ],

            [
                'brand_id' => 2,
                'created_at' => '2020-11-15 08:09:33',
                'deleted_at' => null,
                'id' => 28,
                'name' => 'Botanical Shave Cream',
                'name_fr' => null,
                'sort' => null,
                'updated_at' => '2020-11-15 08:09:33',
            ],

            [
                'brand_id' => 8,
                'created_at' => '2020-11-15 08:09:34',
                'deleted_at' => null,
                'id' => 29,
                'name' => 'Premium (less Than 1g Sugar/serving)',
                'name_fr' => null,
                'sort' => null,
                'updated_at' => '2020-11-15 08:09:34',
            ],

            [
                'brand_id' => 6,
                'created_at' => '2020-11-15 08:09:34',
                'deleted_at' => null,
                'id' => 30,
                'name' => 'Energy Bites',
                'name_fr' => null,
                'sort' => null,
                'updated_at' => '2020-11-15 08:09:34',
            ],

            [
                'brand_id' => 6,
                'created_at' => '2020-11-15 08:09:35',
                'deleted_at' => null,
                'id' => 31,
                'name' => 'Breakfast Ovals',
                'name_fr' => null,
                'sort' => null,
                'updated_at' => '2020-11-15 08:09:35',
            ],

            [
                'brand_id' => 6,
                'created_at' => '2020-11-15 08:09:35',
                'deleted_at' => null,
                'id' => 32,
                'name' => 'Chocolate',
                'name_fr' => null,
                'sort' => null,
                'updated_at' => '2020-11-15 08:09:35',
            ],

            [
                'brand_id' => 7,
                'created_at' => '2020-11-15 08:09:35',
                'deleted_at' => null,
                'id' => 33,
                'name' => 'Kids',
                'name_fr' => null,
                'sort' => null,
                'updated_at' => '2020-11-15 08:09:35',
            ],

            [
                'brand_id' => 3,
                'created_at' => '2020-11-15 08:09:35',
                'deleted_at' => null,
                'id' => 34,
                'name' => '100% Mineral Suncare',
                'name_fr' => null,
                'sort' => null,
                'updated_at' => '2020-11-15 08:09:35',
            ],

            [
                'brand_id' => 3,
                'created_at' => '2020-11-15 08:09:35',
                'deleted_at' => null,
                'id' => 35,
                'name' => 'Baby Leaves - Baby Care',
                'name_fr' => null,
                'sort' => null,
                'updated_at' => '2020-11-15 08:09:35',
            ],

            [
                'brand_id' => 3,
                'created_at' => '2020-11-15 08:09:36',
                'deleted_at' => null,
                'id' => 36,
                'name' => 'Little Leaves - Kids Care',
                'name_fr' => null,
                'sort' => null,
                'updated_at' => '2020-11-15 08:09:36',
            ],

            [
                'brand_id' => 3,
                'created_at' => '2020-11-15 08:09:36',
                'deleted_at' => null,
                'id' => 37,
                'name' => 'Air Purifier',
                'name_fr' => null,
                'sort' => null,
                'updated_at' => '2020-11-15 08:09:36',
            ],

            [
                'brand_id' => 3,
                'created_at' => '2020-11-15 08:09:36',
                'deleted_at' => null,
                'id' => 38,
                'name' => 'Dish',
                'name_fr' => null,
                'sort' => null,
                'updated_at' => '2020-11-15 08:09:36',
            ],
        ]);
    }
}
