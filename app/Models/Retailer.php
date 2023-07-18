<?php

namespace App\Models;

use App\Helpers\SignoffStateHelper;
use App\Http\Requests\AddressFormRequest;
use App\Http\Requests\ContactFormRequest;
use App\Http\Requests\Retailers\RetailerFormRequest;
use App\Http\Requests\Retailers\RetailerRelationsFormRequest;
use App\Http\Requests\Retailers\RetailerSaveRequiredFormRequest;
use App\Http\Requests\Retailers\RetailerStepFormRequest;
use App\RecordableModel;
use App\Traits\HasContacts;
use App\Traits\HasSteps;
use App\Traits\Orderable;
use App\Traits\RequiresSignoff;
use App\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\MessageBag;
use stdClass;
use YlsIdeas\FeatureFlags\Facades\Features;

class Retailer extends RecordableModel
{
    use HasFactory;
    use RequiresSignoff;
    use HasSteps;
    use HasContacts;
    use Orderable;

    const COSTING_TYPES = [
        'landed' => 'Fixed Landed',
        'warehouse' => 'Warehouse',
    ];

    protected $guarded = ['id'];

    protected $recordableEvents = [
        'created',
        'updated',
        'restored',
        'deleted',
        'forceDeleted',
        'synced',
    ];

    protected $cloneable_relations = ['address', 'contacts', 'distributors'];

    protected $eager_relations = [
        'address',
        'accountManager',
        'contacts' => [
            'history' => ['withTrashed'],
        ],
    ];

    public static function getLookupVariables()
    {
        return ['countries', 'accountManagers', 'distributors'];
    }

    public static function loadLookups($model = null)
    {
        $countries = Country::select('id', 'name')->ordered()->get();
        $distributors = Distributor::select('id', 'name')->ordered()->get();

        $accountManagers = User::whereHas('roles.abilities', function ($query) {
            $query->where('abilities.name', 'retailer.account-manager');
        })->select('id', 'name')->ordered()->get();

        $combined = [
            'countries' => $countries,
            'accountManagers' => $accountManagers,
            'distributors' => $distributors,
        ];

        if (! Features::accessible('remove-session-dependency')) {
            Session::put(static::getSessionRelationsKey(), $combined);
        }

        return $combined;
    }

    public static function stepperUpdate(\Illuminate\Http\Request $request, $submitting = false)
    {
        // Validate form sections and handle errors
        $retailerValidation = app(RetailerFormRequest::class)->partialValidated();
        $saveValidation = app(RetailerSaveRequiredFormRequest::class)->partialValidated();
        $relationsValidation = app(RetailerRelationsFormRequest::class)->partialValidated();
        $addressValidation = app(AddressFormRequest::class)->partialValidated();
        $contactsValidation = app(ContactFormRequest::class)->partialValidated();

        $saveMessages = new MessageBag;
        foreach ($saveValidation->errors->all() as $message) {
            $saveMessages->add('retailer-flash', $message);
        }

        if (! $request->id && $saveMessages->isNotEmpty()) {
            $saved = false;
            $model = new Retailer;

            $errors = $model->stepErrors();
            $errors->put('flash', $saveMessages);
        } else {
            $model = self::allStates()->withEagerLoadedRelations()->find($request->id);
            if (! $model) {
                $model = static::startSignoff();
            }
            $model->update($retailerValidation->validated);

            $isDuplicate = false;
            if ($model->{$model->stateField()} == SignoffStateHelper::INITIAL) {
                $model = $model->getLastProposed();
                $isDuplicate = true;
            }

            // Handle Relationships
            $model->distributors()->sync(Arr::get($relationsValidation->validated, 'distributors', []));
            $model->address()->updateOrCreate([], $addressValidation->validated);
            Contact::stepperUpdate($contactsValidation->validated, $model, $isDuplicate, $submitting);

            // Reload model
            $model = self::allStates()->withEagerLoadedRelations('history')->find($model->id);

            $errors = $model->stepErrors();
            if ($saveMessages->isNotEmpty()) {
                $errors->put('flash', $saveMessages);
                $saved = false;
            } else {
                $saved = true;
            }

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

    public function getStepsAttribute()
    {
        return [
            'retailer' => [
                'display' => 'Retailer',
                'formRequest' => RetailerStepFormRequest::class,
            ],
            'contacts' => [
                'display' => 'Contacts',
                'formRequest' => ContactFormRequest::class,
            ],
            'review' => [
                'display' => 'Review',
            ],
        ];
    }

    public function getContactRolesAttribute()
    {
        return [
            'retailer-buyer' => [
                'display' => 'Buyer',
                'multiple' => true,
                'required' => 0,
            ],
            'retailer-other' => [
                'display' => 'Other',
                'multiple' => true,
                'required' => 0,
            ],
        ];
    }

    public function nextStep($step, $signoff)
    {
        return 2; // Always auto-approve
    }

    public function scopeSignoffFilter($query, $user = null)
    {
        if ($user) {
            $query->where('account_manager_id', $user->id);
        } else {
            $query->whereRaw('account_manager_id = users.id');
        }
        $query->orWhereNull('account_manager_id');
    }

    public function scopeWithAccess($query, $user = null)
    {
        if (! $user) {
            $user = auth()->user();
        }

        abort_if(! $user, 401, 'You must be logged in to access this resource.');

        if ($user->can('admin')) {
            return $query;
        }

        $query->where('account_manager_id', $user->id)->orWhereNull('account_manager_id');
    }

    public function address(): MorphOne
    {
        return $this->morphOne(Address::class, 'addressable');
    }

    public function contacts(): MorphMany
    {
        return $this->morphMany(Contact::class, 'contactable');
    }

    public function distributors(): BelongsToMany
    {
        return $this->belongsToMany(Distributor::class);
    }

    public function accountManager(): BelongsTo
    {
        return $this->belongsTo(User::class, 'account_manager_id')->withTrashed();
    }

    public function promoPeriods(): MorphMany
    {
        return $this->morphMany(PromoPeriod::class, 'owner');
    }
}
