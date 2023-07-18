<?php

namespace App;

use Altek\Accountant\Recordable;
use Altek\Eventually\Eventually;
use App\Contracts\RecordableInterface;
use App\Helpers\SignoffStateHelper;
use App\Helpers\StatusHelper;
use App\Models\Signoff;
use App\Traits\HasChanges;
use Bkwld\Cloner\Cloneable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

abstract class RecordableModel extends Model implements RecordableInterface
{
    use HasChanges;
    use Recordable;
    use Eventually;
    use Cloneable;
    use SoftDeletes;

    protected $copyStarted = false;

    public static function getLookupVariables()
    {
        return [];
    }

    public static function loadLookups()
    {
        // \Session::put(static::getSessionRelationsKey() . '.relations', []);
        return [];
    }

    public static function getPreloadedModel($id, $event = null)
    {
        $model = null;
        if (is_callable([get_called_class(), 'allStates'])) {
            $model = static::allStates()->withEagerLoadedRelations($event)->find($id);
        } else {
            $model = static::withEagerLoadedRelations($event)->find($id);
        }

        $lookups = static::loadLookups($model);
        $lookups['model'] = $model;

        return $lookups;
    }

    public static function getShortClassName($addSpaces = true)
    {
        $class = class_basename(get_called_class());

        if ($addSpaces) {
            return Str::of($class)->ucSplit()->implode(' ');
        }

        return $class;
    }

    public static function getSessionRelationsKey()
    {
        return static::getShortClassName(false) . '.relations';
    }

    public static function modifyFormData($formData, $model)
    {
        return $formData;
    }

    public function onCloning($src, $child = null)
    {
        if ($src->copyStarted) {
            if (method_exists($this, 'stateField')) {
                $this->{$this->stateField()} = SignoffStateHelper::IN_PROGRESS;
            }

            if (array_key_exists('status', $src->attributes)) {
                $this->status = StatusHelper::UNSUBMITTED;
            }

            if (array_key_exists('name', $src->attributes)) {
                $this->name = "Copy of {$src->name}";
            }

            if (array_key_exists('name_fr', $src->attributes)) {
                $this->name_fr = "Copie de {$src->name_fr}";
            }

            if (array_key_exists('submitted_by', $src->attributes)) {
                $this->submitted_by = auth()->id();
            }
        } else {
            // If we are cloning a IN_PROGRESS or INITIAL model we want the clone to default to PENDING
            if (method_exists($src, 'stateField')) {
                if ($src->{$src->stateField()} == SignoffStateHelper::IN_PROGRESS || $src->{$src->stateField()} == SignoffStateHelper::INITIAL) {
                    $this->{$this->stateField()} = SignoffStateHelper::PENDING;
                }
            }

            // If cloning a relationship with cloned_from_id set it
            if (Schema::hasColumn($this->getTable(), 'cloned_from_id')) {
                $this->cloned_from_id = $src->id;
            }

            // If status exists and is unsubmitted, move to Active
            if (array_key_exists('status', $src->attributes) && $this->status == StatusHelper::UNSUBMITTED) {
                $this->status = StatusHelper::ACTIVE;
            }
        }
    }

    public function getChangeRelations()
    {
        return $this->cloneable_relations ?? [];
    }

    public function scopeWithEagerLoadedRelations($query, $event = 'none')
    {
        $relations = [];
        if (property_exists($this, 'eager_relations')) {
            $relations = $this->eager_relations;
        }

        foreach (Arr::wrap($relations) as $relation => $config) {
            if (! is_array($config)) {
                $query->with($config);

                continue;
            }

            if ($event && Arr::has($config, $event)) {
                $scopes = Arr::get($config, $event);
                $query->with([$relation => function ($query) use ($scopes) {
                    foreach ($scopes as $scope) {
                        $query->$scope();
                    }
                }]);
            }
        }
    }

    public function handleMediaDeletion($isDuplicate)
    {
        $deletedInputs = request()->input('media-deleted');
        if (! is_array($deletedInputs)) {
            return;
        }

        foreach ($deletedInputs as $id => $deleted) {
            $media = null;
            if ($isDuplicate) {
                $media = Media::withTrashed()->where('cloned_from_id', $id)->first();
            } else {
                $media = Media::withTrashed()->find($id);
            }

            if ($media) {
                if ($deleted && ! $media->trashed()) {
                    $media->delete();
                } elseif (! $deleted && $media->trashed()) {
                    $media->restore();
                }
            }
        }
    }

    public function getRelationsExtra()
    {
        $extra = [];
        foreach ($this->getChangeRelations() as $relation_name) {
            $relation = call_user_func([$this, $relation_name]);
            if (is_a($relation, 'Illuminate\Database\Eloquent\Relations\BelongsToMany')) {
                foreach ($this->$relation_name as $foreign) {
                    $pivot_attributes = Arr::except($foreign->pivot->getAttributes(), [
                        $foreign->pivot->getRelatedKey(),
                        $foreign->pivot->getForeignKey(),
                        $foreign->pivot->getCreatedAtColumn(),
                        $foreign->pivot->getUpdatedAtColumn(),
                    ]);

                    Arr::set($extra, Str::snake($relation_name) . ".{$foreign->id}", [
                        'set' => true,
                        'pivot' => $pivot_attributes,
                    ]);
                }
            }
        }

        return $extra;
    }

    public function uploadFiles($formData)
    {
    }

    public function getDisplayNameAttribute()
    {
        return $this->name ?? "{$this::getShortClassName()} {$this->id}";
    }

    public function getRoutePrefixAttribute()
    {
        return $this->getTable();
    }

    public function createCopy()
    {
        $this->copyStarted = true;
        $originalExempt = $this->clone_exempt_attributes;
        $originalRelations = $this->cloneable_relations;

        $this->cloneable_relations = $originalRelations ? array_diff($originalRelations, ['media']) : [];
        if (property_exists($this, 'clear_on_clone')) {
            $this->clone_exempt_attributes = $this->clear_on_clone;
        }
        foreach ($this->cloneable_relations as $relation) {
            if ($this->$relation && property_exists($this->$relation, 'copyStarted')) {
                $this->$relation->copyStarted = true;
            }
        }

        $clone = $this->duplicate();
        Signoff::startNewSignoff($clone);

        $this->clone_exempt_attributes = $originalExempt;
        foreach ($this->cloneable_relations as $relation) {
            if ($this->$relation && property_exists($this->$relation, 'copyStarted')) {
                $this->$relation->copyStarted = false;
            }
        }
        $this->cloneable_relations = $originalRelations;
        $this->copyStarted = false;

        return $clone;
    }
}
