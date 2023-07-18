<?php

namespace App\Http\Requests\Lookups;

class UnitOfMeasureFormRequest extends \App\Http\Requests\BaseFormRequest
{
    public function rules()
    {
        return [
            'unit' => 'required|max:100',
            'description' => 'required|max:250',
        ];
    }

    public function filters()
    {
        return [
            'unit' => 'trim|lowercase',
            'description' => 'trim|capitalize',
        ];
    }
}
