<?php

namespace App\Http\Controllers;

use App\Actions\Signoffs\ProcessSignoffAction;
use App\Actions\Signoffs\UpdateSignoffStepAction;
use App\Datatables\SignoffsDatatable;
use App\DataTransferObjects\SignoffStepData;
use App\Exports\BulkWebseriesExport;
use App\Http\Requests\Signoffs\FinanceBulkSignoffFormRequest;
use App\Http\Requests\Signoffs\ManagementBulkSignoffFormRequest;
use App\Http\Requests\Signoffs\UpdateSignoffFormRequest;
use App\Http\Requests\Signoffs\WebseriesBulkSignoffFormRequest;
use App\Models\Signoff;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SignoffController extends Controller
{
    const WEBSERIES_STEP = 5;

    const MANAGEMENT_STEP = 4;

    const FINANCE_STEP = 3;

    public function index()
    {
        $datatable = new SignoffsDatatable;

        $signoffs = Signoff::pending()->forUser()->select('proposed_type')->groupBy('proposed_type')->get();

        $signoffTypes = [];
        foreach ($signoffs as $signoff) {
            $type = explode('\\', $signoff->proposed_type);
            $signoffTypes[$signoff->proposed_type] = Str::of(end($type))->snake()->replace('_', ' ')->title();
        }

        return $datatable->render('signoffs.index', compact('datatable', 'signoffTypes'));
    }

    public function show($id)
    {
        // TODO: Rework this to show a proper view for signoffs in progress
        $signoff = Signoff::findOrFail($id);

        return redirect()->route($signoff->signoffConfig->show_route, [$signoff->proposed_id]);

        // $modelClass = $signoff->proposed_type;
        // $preloaded = null;
        // if (method_exists($modelClass, 'getPreloadedModel')) {
        //     $preloaded = $modelClass::getPreloadedModel($signoff->proposed_id, 'history');
        //     extract($preloaded);
        //     $lookupVariables = $modelClass::getLookupVariables();
        // } else {
        //     $model = $modelClass::pending()->findOrFail($signoff->proposed_id);
        //     $lookupVariables = [];
        // }

        // $changes = $model->GetAllChanges($preloaded);
        // return view('signoffs.show', compact('signoff', 'model', 'changes'))->with(compact($lookupVariables));
    }

    public function edit($id)
    {
        $signoff = Signoff::with(['user', 'responses.user'])->findOrFail($id);
        if (! $signoff || $signoff->approved || $signoff->rejected) {
            return redirect()->route('signoffs.show', [$id]);
        }

        $modelClass = $signoff->proposed_type;
        if (method_exists($modelClass, 'getPreloadedModel')) {
            $preloaded = $modelClass::getPreloadedModel($signoff->proposed_id, 'history');
            extract($preloaded);
            $lookupVariables = $modelClass::getLookupVariables();
        } else {
            $model = $modelClass::pending()->findOrFail($signoff->proposed_id);
            $lookupVariables = [];
        }

        $changes = $model->GetAllChanges($preloaded);
        $userResponse = $signoff->getUserResponse();

        return view('signoffs.review', compact('signoff', 'model', 'changes', 'userResponse'))->with(compact($lookupVariables));
    }

    public function update(
        $id,
        UpdateSignoffFormRequest $request,
        UpdateSignoffStepAction $updateSignoffStepAction
    ) {
        $action = $request->input('action');
        $step = $request->input('signoff_step');
        $comment = $request->input('signoff_comment');

        if ($action == 'reject' && empty($comment)) {
            flash('You must include a comment when rejecting.', 'warning');

            return redirect()->back();
        }

        $signoffStepData = SignoffStepData::fromRequest($request);

        $signoff = Signoff::forUser()->with('proposed')->where('step', $step)->find($id);

        if (! $signoff) {
            flash('Requested signoff not found.', 'warning');

            return redirect()->route('signoffs.index');
        }

        $signoffStepData = $signoffStepData->forSignoff($signoff);

        $response = $updateSignoffStepAction->execute($signoffStepData, $request);

        return $response ?? redirect()->route('signoffs.index');
    }

    public function destroy($id)
    {
        Signoff::where('user_id', auth()->id())->findOrFail($id)->delete();

        flash('Successfully deleted submission.', 'success');

        return redirect()->back();
    }

    public function management()
    {
        $brands = Signoff::pending()
            ->forUser()
            ->where(['proposed_type' => \App\Models\Product::class, 'step' => 4])
            ->whereDoesntHave('responses', function ($query) {
                // This check is duplicated from forUser() to also have it apply
                // with admins
                $query->where('user_id', auth()->id())
                    ->where('archived', false)
                    ->whereRaw('signoff_responses.step = signoffs.step');
            })
            ->with(['proposed' => function ($query) {
                $query->with(['brand' => function ($query) {
                    $query->select('id', 'name');
                }])->select('id', 'brand_id');
            }])
            ->select('id', 'proposed_type', 'proposed_id')
            ->get()
            ->sortBy('proposed.brand.name')
            ->groupBy('proposed.brand.name');

        return view('signoffs.management', compact('brands'));
    }

    public function managementReview($brandId)
    {
        $allBrands = false;
        if ($brandId == 'all') {
            $brandId = null;
            $allBrands = true;
        }

        $signoffs = Signoff::pending()
            ->forUser()
            ->where(['proposed_type' => \App\Models\Product::class, 'step' => static::MANAGEMENT_STEP])
            ->with(
                [
                    'proposed' => function ($query) use ($brandId) {
                        $query->with(['brand' => function ($query) {
                            $query->with(['as400Freight', 'currency'])->select('id', 'name', 'currency_id');
                        }])->when($brandId, function ($query) use ($brandId) {
                            $query->where('brand_id', $brandId);
                        });
                    },
                    'initial' => function ($query) {
                        $query->with('as400Pricing');
                    },
                ]
            )
            ->when($brandId, function ($query) use ($brandId) {
                $query->whereHasMorph('proposed', \App\Models\Product::class, function ($query) use ($brandId) {
                    $query->allStates()->where('brand_id', $brandId);
                });
            })
            ->whereDoesntHave('responses', function ($query) {
                // This check is duplicated from forUser() to also have it apply
                // with admins
                $query->where('user_id', auth()->id())
                    ->where('archived', false)
                    ->whereRaw('signoff_responses.step = signoffs.step');
            })
            ->get()
            ->sortBy(['proposed.brand.name', 'proposed.name']);

        if (! $signoffs->count()) {
            flash('Selected brand has no products requiring approval.', 'danger');

            return redirect()->route('signoffs.management');
        }

        return view('signoffs.management-review', compact('signoffs', 'allBrands'));
    }

    public function managementUpdate(
        ManagementBulkSignoffFormRequest $request,
        ProcessSignoffAction $processSignoffAction
    ) {
        if (! $request->selected) {
            flash('You must select at least one product for bulk approval.', 'warning');

            return redirect()->back();
        }

        $signoffs = Signoff::pending()
            ->forUser()
            ->with(['initial', 'proposed'])
            ->whereIn('id', array_keys($request->selected))
            ->get();

        $approved = $request->input('action') === 'approve';
        $comment = $request->signoff_comment ?? ($approved ? 'Bulk Price Approval' : 'Bulk Price Rejection');

        foreach ($signoffs as $signoff) {
            $signoffStepData = new SignoffStepData([
                'action' => $request->action,
                'comment' => $comment,
                'step' => static::MANAGEMENT_STEP,
                'user' => auth()->user(),
            ]);

            $signoffStepData = $signoffStepData->forSignoff($signoff);

            $processSignoffAction->execute($signoffStepData, $request);

            if ($approved) {
                flash('Approval submitted for bulk product signoff(s).', 'success');
            } else {
                flash('Rejection submitted for bulk product signoff(s).', 'warning');
            }
        }

        return redirect()->route('signoffs.management');
    }

    public function financeReview()
    {
        $signoffs = Signoff::pending()
            ->forUser()
            ->where(['proposed_type' => \App\Models\Product::class, 'step' => static::FINANCE_STEP])
            ->with(
                [
                    'proposed' => function ($query) {
                        $query->with(['brand' => function ($query) {
                            $query->with(['as400Freight', 'currency'])->select('id', 'name', 'currency_id');
                        }]);
                    },
                    'initial' => function ($query) {
                        $query->with(['as400Pricing', 'futureLandedCosts']);
                    },
                ]
            )
            ->whereDoesntHave('responses', function ($query) {
                // This check is duplicated from forUser() to also have it apply
                // with admins
                $query->where('user_id', auth()->id())
                    ->where('archived', false)
                    ->whereRaw('signoff_responses.step = signoffs.step');
            })
            ->whereDoesntHave('responses', function ($query) {
                $query->whereRaw('signoff_responses.step = 1');
            })
            ->get()
            ->sortBy(['proposed.brand.name', 'proposed.name']);

        if (! $signoffs->count()) {
            flash('There are no products requiring approval.', 'danger');

            return redirect()->route('signoffs.index');
        }

        return view('signoffs.finance-review', compact('signoffs'));
    }

    public function financeUpdate(
        FinanceBulkSignoffFormRequest $request,
        ProcessSignoffAction $processSignoffAction
    ) {
        if (! $request->selected) {
            flash('You must select at least one product for bulk approval.', 'warning');

            return redirect()->back();
        }

        $signoffs = Signoff::pending()
            ->forUser()
            ->with(['initial', 'proposed'])
            ->whereIn('id', array_keys($request->selected))
            ->get();

        $approved = $request->input('action') === 'approve';
        $comment = $request->signoff_comment ?? ($approved ? 'Bulk Price Approval' : 'Bulk Price Rejection');

        foreach ($signoffs as $signoff) {
            $signoffStepData = new SignoffStepData([
                'action' => $request->action,
                'comment' => $comment,
                'step' => static::FINANCE_STEP,
                'user' => auth()->user(),
            ]);

            $signoffStepData = $signoffStepData->forSignoff($signoff);
            $processSignoffAction->execute($signoffStepData, $request);

            if ($approved) {
                flash('Approval submitted for bulk product signoff(s).', 'success');
            } else {
                flash('Rejection submitted for bulk product signoff(s).', 'warning');
            }
        }

        return redirect()->route('signoffs.finance.review');
    }

    public function webseriesReview()
    {
        $signoffs = Signoff::pending()
            ->forUser()
            ->where(['proposed_type' => \App\Models\Product::class, 'step' => static::WEBSERIES_STEP])
            ->with(
                [
                    'proposed' => function ($query) {
                        $query->with(['brand' => function ($query) {
                            $query->with(['as400Freight', 'currency'])->select('id', 'name', 'currency_id');
                        }]);
                    },
                    'initial' => function ($query) {
                        $query->with(['as400Pricing', 'futureLandedCosts']);
                    },
                ]
            )
            ->whereDoesntHave('responses', function ($query) {
                // This check is duplicated from forUser() to also have it apply
                // with admins
                $query->where('user_id', auth()->id())
                    ->where('archived', false)
                    ->whereRaw('signoff_responses.step = signoffs.step');
            })
            ->get()
            ->sortBy(['proposed.brand.name', 'proposed.name']);

        if (! $signoffs->count()) {
            flash('There are no products requiring approval.', 'danger');

            return redirect()->route('signoffs.index');
        }

        return view('signoffs.webseries-review', compact('signoffs'));
    }

    public function webseriesBulkExport(Request $request)
    {
        if (! $request->selected) {
            flash('You must select at least one product for bulk approval.', 'warning');

            return redirect()->back();
        }

        $export = new BulkWebseriesExport;

        return $export->export($request);
    }

    public function webseriesUpdate(
        WebseriesBulkSignoffFormRequest $request,
        ProcessSignoffAction $processSignoffAction
    ) {
        if (! $request->selected) {
            flash('You must select at least one product for bulk approval.', 'warning');

            return redirect()->back();
        }

        if ($request->input('action') === 'export') {
            $export = new BulkWebseriesExport;

            return $export->export($request);
        }

        $signoffs = Signoff::pending()
            ->forUser()
            ->with(['initial', 'proposed'])
            ->whereIn('id', array_keys($request->selected))
            ->get();

        $approved = $request->input('action') === 'approve';
        $comment = $request->signoff_comment ?? ($approved ? 'Bulk Webseries Approval' : 'Bulk Webseries Rejection');

        foreach ($signoffs as $signoff) {
            $signoffStepData = new SignoffStepData([
                'action' => $request->action,
                'comment' => $comment,
                'step' => static::WEBSERIES_STEP,
                'user' => auth()->user(),
            ]);

            $signoffStepData = $signoffStepData->forSignoff($signoff);
            $processSignoffAction->execute($signoffStepData, $request);

            if ($approved) {
                flash('Approval submitted for bulk product signoff(s).', 'success');
            } else {
                flash('Rejection submitted for bulk product signoff(s).', 'warning');
            }
        }

        return redirect()->route('signoffs.index');
    }

    public function unsubmit($id)
    {
        $signoff = Signoff::when(auth()->user()->isVendor, function ($query) {
            $query->whereHasMorph('initial', '*', function ($query) {
                $query->withAccess();
            });
        })->findOrFail($id);

        return $signoff->unsubmit();
    }
}
