<?php

use App\Http\Livewire\QualityControlForm;
use App\Models\Product;
use App\Models\QualityControlRecord;
use App\Models\RegulatoryInfo;
use App\Models\Vendor;
use App\Models\Warehouse;
use App\Rules\NpnValid;
use App\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;

uses(DatabaseTransactions::class);

it('validates date formats', function (string $field, mixed $value, string $rule) {
    // Arrange
    $user = User::factory()->create();

    // Assert
    Livewire::actingAs($user)
        ->test(QualityControlForm::class)
        ->set($field, $value)
        ->call('submit')
        ->assertHasNoErrors([$field => $rule]);
})->with([
    'received date can be in format Y-m-d' => ['record.received_date', '2022-01-25', 'date_format:"Y-m-d"'],
    'received date can be in format Y/m/d' => ['record.received_date', '2022/01/25', 'date_format:"Y/m/d"'],
    'expiry date can be in format Y-m-d' => ['record.expiry_date', '2022-01-25', 'date_format:"Y-m-d"'],
    'expiry date can be in format Y/m/d' => ['record.expiry_date', '2022/01/25', 'date_format:"Y/m/d"'],
]);

it('validates invalid fields', function (string $field, mixed $value, string $rule) {
    // Arrange
    $user = User::factory()->create();

    // Assert
    Livewire::actingAs($user)
        ->test(QualityControlForm::class)
        ->set($field, $value)
        ->call('submit')
        ->assertHasErrors([$field => $rule]);
})->with([
    'po number is required' => ['record.po_number', null, 'required'],
    'received date is required' => ['record.received_date', null, 'required'],
    'received date is date' => ['record.received_date', 'NOT A DATE', 'date_format:Y-m-d'],
    'product id is required' => ['record.product_id', null, 'required'],
    'quantity received is numeric' => ['record.quantity_received', 'ABC', 'numeric'],
    'quantity received is required' => ['record.quantity_received', null, 'required'],
    'quantity received must be at least 1' => ['record.quantity_received', 0, 'min:1'],
    'lot number is required' => ['record.lot_number', null, 'required'],
    'expiry date is required' => ['record.expiry_date', null, 'required'],
    'expiry date is a date' => ['record.expiry_date', 'NOT A DATE', 'date_format:Y-m-d'],
    'bin number is required' => ['record.bin_number', null, 'required'],
    'identity description is required' => ['record.identity_description', null, 'required'],
    'vendor id is required' => ['record.vendor_id', null, 'required'],
    'din npn on label is required' => ['record.din_npn_on_label', null, 'required'],
    'importer address is required' => ['record.importer_address', null, 'required'],
    'receiving comment is required' => ['record.receiving_comment', null, 'required'],
    'regulatory compliance comment is required' => ['record.regulatory_compliance_comment', null, 'required'],
]);

it('validates NPN field', function ($field, $value, $rule) {
    // Arrange
    $user = User::factory()->create();

    // Assert
    Livewire::actingAs($user)
        ->test(QualityControlForm::class)
        ->set($field, $value)
        ->call('submit')
        ->assertHasErrors([$field => $rule]);
})->with([
    'din npn number is required' => ['record.din_npn_number', null, 'required'],
    'din npn number only allows alpha values starting with NN' => ['record.din_npn_number', 'Z', NpnValid::class],
    'din npm number cannot have less than 8 digits if numeric' => ['record.din_npn_number', '123', NpnValid::class],
    'din npm number cannot have more than 8 digits if numeric' => ['record.din_npn_number', '123456789', NpnValid::class],
]);

it('requires out of specification comment if identity description does not match', function () {
    // Arrange
    $user = User::factory()->create();
    $user->allow('create', QualityControlRecord::class);

    $product = Product::factory()->create([
        'identity_description' => 'Test Identity Description',
    ]);

    // Act & Assert
    $response = Livewire::actingAs($user)
        ->test(QualityControlForm::class)
        ->set('record.identity_description', 'Test Identity Description')
        ->set('record.matches_written_specification', false)
        ->call('submit')
        ->assertHasErrors(['record.out_of_specifications_comment' => 'required_if']);
});

