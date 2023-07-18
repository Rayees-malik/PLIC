<table class="table invoices-table">
    <thead>
        <tr>
            <th>Invoice #</th>
            <th>Invoice Date</th>
            <th class="text-right">Invoice Amount</th>
            <th class="text-right">Download</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($invoices as $mediaCollection)
        @foreach ($mediaCollection as $media)
        <tr>
            <td>{{ $media->custom_properties['identifier'] }}</td>
            <td>{{ $media->invoice ? $media->invoice['invoice_date'] : '' }}</td>
            <td class="text-right">{{ $media->invoice ? App\Helpers\NumberHelper::toAccountingDollar($media->invoice['invoice_amount']) : '' }}</td>
            <td class="text-right">{!! $media->getDownloadLink('', null, null, auth()->user()->can('finance.delete-media') ? 'brand-finance.destroy-media' : null) !!}</td>
        </tr>
        @endforeach
        @endforeach
    </tbody>
</table>
