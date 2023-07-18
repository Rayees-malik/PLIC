<table class="table invoices-table">
    <thead>
        <tr>
            <th>Invoice #</th>
            <th>Invoice Date</th>
            <th>Reference</th>
            <th class="text-right">Invoice Amount</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($records as $record)
        <tr>
            <td>{{ $record->invoice_number }}</td>
            <td>{{ $record->invoice_date }}</td>
            <td>{{ $record->reference }}</td>
            <td class="text-right">{{ App\Helpers\NumberHelper::toAccountingDollar($record->invoice_amount) }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
