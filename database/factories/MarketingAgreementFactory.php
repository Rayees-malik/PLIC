<?php

namespace Database\Factories;

use App\Helpers\SignoffStateHelper;
use App\Models\AS400\AS400Customer;
use App\Models\MarketingAgreement;
use App\Models\MarketingAgreementLineItem;
use App\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class MarketingAgreementFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = MarketingAgreement::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $customer = AS400Customer::factory()->make();
        $account = $customer->customer_number;
        $name = $customer->name . ' (#' . $customer->customer_number . ')';
        $account_other = null;

        return [
            'submitted_by' => User::factory(),
            'send_to' => User::factory()->create()->assign('sales-manager'),
            'name' => $name,
            'account' => $account,
            'account_other' => $account_other,
            'ship_to_number' => $this->faker->randomNumber(),
            'retailer_invoice' => $this->faker->randomNumber(),
            'comment' => $this->faker->sentence(),
            'approval_email' => $this->faker->paragraphs(rand(1, 2), true),
            'tax_rate' => $this->faker->randomFloat(2, 0, 100),
            'deleted_at' => null,
            'state' => SignoffStateHelper::PENDING,
        ];
    }

    public function pending()
    {
        return $this->state(function (array $attributes) {
            return [
                'state' => SignoffStateHelper::PENDING,
            ];
        });
    }

    public function initial()
    {
        return $this->state(function (array $attributes) {
            return [
                'state' => SignoffStateHelper::INITIAL,
            ];
        });
    }

    public function approved()
    {
        return $this->state(function (array $attributes) {
            return [
                'state' => SignoffStateHelper::APPROVED,
            ];
        });
    }

    public function rejected()
    {
        return $this->state(function (array $attributes) {
            return [
                'state' => SignoffStateHelper::REJECTED,
            ];
        });
    }

    public function archived()
    {
        return $this->state(function (array $attributes) {
            return [
                'state' => SignoffStateHelper::ARCHIVED,
            ];
        });
    }

    public function inProgress()
    {
        return $this->state(function (array $attributes) {
            return [
                'state' => SignoffStateHelper::IN_PROGRESS,
            ];
        });
    }

    public function unsubmitted()
    {
        return $this->state(function (array $attributes) {
            return [
                'state' => SignoffStateHelper::UNSUBMITTED,
            ];
        });
    }

    public function withLineItems($count = 1)
    {
        return $this->has(MarketingAgreementLineItem::factory()->count($count), 'lineItems');
    }

    public function validAccount()
    {
        return $this->state(function (array $attributes) {
            $customer = AS400Customer::factory()->make();

            return [
                'name' => $customer->name . ' (#' . $customer->customer_number . ')',
                'account' => $customer->customer_number,
                'account_other' => null,
            ];
        });
    }
}
