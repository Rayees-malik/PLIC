<?php

namespace App\Helpers;

class PromoHelper
{
    public static function getDiscountRange($products, $period, $includeDisco = false)
    {
        if (! $products || ! count($products) || ! $period) {
            return;
        }

        $discounts = PromoHelper::getAllDiscounts($products, $period, $includeDisco);

        $minDiscount = null;
        $maxDiscount = null;
        $lineDrive = false;

        $productCount = $products[0]->brand->products_count;
        $promoCount = 0;

        foreach ($discounts as $discount) {
            $lineDrive = $discount['line_drive'];

            if (! $minDiscount || $discount['total_discount'] < $minDiscount) {
                $minDiscount = $discount['total_discount'];
            }

            if (! $maxDiscount || $discount['total_discount'] > $maxDiscount) {
                $maxDiscount = $discount['total_discount'];
            }

            $promoCount += $discount['count'];
        }

        if (is_null($minDiscount) && is_null($maxDiscount)) {
            return;
        }

        $allProducts = $promoCount >= $productCount;

        return ['low' => $minDiscount, 'high' => $maxDiscount, 'line_drive' => $lineDrive, 'all_products' => $allProducts];
    }

    public static function getAllDiscounts($products, $period, $includeDisco = false)
    {
        if (! $products->count()) {
            return;
        }

        $basePeriod = $period->basePeriod;
        $startDate = $basePeriod && $basePeriod->start_date < $period->start_date ? $basePeriod->start_date : $period->start_date;

        $lineDriveCount = $products[0]->brand->products_count;
        $discounts = [];
        foreach ($products as $product) {
            $totalDiscount = round($product->calculateCombinedPromoDiscount($period->id, optional($basePeriod)->id, $startDate, false, $includeDisco));
            if ($totalDiscount == 0) {
                continue;
            }

            $lineItem = $product->getPromoLineItem($period);
            $baseLineItem = $basePeriod ? $product->getPromoLineItem($basePeriod) : null;

            $brandDiscount = round($product->calculatePromoDiscount($period->id, $startDate, true, $includeDisco));
            $baseBrandDiscount = $basePeriod ? round($product->calculatePromoDiscount($basePeriod->id, $startDate, true)) : null;

            $plDiscount = $lineItem ? round($lineItem->pl_discount ?? 0) : null;
            $basePLDiscount = $baseLineItem ? round($baseLineItem->pl_discount ?? 0) : null;

            $oi = $lineItem ? $lineItem->oi : false;
            $baseOI = $baseLineItem ? $baseLineItem->oi : false;

            $index = "{$brandDiscount}|{$baseBrandDiscount}|{$plDiscount}|{$basePLDiscount}|{$oi}|{$baseOI}";
            if (! array_key_exists($index, $discounts)) {
                $discounts[$index] = [
                    'count' => 1,
                    'line_drive' => false,
                    'total_discount' => $totalDiscount,
                    'brand_discount' => $brandDiscount,
                    'pl_discount' => $plDiscount,
                    'oi' => $oi,
                    'base_brand_discount' => $baseBrandDiscount,
                    'base_pl_discount' => $basePLDiscount,
                    'base_oi' => $baseOI,
                ];
            } else {
                $discounts[$index]['count'] += 1;
            }
        }

        $discounts = array_values($discounts);
        if (count($discounts) == 1 && $discounts[0]['count'] >= $lineDriveCount) {
            $discounts[0]['line_drive'] = true;
        }

        return $discounts;
    }
}
