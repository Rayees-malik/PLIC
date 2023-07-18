<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SignoffConfig extends Model
{
    protected $table = 'signoff_config';

    protected $fillable = ['model', 'show_route'];

    protected $with = ['steps'];

    protected $casts = [
        'final_approval_to' => 'json',
    ];

    public function getNumStepsAttribute()
    {
        return $this->steps->count();
    }

    public function getStep($step)
    {
        return $this->steps->where('step', $step)->first();
    }

    public function steps(): HasMany
    {
        return $this->hasMany(SignoffConfigStep::class)->orderBy('step');
    }

    public function signoffs(): HasMany
    {
        return $this->hasMany(Signoff::class);
    }
}
