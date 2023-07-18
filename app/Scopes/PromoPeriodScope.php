<?php

namespace App\Scopes;

use App\Models\PromoPeriod;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class PromoPeriodScope implements Scope
{
    protected $extensions = [
        'ByOwner',
        'Owned',
        'Active',
        'Inactive',
        'Catalogue',
        'SinceMonthsAgo',
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

    public function addSinceMonthsAgo($query)
    {
        $query->macro('sinceMonthsAgo', function ($query, $months) {
            return $query->where('start_date', '>=', Carbon::now()->subMonth($months)->toDateTimeString());
        });
    }

    protected function addByOwner($query)
    {
        $query->macro('byOwner', function ($query, $owner = null) {
            if (! $owner) {
                return $query->whereNull('owner_id');
            }

            return $query->whereHasMorph('owner', [get_class($owner)], function ($query) use ($owner) {
                $query->where('id', $owner->id);
            });
        });
    }

    protected function addOwned($query)
    {
        $query->macro('owned', function ($query) {
            return $query->whereNotNull('owner_id');
        });
    }

    protected function addActive($query)
    {
        $query->macro('active', function ($query) {
            return $query->where('active', true);
        });
    }

    protected function addInactive($query)
    {
        $query->macro('inactive', function ($query) {
            return $query->where('active', false);
        });
    }

    protected function addCatalogue($query)
    {
        $query->macro('catalogue', function ($query) {
            return $query->where('type', PromoPeriod::CATALOGUE_TYPE);
        });
    }
}
