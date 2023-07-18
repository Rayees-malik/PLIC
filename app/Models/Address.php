<?php

namespace App\Models;

use Altek\Accountant\Contracts\Identifiable;
use App\RecordableModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Session;
use YlsIdeas\FeatureFlags\Facades\Features;

class Address extends RecordableModel
{
    protected $table = 'addresses';

    protected $guarded = ['id'];

    protected $with = ['country'];

    public function supplyExtra(string $event, array $properties, ?Identifiable $user): array
    {
        $extra = [];
        if ($event == 'created' || $event == 'updated') {
            if (Arr::has($properties, 'country_id') && $properties['country_id'] > 0) {
                if (Features::accessible('remove-session-dependency')) {
                    extract($this->addressable_type::loadLookups($this));
                    $country = Country::find($properties['country_id']);
                } else {
                    $sessionKey = call_user_func($this->addressable_type . '::getSessionRelationsKey');

                    if ($sessionKey && Session::has($sessionKey)) {
                        extract(Session::get($sessionKey));
                        $country = $countries->find($properties['country_id']);
                    } else {
                        $country = Country::find($properties['country_id']);
                    }
                }

                $extra = [
                    'country_id' => $country->name,
                ];
            }
        }

        return $extra;
    }

    public function longFormat()
    {
        $address = [];
        $address[] = $this->address;
        $address[] = $this->address2;
        if (! empty($this->city) && ! empty($this->province)) {
            $address[] = "{$this->city}, {$this->province}";
        } else {
            $address[] = $this->city;
            $address[] = $this->province;
        }
        if (! empty($this->postal_code) && ! empty($this->country)) {
            $address[] = "{$this->postal_code}, {$this->country->name}";
        } else {
            $address[] = $this->postal_code;
            $address[] = optional($this->country)->name;
        }

        return implode('<br>', array_filter($address));
    }

    public function addressable(): MorphTo
    {
        return $this->morphTo();
    }

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }
}
