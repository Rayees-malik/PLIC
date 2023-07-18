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
      <h1 class="tw-text-2xl tw-font-semibold">Labelling Form</h1>
      <h2 class="tw-text-md tw-flex tw-justify-between tw-w-full">
        <span>{{ $record->product->stock_id }} &ndash; {{ $record->product->name }}</span>
        <span>QC #:&nbsp;{{ $record->id }}</span>
      </h2>
    </header>

    <section class="tw-shadow tw-rounded-lg tw-bg-white tw-overflow-hidden">
      <div class="tw-border-t tw-pt-2 tw-px-2">
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
            <dt class="tw-font-semibold">Date Issued</dt>
            <dd class="tw-mt-1 tw-col-span-2">{{ now()->format('Y-m-d H:m:s') }}</dd>
          </div>
          <div class="tw-grid tw-grid-cols-3 tw-gap-4 tw-py-2 tw-px-6">
            <dt class="tw-font-semibold">Issued By</dt>
            <dd class="tw-mt-1 tw-col-span-2">{{ auth()->user()->getSignature() }}</dd>
          </div>
        </dl>
      </div>
    </section>

    <table class="tw-table-fixed tw-min-w-full tw-divide-y tw-divide-gray-300 tw-mt-4 tw-border">
      <thead class="tw-bg-gray-100">
        <tr class="tw-divide-x tw-divide-gray-300">
          <th scope="col" class="tw-py-3.5 tw-px-3">Label Code</th>
          <th scope="col" class="tw-py-3.5 tw-px-3">Qty Issued</th>
          <th scope="col" class="tw-py-3.5 tw-px-3">Qty Used</th>
          <th scope="col" class="tw-py-3.5 tw-px-3">Qty Rejected</th>
          <th scope="col" class="tw-py-3.5 tw-px-3">Qty Returned</th>
        </tr>
      </thead>
      <tbody class="tw-bg-white tw-divide-y tw-divide-gray-200">
        <tr class="tw-divide-x tw-divide-gray-200 tw-py-2">
          <td class="tw-px-3 tw-py-2" scope="col">&nbsp;</td>
          <td class="tw-px-3 tw-py-2" scope="col"></td>
          <td class="tw-px-3 tw-py-2" scope="col"></td>
          <td class="tw-px-3 tw-py-2" scope="col"></td>
          <td class="tw-px-3 tw-py-2" scope="col"></td>
        </tr>
        <tr class="tw-divide-x tw-divide-gray-200">
          <td class="tw-px-3 tw-py-2">&nbsp;</td>
          <td class="tw-px-3 tw-py-2"></td>
          <td class="tw-px-3 tw-py-2"></td>
          <td class="tw-px-3 tw-py-2"></td>
          <td class="tw-px-3 tw-py-2"></td>
        </tr>
        <tr class="tw-divide-x tw-divide-gray-200">
          <td class="tw-px-3 tw-py-2 tw-font-semibold">Label Start</td>
          <td class="tw-px-3 tw-py-2">&nbsp;</td>
          <td class="tw-px-3 tw-py-2" colspan=3>Labelling area is clear of previously labelled materials</td>
        </tr>
        <tr class="tw-divide-x tw-divide-gray-200">
          <td class="tw-px-3 tw-py-2 tw-font-semibold">Label Finish</td>
          <td class="tw-px-3 tw-py-2">&nbsp;</td>
          <td class="tw-px-3 tw-py-2" colspan=3>Labelling area is free of rejected and returning labels</td>
        </tr>
        <tr class="tw-divide-x tw-divide-gray-200">
          <td class="tw-px-3 tw-py-2 tw-font-semibold">Labelled By</td>
          <td class="tw-px-3 tw-py-2" colspan="2"></td>
          <td class="tw-px-3 tw-py-2 tw-text-right tw-font-semibold">Date</td>
          <td class="tw-px-3 tw-py-2"></td>
        </tr>
        <tr class="tw-divide-x tw-divide-gray-200">
          <td class="tw-px-3 tw-py-2 tw-font-semibold">Checked By</td>
          <td class="tw-px-3 tw-py-2" colspan="2"></td>
          <td class="tw-px-3 tw-py-2 tw-text-right tw-font-semibold">Date</td>
          <td class="tw-px-3 tw-py-2"></td>
        </tr>
        <tr class="tw-divide-x tw-divide-gray-200">
          <td class="tw-px-3 tw-py-2 tw-font-semibold">Released By</td>
          <td class="tw-px-3 tw-py-2" colspan="2"></td>
          <td class="tw-px-3 tw-py-2 tw-text-right tw-font-semibold">Date</td>
          <td class="tw-px-3 tw-py-2"></td>
        </tr>
      </tbody>
    </table>
  </article>
</body>

</html>
