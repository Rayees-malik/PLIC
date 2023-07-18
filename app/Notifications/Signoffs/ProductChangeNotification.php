<?php

namespace App\Notifications\Signoffs;

use App\Notifications\SignoffNotification;

class ProductChangeNotification extends SignoffNotification
{
    public function resolveAttributes(): array
    {
        return [
            'Product Name' => $this->signoff->proposed->name,
            'Brand' => $this->signoff->proposed->brand->name,
            'Purity Stock Id' => $this->signoff->proposed->stock_id,
        ];
    }

    protected function resolveDynamicProperties(): string
    {
        return $this->signoff->proposed->brand->name . ' - ' . $this->signoff->proposed->stock_id;
    }
}
