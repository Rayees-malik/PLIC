<?php

namespace App\Scopes;

use App\Helpers\StatusHelper;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class StatusScope implements Scope
{
    protected $extensions = [
        'Active',
        'Legacy',
    ];

    public function apply($query, Model $model)
    {
        $query->where("{$model->getTable()}.status", '<>', StatusHelper::LEGACY);
    }

    public function extend($query)
    {
        foreach ($this->extensions as $extension) {
            $this->{"add{$extension}"}($query);
        }
    }

    protected function addActive($query)
    {
        $query->macro('active', function ($query) {
            $model = $query->getModel();

            return $query->where("{$model->getTable()}.status", StatusHelper::ACTIVE);
        });
    }

    protected function addLegacy($query)
    {
        $query->macro('legacy', function ($query) {
            $model = $query->getModel();

            return $query->withoutGlobalScope($this)->where("{$model->getTable()}.status", StatusHelper::LEGACY);
        });
    }
}
