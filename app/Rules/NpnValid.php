<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\DataAwareRule;
use Illuminate\Contracts\Validation\InvokableRule;
use Illuminate\Contracts\Validation\ValidatorAwareRule;

class NpnValid implements InvokableRule, DataAwareRule, ValidatorAwareRule
{
    protected $data = [];

    protected $validator;

    /**
     * Run the validation rule.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @param  Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     * @return void
     */
    public function __invoke($attribute, $value, $fail)
    {
        if (is_null($value)) {
            return;
        }

        // Veterinary NPNs start with 'NN'
        if (! is_numeric($value) && ! str_starts_with($value, 'NN')) {
            $fail('Must start with NN or be 8 digits');
        }

        // Non-veterinary NPNs must be 8 digits
        if (is_numeric($value) && strlen($value) != 8) {
            $fail('Must be 8 digits');
        }
    }

    public function setValidator($validator)
    {
        $this->validator = $validator;

        return $this;
    }

    public function setData($data)
    {
        $this->data = $data;

        return $this;
    }
}
