<table class="table invoices-table">
    <thead>
        <tr>
            <th>PO #</th>
            <th>Date Received</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($records as $record)
        <tr>
            <td>{{ $record->po_number }}</td>
            <td>{{ $record->po_date }}</td>
            <td>{{ $record->status }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
