<?php

namespace App\Notifications\Signoffs;

use App\Notifications\SignoffNotification;

class VendorDiscontinuationNotification extends SignoffNotification
{
    public function resolveAttributes(): array
    {
        return [
            'Vendor Name' => $this->signoff->proposed->name,
        ];
    }
}
