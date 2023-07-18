<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
     */

    'accepted' => 'This must be accepted',
    'active_url' => 'A valid URL is required',
    'after' => 'Must be a date after :date',
    'after_or_equal' => 'Must be a date after or equal to :date',
    'alpha' => 'May only contain letters',
    'alpha_dash' => 'May only contain letters, numbers, dashes and underscores',
    'alpha_num' => 'May only contain letters and numbers',
    'array' => 'Must be an array',
    'before' => 'Must be a date before :date',
    'before_or_equal' => 'Must be a date before or equal to :date',
    'between' => [
        'numeric' => 'Must be between :min and :max',
        'file' => 'Must be between :min and :max kilobytes',
        'string' => 'Must be between :min and :max characters',
        'array' => 'Must have between :min and :max items',
    ],
    'boolean' => 'Must be true or false',
    'confirmed' => 'Confirmation does not match',
    'date' => 'Not a valid date',
    'date_equals' => 'Must be a date equal to :date',
    'date_format' => 'Does not match the format :format',
    'different' => 'Must be different from :other',
    'digits' => 'Must be :digits digits',
    'digits_between' => 'Must be between :min and :max digits',
    'dimensions' => 'Invalid image dimensions',
    'distinct' => 'Field has a duplicate value',
    'email' => 'Invalid email address',
    'exists' => 'The selected value is invalid',
    'file' => 'Must be a file',
    'filled' => 'Required',
    'gt' => [
        'numeric' => 'Must be greater than :value',
        'file' => 'Must be greater than :value kilobytes',
        'string' => 'Must be greater than :value characters',
        'array' => 'Must have more than :value items',
    ],
    'gte' => [
        'numeric' => 'Must be greater than or equal :value',
        'file' => 'Must be greater than or equal :value kilobytes',
        'string' => 'Must be greater than or equal :value characters',
        'array' => 'Must have :value items or more',
    ],
    'image' => 'Must be an image',
    'in' => 'Selected value is invalid',
    'in_array' => 'Value does not exist in :other',
    'integer' => 'Must be an integer',
    'ip' => 'Must be a valid IP address',
    'ipv4' => 'Must be a valid IPv4 address',
    'ipv6' => 'Must be a valid IPv6 address',
    'json' => 'Must be a valid JSON string',
    'lt' => [
        'numeric' => 'Must be less than :value',
        'file' => 'Must be less than :value kilobytes',
        'string' => 'Must be less than :value characters',
        'array' => 'Must have less than :value items',
    ],
    'lte' => [
        'numeric' => 'Must be less than or equal :value',
        'file' => 'Must be less than or equal :value kilobytes',
        'string' => 'Must be less than or equal :value characters',
        'array' => 'Must not have more than :value items',
    ],
    'max' => [
        'numeric' => 'May not be greater than :max',
        'file' => 'May not be greater than :max kilobytes',
        'string' => 'May not be greater than :max characters',
        'array' => 'May not have more than :max items',
    ],
    'mimes' => 'Must be a file of type: :values',
    'mimetypes' => 'Must be a file of type: :values',
    'min' => [
        'numeric' => 'Must be at least :min',
        'file' => 'Must be at least :min kilobytes',
        'string' => 'Must be at least :min characters',
        'array' => 'Must have at least :min items',
    ],
    'not_in' => 'The selected value is invalid',
    'not_regex' => 'Format is invalid',
    'numeric' => 'Must be a number',
    'phone' => 'Invalid phone number',
    'present' => 'Must be present',
    'regex' => 'Format is invalid',
    'required' => 'Required',
    'required_if' => 'Required',
    'required_unless' => 'Required',
    'required_with' => 'Required',
    'required_with_all' => 'Required',
    'required_without' => 'Required',
    'required_without_all' => 'Required',
    'same' => 'Must match :other',
    'size' => [
        'numeric' => 'Must be :size',
        'file' => 'Must be :size kilobytes',
        'string' => 'Must be :size characters',
        'array' => 'Must contain :size items',
    ],
    'starts_with' => 'Must start with one of the following: :values',
    'string' => 'Must be a string',
    'timezone' => 'Must be a valid zone',
    'unique' => 'The :attribute has already been taken',
    'uploaded' => 'Failed to upload',
    'url' => 'URL format is invalid',
    'uuid' => 'Must be a valid UUID',

    // Custom
    'product_category_has' => 'Required',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
     */

    'custom' => [
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap our attribute placeholder
    | with something more reader friendly such as "E-Mail Address" instead
    | of "email". This simply helps us make our message more expressive.
    |
     */

    'attributes' => [],

];