it('allows files to be nullable', function () {
    // Arrange
    $user = User::factory()->create();

    // Act & Assert
    Livewire::actingAs($user)
        ->test(QualityControlForm::class)
        ->set('newFiles.*', null)
        ->call('submit')
        ->assertHasNoErrors(['newFiles.*' => 'nullable']);
});

it('validates damage report fields are required', function (string $field, mixed $value, string $rule) {
    $user = User::factory()->create();

    // Act & Assert
    Livewire::actingAs($user)
        ->test(QualityControlForm::class)
        ->set($field, $value)
        ->call('submit')
        ->assertHasErrors([$field => $rule]);
})->with([
    'number_damaged_cartons is required' => ['record.number_damaged_cartons', null, 'required'],
    'number_damaged_units is required' => ['record.number_damaged_units', null, 'required'],
    'number_to_reject_destroy is required' => ['record.number_to_reject_destroy', null, 'required'],
    'nature_of_damage is required' => ['record.nature_of_damage', null, 'required'],
]);

it('validates sample testing report fields are required', function (string $field, mixed $value, string $rule) {
    // Arrange
    $user = User::factory()->create();

    // Act & Assert
    Livewire::actingAs($user)
        ->test(QualityControlForm::class)
        ->set($field, $value)
        ->call('submit')
        ->assertHasErrors([$field => $rule]);
})->with([
    'number_units_sent_for_testing is required' => ['record.number_units_sent_for_testing', null, 'required'],
    'number_units_for_stability is required' => ['record.number_units_for_stability', null, 'required'],
    'number_units_retained is required' => ['record.number_units_retained', null, 'required'],
]);

it('has no default values for radio button fields when creating a new record', function () {
    // Arrange
    $user = User::factory()->create();

    // Act & Assert
    Livewire::actingAs($user)
        ->test(QualityControlForm::class)
        ->assertSet('record.number_units_sent_for_testing', null)
        ->assertSet('record.number_units_for_stability', null)
        ->assertSet('record.number_units_retained', null)
        ->assertSet('record.matches_written_specification', null);
});

it('validates damage report field values', function (string $field, mixed $value, string $rule) {
    $user = User::factory()->create();

    // Act & Assert
    Livewire::actingAs($user)
        ->test(QualityControlForm::class)
        ->set($field, $value)
        ->call('submit')
        ->assertHasErrors([$field => $rule]);
})->with([
    'number_damaged_cartons must be numeric' => ['record.number_damaged_cartons', 'ABC', 'numeric'],
    'number_damaged_cartons must be greater than zero' => ['record.number_damaged_cartons', -1, 'min:0'],
    'number_damaged_cartons cannot have decimals' => ['record.number_damaged_cartons', 1.1, 'integer'],
    'number_damaged_units must be numeric' => ['record.number_damaged_units', 'ABC', 'numeric'],
    'number_damaged_units must be greater than zero' => ['record.number_damaged_units', -1, 'min:0'],
    'number_damaged_units cannot have decimals' => ['record.number_damaged_units', 1.1, 'integer'],
    'number_to_reject_destroy must be numeric' => ['record.number_to_reject_destroy', 'ABC', 'numeric'],
    'number_to_reject_destroy must be greater than zero' => ['record.number_to_reject_destroy', -1, 'min:0'],
    'number_to_reject_destroy cannot have decimals' => ['record.number_to_reject_destroy', 1.1, 'integer'],
]);

it('validates damage report number counts can be zero', function (string $field, mixed $value, string $rule) {
    $user = User::factory()->create();

    // Act & Assert
    Livewire::actingAs($user)
        ->test(QualityControlForm::class)
        ->set($field, $value)
        ->call('submit')
        ->assertHasNoErrors([$field => $rule]);
})->with([
    'number_damaged_cartons can be zero' => ['record.number_damaged_cartons', 0, 'min:0'],
    'number_damaged_units can be zero' => ['record.number_damaged_units', 0, 'min:0'],
    'number_to_reject_destroy can be zero' => ['record.number_to_reject_destroy', 0, 'min:0'],
]);

