<div>
    @if ($invoiceYears && $invoiceYears->count())
    <div class="accordion-open">
        @foreach ($invoiceYears as $year => $invoiceMonths)
        <h3>{{ $year }}</h3>
        <div>
            <div class="accordion-open">
                @foreach ($invoiceMonths as $month => $invoices)
                <h3>{{ date("F", mktime(0, 0, 0, $month, 1)) }}</h3>
                <div>
                    @include('brand-finance.tables.finance-media')
                </div>
                @endforeach
            </div>
        </div>
        @endforeach
    </div>
    @else
    <em>No weekly rebates found for selected brand.</em>
    @endif
</div>
