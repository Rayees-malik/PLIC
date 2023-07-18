<?php

use App\Http\Requests\Products\RegulatoryStepFormRequest;
use Illuminate\Support\Facades\Validator;

beforeEach(fn () => $this->request = new RegulatoryStepFormRequest);

it('requires npn if the product category is supplements', function () {
    $validator = Validator::make(['npn' => null, 'category_id' => 7], $this->request->rules());

    expect($validator->passes())->toBeFalse();
    expect($validator->errors()->get('npn'))->toContain('Required');
});

it('does not require npn if the product category is not supplements', function () {
    $validator = Validator::make(['category_id' => 4], $this->request->rules());

    expect($validator->errors()->get('npn'))->not()->toContain('Required');
});

it('validates npn if it is present', function () {
    $validator = Validator::make(['npn' => 'N/A'], $this->request->rules());

    expect($validator->errors()->has('npn'))->toBeTrue();
    expect($validator->errors()->get('npn'))->toContain('Must start with NN or be 8 digits');
});

it('does not validate npn if it is blank', function ($value) {
    $validator = Validator::make(['npn' => $value], $this->request->rules());

    expect($validator->errors()->has('npn'))->toBeFalse();
})->with([
    '',
]);

it('validates npn format', function ($value, $message) {
    $validator = Validator::make([
        'npn' => $value,
        'category_id' => 7,
    ], $this->request->rules());

    expect($validator->passes())->toBeFalse();
    expect($validator->errors()->get('npn'))->toContain($message);
})->with([
    ['Z', 'Must start with NN or be 8 digits'],
    ['123', 'Must be 8 digits'],
    ['1234567890', 'Must be 8 digits'],
]);

it('does not validate npn format if it is not required and not present', function () {
    $validator = Validator::make([
        'category_id' => 4,
    ], $this->request->rules());

    expect($validator->passes())->toBeTrue();
});
