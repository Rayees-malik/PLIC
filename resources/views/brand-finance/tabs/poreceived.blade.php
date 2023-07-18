<div class="container">
    <div class="col-xl-12">
        <div class="js-openap-table">
            @if ($records->count())
            <div class="card">
                <div class="card-body">
                    @include('brand-finance.tables.poreceived')
                </div>
            </div>
            @else
            <em>No PO received data found for selected brand.</em>
            @endif
        </div>
    </div>
</div>
