<?php

namespace App\Notifications\Signoffs;

use App\Notifications\SignoffNotification;

class InventoryRemovalNotification extends SignoffNotification
{
    public function resolveAttributes(): array
    {
        return [
            'Brand' => $this->signoff->proposed->lineItems()->first()->product->brand->name,
            'Warehouse' => $this->signoff->proposed->lineItems()->first()->warehouse,
            'Inventory Removal ID' => $this->signoff->proposed->id,
        ];
    }
}
