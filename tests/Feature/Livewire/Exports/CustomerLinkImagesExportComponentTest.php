<?php

use App\Http\Livewire\Exports\CustomerLinkImagesExportComponent;
use Livewire\Livewire;

it('can be rendered', function () {
    $component = Livewire::test(CustomerLinkImagesExportComponent::class);

    $component->assertStatus(200);
});
