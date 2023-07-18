<h3 class="js-review-toggle review-toggle {{ $errors->general->count() ? 'open error' : '' }}">General</h3>
<div class="review-wrap">
    <div class="review-content {{ $errors->general->count() ? 'error' : '' }}">
        <div class="row">
            <div class="col-xl-4">
                <h4>Brand</h4>
                @include('partials.review.field', ['field' => 'brand', 'subfield' => 'name'])
            </div>
            @if ($model->stock_id || auth()->user()->can('product.edit.stockid'))
            <div class="col-xl-4">
                <h4>Stock Id</h4>
                @include('partials.review.field', ['field' => 'stock_id'])
            </div>
            @endif
            <div class="col-xl-4">
                <h4>Is Display</h4>
                @include('partials.review.field', ['field' => 'is_display', 'format' => 'boolean'])
            </div>
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
            @if ($model->supersedes)
            <div class="col-xl-4">
                <h4>Supersedes</h4>
                <span style="color: red;">
                    @include('partials.review.field', ['field' => 'supercedes', 'subfield' => 'name', 'formField' => 'supersedes_id'])
                </span>
            </div>
            @endif
        </div>
        <div class="row">
            <div class="col-xl-4">
                <h4>Country of Origin</h4>
                @include('partials.review.field', ['field' => 'countryOrigin', 'subfield' => 'name'])
            </div>
            <div class="col-xl-4">
                <h4>Country Shipped From</h4>
                @include('partials.review.field', ['field' => 'countryShipped', 'subfield' => 'name'])
            </div>
            @if ($model->country_shipped != 40)
            <div class="col-xl-4">
                <h4>Tariff Code</h4>
                @include('partials.review.field', ['field' => 'tariff_code'])
            </div>
            @endif
        </div>
        <div class="row">
            <div class="col-xl-4">
                <h4>Packaging Compliant Language</h4>
                @include('partials.review.field', ['field' => 'packaging_language', 'format' => 'in_array', 'array' => App\Models\Product::PACKAGING_LANGUAGES])
            </div>
        </div>
        <div class="row">
            <div class="col-xl-4">
                <h4>Category</h4>
                @include('partials.review.field', ['field' => 'category', 'subfield' => 'name', 'formField' => 'category_id'])
            </div>
            <div class="col-xl-4">
                <h4>Subcategory</h4>
                @include('partials.review.field', ['field' => 'subcategory', 'subfield' => 'name', 'formField' => 'subcategory_id'])
            </div>
            <div class="col-xl-4">
                <h4>Catalogue Category</h4>
                @if (!$model->catalogue_category_id && ($model->catalogue_category_proposal || $model->catalogue_category_proposal_fr))
                <strong>English:</strong> @include('partials.review.field', ['field' => 'catalogue_category_proposal'])
                <strong>French:</strong> @include('partials.review.field', ['field' => 'catalogue_category_proposal_fr'])
                @else
                @include('partials.review.field', ['field' => 'catalogueCategory', 'subfield' => 'name', 'formField' => 'catalogue_category_id'])
                @endif
            </div>
        </div>
    </div>
</div>
