<?php

namespace Database\Factories;

use App\Models\SignoffResponse;
use App\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class SignoffResponseFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = SignoffResponse::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => User::factory(),
            'comment' => $this->faker->optional()->sentence(),
            'step' => 1,
        ];
    }

    public function onStep($step)
    {
        return $this->state(function (array $attributes) use ($step) {
            return [
                'step' => $step,
            ];
        });
    }

    public function approved($approvedAt = null)
    {
        $approvedAt = $approvedAt ?: now();

        return $this->state(function (array $attributes) use ($approvedAt) {
            return [
                'approved' => true,
                'created_at' => $approvedAt,
            ];
        });
    }

    public function rejected($rejectedAt = null)
    {
        $rejectedAt ??= now();

        return $this->state(function (array $attributes) use ($rejectedAt) {
            return [
                'approved' => false,
                'archived' => true,
                'comment' => $this->faker->sentence(),
                'created_at' => $rejectedAt,
            ];
        });
    }

    public function commentOnly()
    {
        return $this->state(function (array $attributes) {
            return [
                'archived' => true,
                'comment_only' => true,
            ];
        });
    }

    public function submitted($submittedAt = null)
    {
        $submittedAt ??= now();

        return $this->state(function (array $attributes) use ($submittedAt) {
            return [
                'archived' => true,
                'approved' => true,
                'comment' => 'Submitted',
                'comment_only' => true,
                'step' => 1,
                'created_at' => $submittedAt,
            ];
        });
    }
}
