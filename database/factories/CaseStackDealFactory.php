<?php

namespace Database\Factories;

use App\Models\CaseStackDeal;
use App\Models\PromoPeriod;
use Illuminate\Database\Eloquent\Factories\Factory;

class CaseStackDealFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = CaseStackDeal::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            //
        ];
    }

    public function startsInFuture()
    {
        return $this->for(PromoPeriod::factory()->startsInFuture(), 'period');
    }

    public function endsInFuture()
    {
        return $this->for(PromoPeriod::factory()->endsInFuture(), 'period');
    }

    public function startsInPast()
    {
        return $this->for(PromoPeriod::factory()->startsInPast(), 'period');
    }

    public function endsInPast()
    {
        return $this->for(PromoPeriod::factory()->endsInPast(), 'period');
    }
}
