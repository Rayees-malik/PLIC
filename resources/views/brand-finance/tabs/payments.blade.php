<div>
    @if ($invoiceYears && $invoiceYears->count())
    <div class="accordion-open">
        @foreach ($invoiceYears as $year => $invoiceMonths)
        <h3>{{ $year }}</h3>
        <div>
            <div class="accordion-open">
                @foreach ($invoiceMonths as $month => $cheque)
                <h3>{{ date("F", mktime(0, 0, 0, $month, 1)) }}</h3>
                <div>
                    <div class="accordion{{ request()->search ? '-open' : '' }}">
                        @foreach ($cheque as $chequeNumber => $invoices)
                        <h3>{!! App\Helpers\BrandFinanceHelper::paymentsHeader($chequeNumber, $invoices) !!}</h3>
                        <div>
                            @include('brand-finance.tables.payments')
                        </div>
                        @endforeach
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endforeach
    </div>
    @else
    <em>No payments found for selected brand.</em>
    @endif
</div>
