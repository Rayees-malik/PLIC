<h3 class="js-review-toggle review-toggle {{ $errors->vendor->count() ? 'open error' : '' }}">Vendor</h3>
<div class="review-wrap">
    <div class="review-content {{ $errors->vendor->count() ? 'error' : '' }}">
        <div class="row">
            <div class="col-xl-4">
                <h4>Name</h4>
                @include('partials.review.field', ['field' => 'name'])
            </div>
            <div class="col-xl-4">
                <h4>Phone Number</h4>
                @include('partials.review.field', ['field' => 'phone'])
            </div>
        </div>
        <div class="row">
            <div class="col-xl-4">
                <h4>Address</h4>
                @include('partials.review.field', ['field' => 'address', 'model' => optional($model->address)])
            </div>
            <div class="col-xl-4">
                <h4>Address 2</h4>
                @include('partials.review.field', ['field' => 'address2', 'model' => optional($model)->address])
            </div>
            <div class="col-xl-4">
                <h4>City</h4>
                @include('partials.review.field', ['field' => 'city', 'model' => optional($model)->address])
            </div>
        </div>
        <div class="row">
            <div class="col-xl-4">
                <h4>Province/State</h4>
                @include('partials.review.field', ['field' => 'province', 'model' => optional($model)->address])
            </div>
            <div class="col-xl-4">
                <h4>Postal Code/Zip Code</h4>
                @include('partials.review.field', ['field' => 'postal_code', 'model' => optional($model)->address])
            </div>
            <div class="col-xl-4">
                <h4>Country</h4>
                @include('partials.review.field', ['field' => 'country', 'model' => optional($model)->address, 'subfield' => 'name'])
            </div>
        </div>
    </div>
</div>
