<?php

namespace Database\Seeders;

use DateTime;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class ProductCategoriesSubcategoriesTableSeeder extends Seeder
{
    private $bcid;

    private $fdid;

    private $hhid;

    private $mdid;

    private $msid;

    private $psid;

    private $spid;

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $timestamp = new DateTime;

        $categories = [
            ['name' => 'Body Care', 'flags' => 2697, 'created_at' => $timestamp, 'updated_at' => $timestamp],
            ['name' => 'Food & Beverage', 'flags' => 41073, 'created_at' => $timestamp, 'updated_at' => $timestamp],
            ['name' => 'Household', 'flags' => 8, 'created_at' => $timestamp, 'updated_at' => $timestamp],
            ['name' => 'Medical Device', 'flags' => 4111, 'created_at' => $timestamp, 'updated_at' => $timestamp],
            ['name' => 'Miscellaneous', 'flags' => 8, 'created_at' => $timestamp, 'updated_at' => $timestamp],
            ['name' => 'Pesticides', 'flags' => 16393, 'created_at' => $timestamp, 'updated_at' => $timestamp],
            ['name' => 'Supplements', 'flags' => 1423, 'created_at' => $timestamp, 'updated_at' => $timestamp],
        ];

        DB::table('product_categories')->insert($categories);

        [$this->bcid, $this->fdid, $this->hhid, $this->mdid, $this->msid, $this->psid, $this->spid] = DB::table('product_categories')->orderBy('name')->pluck('id')->toArray();

        $this->insertBodyCare($timestamp);
        $this->insertFoodAndBeverage($timestamp);
        $this->insertHousehold($timestamp);
        $this->insertMedicalDevice($timestamp);
        $this->insertMiscellaneous($timestamp);
        $this->insertSupplements($timestamp);
        $this->insertBodyCareAndHousehold($timestamp);
        $this->insertBodyCareAndSupplements($timestamp);
        $this->insertFoodAndBeverageAndSupplements($timestamp);
    }

    private function insertMedicalDevice(Datetime $timestamp)
    {
        $subcategory = [
            ['code' => 7104, 'category' => 'Gen MDSE Personal Care', 'name' => 'Tampons', 'grocery' => false, 'created_at' => $timestamp, 'updated_at' => $timestamp],
        ];

        DB::table('product_subcategories')->insert($subcategory);

        $ids = Arr::pluck($subcategory, 'code');

        $mdid = $this->mdid;

        DB::table('product_category_product_subcategory')->insertUsing(
            ['product_category_id', 'product_subcategory_id'],
            function ($query) use ($mdid, $ids) {
                $query->select([DB::raw("'{$mdid}' as product_category_id"), 'id'])->from('product_subcategories')->whereIn('code', $ids)->pluck('id')->toArray();
            }
        );
    }

    private function insertSupplements(Datetime $timestamp)
    {
        $subcategory = [
            ['code' => 3101, 'category' => 'OTC Internal Products', 'name' => 'Internal Analgesics', 'grocery' => false, 'created_at' => $timestamp, 'updated_at' => $timestamp],
            ['code' => 3102, 'category' => 'OTC Internal Products', 'name' => 'Internal Cold & Flu & Hayfever & Asthma Care', 'grocery' => false, 'created_at' => $timestamp, 'updated_at' => $timestamp],
            ['code' => 3103, 'category' => 'OTC Internal Products', 'name' => 'Internal Digestive Care', 'grocery' => false, 'created_at' => $timestamp, 'updated_at' => $timestamp],
            ['code' => 5101, 'category' => 'Diet Formulas', 'name' => 'Diet Formulas', 'grocery' => false, 'created_at' => $timestamp, 'updated_at' => $timestamp],
            ['code' => 5201, 'category' => 'Digestive Aids & Enzymes', 'name' => 'Fiber Products & Laxatives', 'grocery' => false, 'created_at' => $timestamp, 'updated_at' => $timestamp],
            ['code' => 5202, 'category' => 'Digestive Aids & Enzymes', 'name' => 'Misc Enzyme Products & Digest Formulas', 'grocery' => false, 'created_at' => $timestamp, 'updated_at' => $timestamp],
            ['code' => 5203, 'category' => 'Digestive Aids & Enzymes', 'name' => 'Prebiotics & Probiotics', 'grocery' => false, 'created_at' => $timestamp, 'updated_at' => $timestamp],
            ['code' => 5301, 'category' => 'Flower Essences', 'name' => 'Flower Essence Remedies', 'grocery' => false, 'created_at' => $timestamp, 'updated_at' => $timestamp],
            ['code' => 5401, 'category' => 'Herbal Formulas', 'name' => 'Allergy Herbal Formulas', 'grocery' => false, 'created_at' => $timestamp, 'updated_at' => $timestamp],
            ['code' => 5402, 'category' => 'Herbal Formulas', 'name' => 'Brain/Circulation Herbal Formulas', 'grocery' => false, 'created_at' => $timestamp, 'updated_at' => $timestamp],
            ['code' => 5403, 'category' => 'Herbal Formulas', 'name' => 'Calmative Herbal Formulas', 'grocery' => false, 'created_at' => $timestamp, 'updated_at' => $timestamp],
            ['code' => 5404, 'category' => 'Herbal Formulas', 'name' => "Children's Herbal Formulas", 'grocery' => false, 'created_at' => $timestamp, 'updated_at' => $timestamp],
            ['code' => 5405, 'category' => 'Herbal Formulas', 'name' => 'Cleansing/Organ Supp Herbal Formulas', 'grocery' => false, 'created_at' => $timestamp, 'updated_at' => $timestamp],
            ['code' => 5406, 'category' => 'Herbal Formulas', 'name' => 'Cold & Flu/Immune System Formulas', 'grocery' => false, 'created_at' => $timestamp, 'updated_at' => $timestamp],
            ['code' => 5407, 'category' => 'Herbal Formulas', 'name' => 'Herbal Energy Formulas', 'grocery' => false, 'created_at' => $timestamp, 'updated_at' => $timestamp],
            ['code' => 5408, 'category' => 'Herbal Formulas', 'name' => 'Mens Herbal Formulas', 'grocery' => false, 'created_at' => $timestamp, 'updated_at' => $timestamp],
            ['code' => 5409, 'category' => 'Herbal Formulas', 'name' => 'Other Herbal Formulas', 'grocery' => false, 'created_at' => $timestamp, 'updated_at' => $timestamp],
            ['code' => 5410, 'category' => 'Herbal Formulas', 'name' => 'Womens Herbal Formulas', 'grocery' => false, 'created_at' => $timestamp, 'updated_at' => $timestamp],
            ['code' => 5501, 'category' => 'Herbal Singles', 'name' => 'Brain/Circulation Herbal Singles', 'grocery' => false, 'created_at' => $timestamp, 'updated_at' => $timestamp],
            ['code' => 5502, 'category' => 'Herbal Singles', 'name' => 'Calmative Herbal Singles', 'grocery' => false, 'created_at' => $timestamp, 'updated_at' => $timestamp],
            ['code' => 5503, 'category' => 'Herbal Singles', 'name' => 'Cold & Flu & Immune System Singles', 'grocery' => false, 'created_at' => $timestamp, 'updated_at' => $timestamp],
            ['code' => 5504, 'category' => 'Herbal Singles', 'name' => 'Mens Herbal Singles', 'grocery' => false, 'created_at' => $timestamp, 'updated_at' => $timestamp],
            ['code' => 5505, 'category' => 'Herbal Singles', 'name' => 'Other Herbal Singles', 'grocery' => false, 'created_at' => $timestamp, 'updated_at' => $timestamp],
            ['code' => 5506, 'category' => 'Herbal Singles', 'name' => 'Womens Herbal Singles', 'grocery' => false, 'created_at' => $timestamp, 'updated_at' => $timestamp],
            ['code' => 5601, 'category' => 'Homeopathic', 'name' => 'Allergy/Respiratory Medicines', 'grocery' => false, 'created_at' => $timestamp, 'updated_at' => $timestamp],
            ['code' => 5602, 'category' => 'Homeopathic', 'name' => "Children's Medicines", 'grocery' => false, 'created_at' => $timestamp, 'updated_at' => $timestamp],
            ['code' => 5603, 'category' => 'Homeopathic', 'name' => 'Cold/Flu Medicines', 'grocery' => false, 'created_at' => $timestamp, 'updated_at' => $timestamp],
            ['code' => 5604, 'category' => 'Homeopathic', 'name' => 'Digestive Aid Medicines', 'grocery' => false, 'created_at' => $timestamp, 'updated_at' => $timestamp],
            ['code' => 5605, 'category' => 'Homeopathic', 'name' => 'Homeopathic Singles', 'grocery' => false, 'created_at' => $timestamp, 'updated_at' => $timestamp],
            ['code' => 5606, 'category' => 'Homeopathic', 'name' => 'Other Medicines', 'grocery' => false, 'created_at' => $timestamp, 'updated_at' => $timestamp],
            ['code' => 5607, 'category' => 'Homeopathic', 'name' => 'Pain Relief Medicines', 'grocery' => false, 'created_at' => $timestamp, 'updated_at' => $timestamp],
            ['code' => 5608, 'category' => 'Homeopathic', 'name' => 'Stress & Sleep Aid Medicines', 'grocery' => false, 'created_at' => $timestamp, 'updated_at' => $timestamp],
            ['code' => 5609, 'category' => 'Homeopathic', 'name' => "Women's Medicines", 'grocery' => false, 'created_at' => $timestamp, 'updated_at' => $timestamp],
            ['code' => 5701, 'category' => 'Misc Supp', 'name' => 'Coenzyme Q', 'grocery' => false, 'created_at' => $timestamp, 'updated_at' => $timestamp],
            ['code' => 5702, 'category' => 'Misc Supp', 'name' => 'Glucosamine & Chondroitin Supp', 'grocery' => false, 'created_at' => $timestamp, 'updated_at' => $timestamp],
            ['code' => 5703, 'category' => 'Misc Supp', 'name' => 'Other Miscellaneous Supp', 'grocery' => false, 'created_at' => $timestamp, 'updated_at' => $timestamp],
            ['code' => 5704, 'category' => 'Misc Supp', 'name' => 'Polyphenols', 'grocery' => false, 'created_at' => $timestamp, 'updated_at' => $timestamp],
            ['code' => 5705, 'category' => 'Misc Supp', 'name' => 'Omega', 'grocery' => false, 'created_at' => $timestamp, 'updated_at' => $timestamp],
            ['code' => 5706, 'category' => 'Misc Supp', 'name' => 'Fish Oils', 'grocery' => false, 'created_at' => $timestamp, 'updated_at' => $timestamp],
            ['code' => 5801, 'category' => 'Sports Nutrition', 'name' => 'Creatine', 'grocery' => false, 'created_at' => $timestamp, 'updated_at' => $timestamp],
            ['code' => 5802, 'category' => 'Sports Nutrition', 'name' => 'Other Sports Supp', 'grocery' => false, 'created_at' => $timestamp, 'updated_at' => $timestamp],
            ['code' => 5803, 'category' => 'Sports Nutrition', 'name' => 'Protein Powder', 'grocery' => false, 'created_at' => $timestamp, 'updated_at' => $timestamp],
            ['code' => 5901, 'category' => 'Vit & Min', 'name' => 'Calcium & Calcium Formulas', 'grocery' => false, 'created_at' => $timestamp, 'updated_at' => $timestamp],
            ['code' => 5902, 'category' => 'Vit & Min', 'name' => 'Carotenoids & Antioxidant Formulas', 'grocery' => false, 'created_at' => $timestamp, 'updated_at' => $timestamp],
            ['code' => 5903, 'category' => 'Vit & Min', 'name' => "Children's Vitamins & Minerals", 'grocery' => false, 'created_at' => $timestamp, 'updated_at' => $timestamp],
            ['code' => 5904, 'category' => 'Vit & Min', 'name' => 'Multi', 'grocery' => false, 'created_at' => $timestamp, 'updated_at' => $timestamp],
            ['code' => 5905, 'category' => 'Vit & Min', 'name' => 'Other Minerals', 'grocery' => false, 'created_at' => $timestamp, 'updated_at' => $timestamp],
            ['code' => 5906, 'category' => 'Vit & Min', 'name' => 'Vitamin B', 'grocery' => false, 'created_at' => $timestamp, 'updated_at' => $timestamp],
            ['code' => 5907, 'category' => 'Vit & Min', 'name' => 'Vitamin C', 'grocery' => false, 'created_at' => $timestamp, 'updated_at' => $timestamp],
            ['code' => 5908, 'category' => 'Vit & Min', 'name' => 'Vitamin E', 'grocery' => false, 'created_at' => $timestamp, 'updated_at' => $timestamp],
            ['code' => 5909, 'category' => 'Vit & Min', 'name' => 'Vitamin D', 'grocery' => false, 'created_at' => $timestamp, 'updated_at' => $timestamp],
            ['code' => 5910, 'category' => 'Vit & Min', 'name' => 'Vitamins A & K', 'grocery' => false, 'created_at' => $timestamp, 'updated_at' => $timestamp],
            ['code' => 5911, 'category' => 'Vit & Min', 'name' => "Women's Vitamin & Mineral Formulas", 'grocery' => false, 'created_at' => $timestamp, 'updated_at' => $timestamp],
            ['code' => 5912, 'category' => 'Vit & Min', 'name' => "Men's Vitamin & Mineral Formulas", 'grocery' => false, 'created_at' => $timestamp, 'updated_at' => $timestamp],
            ['code' => 6301, 'category' => 'Amino Acids', 'name' => 'Amino Acids', 'grocery' => false, 'created_at' => $timestamp, 'updated_at' => $timestamp],
        ];

        DB::table('product_subcategories')->insert($subcategory);

        $ids = Arr::pluck($subcategory, 'code');

        $spid = $this->spid;

        DB::table('product_category_product_subcategory')->insertUsing(
            ['product_category_id', 'product_subcategory_id'],
            function ($query) use ($spid, $ids) {
                $query->select([DB::raw("'{$spid}' as product_category_id"), 'id'])->from('product_subcategories')->whereIn('code', $ids)->pluck('id')->toArray();
            }
        );
    }

    private function insertBodyCareAndSupplements(Datetime $timestamp)
    {
        $subcategory = [
            ['code' => 5302, 'category' => 'Flower Essences', 'name' => 'Flower Essence Topicals', 'grocery' => false, 'created_at' => $timestamp, 'updated_at' => $timestamp],
        ];

        DB::table('product_subcategories')->insert($subcategory);

        $ids = Arr::pluck($subcategory, 'code');

        $spid = $this->spid;
        $bcid = $this->bcid;

        DB::table('product_category_product_subcategory')->insertUsing(
            ['product_category_id', 'product_subcategory_id'],
            function ($query) use ($spid, $ids) {
                $query->select([DB::raw("'{$spid}' as product_category_id"), 'id'])->from('product_subcategories')->whereIn('code', $ids)->pluck('id')->toArray();
            }
        );

        DB::table('product_category_product_subcategory')->insertUsing(
            ['product_category_id', 'product_subcategory_id'],
            function ($query) use ($bcid, $ids) {
                $query->select([DB::raw("'{$bcid}' as product_category_id"), 'id'])->from('product_subcategories')->whereIn('code', $ids)->pluck('id')->toArray();
            }
        );
    }

    private function insertFoodAndBeverageAndSupplements(Datetime $timestamp)
    {
        $subcategory = [
            ['code' => 5303, 'category' => 'Flower Essences', 'name' => 'Flower Essence Foods', 'grocery' => false, 'created_at' => $timestamp, 'updated_at' => $timestamp],
            ['code' => 6101, 'category' => 'Food Supp', 'name' => 'Aloe Products', 'grocery' => false, 'created_at' => $timestamp, 'updated_at' => $timestamp],
            ['code' => 6102, 'category' => 'Food Supp', 'name' => 'Bee Products', 'grocery' => true, 'created_at' => $timestamp, 'updated_at' => $timestamp],
            ['code' => 6103, 'category' => 'Food Supp', 'name' => 'Cartilage Products', 'grocery' => false, 'created_at' => $timestamp, 'updated_at' => $timestamp],
            ['code' => 6104, 'category' => 'Food Supp', 'name' => 'Green Food Supp', 'grocery' => false, 'created_at' => $timestamp, 'updated_at' => $timestamp],
            ['code' => 6105, 'category' => 'Food Supp', 'name' => 'Misc Fruit & Vegetable & Grain Supp', 'grocery' => true, 'created_at' => $timestamp, 'updated_at' => $timestamp],
            ['code' => 6106, 'category' => 'Food Supp', 'name' => 'Soy Supp', 'grocery' => false, 'created_at' => $timestamp, 'updated_at' => $timestamp],
            ['code' => 6107, 'category' => 'Food Supp', 'name' => 'Supp Oils (Food)', 'grocery' => true, 'created_at' => $timestamp, 'updated_at' => $timestamp],
            ['code' => 6108, 'category' => 'Food Supp', 'name' => 'Yeast Products', 'grocery' => true, 'created_at' => $timestamp, 'updated_at' => $timestamp],
            ['code' => 6109, 'category' => 'Food Supp', 'name' => 'Baking', 'grocery' => true, 'created_at' => $timestamp, 'updated_at' => $timestamp],
            ['code' => 6110, 'category' => 'Food Supp', 'name' => 'Meal Replacement Bars', 'grocery' => true, 'created_at' => $timestamp, 'updated_at' => $timestamp],
            ['code' => 6111, 'category' => 'Food Supp', 'name' => 'Protein/Snack Bars', 'grocery' => true, 'created_at' => $timestamp, 'updated_at' => $timestamp],
            ['code' => 6112, 'category' => 'Food Supp', 'name' => 'Packaged Snacks', 'grocery' => true, 'created_at' => $timestamp, 'updated_at' => $timestamp],
            ['code' => 6113, 'category' => 'Food Supp', 'name' => 'Medicinal Teas', 'grocery' => true, 'created_at' => $timestamp, 'updated_at' => $timestamp],
            ['code' => 6116, 'category' => 'Food Supp', 'name' => 'Functional Food', 'grocery' => true, 'created_at' => $timestamp, 'updated_at' => $timestamp],
            ['code' => 6201, 'category' => 'Meal Replacements & Supp Powders', 'name' => 'Powdered Meal Replacements & Supp', 'grocery' => false, 'created_at' => $timestamp, 'updated_at' => $timestamp],
            ['code' => 6202, 'category' => 'Meal Replacements & Supp Powders', 'name' => 'Ready To Drink Meal Replacements', 'grocery' => true, 'created_at' => $timestamp, 'updated_at' => $timestamp],
        ];

        DB::table('product_subcategories')->insert($subcategory);

        $ids = Arr::pluck($subcategory, 'code');

        $spid = $this->spid;
        $fdid = $this->fdid;

        DB::table('product_category_product_subcategory')->insertUsing(
            ['product_category_id', 'product_subcategory_id'],
            function ($query) use ($spid, $ids) {
                $query->select([DB::raw("'{$spid}' as product_category_id"), 'id'])->from('product_subcategories')->whereIn('code', $ids)->pluck('id')->toArray();
            }
        );

        DB::table('product_category_product_subcategory')->insertUsing(
            ['product_category_id', 'product_subcategory_id'],
            function ($query) use ($fdid, $ids) {
                $query->select([DB::raw("'{$fdid}' as product_category_id"), 'id'])->from('product_subcategories')->whereIn('code', $ids)->pluck('id')->toArray();
            }
        );
    }

    private function insertBodyCareAndHousehold(Datetime $timestamp)
    {
        $subcategory = [
            ['code' => 1002, 'category' => 'Aromatherapy & Body Oils', 'name' => 'Essential Oils', 'grocery' => false, 'created_at' => $timestamp, 'updated_at' => $timestamp],
            ['code' => 1003, 'category' => 'Aromatherapy & Body Oils', 'name' => 'Natural & Synth Fragrance Oils & Waters', 'grocery' => false, 'created_at' => $timestamp, 'updated_at' => $timestamp],
        ];

        DB::table('product_subcategories')->insert($subcategory);

        $ids = Arr::pluck($subcategory, 'code');

        $hhid = $this->hhid;
        $bcid = $this->bcid;

        DB::table('product_category_product_subcategory')->insertUsing(
            ['product_category_id', 'product_subcategory_id'],
            function ($query) use ($hhid, $ids) {
                $query->select([DB::raw("'{$hhid}' as product_category_id"), 'id'])->from('product_subcategories')->whereIn('code', $ids)->pluck('id')->toArray();
            }
        );

        DB::table('product_category_product_subcategory')->insertUsing(
            ['product_category_id', 'product_subcategory_id'],
            function ($query) use ($bcid, $ids) {
                $query->select([DB::raw("'{$bcid}' as product_category_id"), 'id'])->from('product_subcategories')->whereIn('code', $ids)->pluck('id')->toArray();
            }
        );
    }

    private function insertBodyCare(Datetime $timestamp)
    {
        $subcategory = [
            ['code' => 1004, 'category' => 'Aromatherapy & Body Oils', 'name' => 'Body, Hair & Massage Oils', 'grocery' => false, 'created_at' => $timestamp, 'updated_at' => $timestamp],
            ['code' => 2101, 'category' => 'Cosmetics & Beauty Aids', 'name' => 'Beauty Products', 'grocery' => false, 'created_at' => $timestamp, 'updated_at' => $timestamp],
            ['code' => 2102, 'category' => 'Cosmetics & Beauty Aids', 'name' => 'Lip Balm', 'grocery' => false, 'created_at' => $timestamp, 'updated_at' => $timestamp],
            ['code' => 2103, 'category' => 'Cosmetics & Beauty Aids', 'name' => 'Lip Gloss/Colour', 'grocery' => false, 'created_at' => $timestamp, 'updated_at' => $timestamp],
            ['code' => 2104, 'category' => 'Cosmetics & Beauty Aids', 'name' => 'Lip Pencil', 'grocery' => false, 'created_at' => $timestamp, 'updated_at' => $timestamp],
            ['code' => 2105, 'category' => 'Cosmetics & Beauty Aids', 'name' => 'Eye Liner/Mascara/Eyebrow Pencils', 'grocery' => false, 'created_at' => $timestamp, 'updated_at' => $timestamp],
            ['code' => 2106, 'category' => 'Cosmetics & Beauty Aids', 'name' => 'Eyeshadows', 'grocery' => false, 'created_at' => $timestamp, 'updated_at' => $timestamp],
            ['code' => 2107, 'category' => 'Cosmetics & Beauty Aids', 'name' => 'Concealer', 'grocery' => false, 'created_at' => $timestamp, 'updated_at' => $timestamp],
            ['code' => 2108, 'category' => 'Cosmetics & Beauty Aids', 'name' => 'Rouge', 'grocery' => false, 'created_at' => $timestamp, 'updated_at' => $timestamp],
            ['code' => 2109, 'category' => 'Cosmetics & Beauty Aids', 'name' => 'Face Powder/Foundation', 'grocery' => false, 'created_at' => $timestamp, 'updated_at' => $timestamp],
            ['code' => 2110, 'category' => 'Cosmetics & Beauty Aids', 'name' => 'Nailpolish', 'grocery' => false, 'created_at' => $timestamp, 'updated_at' => $timestamp],
            ['code' => 2201, 'category' => 'Deodorants', 'name' => 'Crystal Deodorants', 'grocery' => false, 'created_at' => $timestamp, 'updated_at' => $timestamp],
            ['code' => 2202, 'category' => 'Deodorants', 'name' => 'Powder & Spray Deodorants', 'grocery' => false, 'created_at' => $timestamp, 'updated_at' => $timestamp],
            ['code' => 2203, 'category' => 'Deodorants', 'name' => 'Roll', 'grocery' => false, 'created_at' => $timestamp, 'updated_at' => $timestamp],
            ['code' => 2204, 'category' => 'Deodorants', 'name' => 'Stick Deodorants', 'grocery' => false, 'created_at' => $timestamp, 'updated_at' => $timestamp],
            ['code' => 2301, 'category' => 'Hair Products', 'name' => 'Conditioner', 'grocery' => false, 'created_at' => $timestamp, 'updated_at' => $timestamp],
            ['code' => 2302, 'category' => 'Hair Products', 'name' => 'Hair Color Products', 'grocery' => false, 'created_at' => $timestamp, 'updated_at' => $timestamp],
            ['code' => 2303, 'category' => 'Hair Products', 'name' => 'Scalp & Hair Treatments', 'grocery' => false, 'created_at' => $timestamp, 'updated_at' => $timestamp],
            ['code' => 2304, 'category' => 'Hair Products', 'name' => 'Shampoo', 'grocery' => false, 'created_at' => $timestamp, 'updated_at' => $timestamp],
            ['code' => 2305, 'category' => 'Hair Products', 'name' => 'Styling Gel, Spray & Mousse', 'grocery' => false, 'created_at' => $timestamp, 'updated_at' => $timestamp],
            ['code' => 2401, 'category' => 'Oral Care', 'name' => 'Breath Fresheners', 'grocery' => false, 'created_at' => $timestamp, 'updated_at' => $timestamp],
            ['code' => 2402, 'category' => 'Oral Care', 'name' => 'Floss, Tools & Picks, & Misc Oral Care', 'grocery' => false, 'created_at' => $timestamp, 'updated_at' => $timestamp],
            ['code' => 2403, 'category' => 'Oral Care', 'name' => 'Mouth Sprays & Mouthwash', 'grocery' => false, 'created_at' => $timestamp, 'updated_at' => $timestamp],
            ['code' => 2404, 'category' => 'Oral Care', 'name' => 'Toothbrushes', 'grocery' => false, 'created_at' => $timestamp, 'updated_at' => $timestamp],
            ['code' => 2405, 'category' => 'Oral Care', 'name' => 'Toothpastes & Toothpowders', 'grocery' => false, 'created_at' => $timestamp, 'updated_at' => $timestamp],
            ['code' => 2501, 'category' => 'Skin Care', 'name' => 'Body Lotions, Cremes & Butters', 'grocery' => false, 'created_at' => $timestamp, 'updated_at' => $timestamp],
            ['code' => 2502, 'category' => 'Skin Care', 'name' => 'Facial Cleansers / Exfoliants', 'grocery' => false, 'created_at' => $timestamp, 'updated_at' => $timestamp],
            ['code' => 2503, 'category' => 'Skin Care', 'name' => 'Facial Lotions & Cremes', 'grocery' => false, 'created_at' => $timestamp, 'updated_at' => $timestamp],
            ['code' => 2504, 'category' => 'Skin Care', 'name' => 'Facial Serums', 'grocery' => false, 'created_at' => $timestamp, 'updated_at' => $timestamp],
            ['code' => 2505, 'category' => 'Skin Care', 'name' => 'Facial Masks', 'grocery' => false, 'created_at' => $timestamp, 'updated_at' => $timestamp],
            ['code' => 2506, 'category' => 'Skin Care', 'name' => 'Foot Care', 'grocery' => false, 'created_at' => $timestamp, 'updated_at' => $timestamp],
            ['code' => 2507, 'category' => 'Skin Care', 'name' => 'Insect Repellants/Outdoor Sprays', 'grocery' => false, 'created_at' => $timestamp, 'updated_at' => $timestamp],
            ['code' => 2508, 'category' => 'Skin Care', 'name' => 'Mists, Toners & Astringents', 'grocery' => false, 'created_at' => $timestamp, 'updated_at' => $timestamp],
            ['code' => 2509, 'category' => 'Skin Care', 'name' => 'Shaving Cremes / Lotions & Aftershaves', 'grocery' => false, 'created_at' => $timestamp, 'updated_at' => $timestamp],
            ['code' => 2510, 'category' => 'Skin Care', 'name' => 'Suncare Lotions/Cream', 'grocery' => false, 'created_at' => $timestamp, 'updated_at' => $timestamp],
            ['code' => 2511, 'category' => 'Skin Care', 'name' => 'Suncare Spray', 'grocery' => false, 'created_at' => $timestamp, 'updated_at' => $timestamp],
            ['code' => 2512, 'category' => 'Skin Care', 'name' => 'Suncare After Sun', 'grocery' => false, 'created_at' => $timestamp, 'updated_at' => $timestamp],
            ['code' => 2513, 'category' => 'Skin Care', 'name' => 'Babycare/Pre & Postnatal/Children', 'grocery' => false, 'created_at' => $timestamp, 'updated_at' => $timestamp],
            ['code' => 2601, 'category' => 'Soap & Bath', 'name' => 'Bar Soap', 'grocery' => false, 'created_at' => $timestamp, 'updated_at' => $timestamp],
            ['code' => 2602, 'category' => 'Soap & Bath', 'name' => 'Body Wash/Bath Gel', 'grocery' => false, 'created_at' => $timestamp, 'updated_at' => $timestamp],
            ['code' => 2603, 'category' => 'Soap & Bath', 'name' => 'Bubble Bath', 'grocery' => false, 'created_at' => $timestamp, 'updated_at' => $timestamp],
            ['code' => 2604, 'category' => 'Soap & Bath', 'name' => 'Liquid Soap', 'grocery' => false, 'created_at' => $timestamp, 'updated_at' => $timestamp],
            ['code' => 2605, 'category' => 'Soap & Bath', 'name' => 'Mineral / Fragrance Bath', 'grocery' => false, 'created_at' => $timestamp, 'updated_at' => $timestamp],
            ['code' => 2606, 'category' => 'Soap & Bath', 'name' => 'Sanitizer', 'grocery' => false, 'created_at' => $timestamp, 'updated_at' => $timestamp],
            ['code' => 2901, 'category' => 'Bodycare Kits, Sets & Travel Packs', 'name' => 'Gift Packs & Kits (Full Size)', 'grocery' => false, 'created_at' => $timestamp, 'updated_at' => $timestamp],
            ['code' => 2902, 'category' => 'Bodycare Kits, Sets & Travel Packs', 'name' => 'Travel / Trial Kits & Packs', 'grocery' => false, 'created_at' => $timestamp, 'updated_at' => $timestamp],
            ['code' => 1001, 'category' => 'Aromatherapy & Body Oils', 'name' => 'Aromatherapy ACC', 'grocery' => false, 'created_at' => $timestamp, 'updated_at' => $timestamp],
            ['code' => 3201, 'category' => 'OTC Topical Products', 'name' => 'Ear & Nasal & Eye Care', 'grocery' => false, 'created_at' => $timestamp, 'updated_at' => $timestamp],
            ['code' => 3202, 'category' => 'OTC Topical Products', 'name' => 'Family Planning & Sexual Wellness', 'grocery' => false, 'created_at' => $timestamp, 'updated_at' => $timestamp],
            ['code' => 3203, 'category' => 'OTC Topical Products', 'name' => 'Feminine Care', 'grocery' => false, 'created_at' => $timestamp, 'updated_at' => $timestamp],
            ['code' => 3204, 'category' => 'OTC Topical Products', 'name' => 'First Aid ACC', 'grocery' => false, 'created_at' => $timestamp, 'updated_at' => $timestamp],
            ['code' => 3205, 'category' => 'OTC Topical Products', 'name' => 'Medical Supplies And Implements', 'grocery' => false, 'created_at' => $timestamp, 'updated_at' => $timestamp],
            ['code' => 3206, 'category' => 'OTC Topical Products', 'name' => 'Other Topical Misc Care', 'grocery' => false, 'created_at' => $timestamp, 'updated_at' => $timestamp],
            ['code' => 3207, 'category' => 'OTC Topical Products', 'name' => 'Topical Analgesics', 'grocery' => false, 'created_at' => $timestamp, 'updated_at' => $timestamp],
            ['code' => 3208, 'category' => 'OTC Topical Products', 'name' => 'Topical First Aid', 'grocery' => false, 'created_at' => $timestamp, 'updated_at' => $timestamp],
            ['code' => 3209, 'category' => 'OTC Topical Products', 'name' => 'Topical Foot Care', 'grocery' => false, 'created_at' => $timestamp, 'updated_at' => $timestamp],
        ];

        DB::table('product_subcategories')->insert($subcategory);

        $ids = Arr::pluck($subcategory, 'code');

        $bcid = $this->bcid;

        DB::table('product_category_product_subcategory')->insertUsing(
            ['product_category_id', 'product_subcategory_id'],
            function ($query) use ($bcid, $ids) {
                $query->select([DB::raw("'{$bcid}' as product_category_id"), 'id'])->from('product_subcategories')->whereIn('code', $ids)->pluck('id')->toArray();
            }
        );
    }

    private function insertMiscellaneous(Datetime $timestamp)
    {
        $subcategory = [
            ['code' => 9901, 'category' => 'POS', 'name' => 'Brochures', 'grocery' => false, 'created_at' => $timestamp, 'updated_at' => $timestamp],
            ['code' => 9902, 'category' => 'POS', 'name' => 'Posters', 'grocery' => false, 'created_at' => $timestamp, 'updated_at' => $timestamp],
            ['code' => 9903, 'category' => 'POS', 'name' => 'Empty Displays', 'grocery' => false, 'created_at' => $timestamp, 'updated_at' => $timestamp],
            ['code' => 9904, 'category' => 'POS', 'name' => 'Other POS', 'grocery' => false, 'created_at' => $timestamp, 'updated_at' => $timestamp],
        ];

        DB::table('product_subcategories')->insert($subcategory);

        $ids = Arr::pluck($subcategory, 'code');

        $msid = $this->msid;

        DB::table('product_category_product_subcategory')->insertUsing(
            ['product_category_id', 'product_subcategory_id'],
            function ($query) use ($msid, $ids) {
                $query->select([DB::raw("'{$msid}' as product_category_id"), 'id'])->from('product_subcategories')->whereIn('code', $ids)->pluck('id')->toArray();
            }
        );
    }

    private function insertHousehold(Datetime $timestamp)
    {
        $subcategory = [
            ['code' => 7101, 'category' => 'Gen Mdse Personal Care', 'name' => 'Baby Diapers/Wipes', 'grocery' => false, 'created_at' => $timestamp, 'updated_at' => $timestamp],
            ['code' => 7102, 'category' => 'Gen MDSE Personal Care', 'name' => 'Pads', 'grocery' => false, 'created_at' => $timestamp, 'updated_at' => $timestamp],
            ['code' => 7103, 'category' => 'Gen MDSE Personal Care', 'name' => 'Reusable Feminine Hygiene', 'grocery' => false, 'created_at' => $timestamp, 'updated_at' => $timestamp],
            ['code' => 7105, 'category' => 'Gen Mdse Personal Care', 'name' => 'Other', 'grocery' => false, 'created_at' => $timestamp, 'updated_at' => $timestamp],
            ['code' => 7301, 'category' => 'Household Cleaners & Supplies', 'name' => 'Air Fresheners', 'grocery' => true, 'created_at' => $timestamp, 'updated_at' => $timestamp],
            ['code' => 7302, 'category' => 'Household Cleaners & Supplies', 'name' => 'Bath, Kitchen & Other Cleaners', 'grocery' => true, 'created_at' => $timestamp, 'updated_at' => $timestamp],
            ['code' => 7303, 'category' => 'Household Cleaners & Supplies', 'name' => 'Cleaning Supplies', 'grocery' => true, 'created_at' => $timestamp, 'updated_at' => $timestamp],
            ['code' => 7304, 'category' => 'Household Cleaners & Supplies', 'name' => 'Dishwashing Products', 'grocery' => true, 'created_at' => $timestamp, 'updated_at' => $timestamp],
            ['code' => 7305, 'category' => 'Household Cleaners & Supplies', 'name' => 'Liquid Laundry Products', 'grocery' => true, 'created_at' => $timestamp, 'updated_at' => $timestamp],
            ['code' => 7306, 'category' => 'Household Cleaners & Supplies', 'name' => 'Powder Laundry Products', 'grocery' => true, 'created_at' => $timestamp, 'updated_at' => $timestamp],
            ['code' => 7401, 'category' => 'Household Mdse', 'name' => 'Baskets', 'grocery' => false, 'created_at' => $timestamp, 'updated_at' => $timestamp],
            ['code' => 7402, 'category' => 'Household Mdse', 'name' => 'Candles', 'grocery' => false, 'created_at' => $timestamp, 'updated_at' => $timestamp],
            ['code' => 7403, 'category' => 'Household Mdse', 'name' => 'Books', 'grocery' => false, 'created_at' => $timestamp, 'updated_at' => $timestamp],
            ['code' => 7404, 'category' => 'Household Mdse', 'name' => 'Water Receptacles/Filters', 'grocery' => true, 'created_at' => $timestamp, 'updated_at' => $timestamp],
            ['code' => 7405, 'category' => 'Household Mdse', 'name' => 'Other', 'grocery' => false, 'created_at' => $timestamp, 'updated_at' => $timestamp],
            ['code' => 7406, 'category' => 'Household Mdse', 'name' => 'Cutlery/Serving Ware', 'grocery' => true, 'created_at' => $timestamp, 'updated_at' => $timestamp],
            ['code' => 8101, 'category' => 'Pet Food & Pet Care', 'name' => 'Pet Food', 'grocery' => false, 'created_at' => $timestamp, 'updated_at' => $timestamp],
            ['code' => 8102, 'category' => 'Pet Food & Pet Care', 'name' => 'Pet Supplies', 'grocery' => false, 'created_at' => $timestamp, 'updated_at' => $timestamp],
            ['code' => 8103, 'category' => 'Pet Food & Pet Care', 'name' => 'Pet Personal & Body Care', 'grocery' => false, 'created_at' => $timestamp, 'updated_at' => $timestamp],
            ['code' => 8104, 'category' => 'Pet Food & Pet Care', 'name' => 'Pet Supp', 'grocery' => false, 'created_at' => $timestamp, 'updated_at' => $timestamp],
            ['code' => 8105, 'category' => 'Pet Food & Pet Care', 'name' => 'Pet Treats & Snacks', 'grocery' => false, 'created_at' => $timestamp, 'updated_at' => $timestamp],
        ];

          DB::table('product_subcategories')->insert($subcategory);

        $ids = Arr::pluck($subcategory, 'code');

        $hhid = $this->hhid;

        DB::table('product_category_product_subcategory')->insertUsing(
            ['product_category_id', 'product_subcategory_id'],
            function ($query) use ($hhid, $ids) {
                $query->select([DB::raw("'{$hhid}' as product_category_id"), 'id'])->from('product_subcategories')->whereIn('code', $ids)->pluck('id')->toArray();
            }
        );
    }

    private function insertFoodAndBeverage(Datetime $timestamp)
    {
        $subcategory = [
            ['code' => 5804, 'category' => 'Sports Nutrition', 'name' => 'Sports Beverage', 'grocery' => true, 'created_at' => $timestamp, 'updated_at' => $timestamp],
            ['code' => 6114, 'category' => 'Food Supp', 'name' => 'Herbal Teas', 'grocery' => true, 'created_at' => $timestamp, 'updated_at' => $timestamp],
            ['code' => 6115, 'category' => 'Food Supp', 'name' => 'Coffee', 'grocery' => true, 'created_at' => $timestamp, 'updated_at' => $timestamp],
            ['code' => 6117, 'category' => 'Food Supp', 'name' => 'Confectionary', 'grocery' => true, 'created_at' => $timestamp, 'updated_at' => $timestamp],
            ['code' => 6118, 'category' => 'Food Supp', 'name' => 'Spices & Seasonings', 'grocery' => true, 'created_at' => $timestamp, 'updated_at' => $timestamp],
            ['code' => 6901, 'category' => 'Beverages', 'name' => 'Beverages', 'grocery' => true, 'created_at' => $timestamp, 'updated_at' => $timestamp],
            ['code' => 6902, 'category' => 'Beverages', 'name' => 'Ready To Drink Beverages', 'grocery' => true, 'created_at' => $timestamp, 'updated_at' => $timestamp],
            ['code' => 9905, 'category' => 'POS', 'name' => 'Food Display', 'grocery' => true, 'created_at' => $timestamp, 'updated_at' => $timestamp],
        ];

        DB::table('product_subcategories')->insert($subcategory);

        $ids = Arr::pluck($subcategory, 'code');

        $fdid = $this->fdid;

        DB::table('product_category_product_subcategory')->insertUsing(
            ['product_category_id', 'product_subcategory_id'],
            function ($query) use ($fdid, $ids) {
                $query->select([DB::raw("'{$fdid}' as product_category_id"), 'id'])->from('product_subcategories')->whereIn('code', $ids)->pluck('id')->toArray();
            }
        );
    }
}
