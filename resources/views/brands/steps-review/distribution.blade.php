<h3 class="js-review-toggle review-toggle {{ $errors->distribution->count() ? 'open error' : '' }}">Distribution Details</h3>
<div class="review-wrap">
    <div class="review-content {{ $errors->distribution->count() ? 'error' : '' }}">
        <div class="row">
            <div class="col-xl-4">
                <h4>Allows Amazon Resale</h4>
                @include('partials.review.field', ['field' => 'allows_amazon_resale', 'format' => 'boolean'])
            </div>
            <div class="col-xl-4">
                <h4>MAP Pricing</h4>
                @include('partials.review.field', ['field' => 'map_pricing', 'format' => 'boolean'])
            </div>
        </div>
        <div class="row">
            <div class="col-xl-4">
                <h4>Contract Exclusive</h4>
                @include('partials.review.field', ['field' => 'contract_exclusive', 'format' => 'boolean'])
            </div>
            @if (!$model->contract_exclusive)
            <div class="col-xl-4">
                @if ($model->no_other_distributors)
                <h4>No Other Distributors</h4>
                @include('partials.review.field', ['field' => 'no_other_distributors', 'format' => 'boolean'])
                @else
                <h4>Also Distributed By</h4>
                @include('partials.review.field', ['field' => 'also_distributed_by'])
                @endif
            </div>
            @endif
        </div>

    </div>
</div>
