<?php

namespace App\Models;

use App\RecordableModel;
use App\Traits\HasPivotValue;
use App\Traits\Orderable;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Session;
use YlsIdeas\FeatureFlags\Facades\Features;

class Broker extends RecordableModel
{
    use Orderable;
    use HasPivotValue;

    public $pivotOverrides = ['brands' => 'concat'];

    protected $guarded = ['id'];

    protected $pivotChangeType = 'concat';

    protected $cloneable_relations = ['brands'];

    protected $eager_relations = ['brands'];

    protected $recordableEvents = [
        'created',
        'updated',
        'restored',
        'deleted',
        'forceDeleted',
        'synced',
    ];

    public static function getLookupVariables()
    {
        return ['brands'];
    }

    public static function loadLookups($model = null)
    {
        $brands = Brand::ordered()->get();

        $combined = ['brands' => $brands];

        if (! Features::accessible('remove-session-dependency')) {
            Session::put(static::getSessionRelationsKey(), $combined);
        }

        return $combined;
    }

    public function extraUpdates($request)
    {
        $this->brands()->sync(Arr::wrap($request->input('brands')));
    }

    public function brands()
    {
        return $this->belongsToMany(Brand::class);
    }
}
