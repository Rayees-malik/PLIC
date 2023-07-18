<?php

namespace App\Models;

use App\Helpers\SignoffStateHelper;
use App\RecordableModel;
use App\Traits\HasContacts;
use App\Traits\HasStatus;
use App\Traits\HasSteps;
use App\Traits\Orderable;
use App\Traits\RequiresSignoff;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Support\Facades\Session;
use stdClass;
use YlsIdeas\FeatureFlags\Facades\Features;

class Vendor extends RecordableModel
{
    use HasFactory;
    use RequiresSignoff;
    use HasSteps;
    use HasContacts;
    use Orderable;
    use HasStatus;

    protected $guarded = ['id'];

    protected $cloneable_relations = ['address', 'contacts'];

    protected $eager_relations = [
        'address',
        'contacts' => [
            'history' => ['withTrashed'],
        ],
    ];

    public static function getLookupVariables()
    {
        return ['countries', 'brandNumbers'];
    }

    public static function loadLookups($model = null)
    {
        $countries = Country::select('id', 'name')->ordered()->get();

        $brandNumbers = [];
        if ($model && $model->id) {
            $brandNumbers = Brand::whereIn('vendor_id', [$model->id, $model->cloned_from_id])->pluck('brand_number')->toArray();
            array_unique($brandNumbers);
            @sort($brandNumbers);
        }

        $combined = [
            'countries' => $countries,
            'brandNumbers' => $brandNumbers,
        ];

        if (! Features::accessible('remove-session-dependency')) {
            Session::put(static::getSessionRelationsKey(), $combined);
        }

        return $combined;
    }

    public static function stepperUpdate(\Illuminate\Http\Request $request, $submitting = false)
    {
        // Validate form sections and handle errors
        $vendorValidation = app(\App\Http\Requests\Vendors\VendorFormRequest::class)->partialValidated();
        $addressValidation = app(\App\Http\Requests\AddressFormRequest::class)->partialValidated();
        $contactsValidation = app(\App\Http\Requests\ContactFormRequest::class)->partialValidated();

        $saveValidation = app(\App\Http\Requests\Vendors\VendorSaveRequiredFormRequest::class)->partialValidated();
        if (! $request->id && ! $saveValidation->errors->isEmpty()) {
            $saveMessages = new \Illuminate\Support\MessageBag;
            foreach ($saveValidation->errors->all() as $message) {
                $saveMessages->add('vendor-flash', $message);
            }
            $saved = false;
            $model = new Vendor;
            $errors = $model->stepErrors();
            $errors->put('flash', $saveMessages);
        } else {
            $model = self::allStates()->withAccess()->withEagerLoadedRelations()->find($request->id);
            if (! $model) {
                $model = static::startSignoff();

                $user = auth()->user();
                if ($user->isBroker) {
                    // Set submitted_by_broker_id if it's a broker user
                    $model->submitted_by_broker_id = $user->broker_id;
                } elseif ($user->isVendor && ! $user->vendor_id) {
                    // Assign this vendor as the users vendor if it's a vendor user
                    $user->vendor_id = $model->id;
                    $user->save();
                }
            }
            $model->update($vendorValidation->validated);

            $isDuplicate = false;
            if ($model->{$model->stateField()} == SignoffStateHelper::INITIAL) {
                $model = $model->getLastProposed();
                $isDuplicate = true;
            }

            $model->address()->updateOrCreate([], $addressValidation->validated);
            Contact::stepperUpdate($contactsValidation->validated, $model, $isDuplicate, $submitting);

            // Reload model
            $model = self::allStates()->withEagerLoadedRelations('history')->find($model->id);

            $errors = $model->stepErrors();
            $errors->put('missing_contacts', $model->missingContactErrors());
            $saved = true;

            // DEV: Comment out for easier of testing.
            if ($submitting && $errors->allBagsEmpty()) {
                $model = $model->submitSignoff();
            }
        }

        $returnObj = new stdClass;
        $returnObj->model = $model;
        $returnObj->errors = $errors;
        $returnObj->saved = $saved;

        return $returnObj;
    }

