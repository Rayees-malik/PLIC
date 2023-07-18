<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class ValidUPC implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
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
        //see: http://www.gs1.org/how-calculate-check-digit-manually
        //we accept only digits
        if (! preg_match('/^[0-9]+$/', $value)) {
            return false;
        }

        //check valid lengths:
        $l = strlen($value);
        if (! in_array($l, [8, 12, 13, 14, 17, 18])) {
            return false;
        }

        //get check digit
        $check = substr($value, -1);

        $barcode = substr($value, 0, -1);
        $sum_even = $sum_odd = 0;
        $even = true;
        while (strlen($barcode) > 0) {
            $digit = substr($barcode, -1);
            if ($even) {
                $sum_even += 3 * $digit;
            } else {
                $sum_odd += $digit;
            }

            $even = ! $even;
            $barcode = substr($barcode, 0, -1);
        }
        $sum = $sum_even + $sum_odd;
        $sum_rounded_up = ceil($sum / 10) * 10;

        return $check == ($sum_rounded_up - $sum);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Valid UPC Required';
    }
}
