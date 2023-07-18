<?php

use App\Http\Requests\Brands\BrandStepFormRequest;
use App\Models\Currency;
use App\Models\Vendor;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Validator;

uses(DatabaseTransactions::class);

beforeEach(fn () => $this->request = new BrandStepFormRequest);

it('validates required fields', function ($field, $value) {
    $validator = Validator::make([$field => $value], $this->request->rules());

    expect($validator->passes())->toBeFalse();
    expect($validator->errors()->get($field))->toContain('Required');
})->with([
    ['vendor_id', ''],
    ['name', ''],
    ['currency_id', ''],
    ['description', ''],
    ['description_fr', ''],
]);

it('validates sometimes required fields', function ($field, $value, $additional) {
    $validator = Validator::make(array_merge([$field => $value], $additional), $this->request->rules());

    expect($validator->passes())->toBeFalse();
    expect($validator->messages()->get($field))->toContain('Required');
})->with([
    ['brand_number', '', ['signoff_form' => '1']],
]);

it('validates integer fields', function ($field, $value, $passes) {
    $validator = Validator::make([$field => $value], $this->request->rules());

    if ($passes) {
        expect($validator->errors()->get($field))->not()->toContain('Must be an integer');
    } else {
        expect($validator->passes())->toBeFalse();
        expect($validator->errors()->get($field))->toContain('Must be an integer');
    }
})->with([
    ['vendor_id', 1, true],
    ['currency_id', 1, true],
    ['vendor_id', 'ABC', false],
    ['currency_id', 'ABC', false],
    ['vendor_id', 0.1, false],
    ['currency_id', 0.1, false],
]);

it('validates foreign key fields must be positive integer', function ($field, $value, $passes) {
    $validator = Validator::make([$field => $value], $this->request->rules());

    if ($passes) {
        expect($validator->errors()->get($field))->not()->toContain('Must be at least 1');
    } else {
        expect($validator->passes())->toBeFalse();
        expect($validator->errors()->get($field))->toContain('Must be at least 1');
    }
})->with([
    ['vendor_id', 1, true],
    ['currency_id', 1, true],
    ['vendor_id', 0, false],
    ['currency_id', 0, false],
    ['vendor_id', -1, false],
    ['currency_id', -1, false],
]);

it('validates foreign key fields exist in the database', function ($field, $passes, $class) {
    $model = $class::factory()->create();

    if ($passes) {
        $validator = Validator::make([$field => $model->id], $this->request->rules());
    } else {
        $validator = Validator::make([$field => $model->id + 1], $this->request->rules());
    }

    if ($passes) {
        expect($validator->errors()->get($field))->not()->toContain('The selected value is invalid');
    } else {
        expect($validator->passes())->toBeFalse();
        expect($validator->errors()->get($field))->toContain('The selected value is invalid');
    }
})->with([
    ['vendor_id', true, Vendor::class],
    ['currency_id', true, Currency::class],
    ['vendor_id', false, Vendor::class],
    ['currency_id', false, Currency::class],
]);
