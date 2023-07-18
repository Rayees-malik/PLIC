<?php

namespace App\Http\Requests\Users;

use Illuminate\Validation\Rule;

class RoleFormRequest extends \App\Http\Requests\BaseFormRequest
{
    public function rules()
    {
        return [
            'title' => ['required', 'max:255', Rule::unique('roles', 'title')->ignore(request()->name, 'name')],
            'category' => 'required',
            'abilities' => 'required_without:model_abilities',
            'model_abilities' => 'required_without:abilities',
            'description' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'abilities.*' => 'At least one ability is required.',
            'model_abilities.*' => 'At least one ability is required.',
        ];
    }

    public function filters()
    {
        return [
            'title' => 'trim|name_case',
        ];
    }
}
