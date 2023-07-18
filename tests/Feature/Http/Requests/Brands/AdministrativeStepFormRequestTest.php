<?php

use App\Http\Requests\Brands\BrandSaveRequiredFormRequest;
use App\Models\Vendor;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Validator;

uses(DatabaseTransactions::class);

beforeEach(fn () => $this->request = new BrandSaveRequiredFormRequest);

it('validates required fields', function ($field, $value) {
    $validator = Validator::make([$field => $value], $this->request->rules());

    expect($validator->passes())->toBeFalse();
    expect($validator->errors()->get($field))->toContain('Required');
})->with([
    ['vendor_id', ''],
    ['name', ''],
    ['description', ''],
    ['description_fr', ''],
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
    ['vendor_id', 'ABC', false],
    ['vendor_id', 0.1, false],
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
    ['vendor_id', 0, false],
    ['vendor_id', -1, false],
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
]);
