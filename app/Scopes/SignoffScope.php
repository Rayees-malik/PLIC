<?php

namespace App\Scopes;

use App\Helpers\SignoffStateHelper;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class SignoffScope implements Scope
{
    protected $extensions = [
        'Pending',
        'Approved',
        'Rejected',
        'Archived',
        'InProgress',
        'FromInitial',
        'HasInitial',
        'HasInitialOrProposed',
        'ForUser',
    ];

    public function apply($query, Model $model)
    {
        return $query;
    }

    public function extend($query)
    {
        foreach ($this->extensions as $extension) {
            $this->{"add{$extension}"}($query);
        }
    }

    public function addFromInitial($query)
    {
        $query->macro('fromInitial', function ($query, $intial) {
            return $query->where('initial_id', $initial->id);
        });
    }

    public function addHasInitial($query)
    {
        $query->macro('hasInitial', function ($query, Model $model) {
            return $query->where([
                'initial_type' => get_class($model),
                'initial_id' => $model->id,
            ]);
        });
    }

    public function addHasInitialOrProposed($query)
    {
        $query->macro('hasInitialOrProposed', function ($query, Model $model) {
            return $query->where(function ($query) use ($model) {
                $query->where(function ($query) use ($model) {
                    $query->where([
                        'initial_type' => get_class($model),
                        'initial_id' => $model->id,
                    ]);
                })->orWhere(function ($query) use ($model) {
                    $query->where([
                        'proposed_type' => get_class($model),
                        'proposed_id' => $model->id,
                    ]);
                });
            });
        });
    }

    public function addForUser($query)
    {
        $query->macro('forUser', function ($query, $user = null) {
            if (! $user) {
                $user = auth()->user();
            }

            if ($user->can('admin')) {
                return $query;
            }

            // If a user cannot signoff, return a query with no matches
            if ($user->cannot('signoff')) {
                return $query->whereRaw('1 = 0');
            }

            return $query->whereHas('signoffConfigSteps', function ($query) use ($user) {
                $query->whereColumn('step', 'signoffs.step')->whereHas('abilities', function ($query) use ($user) {
                    $query->whereIn('abilities.name', $user->getAbilities()->pluck('name'));
                });
            })->whereHasMorph('initial', '*', function ($query) use ($user) {
                $query->allStates()->signoffFilter($user);
            })->whereDoesntHave('responses', function ($query) use ($user) {
                $query->where('user_id', $user->id)
                    ->where('archived', false)
                    ->whereRaw('signoff_responses.step = signoffs.step');
            });
        });
    }

    protected function addApproved($query)
    {
        $query->macro('approved', function ($query) {
            return $query->where('state', SignoffStateHelper::APPROVED);
        });
    }

    protected function addPending($query)
    {
        $query->macro('pending', function ($query) {
            return $query->where('state', SignoffStateHelper::PENDING);
        });
    }

    protected function addRejected($query)
    {
        $query->macro('rejected', function ($query) {
            return $query->where('state', SignoffStateHelper::REJECTED);
        });
    }

    protected function addArchived($query)
    {
        $query->macro('archived', function ($query) {
            return $query->where('state', SignoffStateHelper::ARCHIVED);
        });
    }

    protected function addInProgress($query)
    {
        $query->macro('inProgress', function ($query) {
            return $query->where('state', SignoffStateHelper::IN_PROGRESS);
        });
    }
}
