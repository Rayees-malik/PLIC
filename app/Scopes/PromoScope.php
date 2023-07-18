<?php

namespace App\Scopes;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class PromoScope implements Scope
{
    protected $extensions = [
        'ByOwner',
        'Owned',
        'Active',
        'Inactive',
        'Catalogue',
        'WithAccess',
    ];

    public function apply($query, Model $model)
    {
    }

    public function extend($query)
    {
        foreach ($this->extensions as $extension) {
            $this->{"add{$extension}"}($query);
        }
    }

    protected function addByOwner($query)
    {
        $query->macro('byOwner', function ($query, $owner = null) {
            return $query->withoutGlobalScope($this)->whereHas('period', function ($query) use ($owner) {
                $query->byOwner($owner);
            });
        });
    }

    protected function addOwned($query)
    {
        $query->macro('owned', function ($query) {
            return $query->withoutGlobalScope($this)->whereHas('period', function ($query) {
                $query->owned();
            });
        });
    }

    protected function addActive($query)
    {
        $query->macro('active', function ($query) {
            return $query->whereHas('period', function ($query) {
                $query->active();
            });
        });
    }

    protected function addInactive($query)
    {
        $query->macro('inactive', function ($query) {
            return $query->whereHas('period', function ($query) {
                $query->inactive();
            });
        });
    }

    protected function addCatalogue($query)
    {
        $query->macro('catalogue', function ($query) {
            return $query->whereHas('period', function ($query) {
                $query->catalogue();
            });
        });
    }

    protected function addWithAccess($query)
    {
        $query->macro('withAccess', function ($query, $user = null) {
            if (! $user) {
                $user = auth()->user();
            }

            // Vendor Users only care about access to the brand
            if ($user && $user->isVendor) {
                return $query->whereHas('brand', function ($query) {
                    $query->withAccess();
                });
            }

            // Allow Costing to see all promos
            if ($user && $user->can('signoff.product.promo.finance')) {
                return $query;
            }

            return $query->where(function ($query) use ($user) {
                $query->where(function ($query) {
                    // Regular Promos
                    return $query->whereHas('period', function ($query) {
                        $query->whereNull('owner_id');
                    })->whereHas('brand', function ($query) {
                        $query->withAccess();
                    });
                })->orWhere(function ($query) use ($user) {
                    // Owned Promos
                    if ($user && $user->can('signoff.retailer.promo')) {
                        $query->whereHas('period', function ($query) use ($user) {
                            $query->whereHasMorph('owner', '*', function ($query) use ($user) {
                                $query->withAccess($user);
                            });
                        });
                    } else {
                        $query->whereRaw('1 = 2');
                    }
                });
            });
        });
    }
}
