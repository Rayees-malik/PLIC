<?php

namespace App\Http\Requests\Lookups;

class BrokerFormRequest extends \App\Http\Requests\BaseFormRequest
{
    public function rules()
    {
        return [
            'name' => 'required|max:500',
        ];
    }

    public function filters()
    {
        return [
            'name' => 'trim|name_case',
        ];
    }
}
