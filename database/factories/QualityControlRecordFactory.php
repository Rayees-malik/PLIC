<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\QualityControlRecord;
use App\Models\Vendor;
use Illuminate\Database\Eloquent\Factories\Factory;

class QualityControlRecordFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = QualityControlRecord::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'vendor_id' => Vendor::factory(),
            'received_date' => $this->faker->date(),
            'product_id' => Product::factory(),
            'quantity_received' => $this->faker->numberBetween(1, 100),
            'po_number' => $this->faker->numberBetween(1000, 1500),
            'identity_description' => $this->faker->sentence(),
            'matches_written_specification' => true,
            'lot_number' => $this->faker->bothify('???-###'),
            'expiry_date' => $this->faker->date(),
            'bin_number' => $this->faker->bothify('?##'),
            'din_npn_number' => $this->faker->numerify('########'),
            'seals_intact' => $this->faker->boolean(50),
            'din_npn_on_label' => $this->faker->boolean(50),
            'importer_address' => $this->faker->boolean(50),
            'bilingual_label' => $this->faker->boolean(50),
            'receiving_comment' => $this->faker->sentence(),
            'number_damaged_cartons' => $this->faker->numberBetween(0, 10),
            'number_damaged_units' => $this->faker->numberBetween(0, 10),
            'number_to_reject_destroy' => $this->faker->numberBetween(0, 10),
            'nature_of_damage' => $this->faker->sentence(),
            'number_units_sent_for_testing' => $this->faker->numberBetween(0, 10),
            'number_units_for_stability' => $this->faker->numberBetween(0, 10),
            'number_units_retained' => $this->faker->numberBetween(0, 10),
            'regulatory_compliance_comment' => $this->faker->sentence(),
        ];
    }
}
