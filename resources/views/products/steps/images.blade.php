<div id="image" class="js-stepper-step stepper-step uploader-wrap">
    @if (!$model->id)
    <div class="row mb-4">
        @include('partials.errors.error-flash', ['message' => 'Product name and category are required to upload files.'])
    </div>
    @endif
    <h3 class="form-section-title">
        Required Images
        <span class="float-right">{{ $model->name }}{{ isset($signoffForm) && optional($model->as400StockData ?? $model->as400StockDataClone)->status == 'D' ? ' (RELIST)' : '' }}</span>
    </h3>

    <div class="mb-2">
        <small>
            <em>
                Notice: Product images must be high resolution (minimum 300dpi) JPG or PNG. For label flats we recommend PDF format.
            </em>
        </small>
    </div>

    <div class="row">
        <div class="col-xl-6 input-wrap {{ $errors->images->has('product') ? ' input-danger' : '' }}">
            <label>Primary Product Image</label>
            @if ($errors->images->has('product'))
            <small class="info-danger">{{ $errors->images->first('product') }}</small>
            @endif
            {!! BladeHelper::uploaderField($model, 'product', ['allowRestore' => $signoffForm ?? false]) !!}
        </div>

        <div class="col-xl-6 input-wrap {{ $errors->images->has('label_flat') ? ' input-danger' : '' }}">
            <label>Label Flat</label>
            @if ($errors->images->has('label_flat'))
            <small class="info-danger">{{ $errors->images->first('label_flat') }}</small>
            @endif
            {!! BladeHelper::uploaderField($model, 'label_flat', ['extensions' => 'pdf', 'allowRestore' => $signoffForm ?? false]) !!}
        </div>

        @if ($model->requiresNutritionalFacts)
        <div class="col-xl-6 input-wrap {{ $errors->images->has('nutritional_facts') ? ' input-danger' : '' }}">
            <label>Nutritional Facts</label>
            @if ($errors->images->has('nutritional_facts'))
            <small class="info-danger">{{ $errors->images->first('nutritional_facts') }}</small>
            @endif
            {!! BladeHelper::uploaderField($model, 'nutritional_facts', ['extensions' => 'pdf', 'allowRestore' => $signoffForm ?? false]) !!}
        </div>
        @endif

        @if ($model->requiresIngredientPanel)
        <div class="col-xl-6 input-wrap {{ $errors->images->has('ingredient_panel') ? ' input-danger' : '' }}">
            <label>Ingredient Panel</label>
            @if ($errors->images->has('ingredient_panel'))
            <small class="info-danger">{{ $errors->images->first('ingredient_panel') }}</small>
            @endif
            {!! BladeHelper::uploaderField($model, 'ingredient_panel', ['extensions' => 'pdf', 'allowRestore' => $signoffForm ?? false]) !!}
        </div>
        @endif
    </div>

    <h3 class="form-section-title">
        Additional Images
    </h3>
    {!! BladeHelper::uploaderField($model, 'additional', ['type' => 'label', 'limit' => 5, 'customProperties' => 'label', 'allowRestore' => $signoffForm ?? false]) !!}
</div>
