<h3 class="js-review-toggle review-toggle {{ $errors->pricing->count() ? 'open error' : '' }}">Pricing</h3>
<div class="review-wrap">
    <div class="review-content {{ $errors->pricing->count() ? 'error' : '' }}">
        <div class="row">
            <div class="col-xl-4">
                <h4>Currency</h4>
                @include('partials.review.field', ['field' => 'currency', 'subfield' => 'name', 'model' => $model->brand])
            </div>
            <div class="col-xl-4">
                <h4>Not for Resale</h4>
                @include('partials.review.field', ['field' => 'not_for_resale', 'format' => 'boolean'])
            </div>
        </div>
        <div class="row">
            <div class="col-xl-4">
                <h4>{{ $model->isNewSubmission ? '' : 'New ' }}PO Price to Purity</h4>
                @include('partials.review.field', ['field' => 'unit_cost'])
            </div>
            @if (!$model->isNewSubmission)
            <div class="col-xl-4">
                <h4>Price Change Reason</h4>
                @include('partials.review.field', ['field' => 'price_change_reason'])
            </div>
            <div class="col-xl-4">
                <h4>Price Change Date</h4>
                @include('partials.review.field', ['field' => 'price_change_date'])
            </div>
            @endif
        </div>
        <div class="row">
            <div class="col-xl-4">
                <h4>Available Ship Date</h4>
                @include('partials.review.field', ['field' => 'available_ship_date'])
            </div>
            <div class="col-xl-4">
                <h4>Minimum Order By QTY</h4>
                @include('partials.review.field', ['field' => 'minimum_order_units'])
            </div>
        </div>

        @can('product.costing')
        @if (isset($signoff) && $signoff->step == 3)
        <div class="row">
            <div class="col-xl-4">
                <h4>Extra Addon Code %</h4>
                @include('partials.review.field', ['field' => 'extra_addon_percent'])
            </div>
        </div>
        @if ($model->isNewSubmission)
        <div class="row">
            <div class="col-xl-4">
                <h4>EDLP</h4>
                @include('partials.review.field', ['field' => 'temp_edlp'])
            </div>
            <div class="col-xl-4">
                <h4>Duty</h4>
                @include('partials.review.field', ['field' => 'temp_duty'])
            </div>
        </div>
        @endif
        <div class="row">
            <div class="col-xl-4">
                <h4>Landed Cost</h4>
                @include('partials.review.field', ['field' => 'landed_cost'])
            </div>
            <div class="col-xl-4">
                <h4>Wholesale Price</h4>
                @include('partials.review.field', ['field' => 'wholesale_price'])
            </div>
        </div>
        @endif
        @endcan
    </div>
</div>
