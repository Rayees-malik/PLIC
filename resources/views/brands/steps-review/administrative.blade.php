<h3 class="js-review-toggle review-toggle {{ $errors->administrative->count() ? 'open error' : '' }}">Administrative</h3>
<div class="review-wrap">
    <div class="review-content {{ $errors->administrative->count() ? 'error' : '' }}">
        <div class="row">
            <div class="col-xl-4">
                <h4>Status</h4>
                @include('partials.review.field', ['field' => 'status', 'wrapper' => 'App\Helpers\StatusHelper::toString'])
            </div>
            <div class="col-xl-4">
                <h4>AS400 Category Code</h4>
                @include('partials.review.field', ['field' => 'category_code'])
            </div>
            <div class="col-xl-4">
                <h4>AS400 Category</h4>
                @include('partials.review.field', ['field' => 'as400_category'])
            </div>
        </div>
        <div class="row">
            <div class="col-xl-4">
                <h4>Education Portal</h4>
                @include('partials.review.field', ['field' => 'education_portal', 'format' => 'boolean'])
            </div>
            <div class="col-xl-4">
                <h4>Hide from Exports</h4>
                @include('partials.review.field', ['field' => 'hide_from_exports', 'format' => 'boolean'])
            </div>
            <div class="col-xl-4">
                <h4>Finance Brand Number</h4>
                @include('partials.review.field', ['field' => 'finance_brand_number'])
            </div>
        </div>
        <div class="row">
            <div class="col-xl-4">
                <h4>Catalogue Notice</h4>
                @include('partials.review.field', ['field' => 'catalogue_notice'])
            </div>
            <div class="col-xl-4">
                <h4>Catalogue Notice (FR)</h4>
                @include('partials.review.field', ['field' => 'catalogue_notice_fr'])
            </div>
        </div>

        <div class="row">
            <div class="col-xl-4">
                <h4>Nutrition House</h4>
                @include('partials.review.field', ['field' => 'nutrition_house', 'format' => 'boolean'])
            </div>
            @if ($model->nutrition_house)
            <div class="col-xl-4">
                <h4>Nutrition House Payment Type</h4>
                @include('partials.review.field', ['field' => 'nutrition_house_payment_type', 'format' => 'ucfirst'])
            </div>
            <div class="col-xl-4">
                <h4>Nutrition House Payment</h4>
                @include('partials.review.field', ['field' => 'nutrition_house_payment'])
            </div>
            @endif
        </div>
        @if ($model->nutrition_house && $model->nutrition_house_payment_type === 'purity')
        <div class="row">
            <div class="col-xl-4">
                <h4>Nutrition House Percentage</h4>
                @include('partials.review.field', ['field' => 'nutrition_house_percentage'])
            </div>
            <div class="col-xl-4">
                <h4>Purity Life Percentage</h4>
                @include('partials.review.field', ['field' => 'nutrition_house_purity_percentage'])
            </div>
        </div>
        @endif
        <div class="row">
            <div class="col-xl-4">
                <h4>Health First Network</h4>
                @include('partials.review.field', ['field' => 'health_first', 'format' => 'boolean'])
            </div>
            @if ($model->health_first)
            <div class="col-xl-4">
                <h4>Health First Network Payment Type</h4>
                @include('partials.review.field', ['field' => 'health_first_payment_type', 'format' => 'ucfirst'])
            </div>
            <div class="col-xl-4">
                <h4>Health First Network Payment</h4>
                @include('partials.review.field', ['field' => 'health_first_payment'])
            </div>
            @endif
        </div>
        @if ($model->health_first && $model->health_first_payment_type === 'purity')
        <div class="row">
            <div class="col-xl-4">
                <h4>Health First Network Percentage</h4>
                @include('partials.review.field', ['field' => 'health_first_percentage'])
            </div>
            <div class="col-xl-4">
                <h4>Purity Life Percentage</h4>
                @include('partials.review.field', ['field' => 'health_first_purity_percentage'])
            </div>
        </div>
        @endif
        <div class="row">
            <div class="col-xl-4">
                <h4>Allow OI Promos</h4>
                @include('partials.review.field', ['field' => 'allow_oi', 'format' => 'boolean'])
            </div>
        </div>
    </div>
</div>
