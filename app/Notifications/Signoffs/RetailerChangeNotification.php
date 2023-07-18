<?php

namespace App\Notifications\Signoffs;

use App\Notifications\SignoffNotification;

class RetailerChangeNotification extends SignoffNotification
{
    public function resolveAttributes(): array
    {
        return [
            'Retailer Name' => $this->signoff->proposed->name,
        ];
    }
}
