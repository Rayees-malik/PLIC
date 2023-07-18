<?php

namespace App\Notifications\Signoffs;

use App\Notifications\SignoffNotification;

class RetailerListingNotification extends SignoffNotification
{
    public function resolveAttributes(): array
    {
        return [
            'Retailer Name' => $this->signoff->proposed->name,
        ];
    }
}
