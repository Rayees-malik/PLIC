<?php

use App\Http\Requests\Products\ProductFormRequest;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Validator;

uses(DatabaseTransactions::class);

it('brand stock id is required', function () {
    $request = new ProductFormRequest;

    $data = [
        'brand_stock_id' => null,
    ];

    $validator = Validator::make(
        $data,
        $request->rules()
    );

    expect($validator->fails())->toBe(true);
    expect($validator->errors())->toHaveKey('brand_stock_id');
    expect($validator->errors()->get('brand_stock_id')[0])->toBe('Required');
});

it('brand stock id must be less than or equal to 15 characters', function () {
    $request = new ProductFormRequest;

    $data = [
        'brand_stock_id' => str_repeat('A', 16),
    ];

    $validator = Validator::make(
        $data,
        $request->rules()
    );

    expect($validator->fails())->toBe(true);
    expect($validator->errors())->toHaveKey('brand_stock_id');
    expect($validator->errors()->get('brand_stock_id')[0])->toBe('May not be greater than 15 characters');

    $data = [
        'brand_stock_id' => str_repeat('A', 15),
    ];

    $validator = Validator::make(
        $data,
        $request->rules()
    );

    expect($validator->errors())->not()->toHaveKey('brand_stock_id');
});
