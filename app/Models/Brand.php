<?php

namespace App\Models;

use Altek\Accountant\Contracts\Identifiable;
use App\Helpers\SignoffStateHelper;
use App\Http\Requests\Brands\AdministrativeStepFormRequest;
use App\Http\Requests\Brands\BrandFormRequest;
use App\Http\Requests\Brands\BrandSaveRequiredFormRequest;
use App\Http\Requests\Brands\BrandStepFormRequest;
use App\Http\Requests\Brands\DistributionStepFormRequest;
use App\Http\Requests\Brands\PurchasingStepFormRequest;
use App\Http\Requests\ContactFormRequest;
use App\Media;
use App\Models\AS400\AS400BrandInvoice;
use App\Models\AS400\AS400BrandOpenAP;
use App\Models\AS400\AS400BrandPOReceived;
use App\Models\AS400\AS400Consignment;
use App\Models\AS400\AS400Freight;
use App\Models\AS400\AS400Margin;
use App\Models\AS400\AS400SpecialPricing;
use App\RecordableModel;
use App\Traits\HasContacts;
use App\Traits\HasStatus;
use App\Traits\HasSteps;
use App\Traits\HasThumbnails;
use App\Traits\Orderable;
use App\Traits\PriceAdjustable;
use App\Traits\RequiresSignoff;
use App\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\MessageBag;
use Spatie\MediaLibrary\HasMedia;
use stdClass;
use YlsIdeas\FeatureFlags\Facades\Features;

class Brand extends RecordableModel implements HasMedia
{
    use HasFactory;
    use HasSteps;
    use RequiresSignoff;
    use HasContacts;
    use HasThumbnails;
    use Orderable;
    use HasStatus;
    use PriceAdjustable;

    protected $guarded = ['id'];

    protected $recordableEvents = [
        'created',
        'updated',
        'restored',
        'deleted',
        'forceDeleted',
        'synced',
    ];

    protected $clone_exempt_attributes = [];

    protected $clear_on_clone = ['broker_proposal', 'purchasing_specialist_id', 'vendor_relations_specialist_id', 'hide_from_exports', 'education_portal'];

    protected $cloneable_relations = ['contacts', 'media', 'brokers'];

    protected $eager_relations = [
        'brokers',
        'media',
        'vendor' => [
            'none' => ['withPending'],
        ],
        'contacts' => [
            'history' => ['withTrashed'],
        ],
    ];

    public static function getLookupVariables()
    {
        return ['vendors', 'brokers', 'currencies', 'prs', 'vrs'];
    }

    public static function loadLookups($model = null)
    {
        $vendors = Vendor::withPending()->withAccess()->select('id', 'name')->ordered()->get();

        if ($model && $model->vendor && ! $vendors->contains('id', $model->vendor_id)) {
            $vendors->push($model->vendor);
            $vendors = $vendors->sortBy('name');
        }

        $brokers = Broker::ordered()->select('id', 'name')->get();
        $currencies = Currency::ordered()->select('id', 'name')->get();
        $prs = User::whereIs('purchasing-specialist')->select('id', 'name')->ordered()->get(); // TODO: Change to ability
        $vrs = User::whereIs('vendor-relations-specialist')->select('id', 'name')->ordered()->get(); // TODO: Change to ability

        $combined = [
            'vendors' => $vendors,
            'brokers' => $brokers,
            'currencies' => $currencies,
            'prs' => $prs,
            'vrs' => $vrs,
        ];

        if (! Features::accessible('remove-session-dependency')) {
            Session::put(static::getSessionRelationsKey(), $combined);
        }

        return $combined;
    }

