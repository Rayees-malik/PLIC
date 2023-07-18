<?php

namespace App\Http\Controllers;

use App\Exports\MarketingAgreementChargeBackExport;
use App\Exports\MarketingAgreementJournalExport;
use App\Exports\PricingAdjustmentUploadExport;
use App\Exports\PricingAdjustmentUploadExportWithMCB;
use App\Exports\PrintableInventoryRemovalExport;
use App\Helpers\ZipHelper;
use App\Media;
use App\Models\Brand;
use App\Models\Product;
use App\Models\PromoPeriod;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class ExportController extends Controller
{
    public function index()
    {
        $brands = Brand::withAccess()->active()->ordered()->select('id', 'name')->get();
        $promoPeriods = PromoPeriod::byOwner()
            ->sinceMonthsAgo(12)
            ->select('id', 'name', 'start_date', 'end_date')
            ->ordered()
            ->get();

        $exports = collect(config('plic.exports'))
            ->map(function ($group) {
                return collect($group)
                    ->when(auth()->user()->isVendor, function ($collection) {
                        return $collection->filter(function ($item, $key) {
                            return ! is_string($item) || (isset($item['vendor_accessible']) && $item['vendor_accessible'] == true);
                        });
                    })
                    ->map(function ($export) {
                        return is_string($export) ? $export : $export['class'];
                    })->toArray();
            })
            ->filter()
            ->toArray();

        return view('exports.index', [
            'exports' => $exports,
            'brands' => $brands,
            'promoPeriods' => $promoPeriods,
        ]);
    }

    public function export($name, Request $request)
    {
        $exports = collect(config('plic.exports'))
            ->map(function ($group) {
                return collect($group)
                    ->when(auth()->user()->isVendor, function ($collection) {
                        return $collection->filter(function ($item, $key) {
                            return ! is_string($item) || (isset($item['vendor_accessible']) && $item['vendor_accessible'] == true);
                        });
                    })
                    ->map(function ($export) {
                        return is_string($export) ? $export : $export['class'];
                    })->toArray();
            })
            ->filter()
            ->collapse();

        $exportClass = $exports[$name];
        $export = new $exportClass;

        return $export->export($request);
    }

    public function listingFormIndex()
    {
        return view('exports.listingforms.index', [
            'forms' => array_keys(config('plic.listing_forms')),
        ]);
    }

    public function listingFormExport(Request $request)
    {
        $this->validate($request, [
            'retailer' => 'required',
            'stock_ids' => 'required',
        ]);

        $retailer = $request->retailer;
        $includeNonCatalogue = $request->include_noncatalogue == 1;

        $formClass = Arr::get(config('plic.listing_forms'), $retailer, null);
        abort_if(! $formClass, 404);

        $stockIds = explode(' ', preg_replace('/\ +/', ' ', preg_replace('/[^A-Za-z0-9\ ]/', ' ', $request->stock_ids)));

        return (new $formClass)->export($stockIds, $includeNonCatalogue);
    }

    // Special Exports
    public function pafUpload($id)
    {
        $export = new PricingAdjustmentUploadExport;

        return $export->export($id);
    }

    // Special Exports
    public function pafUploadWithMcb($id)
    {
        $export = new PricingAdjustmentUploadExportWithMCB;

        return $export->export($id);
    }

    public function mafJournal($id)
    {
        $export = new MarketingAgreementJournalExport;

        return $export->export($id);
    }

    public function mafChargeBack($id, $brandId)
    {
        $export = new MarketingAgreementChargeBackExport;

        return $export->export($id, $brandId);
    }

    public function printableInventoryRemoval($id)
    {
        $export = new PrintableInventoryRemovalExport;

        return $export->export($id);
    }

    public function brandImages($id)
    {
        // Confirm permissions
        $brand = Brand::withAccess()->select('id', 'name')->findOrFail($id);

        $media = Media::with(['model' => function ($query) {
            $query->select('id', 'stock_id');
        }])->whereHasMorph('model', Product::class, function ($query) use ($id) {
            $query->where('brand_id', $id);
        })->where('collection_name', 'product')->get();

        $files = [];
        foreach ($media as $file) {
            $extension = pathinfo($file->file_name, PATHINFO_EXTENSION);
            $files["{$file->model->stock_id}.{$extension}"] = $file->getPath();
        }

        $zipFile = ZipHelper::zipFiles($files, true);

        $brandSlug = Str::slug($brand->name, '_');

        return ZipHelper::download($zipFile, "{$brandSlug}_images.zip");
    }
}
