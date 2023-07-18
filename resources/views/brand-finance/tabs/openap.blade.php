<div class="container">
    <div class="col-xl-12">
        <div class="js-openap-table">
            @if ($records->count())
            <h2>Total Balance: {{ App\Helpers\NumberHelper::toAccountingDollar($records->sum('invoice_amount')) }}</h2>
            <div class="card">
                <div class="card-body">
                    @include('brand-finance.tables.openap')
                </div>
            </div>
            @else
            <em>No open AP data data found for selected brand.</em>
            @endif
        </div>
    </div>
</div>
