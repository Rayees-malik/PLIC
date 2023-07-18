<?php

namespace App;

use Altek\Accountant\Contracts\Recordable;
use Altek\Accountant\Recordable as RecordableTrait;
use Altek\Eventually\Eventually;
use App\Traits\Notifiable;
use App\Traits\Orderable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Str;
use Lab404\Impersonate\Models\Impersonate;
use Silber\Bouncer\Database\HasRolesAndAbilities;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class User extends Authenticatable implements Recordable, HasMedia
{
    use HasFactory;
    use Impersonate;
    use SoftDeletes;
    use HasRolesAndAbilities;
    use RecordableTrait;
    use Eventually;
    use Notifiable;
    use Orderable;
    use InteractsWithMedia;

    protected $fillable = ['name', 'email', 'password'];

    protected $hidden = ['password', 'remember_token'];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'unsubscriptions' => 'json',
    ];

    public function scopeWithAccess($query, $user = null)
    {
        if (! $user) {
            $user = auth()->user();
        }

        abort_if(! $user, 401, 'You must be logged in to access this resource.');

        if ($user->isVendor && $user->can('view', User::class)) {
            // Broker Access
            if ($user->isBroker) {
                return $query->where('broker_id', $user->broker_id);
            }

            return $query->where('vendor_id', $user->vendor_id);
        } elseif ($user->can('admin') || $user->can('manage', User::class)) {
            // Internal Access
            return $query;
        }

        // Else, No Access
        $query->whereRaw('1=0');
    }

    public function wantsMailNotification($notification)
    {
        return ! in_array($notification, $this->unsubscriptions ?? []);
    }

    // public function wantsMailNotification($notification)
    // {
    //     return empty($this->unsubscriptions) || Str::of(class_basename($notification))->lower()->contains('rejected') || !in_array(class_basename($notification), $this->unsubscriptions);
    // }

    public function getFirstNameAttribute()
    {
        return explode(' ', $this->name)[0];
    }

    public function getLastNameAttribute()
    {
        return array_values(array_slice(explode(' ', $this->name), -1))[0];
    }

    public function getInitialsAttribute()
    {
        return "{$this->firstName[0]}{$this->lastName[0]}";
    }

    public function getAbilitiesAttribute()
    {
        return array_unique($this->getAbilities()->where('name', '<>', '*')->pluck('name')->toArray());
    }

    public function getIsVendorAttribute()
    {
        if ($this->can('admin')) {
            return false;
        }

        return $this->can('vendor') && ($this->can('user.assign.broker') || $this->can('user.assign.vendor'));
    }

    public function getIsBrokerAttribute()
    {
        if ($this->can('admin')) {
            return false;
        }

        return $this->can('vendor') && $this->can('user.assign.broker');
    }

    public function canImpersonate()
    {
        return $this->can('admin') && $this->can('impersonate-users');
    }

    public function canBeImpersonated()
    {
        return auth()->user()->isNot($this) && ! is_impersonating();
    }

    public function vendor()
    {
        return $this->belongsTo(\App\Models\Vendor::class);
    }

    public function broker()
    {
        return $this->belongsTo(\App\Models\Broker::class);
    }

    public function submissions()
    {
        return $this->hasMany(\App\Models\Signoff::class);
    }

    public function marketingAgreements()
    {
        return $this->hasMany(\App\Models\MarketingAgreement::class, 'submitted_by');
    }

    public function pricingAdjustments()
    {
        return $this->hasMany(\App\Models\PricingAdjustment::class, 'submitted_by');
    }

    public function inventoryRemovals()
    {
        return $this->hasMany(\App\Models\InventoryRemoval::class, 'submitted_by');
    }

    public function brandDiscoRequests()
    {
        return $this->hasMany(\App\Models\BrandDiscoRequest::class, 'submitted_by');
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('signature')
            ->singleFile()
            ->acceptsMimeTypes(['image/png', 'image/jpeg', 'image/gif']);
    }

    public function getSignature(): Media|null
    {
        return $this->getFirstMedia('signature');
    }
}