it('validates sample testing report field values', function (string $field, mixed $value, string $rule) {
    $user = User::factory()->create();

    // Act & Assert
    Livewire::actingAs($user)
        ->test(QualityControlForm::class)
        ->set($field, $value)
        ->call('submit')
        ->assertHasErrors([$field => $rule]);
})->with([
    'number_units_sent_for_testing must be numeric' => ['record.number_units_sent_for_testing', 'ABC', 'numeric'],
    'number_units_sent_for_testing must be greater than zero' => ['record.number_units_sent_for_testing', -1, 'min:0'],
    'number_units_sent_for_testing cannot have decimals' => ['record.number_units_sent_for_testing', 1.1, 'integer'],
    'number_units_for_stability must be numeric' => ['record.number_units_for_stability', 'ABC', 'numeric'],
    'number_units_for_stability must be greater than zero' => ['record.number_units_for_stability', -1, 'min:0'],
    'number_units_for_stability cannot have decimals' => ['record.number_units_for_stability', 1.1, 'integer'],
    'number_units_retained must be numeric' => ['record.number_units_retained', 'ABC', 'numeric'],
    'number_units_retained must be greater than zero' => ['record.number_units_retained', -1, 'min:0'],
    'number_units_retained cannot have decimals' => ['record.number_units_retained', 1.1, 'integer'],
]);

it('validates sample testing report number counts can be zero', function (string $field, mixed $value, string $rule) {
    $user = User::factory()->create();

    // Act & Assert
    Livewire::actingAs($user)
        ->test(QualityControlForm::class)
        ->set($field, $value)
        ->call('submit')
        ->assertHasNoErrors([$field => $rule]);
})->with([
    'number_units_sent_for_testing can be zero' => ['record.number_units_sent_for_testing', 0, 'min:0'],
    'number_units_for_stability can be zero' => ['record.number_units_for_stability', 0, 'min:0'],
    'number_units_retained can be zero' => ['record.number_units_retained', 0, 'min:0'],
]);

it('allows uploading files', function () {
    // Arrange
    $user = User::factory()->create();
    $user->allow('create', QualityControlRecord::class);

    $vendor = Vendor::factory()->create();
    $product = Product::factory()->create();

    Storage::fake('tmp-for-tests');
    $file = UploadedFile::fake()->image('certificate.jpg');

    // Act
    Livewire::actingAs($user)
        ->test(QualityControlForm::class)
        ->set('newFiles', $file)
        ->set('record.vendor_id', $vendor->id)
        ->set('record.warehouse_id', 1)
        ->set('record.po_number', 'PO-123456')
        ->set('record.received_date', '2021-01-01')
        ->set('record.product_id', $product->id)
        ->set('record.quantity_received', 1)
        ->set('record.lot_number', 'LT-123456')
        ->set('record.expiry_date', '2021-01-01')
        ->set('record.bin_number', '123456')
        ->set('record.din_npn_number', '12345678')
        ->set('record.identity_description', 'Test Identity Description')
        ->set('record.matches_written_specification', true)
        ->set('record.number_units_sent_for_testing', 1)
        ->set('record.number_units_for_stability', 1)
        ->set('record.number_units_retained', 1)
        ->set('record.number_damaged_cartons', 1)
        ->set('record.number_damaged_units', 2)
        ->set('record.number_to_reject_destroy', 1)
        ->set('record.nature_of_damage', 3)
        ->set('record.seals_intact', true)
        ->set('record.receiving_comment', 'Test Receiving Comment')
        ->set('record.din_npn_on_label', true)
        ->set('record.importer_address', true)
        ->set('record.bilingual_label', true)
        ->set('record.regulatory_compliance_comment', 'Test Regulatory Compliance Comment')
        ->call('submit')
        ->assertHasNoErrors();

    // Assert
    $record = QualityControlRecord::first();
    expect($record->getMedia('qc-files')->count())->toBe(1);
    expect($record->media()->count())->toBe(1);
});

