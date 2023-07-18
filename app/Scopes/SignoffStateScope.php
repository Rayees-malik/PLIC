<?php

namespace App\Scopes;

use App\Helpers\SignoffStateHelper;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class SignoffStateScope implements Scope
{
    protected $extensions = [
        'WithPending',
        'AllStates',
        'Initial',
        'Pending',
        'Approved',
        'Rejected',
        'Archived',
        'InProgress',
    ];

    public function apply($query, Model $model)
    {
        $query->where("{$model->getTable()}.{$model->stateField()}", SignoffStateHelper::INITIAL);
    }

    public function extend($query)
    {
        foreach ($this->extensions as $extension) {
            $this->{"add{$extension}"}($query);
        }
    }

    protected function addWithPending($query)
    {
        $query->macro('withPending', function ($query) {
            $model = $query->getModel();

            return $query
                ->withoutGlobalScope($this)
                ->where(function ($query) use ($model) {
                    return $query->where("{$model->getTable()}.{$model->stateField()}", SignoffStateHelper::INITIAL)
                        ->orWhere(function ($query) use ($model) {
                            $query->where("{$model->getTable()}.{$model->stateField()}", SignoffStateHelper::PENDING)->whereHas('signoffs');
                        });
                });
        });
    }

    protected function addAllStates($query)
    {
        $query->macro('allStates', function ($query) {
            return $query->withoutGlobalScope($this);
        });
    }

    protected function addInitial($query)
    {
        $query->macro('initial', function ($query) {
            $model = $query->getModel();

            return $query->withoutGlobalScope($this)->where("{$model->getTable()}.{$model->stateField()}", SignoffStateHelper::INITIAL);
        });
    }

    protected function addApproved($query)
    {
        $query->macro('approved', function ($query) {
            $model = $query->getModel();

            return $query->withoutGlobalScope($this)->where("{$model->getTable()}.{$model->stateField()}", SignoffStateHelper::APPROVED);
        });
    }

    protected function addPending($query)
    {
        $query->macro('pending', function ($query) {
            $model = $query->getModel();

            return $query->withoutGlobalScope($this)->where("{$model->getTable()}.{$model->stateField()}", SignoffStateHelper::PENDING);
        });
    }

    protected function addRejected($query)
    {
        $query->macro('rejected', function ($query) {
            $model = $query->getModel();

            return $query->withoutGlobalScope($this)->where("{$model->getTable()}.{$model->stateField()}", SignoffStateHelper::REJECTED);
        });
    }

    protected function addArchived($query)
    {
        $query->macro('archived', function ($query) {
            $model = $query->getModel();

            return $query->withoutGlobalScope($this)->where("{$model->getTable()}.{$model->stateField()}", SignoffStateHelper::ARCHIVED);
        });
    }

    protected function addInProgress($query)
    {
        $query->macro('inProgress', function ($query) {
            $model = $query->getModel();

            return $query->withoutGlobalScope($this)->where("{$model->getTable()}.{$model->stateField()}", SignoffStateHelper::IN_PROGRESS);
        });
    }
}
