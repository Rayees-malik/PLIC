<h3 class="js-review-toggle review-toggle {{ $errors->images->count() ? 'open error' : '' }}">Images</h3>
<div class="review-wrap">
    <div class="review-content {{ $errors->images->count() ? 'error' : '' }}">
        <div class="row">
            <div class="col-xl-6">
                <h4>Primary Product Image</h4>
                @include('partials.review.media-field', ['collection' => 'product'])
            </div>
            <div class="col-xl-6">
                <h4>Label Flat</h4>
                @include('partials.review.media-field', ['collection' => 'label_flat'])
            </div>
            @if ($model->requiresNutritionalFacts)
            <div class="col-xl-6">
                <h4>Nutritional Facts</h4>
                @include('partials.review.media-field', ['collection' => 'nutritional_facts'])
            </div>
            @endif
            @if ($model->requiresIngredientPanel)
            <div class="col-xl-6">
                <h4>Ingredient Panel</h4>
                @include('partials.review.media-field', ['collection' => 'ingredient_panel'])
            </div>
            @endif
        </div>
        <div class="row">
            <div class="col">
                <h4>Additional Images</h4>
                @include('partials.review.media-field', ['collection' => 'additional', 'customProperty' => 'label'])
            </div>
        </div>
    </div>
</div>
