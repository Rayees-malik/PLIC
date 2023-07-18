<?php

namespace App\Traits;

use App\Actions\Signoffs\SubmitSignoffAction;
use App\Datatables\OwnedPromosDatatable;
use App\Datatables\PromoPeriodsDatatable;
use App\Datatables\PromosDatatable;
use App\DataTransferObjects\SignoffSubmitData;
use App\Helpers\SignoffStateHelper;
use App\Http\Requests\Promos\PromoFormRequest;
use App\Http\Requests\Promos\PromoPeriodFormRequest;
use App\Models\Brand;
use App\Models\Promo;
use App\Models\PromoPeriod;
use App\Models\Retailer;
use App\Models\Signoff;
use Carbon\Carbon;
use Carbon\CarbonInterval;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Config;

trait PromoOwnerController
{
    // Helper Methods
    public function getModel($ownerId)
    {
        return null;
    }

    public function checkLookups($brands, $periods)
    {
        if ($brands && $brands->count() == 0) {
            flash('You must submit a brand before you can submit a promotion', 'danger');

            return false;
        }
        if ($periods && $periods->count() == 0) {
            flash('There are no open promo periods, please try again later', 'danger');

            return false;
        }

        return true;
    }

    public function getPromoConfig($owner)
    {
        return $owner ? Arr::get(Config::get('retailer-promos'), $owner->id) : null;
    }

    // Promos
    public function promosIndex(Request $request)
    {
        $ownerId = $request->ownerId;
        $owner = $this->getModel($ownerId);

        $promos = Promo::byOwner($owner)->withAccess()
            ->join('brands', 'brands.id', 'promos.brand_id')
            ->join('promo_periods', 'promo_periods.id', 'promos.period_id')
            ->select(
                'promo_periods.name as promo_periods.name',
                'promos.id',
                'promos.name',
                'brands.id as brands.id',
                'brands.name as brands.name',
                'promo_periods.id as promo_periods.id',
                'promo_periods.name as promo_periods.name',
                'promo_periods.start_date as promo_periods.start_date',
                'promo_periods.end_date as promo_periods.end_date',
                'promo_periods.active as promo_periods.active'
            );

        $brands = Brand::withAccess()->active()->select('id', 'name')->ordered()->get();
        $periods = PromoPeriod::byOwner()->select('id', 'name')->ordered()->get();
        $retailers = collect([]);

        $datatable = new PromosDatatable($promos);

        return $datatable->render('promos.index', compact('owner', 'brands', 'retailers', 'periods'));
    }

    public function promosOwnedIndex(Request $request)
    {
        $tableHeader = 'Retailer Promos';
        $owner = null;

        // TODO: remove hardcoded retailers
        $promos = Promo::owned()->withAccess()
            ->join('brands', 'brands.id', 'promos.brand_id')
            ->join('promo_periods', 'promo_periods.id', 'promos.period_id')
            ->join('retailers', 'retailers.id', 'promo_periods.owner_id')
            ->select(
                'promo_periods.name as promo_periods.name',
                'promo_periods.active as promo_periods.active',
                'promos.id',
                'promos.name',
                'brands.id as brands.id',
                'brands.name as brands.name',
                'promo_periods.name as promo_periods.name',
                'promo_periods.start_date as promo_periods.start_date',
                'promo_periods.end_date as promo_periods.end_date',
                'retailers.id as retailers.id',
                'retailers.name as retailers.name'
            );

        Promo::whereHas('period', function ($query) {
            $query->whereNull('owner_id');
        })->count();

        $brands = Brand::withAccess()->active()->select('id', 'name')->ordered()->get();
        $retailers = Retailer::where('allow_promos', true)->select('id', 'name')->ordered()->get();
        if (count($retailers)) {
            $owner = $retailers[0];
        }

        $periods = collect([]);

        $datatable = new OwnedPromosDatatable($promos);

        return $datatable->render('promos.index', compact('tableHeader', 'owner', 'brands', 'retailers', 'periods'));
    }

    public function promosCreate(Request $request)
    {
        $ownerId = $request->ownerId;
        $owner = $this->getModel($ownerId);

        extract(Promo::loadLookups(null, $owner));
        if (! $this->checkLookups($brands, $periods)) {
            return redirect()->back();
        }

        $model = new Promo;

        return view('promos.add', compact(Promo::getLookupVariables(), 'model', 'owner'));
    }

