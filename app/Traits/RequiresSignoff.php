<?php

namespace App\Traits;

use App\Events\SignoffUnsubmitted;
use App\Helpers\SignoffStateHelper;
use App\Models\Signoff;
use App\Models\SignoffResponse;
use App\Scopes\SignoffStateScope;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Arr;

trait RequiresSignoff
{
    protected $requiresSignoff = true;

    public static function bootRequiresSignoff()
    {
        static::addGlobalScope(new SignoffStateScope);
    }

    public static function stateField(): string
    {
        return 'state';
    }

    public static function create(array $attributes = [])
    {
        throw new Exception('create() cannot be used with RequiresSignoff models.');
    }

    public static function startSignoff($values = null)
    {
        $model = new static;
        $model->{$model->stateField()} = SignoffStateHelper::IN_PROGRESS;
        if ($values) {
            $model->fill($values);
        }

        $model->save();

        Signoff::startNewSignoff($model);

        return $model;
    }

    public function getCanUnsubmitPendingAttribute()
    {
        return false;
    }

    public function getCanUnsubmitApprovedAttribute()
    {
        return false;
    }

    public function getCanUpdateAttribute()
    {
        // In-Progress and unsubmitted updates are allowed
        if ($this->{$this->stateField()} == SignoffStateHelper::IN_PROGRESS) {
            return true;
        }

        return Signoff::hasInitialOrProposed($this)->pending()->count() === 0;
    }

    public function getIsCompletedProposedAttribute()
    {
        return $this->signoff
            && $this->signoff->state == SignoffStateHelper::APPROVED
            && $this->signoff->proposed_id == $this->id;
    }

    public function getIsNewSubmissionAttribute()
    {
        if (! $this->id) {
            return true;
        }

        if ($this->signoff && isset($this->signoff->new_submission)) {
            return $this->signoff->new_submission;
        }

        return false;
    }

    public function hasSignoffFrom($user)
    {
        return optional($this->signoff)->user_id == $user->id || optional($this->signoffs)->where('user_id', $user->id)->count();
    }

    public function getUnsubmitNotificationUsers()
    {
    }

    public function onUnsubmit($signoff)
    {
        $users = $this->getUnsubmitNotificationUsers();

        if ($users) {
            event(new SignoffUnsubmitted($signoff, $users));
        }
    }

    public function getProposedForUser()
    {
        $signoff = Signoff::where('user_id', auth()->id())
            ->where('proposed_id', '<>', $this->id)
            ->inProgress()
            ->hasInitial($this)
            ->select('proposed_id')
            ->first();

        return $signoff ? $signoff->proposed_id : null;
    }

    public function getLastProposed()
    {
        $lastSignoff = $this->signoffs()->with('proposed')->latest()->first();

        return $lastSignoff ? $lastSignoff->proposed : null;
    }

    public function getState()
    {
        return SignoffStateHelper::toString($this->{$this::stateField()});
    }

    public function nextStep($step, $signoff)
    {
        return ++$step;
    }

    public function prevStep($step, $signoff)
    {
        return --$step;
    }

    public function onSignoffComplete($signoff)
    {
    }

    public function signoffApproved($signoff)
    {
        $initial = $signoff->initial;
        $proposed = $signoff->proposed->fresh(); // get fresh model

        $properties = Arr::except($proposed->attributesToArray(), [
            $proposed->getKeyName(),
            $proposed->getCreatedAtColumn(),
            $proposed->getUpdatedAtColumn(),
            $proposed->stateField(),
        ]);

        if (property_exists($proposed, 'clone_exempt_attributes')) {
            $properties = Arr::except($properties, $proposed->clone_exempt_attributes);
        }

        $initial->fill($properties);

        // REJECTED is here to fix an issue with PPMS migrated products
        if ($initial->{$initial->stateField()} == SignoffStateHelper::PENDING || $initial->{$initial->stateField()} == SignoffStateHelper::REJECTED) {
            $initial->{$initial->stateField()} = SignoffStateHelper::INITIAL;
        }
        $initial->save();
        $initial->copyRelationsFrom($proposed);

        $proposed->{$proposed->stateField()} = SignoffStateHelper::APPROVED;
        $proposed->save();

        $initial->onSignoffComplete($signoff);
    }

    public function signoffRejected($signoff)
    {
        if ($this->{$this->stateField()} !== SignoffStateHelper::INITIAL) {
            $this->{$this->stateField()} = SignoffStateHelper::REJECTED;
            $this->save();
        }

        $proposed = $signoff->proposed;
        $proposed->{$proposed->stateField()} = SignoffStateHelper::REJECTED;
        $proposed->save();
    }

    public function update(array $attributes = [], array $options = [])
    {
        // If it's anything but an "INITIAL" model, updates are fine.
        if ($this->{$this->stateField()} != SignoffStateHelper::INITIAL) {
            return parent::update($attributes, $options);
        }

        // If it's INITIAL we need to make a signoff out of it.
        return $this->createSignoff($attributes, $options);
    }

    public function createSignoff(array $attributes = [], array $options = [])
    {
        // If updates are locked, prevent signoff creation
        if (! $this->canUpdate) {
            return;
        }

        // Duplicate model and update the new proposed model, not original
        $proposed = $this->duplicate();
        $proposed->update($attributes, $options);

        return Signoff::startNewSignoff($this, $proposed);
    }

