<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta http-equiv="X-UA-Compatible" content="ie=edge" />

  <!-- Fonts -->
  <link href="https://fonts.googleapis.com/css?family=Ubuntu:300,400,500,700" rel="stylesheet" />
  <link href="https://fonts.googleapis.com/css2?family=Oswald:wght@400;700&display=swap" rel="stylesheet">

  <link href="{{ mix('css/tailwind.css') }}" rel="stylesheet">
  <link href="{{ public_path() . '/css/tailwind.css' }}" rel="stylesheet">
  <link href="{{ mix('css/vendor.css') }}" rel="stylesheet">
  <link href="{{ public_path() . '/css/vendor.css' }}" rel="stylesheet">
</head>

<body class="tw-bg-white tw-text-sm">
  <article class="tw-px-1 tw-pt-1">
    <header class="tw-flex tw-flex-col tw-items-center tw-mb-4 tw-space-y-1">
      <div class="tw-w-28">
      <img src="{{ asset('/images/mail_footer_logo.png') }}" alt="PLIC Logo" class="tw-object-scale-down">
      </div>
      <h1 class="tw-text-2xl tw-py-4 tw-font-semibold">QC Record</h1>
      <h2 class="tw-text-md tw-flex tw-justify-between tw-w-full">
        <span>{{ $record->product->stock_id }} &ndash; {{ $record->product->name }}</span>
        <span>QC #:&nbsp;{{ $record->id }}</span>
      </h2>
    </header>

    <h3 class="tw-pt-2 tw-text-lg">Receiving</h3>
    <section class="tw-shadow tw-my-2 tw-rounded-lg tw-bg-white tw-overflow-hidden tw-break-inside-avoid-page">
      <div class="tw-border-t tw-px-2">
        <dl class="tw-divide-y tw-divide-gray-200">
          <div class="tw-grid tw-grid-cols-3 tw-gap-4 tw-py-2 tw-px-6">
            <dt class="tw-font-semibold">Vendor</dt>
            <dd class="tw-mt-1 tw-col-span-2">{{ $record->vendor?->name }}</dd>
          </div>
          <div class="tw-grid tw-grid-cols-3 tw-gap-4 tw-py-2 tw-px-6">
            <dt class="tw-font-semibold">Warehouse</dt>
            <dd class="tw-mt-1 tw-col-span-2">{{ $record->warehouse->number . ' - ' . $record->warehouse->name }}</dd>
          </div>
          <div class="tw-grid tw-grid-cols-3 tw-gap-4 tw-py-2 tw-px-6">
            <dt class="tw-font-semibold">Received Date</dt>
            <dd class="tw-mt-1 tw-col-span-2">{{ $record->received_date }}</dd>
          </div>
          <div class="tw-grid tw-grid-cols-3 tw-gap-4 tw-py-2 tw-px-6">
            <dt class="tw-font-semibold">Quantity Received</dt>
            <dd class="tw-mt-1 tw-col-span-2">{{ $record->quantity_received }}</dd>
          </div>
          <div class="tw-grid tw-grid-cols-3 tw-gap-4 tw-py-2 tw-px-6">
            <dt class="tw-font-semibold">PO Number</dt>
            <dd class="tw-mt-1 tw-col-span-2">{{ $record->po_number }}</dd>
          </div>
          <div class="tw-grid tw-grid-cols-3 tw-gap-4 tw-py-2 tw-px-6">
            <dt class="tw-font-semibold">Lot Number</dt>
            <dd class="tw-mt-1 tw-col-span-2">{{ $record->lot_number }}</dd>
          </div>
          <div class="tw-grid tw-grid-cols-3 tw-gap-4 tw-py-2 tw-px-6">
            <dt class="tw-font-semibold">Expiry Date</dt>
            <dd class="tw-mt-1 tw-col-span-2">{{ $record->expiry_date }}</dd>
          </div>
          <div class="tw-grid tw-grid-cols-3 tw-gap-4 tw-py-2 tw-px-6">
            <dt class="tw-font-semibold">Bin Number</dt>
            <dd class="tw-mt-1 tw-col-span-2">{{ $record->bin_number }}</dd>
          </div>
          <div class="tw-grid tw-grid-cols-3 tw-gap-4 tw-py-2 tw-px-6">
            <dt class="tw-font-semibold">NPN</dt>
            <dd class="tw-mt-1 tw-col-span-2">{{ $record->din_npn_number }}</dd>
          </div>
          <div class="tw-grid tw-grid-cols-3 tw-gap-4 tw-py-2 tw-px-6">
            <dt class="tw-font-semibold">Seals Intact</dt>
            <dd class="tw-mt-1 tw-col-span-2">{{ $record->seals_intact ? 'Yes' : 'No' }}</dd>
          </div>
          <div class="tw-grid tw-grid-cols-3 tw-gap-4 tw-py-2 tw-px-6">
            <dt class="tw-font-semibold">DIN/NPN on Label</dt>
            <dd class="tw-mt-1 tw-col-span-2">{{ $record->din_npn_on_label ? 'Yes' : 'No' }}</dd>
          </div>
          <div class="tw-grid tw-grid-cols-3 tw-gap-4 tw-py-2 tw-px-6">
            <dt class="tw-font-semibold">Bilingual Label</dt>
            <dd class="tw-mt-1 tw-col-span-2">{{ $record->bilingual_label ? 'Yes' : 'No' }}</dd>
          </div>
          <div class="tw-grid tw-grid-cols-3 tw-gap-4 tw-py-2 tw-px-6">
            <dt class="tw-font-semibold">Receiving Comment</dt>
            <dd class="tw-mt-1 tw-col-span-2">{{ $record->receiving_comment }}</dd>
          </div>
        </dl>
      </div>
    </section>

    <h3 class="tw-pt-2 tw-text-lg tw-break-before-page">Damage Report</h3>
    <section class="tw-shadow tw-my-2 tw-rounded-lg tw-bg-white tw-overflow-hidden tw-break-inside-avoid-page">
      <div class="tw-border-t tw-px-2">
        <dl class="tw-divide-y tw-divide-gray-200">
          <div class="tw-grid tw-grid-cols-3 tw-gap-4 tw-py-2 tw-px-6">
            <dt class="tw-font-semibold"># of Damaged Cartons</dt>
            <dd class="tw-mt-1 tw-col-span-2">{{ $record->number_damaged_cartons }}</dd>
          </div>
          <div class="tw-grid tw-grid-cols-3 tw-gap-4 tw-py-2 tw-px-6">
            <dt class="tw-font-semibold"># of Damaged Units</dt>
            <dd class="tw-mt-1 tw-col-span-2">{{ $record->number_damaged_units }}</dd>
          </div>
          <div class="tw-grid tw-grid-cols-3 tw-gap-4 tw-py-2 tw-px-6">
            <dt class="tw-font-semibold"># Units to Reject/Destroy</dt>
            <dd class="tw-mt-1 tw-col-span-2">{{ $record->number_to_reject_destroy }}</dd>
          </div>
          <div class="tw-grid tw-grid-cols-3 tw-gap-4 tw-py-2 tw-px-6">
            <dt class="tw-font-semibold"># of Units Sent For Testing</dt>
            <dd class="tw-mt-1 tw-col-span-2">{{ $record->number_units_sent_for_testing }}</dd>
          </div>
          <div class="tw-grid tw-grid-cols-3 tw-gap-4 tw-py-2 tw-px-6">
            <dt class="tw-font-semibold"># of Units For Stability</dt>
            <dd class="tw-mt-1 tw-col-span-2">{{ $record->number_units_for_stability }}</dd>
          </div>
          <div class="tw-grid tw-grid-cols-3 tw-gap-4 tw-py-2 tw-px-6">
            <dt class="tw-font-semibold"># of Units Retained</dt>
            <dd class="tw-mt-1 tw-col-span-2">{{ $record->number_units_retained }}</dd>
          </div>
          <div class="tw-grid tw-grid-cols-3 tw-gap-4 tw-py-2 tw-px-6">
            <dt class="tw-font-semibold">Units Taken</dt>
            <dd class="tw-mt-1 tw-col-span-2">{{ $record->units_taken }}</dd>
          </div>
          <div class="tw-grid tw-grid-cols-3 tw-gap-4 tw-py-2 tw-px-6">
            <dt class="tw-font-semibold">Regulatory Compliance Comment</dt>
            <dd class="tw-mt-1 tw-col-span-2">{{ $record->regulatory_compliance_comment }}</dd>
          </div>
        </dl>
      </div>
    </section>

    <h3 class="tw-pt-2 tw-text-lg">Identity Testing</h3>
    <section class="tw-shadow tw-my-2 tw-rounded-lg tw-bg-white tw-overflow-hidden tw-break-inside-avoid-page">
      <div class="tw-border-t tw-px-2">
        <dl class="tw-divide-y tw-divide-gray-200">
          <div class="tw-grid tw-grid-cols-3 tw-gap-4 tw-py-2 tw-px-6">
            <dt class="tw-font-semibold">Identity Description</dt>
            <dd class="tw-mt-1 tw-col-span-2">{{ $record->identity_description }}</dd>
          </div>
          <div class="tw-grid tw-grid-cols-3 tw-gap-4 tw-py-2 tw-px-6">
            <dt class="tw-font-semibold">Matches Written Specification</dt>
            <dd class="tw-mt-1 tw-col-span-2">{{ $record->matches_written_specification ? 'Yes' : 'No' }}</dd>
          </div>
          <div class="tw-grid tw-grid-cols-3 tw-gap-4 tw-py-2 tw-px-6">
            <dt class="tw-font-semibold">Out of Specification Report</dt>
            <dd class="tw-mt-1 tw-col-span-2">{{ $record->out_of_specifications_comment }}</dd>
          </div>
        </dl>
      </div>
    </section>
  </article>
</body>

</html>