    public function promosStore(PromoFormRequest $request, SubmitSignoffAction $submitSignoffAction)
    {
        $ownerId = $request->ownerId;
        $owner = $this->getModel($ownerId);

        $formData = $request->partialValidated()->validated;
        $brand = Brand::withPending()->select('id', 'name')->findOrFail(Arr::get($formData, 'brand_id'));
        $period = PromoPeriod::byOwner($owner)->select('id', 'name', 'start_date', 'end_date')->findOrFail(Arr::get($formData, 'period_id'));

        $ownerName = $owner ? "{$owner->name} - " : '';
        $formData['name'] = "{$ownerName}{$brand->name} - {$period->name}";
        $formData['submitted_by'] = auth()->id();
        $promo = Promo::startSignoff($formData);
        $promo->extraUpdates($request);

        if (! $promo->formErrors->allBagsEmpty()) {
            $route = $owner ? redirect()->route("{$owner->routePrefix}.promos.edit", ['id' => $promo->id, 'ownerId' => $owner->id]) : redirect()->route('promos.edit', $promo->id);

            return $route->withInput()->with(['errors' => $promo->formErrors]);
        }

        $action = $request->input('action');
        if ($action === 'save') {
            flash('Promo has been saved.', 'success');

            return $owner ? redirect()->route("{$owner->routePrefix}.promos.edit", ['id' => $promo->id, 'ownerId' => $owner->id]) : redirect()->route('promos.edit', $promo->id);
        } else {
            if (! $owner) {
                $promo->setDefaultPLDiscount();
            }

            $promo = $promo->submitSignoff();

            $signoffSubmitData = SignoffSubmitData::fromRequest($request);
            $signoffSubmitData = $signoffSubmitData->forSignoff($promo->signoff);
            $submitSignoffAction->execute($signoffSubmitData);

            flash('Successfully submitted promo for approval.', 'success');

            return $owner ? redirect()->route("{$owner->routePrefix}.promos.index", $owner->id) : redirect()->route('promos.index');
        }
    }

    public function promosEdit(Request $request)
    {
        $id = $request->id;
        $ownerId = $request->ownerId;

        $owner = $this->getModel($ownerId);

        extract(Promo::getPreloadedModel($id));
        if (! $this->checkLookups($brands, $periods)) {
            return redirect()->back();
        }

        if (! $model->period->active && auth()->user()->isVendor) {
            flash('Cannot edit a promo for a period that is already closed.', 'danger');

            return $owner ? redirect()->route("{$owner->routePrefix}.promos.index", $owner->id) : redirect()->route('promos.index');
        }

        if ($model->isCompletedProposed) {
            return $owner ? redirect()->route("{$owner->routePrefix}.promos.edit", ['id' => $model->signoff->initial_id, 'ownerId' => $owner->id]) : redirect()->route('promos.edit', $model->signoff->initial_id);
        }

        $proposed = $model->getProposedForUser();
        if ($proposed) {
            return $owner ? redirect()->route("{$owner->routePrefix}.promos.edit", ['id' => $proposed, 'ownerId' => $owner->id]) : redirect()->route('promos.edit', $proposed);
        }

        return view('promos.edit', compact('model', 'owner', Promo::getLookupVariables()));
    }

    public function promosCopy($id)
    {
        $model = Promo::allStates()->withAccess()->withEagerLoadedRelations()->findOrFail($id);
        $newPeriod = PromoPeriod::active()
            ->where('owner_id', $model->period->owner_id)
            ->where('owner_type', $model->period->owner_type)
            ->whereDoesntHave('promos', function ($query) use ($model) {
                $query->withPending()->where('brand_id', $model->id);
            })
            ->select('id')
            ->ordered()
            ->first();

        if (! $newPeriod) {
            flash('There are no available promo periods to copy the promo to.', 'danger');

            return redirect()->back();
        }

        $model->period_id = $newPeriod->id;
        $clone = $model->createCopy();

        return redirect()->route('promos.edit', $clone->id);
    }

