<h3 class="js-review-toggle review-toggle {{ $errors->retailer->count() ? 'open error' : '' }}">Retailer</h3>
<div id="review-retailer" class="review-wrap">
    <div class="review-content {{ $errors->retailer->count() ? 'error' : '' }}">
        <div class="row">
            <div class="col-xl-8">
                <h4>Name</h4>
                @include('partials.review.field', ['field' => 'name'])
            </div>
            <div class="col-xl-4">
                <h4># Stores</h4>
                @include('partials.review.field', ['field' => 'number_stores'])
            </div>
        </div>
        <div class="row">
            <div class="col-xl-4">
                <h4>Start of Fiscal Year</h4>
                @include('partials.review.field', ['field' => 'fiscal_year_start'])
            </div>
            <div class="col-xl-4">
                <h4>Key Account Manager</h4>
                @include('partials.review.field', ['field' => 'accountManager', 'subfield' => 'name', 'formField' => 'account_manager_id'])
            </div>
        </div>
        <div class="row">
            <div class="col-xl-4">
                <h4>Markup</h4>
                @include('partials.review.field', ['field' => 'markup'])
            </div>
            <div class="col-xl-4">
                <h4>Target Margin</h4>
                @include('partials.review.field', ['field' => 'target_margin'])
            </div>
            <div class="col-xl-4">
                <h4>AS400 Pricing File</h4>
                @include('partials.review.field', ['field' => 'as400_pricing_file'])
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <h4>Distribution Type</h4>
                @include('partials.review.field', ['field' => 'distribution_type'])
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <h4>Distributors</h4>
                @include('partials.review.many-field', ['relation' => 'distributors', 'field' => 'name'])
            </div>
        </div>
        <div class="row">
            <div class="col-xl-4">
                <h4>Warehouse Number</h4>
                @include('partials.review.field', ['field' => 'warehouse_number'])
            </div>
            <div class="col-xl-4">
                <h4>Costing Type</h4>
                @include('partials.review.field', ['field' => 'costing_type', 'format' => 'in_array', 'array' => App\Models\Retailer::COSTING_TYPES])
            </div>
            <div class="col-xl-4">
                <h4>Allow Promos</h4>
                @include('partials.review.field', ['field' => 'allow_promos', 'format' => 'boolean'])
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