    public static function stepperUpdate(Request $request, $submitting = false)
    {
        // Validate form sections and handle errors
        $brandValidation = app(BrandFormRequest::class)->partialValidated();
        $contactsValidation = app(ContactFormRequest::class)->partialValidated();

        $saveValidation = app(BrandSaveRequiredFormRequest::class)->partialValidated();

        if (! $request->id && ! $saveValidation->errors->isEmpty()) {
            $saveMessages = new MessageBag;
            foreach ($saveValidation->errors->all() as $message) {
                $saveMessages->add('brand-flash', $message);
            }
            $saved = false;
            $model = new Brand;

            $errors = $model->stepErrors();
            $errors->put('flash', $saveMessages);
        } else {
            $model = self::allStates()->withAccess()->withEagerLoadedRelations()->find($request->id);
            if (! $model) {
                $model = static::startSignoff();
            }
            $model->update(Arr::wrap($brandValidation->validated));

            $isDuplicate = false;
            if ($model->{$model->stateField()} == SignoffStateHelper::INITIAL) {
                $model = $model->getLastProposed();
                $isDuplicate = true;
            }

            if (auth()->user()->isBroker) {
                $brokerId = auth()->user()->broker_id;
                if (is_array($request->brokers)) {
                    if (! in_array($brokerId, $request->brokers)) {
                        $request->brokers[] = $brokerId;
                    }
                } else {
                    $request->brokers = [$brokerId];
                }
            }

            $model->brokers()->sync($request->brokers);
            Contact::stepperUpdate($contactsValidation->validated, $model, $isDuplicate, $submitting);

            $model->handleMediaDeletion($isDuplicate);

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

    public static function startSignoff($values = null)
    {
        $model = new static;
        $model->{$model->stateField()} = SignoffStateHelper::IN_PROGRESS;
        if ($values) {
            $model->fill($values);
        }

        // Default VRS to Brand Administrator role
        if (! $model->vendor_relations_specialist_id) {
            $user = User::whereIs('brand-administrator')->select('id')->first();
            $model->vendor_relations_specialist_id = $user ? $user->id : null;
        }

        $model->save();
        Signoff::startNewSignoff($model);

        return $model;
    }

    public function supplyExtra(string $event, array $properties, ?Identifiable $user): array
    {
        $extra = [];
        if ($event == 'created' || $event == 'updated') {
            if (Features::accessible('remove-session-dependency')) {
                extract(self::loadLookups($this));
            } else {
                extract(Session::get(static::getSessionRelationsKey()));
            }

            $extra = [
                'vendor_id' => $properties['vendor_id'] > 0 ? optional($vendors->find($properties['vendor_id']))->name : '',
                'made_in_canada' => $properties['made_in_canada'] ? 'Yes' : 'No',
                'currency_id' => $properties['currency_id'] > 0 ? optional($currencies->find($properties['currency_id']))->name : '',
                'contract_exclusive' => $properties['contract_exclusive'] ? 'Yes' : 'No',
                'no_other_distributors' => $properties['no_other_distributors'] ? 'Yes' : 'No',
                'allows_amazon_resale' => $properties['allows_amazon_resale'] ? 'Yes' : 'No',
                'map_pricing' => $properties['map_pricing'] ? 'Yes' : 'No',
                'nutrition_house' => $properties['nutrition_house'] ? 'Yes' : 'No',
                'nutrition_house_payment_type' => $properties['nutrition_house_payment_type'] ? 'Yes' : 'No',
                'health_first' => $properties['health_first'] ? 'Yes' : 'No',
                'health_first_payment_type' => $properties['health_first_payment_type'] ? 'Yes' : 'No',
                'allow_oi' => $properties['allow_oi'] ? 'Yes' : 'No',
                'purchasing_specialist_id' => $properties['purchasing_specialist_id'] > 0 ? optional($prs->find($properties['purchasing_specialist_id']))->name : '',
                'vendor_relations_specialist_id' => $properties['vendor_relations_specialist_id'] > 0 ? optional($vrs->find($properties['vendor_relations_specialist_id']))->name : '',
                'in_house_brand' => $properties['in_house_brand'] ? 'Yes' : 'No',
                'business_partner_program' => $properties['business_partner_program'] ? 'Yes' : 'No',
                'hide_from_exports' => $properties['hide_from_exports'] ? 'Yes' : 'No',
                'education_portal' => $properties['education_portal'] ? 'Yes' : 'No',
            ];

            // handle BelongsToMany relations
            if ($event == 'created') {
                $extra = array_merge($extra, $this->getRelationsExtra());
            }
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
        if ($user->can('vendor.access-all') || $user->can('manage', Brand::class)) {
            return $query;
        }

        if ($user->can('vendor')) {
            // Broker Access
            if ($user->can('user.assign.broker')) {
                return $query->whereHas('brokers', function ($query) use ($user) {
                    $query->where('id', $user->broker_id ?? -1);
                });
            }

            // Vendor Access
            if ($user->can('user.assign.vendor')) {
                return $query->whereHas('vendor', function ($query) use ($user) {
                    $query->where('id', $user->vendor_id ?? -1);
                });
            }
        }

        // Else, No Access
        $query->whereRaw('1=0');
    }

    public function scopeSignoffFilter($query, $user = null)
    {
        if ($user && $user->can('signoff.webseries')) {
            return $query;
        }

        if ($user) {
            $query->where('vendor_relations_specialist_id', $user->id)
                ->orWhere('purchasing_specialist_id', $user->id);
        } else {
            // TODO: Switch to check that determines if each step requires the VRS check?
            $webseriesIds = implode(',', User::whereHas('roles.abilities', function ($query) {
                $query->where('name', 'signoff.webseries');
            })->pluck('id')->toArray());

            $query->whereRaw("vendor_relations_specialist_id = users.id or purchasing_specialist_id = users.id or users.id in ({$webseriesIds})");
        }

        $query->orWhereNull('vendor_relations_specialist_id')
            ->orWhereNull('purchasing_specialist_id');
    }

    public function scopeForExport($query)
    {
        $query->where('hide_from_exports', false);
    }

    public function getNameFr()
    {
        return $this->name_fr ?: $this->name;
    }

    public function getStepsAttribute()
    {
        return [
            'brand' => [
                'display' => 'Brand',
                'formRequest' => BrandStepFormRequest::class,
            ],
            'uploads' => [
                'display' => 'Uploads',
            ],
            'distribution' => [
                'display' => 'Distribution',
                'formRequest' => DistributionStepFormRequest::class,
            ],
            'purchasing' => [
                'display' => 'Purchasing',
                'formRequest' => PurchasingStepFormRequest::class,
            ],
            'contacts' => [
                'display' => 'Contacts',
                'formRequest' => ContactFormRequest::class,
            ],
            'administrative' => [
                'display' => 'Administrative',
                'formRequest' => AdministrativeStepFormRequest::class,
                'hidden' => auth()->user()->cannot('edit', Vendor::class) || auth()->user()->isVendor,
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
                'display' => 'Brand',
                'multiple' => true,
                'required' => 0,
            ],
        ];
    }

    public function getOGD($priceCode = null, $date = null)
    {
        // Load special pricing relation if not already loaded
        if (! isset($this->relations['as400SpecialPricing'])) {
            $this->load(['as400SpecialPricing' => function ($query) use ($priceCode, $date) {
                $query->byCode($priceCode)->forDate($date);
            }]);
        }

        // if no date set, use today
        if ($date) {
            $date = new Carbon($date);
        } else {
            $date = Carbon::now();
        }

        $specialPricing = $this->as400SpecialPricing->where('price_code', $priceCode)->filter(function ($price) use ($date) {
            return $date->greaterThanOrEqualTo($price->start_date) && $date->lessThanOrEqualTo($price->end_date);
        })->first();

        return optional($specialPricing)->percent_discount;
    }

    public function catalogueCaseStackDealsFormat($period1, $period2, $english)
    {
        $caseStackDeal1 = $this->caseStackDeals->where('period_id', optional($period1)->id)->first();
        $caseStackDeal2 = $this->caseStackDeals->where('period_id', optional($period2)->id)->first();

        $deal1 = $caseStackDeal1 ? ($english ? $caseStackDeal1->deal : $caseStackDeal1->deal_fr) : null;
        $deal2 = $caseStackDeal2 ? ($english ? $caseStackDeal2->deal : $caseStackDeal2->deal_fr) : null;

        $catData = '';
        if ($deal1 && (! $period2 || $deal1 == $deal2)) {
            $catData = "<CharStyle:><ParaStyle:VendorDeal><CharStyle:Red>{$deal1}<CharStyle:><CharStyle:Mo>";
        } else {
            if ($deal1) {
                $monthName = $english ? $period1->start_date->shortMonthName : $period1->start_date->locale('fr')->shortMonthName;
                $catData = "<CharStyle:><ParaStyle:VendorDeal><CharStyle:Red>{$monthName}: {$deal1}<CharStyle:><CharStyle:Mo>";
            }
            if ($deal2) {
                $monthName = $english ? $period2->start_date->shortMonthName : $period2->start_date->locale('fr')->shortMonthName;
                $catData .= $deal1 ? "\n" : '';
                $catData .= "<CharStyle:><ParaStyle:VendorDeal><CharStyle:Red>{$monthName}: {$deal2}<CharStyle:><CharStyle:Mo>";
            }
        }

        return str_replace("\r\n", "\n", $catData);
    }

    public function vendor(): BelongsTo
    {
        return $this->belongsTo(Vendor::class)->withPending();
    }

    public function brokers(): BelongsToMany
    {
        return $this->belongsToMany(Broker::class);
    }

    public function contacts(): MorphMany
    {
        return $this->morphMany(Contact::class, 'contactable');
    }

    public function purchasingSpecialist(): BelongsTo
    {
        return $this->belongsTo(User::class, 'purchasing_specialist_id')->withTrashed();
    }

    public function vendorRelationsSpecialist(): BelongsTo
    {
        return $this->belongsTo(User::class, 'vendor_relations_specialist_id')->withTrashed();
    }

    public function currency(): BelongsTo
    {
        return $this->belongsTo(Currency::class);
    }

    public function catalogueCategories(): HasMany
    {
        return $this->hasMany(CatalogueCategory::class);
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    public function promos(): HasMany
    {
        return $this->hasMany(Promo::class);
    }

    public function caseStackDeals(): HasMany
    {
        return $this->hasMany(CaseStackDeal::class);
    }

    public function discoRequests(): HasMany
    {
        return $this->hasMany(BrandDiscoRequest::class);
    }

    // Deductions Portal Media

    public function mediaInvoices(): MorphMany
    {
        return $this->morphMany(Media::class, 'model')->where('collection_name', 'deductions_in');
    }

    public function mediaDebitMemos(): MorphMany
    {
        return $this->morphMany(Media::class, 'model')->where('collection_name', 'deductions_dm');
    }

    public function mediaCreditRebates(): MorphMany
    {
        return $this->morphMany(Media::class, 'model')->where('collection_name', 'deductions_cr');
    }

    // AS400 Relations
    public function as400SpecialPricing(): MorphMany
    {
        return $this->morphMany(AS400SpecialPricing::class, 'priceable');
    }

    public function as400Freight(): HasOne
    {
        return $this->hasOne(AS400Freight::class);
    }

    public function as400Consignment(): HasOne
    {
        return $this->hasOne(AS400Consignment::class);
    }

    public function as400Margin(): HasOne
    {
        return $this->hasOne(AS400Margin::class);
    }

    public function as400Invoices(): HasMany
    {
        return $this->hasMany(AS400BrandInvoice::class);
    }

    public function as400OpenAP(): HasMany
    {
        return $this->hasMany(AS400BrandOpenAP::class);
    }

    public function as400POReceived(): HasMany
    {
        return $this->hasMany(AS400BrandPOReceived::class);
    }
}
