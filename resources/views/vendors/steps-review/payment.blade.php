<h3 class="js-review-toggle review-toggle {{ $errors->payment->count() ? 'open error' : '' }}">Payment</h3>
<div class="review-wrap">
    <div class="review-content {{ $errors->payment->count() ? 'error' : '' }}">
        <div class="row">
            <div class="col-xl-4">
                <h4>Who to MCB</h4>
                @include('partials.review.field', ['field' => 'who_to_mcb'])
            </div>
        </div>
        <div class="row">
            <div class="col-xl-4">
                <h4>Cheque Payable To</h4>
                @include('partials.review.field', ['field' => 'cheque_payable_to'])
            </div>
            <div class="col-xl-4">
                <h4>Payment Terms</h4>
                @include('partials.review.field', ['field' => 'payment_terms'])
            </div>
        </div>
        <div class="row">
            <div class="col-xl-4 offset-xl-8">
                <h4>Consignment</h4>
                @include('partials.review.field', ['field' => 'consignment', 'format' => 'boolean'])
            </div>
        </div>
        <div class="row">
            <div class="col">
                <h4>Special Shipping Requirements</h4>
                @include('partials.review.field', ['field' => 'special_shipping_requirements'])
            </div>
        </div>
        <div class="row">
            <div class="col">
                <h4>Return Policy</h4>
                @include('partials.review.field', ['field' => 'return_policy'])
            </div>
        </div>
        <div class="row">
            <div class="col-xl-4">
                <h4>FOB Purity Distribution Center</h4>
                @include('partials.review.field', ['field' => 'fob_purity_distribution_centres', 'format' => 'boolean'])
            </div>
        </div>
    </div>
</div>
