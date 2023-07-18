<?php

use App\Http\Requests\PricingAdjustments\PricingAdjustmentLineItemFormRequest;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Validator;

uses(DatabaseTransactions::class);

it('does not have a valid value for total mcb', function ($data) {
    $request = new PricingAdjustmentLineItemFormRequest;

    $data = array_merge([
        'morph_id' => [
            1,
        ],
        'morphy_type' => [
            'SomeClass::class',
        ],
        'total_discount' => [
            0,
        ],
        'who_to_mcb' => [
            'Someone',
        ],
    ], $data);

    $validator = Validator::make(
        $data,
        $request->rules()
    );

    expect($validator->fails())->toBe(true);
    expect($validator->errors()->keys())->toContain('total_mcb.0');
})->with([
    [[
        'total_mcb' => [
            '15fats',
        ],
    ]],
    [[
        'total_mcb' => [
            '15.fats',
        ],
    ]],
    [[
        'total_mcb' => [
            'fats.15',
        ],
    ]],
    [[
        'total_mcb' => [
            'fats',
        ],
    ]],
    [[
        'total_mcb' => [
            'fats.cans',
        ],
    ]],
    [[
        'total_mcb' => [
            'fats15',
        ],
    ]],
    [[
        'total_mcb' => [
            '.',
        ],
    ]],
    [[
        'total_mcb' => [
            '4.',
        ],
    ]],
    [[
        'total_mcb' => [
            '.5',
        ],
    ]],
    [[
        'total_mcb' => [
            '.58',
        ],
    ]],
]);

it('has a valid value for total mcb', function (array $data) {
    $request = new PricingAdjustmentLineItemFormRequest;

    $data = array_merge([
        'morph_id' => [
            1,
        ],
        'morphy_type' => [
            'SomeClass::class',
        ],
    ], $data);

    $validator = Validator::make(
        $data,
        $request->rules()
    );

    expect($validator->passes())->toBe(true);
    expect($validator->errors()->keys())->not()->toContain('total_mcb');
})->with([
    [[
        'total_mcb.0' => [
            '0',
        ],
    ]],
    [[
        'who_to_mcb.0' => [
            'Someone',
        ],
        'total_mcb.0' => [
            '0.5',
        ],
    ]],
    [[
        'who_to_mcb.0' => [
            'Someone',
        ],
        'total_mcb.0' => [
            '0.25',
        ],
    ]],
    [[
        'who_to_mcb.0' => [
            'Someone',
        ],
        'total_mcb.0' => [
            '15',
        ],
    ]],
    [[
        'who_to_mcb.0' => [
            'Someone',
        ],
        'total_mcb.0' => [
            '15.0',
        ],
    ]],
    [[
        'who_to_mcb.0' => [
            'Someone',
        ],
        'total_mcb' => [
            '15.00',
        ],
    ]],
]);
