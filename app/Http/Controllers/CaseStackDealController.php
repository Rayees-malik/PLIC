<?php

namespace App\Http\Controllers;

use App\Http\Requests\Promos\CaseStackDealFormRequest;
use App\Models\Brand;
use App\Models\CaseStackDeal;
use App\Models\PromoPeriod;
use Illuminate\Support\Arr;

class CaseStackDealController extends Controller
{
    public function index($brandId = null)
    {
        $brands = Brand::withPending()->withAccess()->select('id', 'name')->ordered()->get();
        $brandId ??= optional($brands->first())->id;

        $promoPeriods = PromoPeriod::byOwner()
            ->active()
            ->where('type', PromoPeriod::CATALOGUE_TYPE)
            ->with(['caseStackDeals' => function ($query) use ($brandId) {
                $query->byBrand($brandId);
            }])
            ->select('id', 'name', 'start_date', 'end_date')
            ->ordered()
            ->get();

        return view('casestackdeals.index', compact('brandId', 'brands', 'promoPeriods'));
    }

    public function update(CaseStackDealFormRequest $request)
    {
        $brandId = $request->brand_id;
        foreach (Arr::get($request, 'deal', []) as $periodId => $deal) {
            $dealFR = $request->deal_fr[$periodId];

            if ($deal || $dealFR) {
                CaseStackDeal::withTrashed()->updateOrCreate(
                    ['brand_id' => $brandId, 'period_id' => $periodId],
                    ['deal' => $deal, 'deal_fr' => $dealFR, 'last_updated_by' => auth()->id(), 'deleted_at' => null],
                );
            } else {
                $caseStackDeal = CaseStackDeal::where(['brand_id' => $brandId, 'period_id' => $periodId])->select('id')->first();
                if ($caseStackDeal) {
                    $caseStackDeal->delete();
                }
            }
        }

        flash('Case stack deals updated', 'success');

        return redirect()->route('casestackdeals.index', $request->brand_id);
    }
}
