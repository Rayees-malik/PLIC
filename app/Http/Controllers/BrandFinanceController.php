<?php

namespace App\Http\Controllers;

use App\Media;
use App\Models\Brand;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

class BrandFinanceController extends Controller
{
    public function index($brand_id = null, $tab = 'payments')
    {
        if (auth()->user()->can('finance.vendor.all')) {
            $brands = Brand::ordered();
        } elseif (auth()->user()->can('finance.vendor')) {
            $brands = Brand::ordered()->withAccess();
        } else {
            abort(403);
        }

        $brands = $brands->select('id', 'brand_number', 'finance_brand_number', 'name')->get();

        if ($brands->count() == 0) {
            $url = route('brands.create');
            flash("You must first submit a brand. Please <a href=\"{$url}\">add your first brand</a>.", 'danger');

            return redirect()->route('brands.index');
        }

        if ($tab == 'admin' && auth()->user()->cannot('finance.force-upload')) {
            $tab = 'payments';
        }

        if ($brands->count() == 1) {
            $brand_id = $brands->first()->id;
        }

        if ($brand_id) {
            $brand = Brand::select('id', 'name')->findOrFail($brand_id);

            return $this->{"{$tab}Tab"}($brands, $brand);
        }

        if (auth()->user()->can('finance.force-upload')) {
            return $this->{'adminTab'}($brands, null);
        }

        $brand = null;
        $invoiceYears = null;
        $activeTab = null;
        $requiresLoad = false;

        return view('brand-finance.index', compact('brands', 'brand', 'invoiceYears', 'activeTab', 'requiresLoad'));
    }

    public function loadBrandMedia($brand, $relations)
    {
        $brandGrouped = [];
        foreach (Arr::wrap($relations) as $relation) {
            $brand->load($relation);

            $grouped = [];
            foreach ($brand->$relation as $media) {
                if (! array_key_exists($media->custom_properties['identifier'], $grouped)) {
                    $grouped[$media->custom_properties['identifier']] = [];
                }

                $grouped[$media->custom_properties['identifier']][] = $media;
            }
            $brandGrouped[$relation] = $grouped;
        }

        $brand->financeMedia = $brandGrouped;
    }

    public function paymentsTab($brands, $brand)
    {
        $this->loadBrandMedia($brand, ['mediaInvoices', 'mediaDebitMemos', 'mediaCreditRebates']);
        $invoices = $brand
            ->as400Invoices();

        $searchTerm = trim(request()->search);
        if ($searchTerm) {
            $searchTerm = "%{$searchTerm}%";
            $invoices = $invoices->where(function ($query) use ($searchTerm) {
                $query->whereRaw("lower(cheque_number) like '{$searchTerm}'")
                    ->orWhereRaw("lower(invoice_number) like '{$searchTerm}'")
                    ->orWhereRaw("lower(reference) like '{$searchTerm}'")
                    ->orWhereRaw("lower(voucher_date) like '{$searchTerm}'")
                    ->orWhereRaw("lower(invoice_date) like '{$searchTerm}'");
            });
        }

        $invoiceYears = $invoices
            ->orderBy('voucher_date', 'desc')
            ->orderBy('invoice_date', 'desc')
            ->select('*', DB::raw('year(voucher_date) as voucher_year'), DB::raw('month(voucher_date) as voucher_month'))
            ->get()
            ->groupBy('voucher_year')
            ->transform(function ($item, $k) {
                return $item->groupBy('voucher_month')
                    ->transform(function ($item, $k) {
                        return $item->groupBy('cheque_number');
                    });
            });

        $activeTab = 'payments';
        $requiresLoad = $invoiceYears->count() > 0;

        return view('brand-finance.index', compact('brands', 'brand', 'invoiceYears', 'activeTab', 'requiresLoad'));
    }

    public function filesTab($activeTab, $relation, $brands, $brand)
    {
        $searchTerm = trim(request()->search);
        $brand->load([$relation => function ($query) use ($searchTerm) {
            $query->orderBy('created_at', 'desc');

            if ($searchTerm) {
                $query->whereRaw('lower(custom_properties) like ?', ["%{$searchTerm}%"])
                    ->orWhereRaw('lower(file_name) like ?', ["%{$searchTerm}%"]);
            }
        }]);

        $invoices = $brand->as400Invoices()
            ->orderBy('invoice_date', 'desc')
            ->get()
            ->toArray();
        $invoices = array_column($invoices, null, 'invoice_number');

        foreach ($brand->$relation as $media) {
            $media->invoice = Arr::get($invoices, $media->custom_properties['identifier']);
        }

        $invoiceYears = $brand->$relation
            ->groupBy(function ($item) {
                return $item->created_at->year;
            })->transform(function ($item, $k) {
                return $item->groupBy(function ($item) {
                    return $item->created_at->month;
                })->transform(function ($item, $k) {
                    return $item->groupBy(function ($item) {
                        return $item->custom_properties['identifier'];
                    });
                });
            });

        $requiresLoad = $invoiceYears->count() > 0;

        return view('brand-finance.index', compact('brands', 'brand', 'invoiceYears', 'activeTab', 'requiresLoad'));
    }

    public function invoicesTab($brands, $brand)
    {
        return $this->filesTab('invoices', 'mediaInvoices', $brands, $brand);
    }

    public function debitmemosTab($brands, $brand)
    {
        return $this->filesTab('debitmemos', 'mediaDebitMemos', $brands, $brand);
    }

    public function rebatesTab($brands, $brand)
    {
        return $this->filesTab('rebates', 'mediaCreditRebates', $brands, $brand);
    }

    public function openapTab($brands, $brand)
    {
        $records = $brand
            ->as400OpenAP()
            ->orderBy('invoice_date', 'desc')
            ->get();

        $activeTab = 'openap';
        $requiresLoad = false;

        return view('brand-finance.index', compact('brands', 'brand', 'records', 'activeTab', 'requiresLoad'));
    }

    public function poreceivedTab($brands, $brand)
    {
        $records = $brand
            ->as400POReceived()
            ->orderBy('po_date', 'desc')
            ->get();

        $activeTab = 'poreceived';
        $requiresLoad = false;

        return view('brand-finance.index', compact('brands', 'brand', 'records', 'activeTab', 'requiresLoad'));
    }

    public function adminTab($brands, $brand)
    {
        $activeTab = 'admin';
        $requiresLoad = false;

        return view('brand-finance.index', (compact('brands', 'brand', 'activeTab', 'requiresLoad')));
    }

    public function forceUpload()
    {
        Artisan::queue('import:deductions', []);

        flash('File import process has been started.', 'success');

        return redirect()->back();
    }

    public function destroyMedia($id)
    {
        $media = Media::findOrFail($id);

        // Ensure it's a Finance file
        $fileName = $media->file_name;
        if (! in_array($media->collection_name, ['deductions_in', 'deductions_dm', 'deductions_cr'])) {
            return abort(403);
        }

        $media->delete();
        flash("Successfully deleted {$fileName}", 'success');

        return redirect()->back();
    }
}
