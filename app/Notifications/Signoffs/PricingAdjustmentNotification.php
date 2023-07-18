<?php

namespace App\Notifications\Signoffs;

use App\Notifications\SignoffNotification;

class PricingAdjustmentNotification extends SignoffNotification
{
    public function resolveAttributes(): array
    {
        return [
            'Account Name' => $this->signoff->proposed->name,
            'PAF ID' => $this->signoff->proposed->id,
        ];
    }
}
