<?php

namespace App\Models;

use App\Traits\Orderable;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Silber\Bouncer\Database\Ability as BaseAbility;

class Ability extends BaseAbility
{
    use SoftDeletes;
    use Orderable;

    const ORDER_BY = ['category' => 'asc', 'title' => 'asc'];

    protected $fillable = ['name', 'title', 'description', 'category'];

    protected $hidden = ['pivot'];

    // Filter out model abilities (with NULL entity type/id)
    public function scopeFilterModelAbilities($query)
    {
        $query->whereNull('entity_type')->whereNull('entity_id');
    }

    public function signoffConfigSteps(): BelongsToMany
    {
        return $this->belongsToMany(SignoffConfigStep::class);
    }
}
