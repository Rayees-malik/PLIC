<?php

namespace App\Http\Requests\Lookups;

class CountryFormRequest extends \App\Http\Requests\BaseFormRequest
{
    public function rules()
    {
        return [
            'name' => 'required|max:250',
            'alpha2' => 'required|min:2|max:2',
            'alpha3' => 'required|min:3|max:3',
        ];
    }

    public function filters()
    {
        return [
            'name' => 'trim|capitalize',
            'alpha2' => 'trim|uppercase',
            'alpha3' => 'trim|uppercase',
        ];
    }
}