it('saves the record', function () {
    // Arrange
    $user = User::factory()->create();
    $user->allow('create', QualityControlRecord::class);
    $vendor = Vendor::factory()->create();
    $product = Product::factory()->create();

    expect(QualityControlRecord::count())->toBe(0);

    // Act
    Livewire::actingAs($user)
        ->test(QualityControlForm::class)
        ->set('record.po_number', 'ABC123')
        ->set('record.product_id', $product->id)
        ->set('record.vendor_id', $vendor->id)
        ->set('record.warehouse_id', 1)
        ->set('record.quantity_received', 1)
        ->set('record.expiry_date', '2021-01-01')
        ->set('record.bin_number', 'A1')
        ->set('record.din_npn_number', '12345678')
        ->set('record.lot_number', 'POC-456')
        ->set('record.identity_description', 'Test Identity Description')
        ->set('record.received_date', '2021-01-01')
        ->set('record.matches_written_specification', true)
        ->set('record.number_units_sent_for_testing', 1)
        ->set('record.number_units_for_stability', 1)
        ->set('record.number_units_retained', 1)
        ->set('record.number_damaged_cartons', 1)
        ->set('record.number_damaged_units', 2)
        ->set('record.number_to_reject_destroy', 1)
        ->set('record.nature_of_damage', 3)
        ->set('record.seals_intact', true)
        ->set('record.receiving_comment', 'Test Receiving Comment')
        ->set('record.din_npn_on_label', true)
        ->set('record.importer_address', true)
        ->set('record.bilingual_label', true)
        ->set('record.regulatory_compliance_comment', 'Test Regulatory Compliance Comment')
        ->call('submit')
        ->assertHasNoErrors();

    // Assert
    expect(QualityControlRecord::count())->toBe(1);
});

it('loads data when editing an existing record', function () {
    $user = User::factory()->create();
    $record = QualityControlRecord::factory()->for($user)->create();

    Livewire::actingAs($user)
        ->test(QualityControlForm::class, ['record' => $record])
        ->assertSet('record.received_date', $record->received_date)
        ->assertSet('record.po_number', $record->po_number)
        ->assertSet('record.product_id', $record->product_id)
        ->assertSet('record.vendor_id', $record->vendor_id)
        ->assertSet('record.quantity_received', $record->quantity_received)
        ->assertSet('record.identity_description', $record->identity_description)
        ->assertSet('record.matches_written_specification', $record->matches_written_specification);
});

it('defaults the warehouse if user only has access to a single warehouse', function () {
    // Arrange
    $user = User::factory()->create();
    $user->assign('qc-technician-01');

    $product = Product::factory()->create();
    $vendor = Vendor::factory()->create();

    // Act & Assert
    Livewire::actingAs($user)
        ->test(QualityControlForm::class)
        ->assertSeeText('01 - Acton')
        ->assertDontSeeText('04 - Vancouver')
        ->assertDontSeeText('08 - Calgary')
        ->assertDontSeeText('09 - Laval');
});

it('allows selection of a warehouse if use has access to multiple warehouses', function () {
    // Arrange
    $user = User::factory()->create();
    $user->assign('qc-technician-04');
    $user->assign('qc-technician-08');

    $product = Product::factory()->create();
    $vendor = Vendor::factory()->create();

    // Act & Assert
    Livewire::actingAs($user)
        ->test(QualityControlForm::class)
        ->assertSeeText('04 - Vancouver')
        ->assertSeeText('08 - Calgary')
        ->assertDontSeeText('01 - Acton')
        ->assertDontSeeText('09 - Laval');
});

it('warehouses are ordered by number', function () {
    // Arrange
    $user = User::factory()->create();
    $user->assign('qc-technician-04');
    $user->assign('qc-technician-08');

    $product = Product::factory()->create();
    $vendor = Vendor::factory()->create();

    // Act & Assert
    Livewire::actingAs($user)
        ->test(QualityControlForm::class)
        ->assertSeeTextInOrder([
            '04 - Vancouver', '08 - Calgary',
        ])->assertDontSee('01 - Acton')
        ->assertDontSee('09 - Laval');
});

