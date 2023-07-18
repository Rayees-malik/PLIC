<?php

namespace App;

use Altek\Accountant\Models\Ledger as BaseLedger;

class Ledger extends BaseLedger
{
    protected $casts = [
        'properties' => 'json',
        'modified' => 'json',
        'pivot' => 'json',
        'extra' => 'json',
    ];

    public static function boot()
    {
        parent::boot();

        static::creating(function (BaseLedger $model) {
            if ($model->event == 'created' || $model->event == 'updated') {
                if (empty($model->modified) && empty($model->pivot)) {
                    return false;
                }
            }
        });
    }
}
