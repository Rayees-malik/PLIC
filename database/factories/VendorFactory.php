<?php

namespace Database\Factories;

use App\Helpers\SignoffStateHelper;
use App\Helpers\StatusHelper;
use App\Models\Vendor;
use Illuminate\Database\Eloquent\Factories\Factory;

class VendorFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Vendor::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->company(),
            'phone' => $this->faker->phoneNumber(),
            'who_to_mcb' => $this->faker->optional()->name(),
            'cheque_payable_to' => $this->faker->company(),
            'payment_terms' => $this->faker->word(),
            'special_shipping_requirements' => $this->faker->optional()->sentence(),
            'backorder_policy' => $this->faker->optional()->sentence(),
            'return_policy' => $this->faker->optional()->sentence(),
            'fob_purity_distribution_centres' => true,
            'consignment' => false,
            'status' => StatusHelper::ACTIVE,
            'state' => SignoffStateHelper::INITIAL,
        ];
    }

    public function approved()
    {
        return $this
            ->afterCreating(function (Vendor $vendor) {
                $vendor = $vendor->duplicate();
                $vendor->state = SignoffStateHelper::APPROVED;
                $vendor->save();
            });
    }
}