    public function supplyExtra(string $event, array $properties, ?\Altek\Accountant\Contracts\Identifiable $user): array
    {
        $extra = [];
        if ($event == 'created' || $event == 'updated') {
            if (Features::accessible('remove-session-dependency')) {
                extract(self::loadLookups($this));
            } else {
                extract(Session::get(static::getSessionRelationsKey()));
            }

            $extra = [
                'fob_purity_distribution_centres' => $properties['fob_purity_distribution_centres'] == '1' ? 'Yes' : 'No',
                'consignment' => $properties['consignment'] == '1' ? 'Yes' : 'No',
            ];
        }

        return $extra;
    }

    public function scopeWithAccess($query, $user = null)
    {
        if (! $user) {
            $user = auth()->user();
        }

        abort_if(! $user, 401, 'You must be logged in to access this resource.');

        // All Access
        if ($user->can('vendor.access-all') || $user->can('manage', Vendor::class)) {
            return $query;
        }

        if ($user->can('vendor')) {
            if ($user->isBroker) {
                // Broker Access
                return $query->whereHas('brands.brokers', function ($query) use ($user) {
                    $query->where('id', $user->broker_id ?? -1);
                })->orWhere(function ($query) use ($user) {
                    $query->where('submitted_by_broker_id', $user->broker_id ?? -1)->whereDoesntHave('brands');
                })->orWhereHas('clonedFrom', function ($query) use ($user) {
                    $query->whereHas('brands.brokers', function ($query) use ($user) {
                        $query->where('id', $user->broker_id ?? -1);
                    })->orWhere(function ($query) use ($user) {
                        $query->where('submitted_by_broker_id', $user->broker_id ?? -1)->whereDoesntHave('brands');
                    });
                });
            } elseif ($user->isVendor) {
                // Vendor Access
                return $query->where('id', $user->vendor_id ?? -1)
                    ->orWhere('cloned_from_id', $user->vendor_id ?? -1);
            }
        }

        // Else, No Access
        $query->whereRaw('1 = 0');
    }

    public function scopeSignoffFilter($query, $user = null)
    {
        $query->whereHas('brands', function ($query) use ($user) {
            $query->signoffFilter($user);
        })->orDoesntHave('brands');
    }

    public function getStepsAttribute()
    {
        return [
            'vendor' => [
                'display' => 'Vendor',
                'formRequest' => \App\Http\Requests\Vendors\VendorStepFormRequest::class,
            ],
            'contacts' => [
                'display' => 'Contacts',
                'formRequest' => \App\Http\Requests\ContactFormRequest::class,
            ],
            'payment' => [
                'display' => 'Payment',
                'formRequest' => \App\Http\Requests\Vendors\PaymentStepFormRequest::class,
            ],
            'review' => [
                'display' => 'Review',
            ],
        ];
    }

    public function getContactRolesAttribute()
    {
        return [
            'vendor' => [
                'display' => 'Vendor',
                'multiple' => true,
                'required' => 1,
            ],
            'qc' => [
                'display' => 'QC',
                'multiple' => true,
                'required' => 1,
            ],
            'purchasing' => [
                'display' => 'Purchasing',
                'multiple' => true,
                'required' => 1,
            ],
            'plic' => [
                'display' => 'PLIC',
                'multiple' => true,
                'required' => 1,
            ],
            'payment' => [
                'display' => 'Payment',
                'multiple' => true,
                'required' => 1,
            ],
        ];
    }

    public function address(): MorphOne
    {
        return $this->morphOne(Address::class, 'addressable');
    }

    public function contacts(): MorphMany
    {
        return $this->morphMany(Contact::class, 'contactable');
    }

    public function brands(): HasMany
    {
        return $this->hasMany(Brand::class)->withPending();
    }

    public function clonedFrom(): HasOne
    {
        return $this->hasOne(Vendor::class, 'id', 'cloned_from_id');
    }
}
