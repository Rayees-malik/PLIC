<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Validator;

class ValidContact implements Rule
{
    protected $contactFields = [
        'contact-id',
        'contact-name',
        'contact-position',
        'contact-email',
        'contact-phone',
    ];

    protected $errorMessage = 'Required';

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct(protected $validationRules = null)
    {
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        [$prefix, $index] = explode('.', $attribute);
        if (request()->input("contact-deleted.{$index}") == '1') {
            return true;
        }

        if (empty($value)) {
            foreach ($this->contactFields as $field) {
                if ($field != $prefix && ! empty(request()->input("{$field}.{$index}"))) {
                    $this->errorMessage = 'Required';

                    return false;
                }
            }

            return true;
        }

        if (! empty($this->validationRules)) {
            $validator = Validator::make(['value' => $value], ['value' => $this->validationRules]);
            if ($validator->fails()) {
                $this->errorMessage = $validator->errors()->first();

                return false;
            }
        }

        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return $this->errorMessage;
    }
}
