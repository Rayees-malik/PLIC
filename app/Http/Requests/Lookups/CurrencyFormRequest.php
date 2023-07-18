<?php

namespace App\Http\Requests\Lookups;

class CurrencyFormRequest extends \App\Http\Requests\BaseFormRequest
{
    public function rules()
    {
        return [
            'name' => 'required|max:500',
            'exchange_rate' => 'required|numeric|between:0,100000',
        ];
    }

    public function filters()
    {
        return [
            'name' => 'trim|capitalize',
        ];
    }
}
