<h3 class="js-review-toggle review-toggle {{ $errors->details->count() ? 'open error' : '' }}">Details</h3>
<div class="review-wrap">
    <div class="review-content {{ $errors->details->count() ? 'error' : '' }}">
        <div class="row">
            <div class="col-xl-6">
                <h4>Tester Available to Order</h4>
                @include('partials.review.field', ['field' => 'tester_available', 'format' => 'boolean'])
            </div>
            @if ($model->tester_available)
            <div class="col-xl-6">
                <h4>Tester Brand Product Code</h4>
                @include('partials.review.field', ['field' => 'tester_brand_stock_id'])
            </div>
            @endif
        </div>
        <div class="row">
            <div class="col-xl-6">
                <h4>Brand Product Code</h4>
                @include('partials.review.field', ['field' => 'brand_stock_id'])
            </div>
        </div>
        <div class="row">
            <div class="col">
                <h4>Product Flags</h4>
                @include('partials.review.many-field', ['relation' => 'flags', 'field' => 'name'])
            </div>
        </div>
        <div class="row">
            <div class="col">
                <h4>Description</h4>
                @include('partials.review.field', ['field' => 'description'])
            </div>
        </div>
        <div class="row">
            <div class="col">
                <h4>Description (FR)</h4>
                @include('partials.review.field', ['field' => 'description_fr'])
            </div>
        </div>
        <div class="row">
            <div class="col-xl-6">
                <h4>Key Product Features</h4>
                <ol>
                    @for ($i = 1; $i <= 5; $i++) <li>@include('partials.review.field', ['field' => "features_{$i}"])</li>
                        @endfor
                </ol>
            </div>
            <div class="col-xl-6">
                <h4>Key Product Features (FR)</h4>
                <ol>
                    @for ($i = 1; $i <= 5; $i++) <li>@include('partials.review.field', ['field' => "features_fr_{$i}"])</li>
                        @endfor
                </ol>
            </div>
        </div>
        <div class="row">
            <div class="col-xl-6">
                <h4>Ingredients (EN)</h4>
                @include('partials.review.field', ['field' => 'ingredients'])
            </div>
            <div class="col-xl-6">
                <h4>Ingredients (FR)</h4>
                @include('partials.review.field', ['field' => 'ingredients_fr'])
            </div>
        </div>

        @if ($model->hasRecommendedUse)
        <div class="row">
            <div class="col-xl-6">
                <h4>Recommended Use/Indications (EN)</h4>
                @include('partials.review.field', ['field' => 'recommended_use'])
            </div>
            <div class="col-xl-6">
                <h4>Recommended Use/Indications (FR)</h4>
                @include('partials.review.field', ['field' => 'recommended_use_fr'])
            </div>
        </div>
        @endif

        @if ($model->hasRecommendedDosage)
        <div class="row">
            <div class="col-xl-6">
                <h4>Recommended Dosage (EN)</h4>
                @include('partials.review.field', ['field' => 'recommended_dosage'])
            </div>
            <div class="col-xl-6">
                <h4>Recommended Dosage (FR)</h4>
                @include('partials.review.field', ['field' => 'recommended_dosage_fr'])
            </div>
        </div>
        @endif

        @if ($model->hasWarnings)
        <div class="row">
            <div class="col-xl-6">
                <h4>Cautions & Warnings (EN)</h4>
                @include('partials.review.field', ['field' => 'warnings'])
            </div>
            <div class="col-xl-6">
                <h4>Cautions & Warnings (FR)</h4>
                @include('partials.review.field', ['field' => 'warnings_fr'])
            </div>
        </div>
        <div class="row">
            <div class="col-xl-6">
                <h4>Contraindications (EN)</h4>
                @include('partials.review.field', ['field' => 'contraindications'])
            </div>
            <div class="col-xl-6">
                <h4>Contraindications (FR)</h4>
                @include('partials.review.field', ['field' => 'contraindications_fr'])
            </div>
        </div>
        @endif

        <div class="row">
            <div class="col-xl-6">
                <h4>Benefits (EN)</h4>
                @include('partials.review.field', ['field' => 'benefits'])
            </div>
            <div class="col-xl-6">
                <h4>Benefits (FR)</h4>
                @include('partials.review.field', ['field' => 'benefits_fr'])
            </div>
        </div>

        <div class="row">
            <div class="col-xl-4">
                <h4>Shelf Life</h4>
                @include('partials.review.field', ['field' => 'shelf_life', 'suffix' => ucfirst($model->shelf_life_units)])
            </div>
        </div>
        <div class="row">
            <div class="col">
                <h4>Product Allergens</h4>
                <div class="allergens-wrap mb-2">
                    <div></div>
                    <div><b>Contains</b></div>
                    <div><b>May Contain</b></div>
                    <div><b>Does Not Contain</b></div>
                </div>
                @foreach ($allergens as $allergen)
                <div class="allergens-wrap">
                    <div>{{ $allergen->name }}</div>
                    <div class="radio-wrap">
                        <label class="radio">
                            @if ($model->getAllergenStatus($allergen) == 1)
                            <input type="radio" checked disabled>
                            @endif
                            <span class="radio-checkmark"></span>
                            <span class="radio-label"></span>
                        </label>
                    </div>
                    <div class="radio-wrap">
                        <label class="radio">
                            @if ($model->getAllergenStatus($allergen) == 0)
                            <input type="radio" checked disabled>
                            @endif
                            <span class="radio-checkmark"></span>
                            <span class="radio-label"></span>
                        </label>
                    </div>
                    <div class="radio-wrap">
                        <label class="radio">
                            @if ($model->getAllergenStatus($allergen) == -1)
                            <input type="radio" checked disabled>
                            @endif
                            <span class="radio-checkmark"></span>
                            <span class="radio-label"></span>
                        </label>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        <div class="row">
            <div class="col-xl-4">
                <h4>Product Certifications</h4>
                @include('partials.review.many-field', ['relation' => 'certifications', 'field' => 'name'])
            </div>
        </div>
    </div>
</div>