    public function promosUpdate(PromoFormRequest $request, SubmitSignoffAction $submitSignoffAction)
    {
        $id = $request->id;
        $promo = Promo::with(['period.owner' => function ($query) {
            $query->select('id', 'name');
        }])->allStates()->withAccess()->findOrFail($id);

        $owner = $promo->period->owner;

        if (! $promo->validRequest($request)) {
            flash($promo->INVALID_REQUEST_ERROR, 'danger');

            return $owner ? redirect()->route("{$owner->routePrefix}.promos.index", $owner->id) : redirect()->route('promos.index');
        }

        if (! $promo->canUpdate) {
            flash($promo->UPDATE_PENDING_ERROR, 'danger');

            return $owner ? redirect()->route("{$owner->routePrefix}.promos.index", $owner->id) : redirect()->route('promos.index');
        }

        if (! $promo->period->active && auth()->user()->isVendor) {
            flash('Cannot edit a promo for a period that is already closed.', 'danger');

            return $owner ? redirect()->route("{$owner->routePrefix}.promos.index", $owner->id) : redirect()->route('promos.index');
        }

        $formData = $request->partialValidated()->validated;

        if (Arr::exists($formData, 'period_id') && $promo->period_id !== $formData['period_id']) {
            $ownerName = $owner ? "{$owner->name} - " : '';
            $brand = Brand::withPending()->select('id', 'name')->findOrFail($promo->brand_id);
            $period = PromoPeriod::select('id', 'name', 'start_date', 'end_date')->findOrFail($formData['period_id']);
            $formData['name'] = "{$ownerName}{$brand->name} - {$period->name}";
        }

        $promo->update($formData);
        if ($promo->{$promo->stateField()} == SignoffStateHelper::INITIAL) {
            $promo = $promo->getLastProposed();
        }
        $promo->extraUpdates($request);

        if (! $promo->formErrors->allBagsEmpty()) {
            $route = $owner ? redirect()->route("{$owner->routePrefix}.promos.edit", ['id' => $promo->id, 'ownerId' => $owner->id]) : redirect()->route('promos.edit', $promo->id);

            return $route->withInput()->with(['errors' => $promo->formErrors]);
        }

        $action = $request->input('action');
        if ($action === 'save') {
            flash('Promo has been saved.', 'success');

            return $owner ? redirect()->route("{$owner->routePrefix}.promos.edit", ['id' => $promo->id, 'ownerId' => $owner->id]) : redirect()->route('promos.edit', $promo->id);
        } else {
            if (! $owner && $promo->isNewSubmission) {
                $promo->setDefaultPLDiscount();
            }

            $promo = $promo->submitSignoff();

            $signoffSubmitData = SignoffSubmitData::fromRequest($request);
            $signoffSubmitData = $signoffSubmitData->forSignoff($promo->signoff);
            $submitSignoffAction->execute($signoffSubmitData);

            flash('Successfully submitted promo for approval.', 'success');

            return $owner ? redirect()->route("{$owner->routePrefix}.promos.index", $owner->id) : redirect()->route('promos.index');
        }
    }

    public function promosRenderProductsTable(Request $request)
    {
        $period = PromoPeriod::with(['owner' => function ($query) {
            $query->select('id');
        }])->findOrFail($request->periodId);
        $owner = $period->owner;
        $promoConfig = $this->getPromoConfig($owner);

        $brandId = $request->brandId;
        $brand = Brand::with(['currency' => function ($query) {
            $query->select('id', 'exchange_rate');
        }])->select('id', 'currency_id', 'default_pl_discount')->findOrFail($brandId);

        $model = new Promo;
        $model->dollar_discount = $request->dollarDiscount;

        $basePeriodId = $period->base_period_id;
        $categories = Promo::getPromoProductsForBrand($brandId, $period);

        return view('promos.product-promo-table', compact('brand', 'categories', 'model', 'basePeriodId', 'promoConfig'));
    }

    public function promosShow(Request $request)
    {
        $id = $request->id;
        $model = Promo::allStates()
            ->withAccess()
            ->with([
                'brand' => function ($query) {
                    $query->select('id', 'name', 'allow_oi');
                },
                'period' => function ($query) {
                    $query->with(['owner' => function ($query) {
                        $query->select('id', 'name');
                    }]);
                },
            ])
            ->findOrFail($id);

        $promoConfig = $this->getPromoConfig($model->period->owner);
        $basePeriodId = $model->period->base_period_id;
        $categories = Promo::getPromoProductsForBrand($model->brand_id, $model->period, $model);

        return view('promos.show', compact('model', 'promoConfig', 'categories', 'basePeriodId'));
    }

    // public function promosDestroy(Request $request)
    // {
    //     $id = $request->id;
    //     $ownerId = $request->ownerId;
    //     $owner = $this->getModel($ownerId);
    //     $promo = Promo::allStates()->findOrFail($id)->delete();

