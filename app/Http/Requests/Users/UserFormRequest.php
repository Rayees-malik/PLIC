<?php

namespace App\Http\Requests\Users;

class UserFormRequest extends \App\Http\Requests\BaseFormRequest
{
    public function rules()
    {
        $checkId = $this->id ?? ($this->getMethod() == 'PATCH' ? auth()->id() : null);
        $rules = [
            'name' => 'required|max:255',
            'email' => "required|email:filter|unique:users,email,{$checkId}",
            'password' => [
                'required',
                'min:8',
                'regex:/^(?=.{8,})((?=.*\d)(?=.*[a-z])(?=.*[A-Z])|(?=.*\d)(?=.*[a-zA-Z])(?=.*[\W_])|(?=.*[a-z])(?=.*[A-Z])(?=.*[\W_])).*$/',
                'confirmed',
            ],
            'password_confirmation' => 'same:password',
            'roles' => 'sometimes|array',
            'vendor_id' => 'sometimes',
            'broker_id' => 'sometimes',
            'vendor_user_type' => 'sometimes',
            'subscriptions' => 'sometimes',
        ];

        if ($this->getMethod() == 'PATCH') {
            $rules['password'] = 'nullable|confirmed';
            $rules['password_confirmation'] = 'required_with:password|same:password';
        } else {
            $rules['password'] = 'required|confirmed';
            $rules['password_confirmation'] = 'same:password';
        }

        return $rules;
    }

    public function messages()
    {
        return [
            'password.regex' => 'Passwords must use at least three of the four available character types: lowercase letters, uppercase letters, numbers, and symbols',
        ];
    }

    public function filters()
    {
        return [
            'email' => 'trim|lowercase',
            'name' => 'trim',
        ];
    }
}
