<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class SignoffConfigStep extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function signoffConfig(): BelongsTo
    {
        return $this->belongsTo(SignoffConfig::class);
    }

    public function abilities(): BelongsToMany
    {
        return $this->belongsToMany(Ability::class);
    }
}
