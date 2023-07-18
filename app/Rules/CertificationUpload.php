<?php

namespace App\Rules;

use App\Models\Certification;
use Illuminate\Contracts\Validation\Rule;

class CertificationUpload implements Rule
{
    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        if ($value == '0') {
            return true;
        }

        $cert = Certification::find($value);
        if (! $cert || ! $cert->requires_documentation) {
            return true;
        }

        $fileInput = 'fileuploader-list-' . str_replace('.', '_', $attribute);

        return isset(request()->{$fileInput}) && request()->{$fileInput} !== '[]';
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Certification document required';
    }
}
