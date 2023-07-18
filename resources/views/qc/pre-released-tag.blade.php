<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta http-equiv="X-UA-Compatible" content="ie=edge" />

  <!-- Fonts -->
  <link href="https://fonts.googleapis.com/css?family=Ubuntu:300,400,500,700" rel="stylesheet" />
  <link href="https://fonts.googleapis.com/css2?family=Oswald:wght@400;700&display=swap" rel="stylesheet">

  <!-- Styles -->
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
      <h1 class="tw-text-2xl tw-py-4 tw-font-semibold">Pre-Release Tag</h1>
      <h2 class="tw-text-md tw-flex tw-justify-between tw-w-full">
        <span>{{ $record->product->stock_id }} &ndash; {{ $record->product->name }}</span>
        <span>QC #:&nbsp;{{ $record->id }}</span>
      </h2>
    </header>

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
            <dt class="tw-font-semibold">Quantity Received for Sales</dt>
            <dd class="tw-mt-1 tw-col-span-2">{{ $record->quantity_received - $record->number_units_sent_for_testing - $record->number_units_for_stability - $record->number_units_retained }}</dd>
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
            <dt class="tw-font-semibold">NPN</dt>
            <dd class="tw-mt-1 tw-col-span-2">{{ $record->din_npn_number }}</dd>
          </div>
          <div class="tw-grid tw-grid-cols-3 tw-gap-4 tw-py-2 tw-px-6">
            <dt class="tw-font-semibold">Pre-Released By</dt>
            <dd class="tw-mt-1 tw-col-span-2 tw-flex tw-flex-col">
              <div>{{ $record->completedBy->name }}</div>
              <div>{{ $record->completedBy->getSignature() }}</div>
            </dd>
          </div>
          <div class="tw-grid tw-grid-cols-3 tw-gap-4 tw-py-2 tw-px-6">
            <dt class="tw-font-semibold">Date Pre-Released</dt>
            <dd class="tw-mt-1 tw-col-span-2">{{ $record->completed_at }}</dd>
          </div>
          <div class="tw-grid tw-grid-cols-3 tw-gap-4 tw-py-2 tw-px-6">
            <dt class="tw-font-semibold">Reason for Pre-Release</dt>
            <dd class="tw-mt-1 tw-col-span-2">{{ $record->pre_release_reason }}</dd>
          </div>
          <div class="tw-grid tw-grid-cols-3 tw-gap-4 tw-py-2 tw-px-6">
            <dt class="tw-font-semibold">Requested By</dt>
            <dd class="tw-mt-1 tw-col-span-2">{{ $record->pre_release_requested_by }}</dd>
          </div>
        </dl>
      </div>
    </section>
  </article>
</body>
</html>
