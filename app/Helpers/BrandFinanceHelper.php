<?php

namespace App\Helpers;

class BrandFinanceHelper
{
    public static function paymentsHeader($chequeNumber, $invoices)
    {
        $date = $invoices[0]->voucher_date;

        $chequeTotal = 0;
        foreach ($invoices as $invoice) {
            $chequeTotal += $invoice->invoice_amount - $invoice->discount_amount;
        }

        $chequeTotal = NumberHelper::toAccountingDollar($chequeTotal);

        return "<span class=\"accounting-cheque\">Cheque #{$chequeNumber}</span><span class=\"accounting-date\">{$date}</span><span class=\"accounting-total\">{$chequeTotal}</span>";
    }

    public static function paymentsTotals($invoices)
    {
        $date = $invoices[0]->voucher_date;

        $amountTotal = 0;
        $discountTotal = 0;
        foreach ($invoices as $invoice) {
            $amountTotal += $invoice->invoice_amount;
            $discountTotal += $invoice->discount_amount;
        }

        $chequeTotal = NumberHelper::toAccountingDollar($amountTotal - $discountTotal);
        $amountTotal = NumberHelper::toAccountingDollar($amountTotal);
        $discountTotal = NumberHelper::toAccountingDollar($discountTotal);

        return "<td class=\"accounting-total text-right\">{$amountTotal}</td><td class=\"accounting-total text-right\">{$discountTotal}</td><td class=\"accounting-total text-right\">{$chequeTotal}</td>";
    }
}
