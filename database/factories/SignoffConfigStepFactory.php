<?php

namespace Database\Factories;

use App\Models\SignoffConfigStep;
use Illuminate\Database\Eloquent\Factories\Factory;

class SignoffConfigStepFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = SignoffConfigStep::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'step' => 1,
            'name' => $this->faker->words(2, true),
            'form_view' => 'foo.form',
            'signoffs_required' => 1,
        ];
    }
}
