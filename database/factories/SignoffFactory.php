<?php

namespace Database\Factories;

use App\Helpers\SignoffStateHelper;
use App\Models\Signoff;
use App\Models\SignoffConfig;
use App\Models\SignoffResponse;
use App\User;
use Exception;
use Illuminate\Database\Eloquent\Factories\Factory;

class SignoffFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Signoff::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => User::factory(),
            'state' => SignoffStateHelper::PENDING,
            'new_submission' => false,
        ];
    }

    public function newSubmission()
    {
        return $this->state(['new_submission' => true]);
    }

    public function submitted($submittedAt = null)
    {
        $submittedAt ??= now();

        return $this->state(function ($attributes) use ($submittedAt) {
            return [
                'submitted_at' => $submittedAt,
                'step' => 1,
            ];
        })->has(SignoffResponse::factory()->submitted($submittedAt), 'responses');
    }

    public function approved()
    {
        return $this->approveToStep($this->getNumberOfSteps());
    }

    public function rejectStep($step, $rejectedAt = null)
    {
        $this->validateStep($step);

        $instance = $this;

        $rejectedAt ??= now();

        $instance = $instance->approveToStep($step);

        return $instance
            ->has(SignoffResponse::factory()->rejected($rejectedAt)->onStep($step), 'responses')
            ->afterCreating(function (Signoff $signoff) {
                $signoff = $signoff->fresh();
                $signoff->step = 0;
                $signoff->state = SignoffStateHelper::REJECTED;
                $signoff->initial->state = SignoffStateHelper::REJECTED;
                $signoff->proposed->state = SignoffStateHelper::REJECTED;

                $signoff->push();
            });
    }

    public function approveStep($step, $approvedAt = null)
    {
        $this->validateStep($step);

        $instance = $this;

        if (! array_key_exists('step', $this->raw())) {
            $instance = $instance->submitted();
        }

        return $instance
            ->has(SignoffResponse::factory()->approved($approvedAt), 'responses')
            ->afterCreating(function (Signoff $signoff) use ($step) {
                $signoff = $signoff->fresh();
                if ($signoff->signoffConfig->numSteps == $step) {
                    $signoff->initial->state = SignoffStateHelper::INITIAL;
                    $signoff->proposed->state = SignoffStateHelper::APPROVED;
                    $signoff->state = SignoffStateHelper::APPROVED;
                }

                $signoff->step = $step + 1;

                $signoff->push();
            });
    }

    public function approveUpToStep($step)
    {
        $this->validateStep($step);

        return $this->approveToStep(--$step);
    }

    public function approveToStep($step)
    {
        $this->validateStep($step);

        $instance = $this;

        if (! array_key_exists('step', $this->raw())) {
            $instance = $instance->submitted();
        } else {
            if ($this->raw()['step'] != $step) {
                $responseSteps = collect(range(1, $step))->map(function ($item) {
                    return ['step' => $item];
                })->toArray();

                $instance = $instance->has(SignoffResponse::factory()->count(count($responseSteps))->approved()->sequence(...$responseSteps), 'responses');
            }
        }

        $this->raw()['step'] = $step;

        return $instance
            ->afterCreating(function (Signoff $signoff) use ($step) {
                $signoff = $signoff->fresh();
                if ($signoff->signoffConfig->numSteps == $step) {
                    $signoff->initial->state = SignoffStateHelper::INITIAL;
                    $signoff->proposed->state = SignoffStateHelper::APPROVED;
                    $signoff->state = SignoffStateHelper::APPROVED;
                }
                $signoff->step = $step + 1;

                $signoff->push();
            });
    }

    public function archived()
    {
        return $this->state(function ($attributes) {
            return [
                'state' => SignoffStateHelper::ARCHIVED,
            ];
        });
    }

    public function pending()
    {
        return $this->state(function ($attributes) {
            return [
                'state' => SignoffStateHelper::PENDING,
            ];
        });
    }

    public function inProgress()
    {
        return $this->state(function ($attributes) {
            return [
                'state' => SignoffStateHelper::IN_PROGRESS,
            ];
        });
    }

    private function getNumberOfSteps()
    {
        return SignoffConfig::find($this->raw()['signoff_config_id'])->numSteps;
    }

    private function validateStep($step)
    {
        if ($step < 1) {
            throw new Exception('Steps must be greater than or equal to 1.');
        } else {
            $maxSteps = $this->getNumberOfSteps();

            if ($step > $maxSteps) {
                throw new Exception("{$step} steps to set approved exceeds number of steps in config, which is {$maxSteps}.");
            }
        }
    }
}
