<table class="table datatable invoices-table">
    <thead>
        <tr>
            <th>Invoice #</th>
            <th>Invoice Date</th>
            <th>Reference</th>
            <th class="text-right">Amount</th>
            <th class="text-right">Discount</th>
            <th class="text-right">Total</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($invoices as $invoice)
        <tr>
            <td>{{ $invoice->invoice_number }}</td>
            <td>{{ $invoice->invoice_date }}</td>
            <td>{{ $invoice->reference }}</td>
            <td class="text-right">{{ App\Helpers\NumberHelper::toAccountingDollar($invoice->invoice_amount) }}</td>
            <td class="text-right">{{ App\Helpers\NumberHelper::toAccountingDollar($invoice->discount_amount) }}</td>
            <td class="text-right">{{ App\Helpers\NumberHelper::toAccountingDollar($invoice->invoice_amount - $invoice->discount_amount) }}</td>
        </tr>
        @foreach ($brand->financeMedia as $mediaType)
        @foreach (Arr::get($mediaType, $invoice->invoice_number, []) as $media)
        <tr>
            <td colspan="6" class="text-right p-0">{!! $media->getDownloadLink('', null, null, auth()->user()->can('finance.delete-media') ? 'brand-finance.destroy-media' : null) !!}</td>
        </tr>
        @endforeach
        @endforeach
        @endforeach
        <tr>
            <td colspan="3"></td>
            {!! App\Helpers\BrandFinanceHelper::paymentsTotals($invoices) !!}
        </tr>
    </tbody>
</table>