it('saves details when a tag is processed', function (string $tag) {
    $this->travelTo('2022-01-01 00:00:00');
    $user = User::factory()->create();
    $user->allow('update', QualityControlRecord::class);

    $record = QualityControlRecord::factory()
        ->for($user)
        ->for(Warehouse::factory())
        ->create();

    $this->travelBack();

    Livewire::actingAs($user)
        ->test(QualityControlForm::class, ['record' => $record])
        ->call('generateTag', $tag);

    $record->refresh();

    expect($record->completed_at)->not()->toBe('2022-01-01 00:00:00');
    expect($record->generated_tag)->toBe($tag);
    expect($record->completed_by)->toBe($user->fresh()->id);
})->with([
    'approval',
    'rejection',
    'destruction',
]);

it('requires requested by and requestor to process pre release tag', function () {
    $this->travelTo('2022-01-01 00:00:00');
    $user = User::factory()->create();
    $user->allow('update', QualityControlRecord::class);

    $record = QualityControlRecord::factory()
        ->for($user)
        ->for(Warehouse::factory())
        ->create();

    $this->travelBack();

    Livewire::actingAs($user)
        ->test(QualityControlForm::class, ['record' => $record])
        ->call('generateTag', 'pre-released')
        ->call('generateTag', 'pre-released')
        ->assertHasErrors([
            'record.pre_release_reason' => 'required_if',
            'record.pre_release_requested_by' => 'required_if',
        ]);
});

it('saves details when a pre-release tag is processed', function () {
    $this->travelTo('2022-01-01 00:00:00');
    $user = User::factory()->create();
    $user->allow('update', QualityControlRecord::class);

    $record = QualityControlRecord::factory()
        ->for($user)
        ->for(Warehouse::factory())
        ->create();

    $this->travelBack();

    Livewire::actingAs($user)
        ->test(QualityControlForm::class, ['record' => $record])
        ->call('generateTag', 'pre-released')
        ->set('record.pre_release_reason', 'Because I Said So!')
        ->set('record.pre_release_requested_by', 'John Doe')
        ->call('generateTagDocument', 'pre-released');

    $record->refresh();

    expect($record->completed_at)->not()->toBe('2022-01-01 00:00:00');
    expect($record->generated_tag)->toBe('pre-released');
    expect($record->completed_by)->toBe($user->fresh()->id);
    expect($record->pre_release_reason)->toBe('Because I Said So!');
    expect($record->pre_release_requested_by)->toBe('John Doe');
});

it('downloads a pdf of the generated tag', function (string $tag) {
    $user = User::factory()->create();
    $user->allow('update', QualityControlRecord::class);

    $record = QualityControlRecord::factory()
        ->for($user)
        ->for(Warehouse::factory())
        ->create();

    $filename = "{$tag}_{$record->id}_{$record->product->stock_id}.pdf";

    $response = Livewire::actingAs($user)
        ->test(QualityControlForm::class, ['record' => $record])
        ->call('generateTag', $tag);

    $record->refresh();

    $response->assertFileDownloaded($filename);
})->with([
    'approval',
    'rejection',
    'destruction',
]);

it('displays a modal to capture pre release reason and requestor', function () {
    $user = User::factory()->create();
    $user->allow('update', QualityControlRecord::class);

    $record = QualityControlRecord::factory()
        ->for($user)
        ->for(Warehouse::factory())
        ->create();

    Livewire::actingAs($user)
        ->test(QualityControlForm::class, ['record' => $record])
        ->call('generateTag', 'pre-released')
        ->assertSet('showPreReleaseModal', true);
});

it('populates record npn when selecting a product', function () {
    $user = User::factory()->create();
    $user->allow('update', QualityControlRecord::class);

    $product = Product::factory()->has(RegulatoryInfo::factory())->create();
    $product->regulatoryInfo->npn = '12345678';
    $product->push();

    QualityControlRecord::factory()
        ->for($user)
        ->for(Warehouse::factory())
        ->create();

    Livewire::actingAs($user)
        ->test(QualityControlForm::class)
        ->assertNotSet('record.din_npn_number', '12345678')
        ->call('productSelected', name: 'record.productId', productId: $product->id)
        ->assertSet('record.din_npn_number', '12345678');
});