    //     flash('Successfully deleted promo', 'success');
    //     return $owner ? redirect()->route("{$owner->routePrefix}.promos.index", $owner->id) : redirect()->route('promos.index');
    // }

    // Promo Periods
    public function promoPeriodsIndex(Request $request)
    {
        return $this->renderPromoPeriodsIndex(false, $this->getModel($request->ownerId));
    }

    public function promoPeriodsInactive(Request $request)
    {
        return $this->renderPromoPeriodsIndex(true, $this->getModel($request->ownerId));
    }

    public function renderPromoPeriodsIndex($inactive = false, $owner = null)
    {
        if ($inactive) {
            $periods = PromoPeriod::inactive();
            $selected = 'inactive';
            $reverse = true;
        } else {
            $periods = PromoPeriod::active();
            $selected = 'active';
            $reverse = false;
        }

        $periods->byOwner($owner)->select(['id', 'name', 'start_date', 'end_date', 'type', 'active']);
        $datatable = new PromoPeriodsDatatable($periods, $reverse);

        return $datatable->render('promos.periods.index', compact('selected', 'owner'));
    }

    public function promoPeriodsGenerate(Request $request)
    {
        $owner = $this->getModel($request->ownerId);
        PromoPeriod::generatePeriods($owner);
        $year = Carbon::now()->add(CarbonInterval::year())->format('Y');

        flash("Successfully created promo periods for year {$year}", 'success');

        return $owner ? redirect()->route("{$owner->routePrefix}.promos.periods.index", $owner->id) : redirect()->route('promos.periods.index');
    }

    public function promoPeriodsCreate(Request $request)
    {
        $owner = $this->getModel($request->ownerId);
        $basePeriods = PromoPeriod::byOwner($owner)->catalogue()->ordered()->get();
        $model = new PromoPeriod;

        return view('promos.periods.add', compact('owner', 'basePeriods', 'model'));
    }

    public function promoPeriodsStore(PromoPeriodFormRequest $request)
    {
        $ownerId = $request->ownerId;
        $owner = $this->getModel($ownerId);

        $validated = $request->validated();
        $period = PromoPeriod::create($validated);

        if ($owner) {
            $period->owner()->associate($owner)->save();
        }

        flash("Successfully created promo period {$period->name}", 'success');

        return $owner ? redirect()->route("{$owner->routePrefix}.promos.periods.index", $owner->id) : redirect()->route('promos.periods.index');
    }

    public function promoPeriodsEdit(Request $request)
    {
        $id = $request->id;
        $ownerId = $request->ownerId;
        $owner = $this->getModel($ownerId);

        $model = PromoPeriod::findOrFail($id);
        $basePeriods = PromoPeriod::byOwner($owner)->catalogue()->ordered()->get();

        return view('promos.periods.edit', compact('owner', 'model', 'basePeriods'));
    }

    public function promoPeriodsToggleActive(Request $request)
    {
        $period = PromoPeriod::findOrFail($request->id);
        $period->active = ! $period->active;
        $period->save();

        if (! $period->active) {
            $promoIds = Promo::allStates()->where('period_id', $period->id)->pluck('id')->toArray();
            Signoff::inProgress()->whereIn('initial_id', $promoIds)->where('initial_type', \App\Models\Promo::class)->update(['state' => SignoffStateHelper::ARCHIVED]);
        }
    }

    public function promoPeriodsUpdate(PromoPeriodFormRequest $request)
    {
        $id = $request->id;
        $period = PromoPeriod::with(['owner' => function ($query) {
            $query->select('id');
        }])->findOrFail($id);
        $period->update($request->validated());

        flash("Successfully updated promo period {$period->name}", 'success');

        return $period->owner ? redirect()->route("{$period->owner->routePrefix}.promos.periods.index", $period->owner->id) : redirect()->route('promos.periods.index');
    }

    public function promoPeriodsRenderSelect(Request $request)
    {
        $owner = $this->getModel($request->ownerId);
        $brandId = $request->brandId;

        $periods = PromoPeriod::active()
            ->byOwner($owner)
            ->whereDoesntHave('promos', function ($query) use ($brandId) {
                $query->withPending()->where('brand_id', $brandId);
            })
            ->select('id', 'name', 'start_date', 'end_date', 'base_period_id', 'type')
            ->ordered()
            ->get();

        return view('promos.period-select', compact('periods'));
    }
}
