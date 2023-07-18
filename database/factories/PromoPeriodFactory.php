<?php

namespace Database\Factories;

use App\Models\PromoPeriod;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

class PromoPeriodFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = PromoPeriod::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->words(2, true),
        ];
    }

    public function startsInFuture()
    {
        return $this->state(function (array $attributes) {
            return [
                'start_date' => Carbon::now()->addMonth(1),
                'end_date' => Carbon::now()->addMonths(2),
            ];
        });
    }

    public function endsInFuture()
    {
        return $this->state(function (array $attributes) {
            return [
                'end_date' => Carbon::now()->addMonths(2),
            ];
        });
    }

    public function startsInPast()
    {
        return $this->state(function (array $attributes) {
            return [
                'start_date' => Carbon::now()->subMonth(1),
            ];
        });
    }

    public function endsInPast()
    {
        return $this->state(function (array $attributes) {
            return [
                'start_date' => Carbon::now()->subMonths(2),
                'end_date' => Carbon::now()->subMonth(1),
            ];
        });
    }
}
