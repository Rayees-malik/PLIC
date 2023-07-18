<?php

namespace App\Imports;

use App\Helpers\SignoffStateHelper;
use App\Models\Product;
use App\Models\Signoff;
use App\Models\SignoffResponse;
use App\User;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class PricingUpdateImport
{
    public static function import($file, User $submitter)
    {
        $reader = IOFactory::createReaderForFile($file);
        $reader->setReadDataOnly(true);
        $spreadsheet = $reader->load($file);

        $importData = $spreadsheet->getActiveSheet()->toArray(null, true, true, false);
        unset($importData[0]);

        $errors = [];
        $recordsProcessed = 0;

        foreach ($importData as $row) {
            [$stockId, $newPrice, $reason, $changeDate, $currency, $comment] = $row;

            if (! $stockId) {
                // skip empty rows
                continue;
            }

            $product = Product::with('as400Pricing', 'brand', 'brand.currency', 'brand.as400Freight', 'brand.as400Margin')
                ->whereHas('as400Pricing', function ($query) {
                    $query->where('po_price', '>', 0);
                })
                ->where('stock_id', $stockId)
                ->first();

            if (! $product) {
                $errors[] = [
                    $stockId,
                    $newPrice,
                    $reason,
                    $changeDate,
                    $currency,
                    $comment,
                    "Product with stock id {$stockId} not found",
                ];

                continue;
            }

            if ($newPrice == 0) {
                $errors[] = [
                    $stockId,
                    0.00,
                    $reason,
                    $changeDate,
                    $currency,
                    $comment,
                    "Cannot update price to {$newPrice}",
                ];

                continue;
            }

            if (! $product->canUpdate) {
                $recordsProcessed++;

                $errors[] = [
                    $stockId,
                    $newPrice,
                    $reason,
                    $changeDate,
                    $currency,
                    $comment,
                    'Product is locked for updates',
                ];

                continue;
            }

            $proposed = $product->duplicate();
            $extraAddonPercent = $product->as400Pricing->extra_addon_percent;

            $prices = PricingUpdateImport::calculatePrices($proposed, $extraAddonPercent, $newPrice);

            $proposed->update([
                'unit_cost' => $newPrice,
                'price_change_date' => Date::excelToDateTimeObject($changeDate),
                'price_change_reason' => $reason,
                'stock_id' => $stockId,
                'extra_addon_percent' => $extraAddonPercent,
                'landed_cost' => $prices['landed_cost'],
                'wholesale_price' => $prices['wholesale_price'],
                'suggested_retail_price' => $prices['srp'],
            ]);

            $signoff = Signoff::startNewSignoff($product, $proposed, auth()->user());

            $signoff->submitted_at = now();
            $signoff->step = 3;
            $signoff->state = SignoffStateHelper::PENDING;
            $signoff->save();

            $comment = "Bulk price update: Submitted by {$submitter->name}; {$comment}";

            SignoffResponse::saveResponse($signoff, true, $comment, true);

            $recordsProcessed++;
        }

        $flashMessage = "Processed {$recordsProcessed} records; " . count($errors) . ' failed.';

        if (count($errors) > 0) {
            $filename = 'PricingUpdateImport_' . auth()->id() . '_' . now()->format('Ymdhis') . '.xlsx';

            $header = ['Stock ID', 'New Price', 'Reason', 'Change Date', 'Currency', 'Comment', 'Error'];

            $spreadsheet = new Spreadsheet;
            $sheet = $spreadsheet->getActiveSheet();
            $sheet->fromArray($header, null, 'A1');
            $sheet->fromArray($errors, null, 'A2');

            $writer = new Xlsx($spreadsheet);

            if (! Storage::disk('reports')->exists('imports')) {
                Storage::disk('reports')->makeDirectory('imports');
            }

            $writer->save(storage_path('app/reports/imports/' . $filename));

            $flashMessage .= ' See <a href="' . asset('reports/imports/' . $filename) . '">exception report</a> for details.';
        }

        flash($flashMessage, 'success');

        return redirect()->route('imports.index');
    }

    private static function calculatePrices(Product $product, $extraAddonPercent, $newPrice)
    {
        $extraAddonPercent = 1 + $extraAddonPercent / 100;

        $exchangeRate = $product->brand->currency->exchange_rate;
        $freightPercent = 1 + (optional($product->brand->as400Freight)->freight ?? 0) / 100;
        $dutyPercent = 1 + (optional($product->as400Pricing)->duty ?? 0) / 100;
        $edlp = 1 - (optional($product->as400Pricing)->edlp_discount ?? 0.00) / 100;
        $margin = 1 - optional($product->brand->as400Margin ?? 0)->margin / 100;
        $cost = $newPrice;

        if ($product->purity_sell_by_unit == 2) {
            $sellByCount = $product->inner_units;
        } elseif ($product->purity_sell_by_unit == 4) {
            $sellByCount = $product->master_units;
        } else {
            $sellByCount = 1;
        }

        $landedCost = round($cost * $exchangeRate * $extraAddonPercent * $freightPercent * $dutyPercent * $edlp, 2);

        $wholesalePrice = round($landedCost / $margin, 2);
        $srp = $sellByCount ? max([round(((round(($wholesalePrice / $sellByCount) / 0.06) * 10) - 1) / 100, 2), 0]) : 'N/A';

        return [
            'cost' => $cost,
            'landed_cost' => $landedCost,
            'wholesale_price' => $wholesalePrice,
            'srp' => $srp,
            'margin' => $margin,
            'extra_addon_percent' => $extraAddonPercent,
            'exchange_rate' => $exchangeRate,
            'freight_percent' => $freightPercent,
            'duty_percent' => $dutyPercent,
            'edlp' => $edlp,
            'sell_by_count' => $sellByCount,
        ];
    }
}
