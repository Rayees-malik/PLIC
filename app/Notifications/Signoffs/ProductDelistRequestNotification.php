<?php

namespace App\Notifications\Signoffs;

use App\Notifications\SignoffNotification;

class ProductDelistRequestNotification extends SignoffNotification
{
    public function resolveAttributes(): array
    {
        $data = [
            'Product Name' => $this->signoff->proposed->product->name,
            'Brand' => $this->signoff->proposed->product->brand->name,
        ];

        if ($this->signoff->proposed->product->stock_id) {
            $data['Purity Stock Id'] = $this->signoff->proposed->product->stock_id;
        }

        $data['Delist Reason'] = $this->signoff->proposed->reason;

        return $data;
    }

    protected function resolveDynamicProperties(): string
    {
        return $this->signoff->proposed->product->brand->name . ' - ' . $this->signoff->proposed->product->stock_id;
    }
}
