<?php

use App\Http\Livewire\Input\ProductSelect;
use App\Models\Product;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Livewire\Livewire;

uses(DatabaseTransactions::class);

it('sets the model attribute', function () {
    Livewire::test(ProductSelect::class, [
        'model' => 'my-product-dropdown',
    ])->assertStatus(200)
        ->assertSet('model', 'my-product-dropdown');
});

it('can render the component without an initial value', function () {
    $options = Product::factory()->count(3)->create();

    Livewire::test(ProductSelect::class, [
        'model' => null,
    ])->assertStatus(200)
        ->assertSet('selections', []);
});

it('can render the component with an initial value', function () {
    $record = Product::factory()->create();

    Livewire::test(ProductSelect::class, [
        'model' => null,
        'initialValue' => $record->id,
    ])->assertStatus(200)
        ->assertSet('selections', $record->id);
});

it('formats the options array correctly', function () {
    $records = Product::factory()->catalogueActive()->count(3)->create();

    $options = $records->map(fn ($item) => [
        'label' => $item->stock_id . ' : ' . $item->name,
        'value' => $item->id,
    ])->sortBy('label')
        ->values()
        ->all();

    Livewire::test(ProductSelect::class, [
        'model' => null,
    ])->assertStatus(200)
        ->assertSet('options', $options);
});

it('emits an event when the options are updated', function () {
    $records = Product::factory()->catalogueActive()->count(1)->create();

    $options = $records->map(fn ($item) => [
        'value' => $item->id,
        'label' => $item->stock_id . ' : ' . $item->name,
    ])->sortBy('label')
        ->filter(fn ($item) => $item['value'] == $records->first()->id)
        ->values()->all();

    Livewire::test(ProductSelect::class, [
        'model' => 'my-product-dropdown',
    ])->call('search', $records[0]->stock_id)
        ->assertStatus(200)
        ->assertSet('options', $options)
        ->assertEmitted('select-options-updated', $options, $records[0]->stock_id);
});
