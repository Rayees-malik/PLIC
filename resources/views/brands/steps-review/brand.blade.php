<h3 class="js-review-toggle review-toggle {{ $errors->brand->count() ? 'open error' : '' }}">Brand</h3>
<div class="review-wrap">
    <div class="review-content {{ $errors->brand->count() ? 'error' : '' }}">
        <div class="row">
            <div class="col-xl-4">
                <h4>Vendor</h4>
                @include('partials.review.field', ['field' => 'vendor', 'subfield' => 'name'])
            </div>
            @if (auth()->user()->can('brand.edit.number') || $model->brand_number)
            <div class="col-xl-4">
                <h4>Brand Number</h4>
                @include('partials.review.field', ['field' => 'brand_number'])
            </div>
            @endif
        </div>
        <div class="row">
            <div class="col-xl-4">
                <h4>Name</h4>
                @include('partials.review.field', ['field' => 'name'])
            </div>
            <div class="col-xl-4">
                <h4>Name (FR)</h4>
                @include('partials.review.field', ['field' => 'name_fr'])
            </div>
            <div class="col-xl-4">
                <h4>Made in Canada</h4>
                @include('partials.review.field', ['field' => 'made_in_canada', 'format' => 'boolean'])
            </div>
        </div>
        <div class="row">
            <div class="col-xl-4">
                <h4>Brokers</h4>
                @include('partials.review.many-field', ['relation' => 'brokers', 'field' => 'name'])
            </div>
            <div class="col-xl-4">
                <h4>Other Brokers</h4>
                @include('partials.review.field', ['field' => 'broker_proposal'])
            </div>
            <div class="col-xl-4">
                <h4>Currency</h4>
                @include('partials.review.field', ['field' => 'currency', 'subfield' => 'name'])
            </div>
        </div>
        <div class="row">
            <div class="col-xl-4">
                <h4>Website</h4>
                @include('partials.review.field', ['field' => 'website'])
            </div>
            <div class="col-xl-4">
                <h4>Phone Number</h4>
                @include('partials.review.field', ['field' => 'phone'])
            </div>
        </div>
        <div class="row">
            <div class="col-xl-6">
                <h4>Brand Description</h4>
                @include('partials.review.field', ['field' => 'description'])
            </div>
            <div class="col-xl-6">
                <h4>Brand Description (FR)</h4>
                @include('partials.review.field', ['field' => 'description_fr'])
            </div>
        </div>
        <div class="row">
            <div class="col-xl-6">
                <h4>Unpublished New Listing Deal</h4>
                @include('partials.review.field', ['field' => 'unpublished_new_listing_deal'])
            </div>
            <div class="col-xl-6">
                <h4>Unpublished New Listing Deal (FR)</h4>
                @include('partials.review.field', ['field' => 'unpublished_new_listing_deal_fr'])
            </div>
        </div>
    </div>
</div>
