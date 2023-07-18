<?php

namespace App\Notifications\Signoffs;

use App\Notifications\SignoffNotification;

class MarketingAgreementNotification extends SignoffNotification
{
    public function resolveAttributes(): array
    {
        $data = [
            'Account Name' => $this->signoff->proposed->name,
            'Account #' => $this->signoff->proposed->account == 'Other' ? $this->signoff->proposed->account_other : $this->signoff->proposed->account,
            'MAF ID' => $this->signoff->proposed->id,
            'Retailer Invoice #' => $this->signoff->proposed->retailer_invoice,
        ];

        $total = $this->signoff->proposed->lineItems()->sum('cost');
        $taxRate = $this->signoff->proposed->tax_rate;

        if (is_null($taxRate) || $taxRate == 0) {
            $data['Total'] = '$' . number_format($total, 2);
        } else {
            $data['Total'] = '$' . number_format($total * (1 + ($taxRate / 100)), 2);
        }

        return $data;
    }
}
