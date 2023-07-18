<?php

namespace App\Http\Requests\Users;

use Illuminate\Validation\Rule;

class AbilityFormRequest extends \App\Http\Requests\BaseFormRequest
{
    public function rules()
    {
        return [
            'title' => ['required', 'max:255', Rule::unique('abilities', 'title')->ignore(request()->name, 'name')],
            'category' => 'required',
            'description' => 'required',
        ];
    }

    public function filters()
    {
        return [
            'title' => 'name_case',
            'category' => 'name_case',
        ];
    }
}