    public function submitSignoff()
    {
        $signoff = $this->signoff()->with('proposed')->first();
        if (! $signoff) {
            throw new Exception('Could not find Signoff for model');
        }

        $proposed = null;
        if ($signoff->initial_id == $signoff->proposed_id) {
            $proposed = $this->duplicate();
            $signoff->proposed()->associate($proposed);
        } else {
            $proposed = $signoff->proposed;
        }

        if ($this->{$this->stateField()} != SignoffStateHelper::INITIAL) {
            $this->{$this->stateField()} = SignoffStateHelper::PENDING;
            $this->save();
        }
        if ($proposed->{$proposed->stateField()} != SignoffStateHelper::PENDING) {
            $proposed->{$proposed->stateField()} = SignoffStateHelper::PENDING;
            $proposed->save();
        }

        $signoff->step = 0;
        $signoff->state = SignoffStateHelper::PENDING;
        $signoff->submitted_at = Carbon::now()->toDateTimeString();
        $signoff->gotoNextStep();

        // Create 'SUBMITTED' event
        SignoffResponse::saveResponse($signoff, true, 'Submitted', true);

        // Prevent further updates from REJECTED or PENDING Signoffs
        Signoff::where('initial_id', $signoff->initial_id)->where('initial_type', $signoff->initial_type)->inProgress()->update([$this->stateField() => SignoffStateHelper::ARCHIVED]);

        return $signoff->proposed;
    }

    public function scopeWithApprovedSignoffs($query)
    {
        return $query->with([
            'signoffs' => function ($query) {
                $query->with(['user', 'responses.user'])->where('state', SignoffStateHelper::APPROVED)->orderBy('id', 'desc');
            },
        ]);
    }

    public function scopeWithLastSignoff($query)
    {
        return $query->with([
            'signoffs' => function ($query) {
                $query->with(['user', 'responses.user'])->orderBy('id', 'desc')->limit(1);
            },
            'signoff' => function ($query) {
                $query->with(['user', 'responses.user']);
            },
        ]);
    }

    public function scopeWithAccess($query, $user = null)
    {
        return $query;
    }

    public function scopeSignoffFilter($query, $user = null)
    {
        return $query;
    }

    public function copyRelationsFrom($from)
    {
        if (! method_exists($this, 'getCloneableRelations')) {
            return;
        }

        foreach ($from->getCloneableRelations() as $relation_name) {
            $this->copyRelation($from, $relation_name);
        }
    }

    public function getSummaryArray($signoff = null)
    {
        return [
            'Name' => $this->displayName,
        ];
    }

    public function validRequest($request)
    {
        return ! $this->id || $request->updated_at == $this->updated_at;
    }

    public function signoff()
    {
        return $this->morphOne(Signoff::class, 'proposed');
    }

    public function signoffs()
    {
        return $this->morphMany(Signoff::class, 'initial');
    }

    protected function copyRelation($from, $relation_name)
    {
        $relation = call_user_func([$from, $relation_name]);
        if (is_a($relation, 'Illuminate\Database\Eloquent\Relations\BelongsToMany')) {
            $this->copyPivotedRelation($relation, $relation_name);
        } else {
            $this->copyDirectRelation($relation, $relation_name);
        }
    }

    protected function copyPivotedRelation($relation, $relation_name)
    {
        $relations = [];
        $relation->get()->each(function ($foreign) use (&$relations) {
            $pivot_attributes = Arr::except($foreign->pivot->getAttributes(), [
                $foreign->pivot->getRelatedKey(),
                $foreign->pivot->getForeignKey(),
                $foreign->pivot->getCreatedAtColumn(),
                $foreign->pivot->getUpdatedAtColumn(),
            ]);
            $relations[$foreign->id] = $pivot_attributes;
        });
        $this->$relation_name()->sync($relations);
    }

    protected function copyDirectRelation($relation, $relation_name)
    {
        $relation->withTrashed()->get()->each(function ($foreign) use ($relation, $relation_name) {
            if ($foreign->trashed()) {
                if (! empty($foreign->cloned_from_id)) {
                    $this->$relation_name()->withTrashed()->find($foreign->cloned_from_id)->delete();
                }
            } else {
                // Media has special handling rules
                if ($relation_name == 'media') {
                    // new media (deleted media handled above)
                    if (empty($foreign->cloned_from_id)) {
                        $foreign->copy($this, $foreign->collection_name);
                    } else {
                        $relation = $this->$relation_name()->withTrashed()->find($foreign->cloned_from_id);
                        // for media
                        if ($relation->custom_properties || $foreign->custom_properties) {
                            $relation->custom_properties = $foreign->custom_properties;
                            $relation->save();
                        }
                    }
                } else {
                    $properties = Arr::except($foreign->attributesToArray(), [
                        'cloned_from_id', // we never want to copy this
                        $foreign->getKeyName(),
                        $foreign->getCreatedAtColumn(),
                        $foreign->getUpdatedAtColumn(),
                        $relation->getForeignKeyName(), // TODO: May need to be adjusted depending on relation type
                    ]);

                    $this->$relation_name()->updateOrCreate(['id' => $foreign->cloned_from_id ?? null], $properties);
                }
            }
        });
    }
}
