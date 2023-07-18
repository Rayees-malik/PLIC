<?php

namespace App\Http\Requests;

use App\Rules\ValidContact;
use App\SteppedFormRequest;

class ContactFormRequest extends SteppedFormRequest
{
    public function rules()
    {
        return [
            'contact-id.*' => 'present',

            'contact-role' => 'required|array',
            'contact-name' => 'required|array',
            'contact-position' => 'required|array',
            'contact-email' => 'required|array',
            'contact-phone' => 'required|array',
            'contact-deleted' => 'nullable',

            'contact-role.*' => 'required|string',
            'contact-name.*' => [new ValidContact],
            'contact-position.*' => [new ValidContact],
            'contact-email.*' => [new ValidContact('email:filter')],
            'contact-phone.*' => [new ValidContact('phone:AUTO,CA,US')],
        ];
    }

    public function filters()
    {
        return [
            'contact-name.*' => 'trim|capitalize',
            'contact-position.*' => 'trim|capitalize',
            'contact-email.*' => 'trim|lowercase',
        ];
    }
}
