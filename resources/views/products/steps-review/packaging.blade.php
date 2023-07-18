<h3 class="js-review-toggle review-toggle {{ $errors->packaging->count() ? 'open error' : '' }}">Packaging</h3>
<div class="review-wrap">
    <div class="review-content {{ $errors->packaging->count() ? 'error' : '' }}">
        <div class="row">
            <div class="col-sm-4">
                <h4>Unit Bought/Sold By Purity</h4>
                @include('partials.review.field', ['field' => 'purity_sell_by_unit', 'format' => 'bitarray', 'bitarray' => App\Models\Product::SELL_BY_UNITS])
            </div>
            <div class="col-xl-4">
                <h4>Unit Sold By Retailer</h4>
                @include('partials.review.field', ['field' => 'retailer_sell_by_unit', 'format' => 'bitarray', 'bitarray' => App\Models\Product::SELL_BY_UNITS])
            </div>
        </div>
        <h3>Single Unit</h3>
        <div class="row">
            <div class="col-xl-4">
                <h4>UPC/EAN Code</h4>
                @include('partials.review.field', ['field' => 'upc'])
            </div>
            <div class="col-xl-4">
                <h4>Product Size</h4>
                @include('partials.review.field', ['field' => 'size'])
            </div>
            <div class="col-xl-4">
                <h4>Unit of Measure</h4>
                @include('partials.review.field', ['field' => 'uom', 'subfield' => 'unit', 'formField' => 'uom_id'])
            </div>
        </div>
        <div class="row">
            <div class="col-xl-4">
                <h4>Unit Width</h4>
                @include('partials.review.field', ['field' => 'width', 'model' => $model->dimensions, 'suffix' => old('measurement_system') == 'imperial' ? 'in' : 'cm', 'formField' => 'unit_width'])
            </div>
            <div class="col-xl-4">
                <h4>Unit Depth</h4>
                @include('partials.review.field', ['field' => 'depth', 'model' => $model->dimensions, 'suffix' => old('measurement_system') == 'imperial' ? 'in' : 'cm', 'formField' => 'unit_depth'])
            </div>
            <div class="col-xl-4">
                <h4>Unit Height</h4>
                @include('partials.review.field', ['field' => 'height', 'model' => $model->dimensions, 'suffix' => old('measurement_system') == 'imperial' ? 'in' : 'cm', 'formField' => 'unit_height'])
            </div>
        </div>
        <div class="row">
            <div class="col-sm-4">
                <h4>Unit Gross Weight</h4>
                @include('partials.review.field', ['field' => 'gross_weight', 'model' => $model->dimensions, 'suffix' => old('measurement_system') == 'imperial' ? 'lb' : 'kg', 'formField' => 'unit_gross_weight'])
            </div>
            @if ($model->hasNetWeight)
            <div class="col-sm-4">
                <h4>Unit Net Weight</h4>
                @include('partials.review.field', ['field' => 'net_weight', 'model' => $model->dimensions, 'suffix' => old('measurement_system') == 'imperial' ? 'lb' : 'kg', 'formField' => 'unit_net_weight'])
            </div>
            @endif
        </div>
        <h3>Inner Case</h3>
        <div class="row">
            <div class="col-xl-4">
                <h4>UPC/EAN Code</h4>
                @include('partials.review.field', ['field' => 'inner_upc'])
            </div>
        </div>
        <div class="row">
            <div class="col-xl-4">
                <h4>Inner Width</h4>
                @include('partials.review.field', ['field' => 'width', 'model' => $model->innerDimensions, 'suffix' => old('measurement_system') == 'imperial' ? 'in' : 'cm', 'formField' => 'inner_width'])
            </div>
            <div class="col-xl-4">
                <h4>Inner Depth</h4>
                @include('partials.review.field', ['field' => 'depth', 'model' => $model->innerDimensions, 'suffix' => old('measurement_system') == 'imperial' ? 'in' : 'cm', 'formField' => 'inner_depth'])
            </div>
            <div class="col-xl-4">
                <h4>Inner Height</h4>
                @include('partials.review.field', ['field' => 'height', 'model' => $model->innerDimensions, 'suffix' => old('measurement_system') == 'imperial' ? 'in' : 'cm', 'formField' => 'inner_height'])
            </div>
        </div>
        <div class="row">
            <div class="col-xl-4">
                <h4>Inner Gross Weight</h4>
                @include('partials.review.field', ['field' => 'gross_weight', 'model' => $model->innerDimensions, 'suffix' => old('measurement_system') == 'imperial' ? 'lb' : 'kg', 'formField' => 'inner_gross_weight'])
            </div>
            <div class="col-xl-4">
                <h4>Units Per Inner</h4>
                @include('partials.review.field', ['field' => 'inner_units'])
            </div>
        </div>
        <h3>Master Case</h3>
        <div class="row">
            <div class="col-xl-4">
                <h4>UPC/EAN Code</h4>
                @include('partials.review.field', ['field' => 'master_upc'])
            </div>
        </div>
        <div class="row">
            <div class="col-xl-4">
                <h4>Case Width</h4>
                @include('partials.review.field', ['field' => 'width', 'model' => $model->masterDimensions, 'suffix' => old('measurement_system') == 'imperial' ? 'in' : 'cm', 'formField' => 'master_width'])
            </div>
            <div class="col-xl-4">
                <h4>Case Depth</h4>
                @include('partials.review.field', ['field' => 'depth', 'model' => $model->masterDimensions, 'suffix' => old('measurement_system') == 'imperial' ? 'in' : 'cm', 'formField' => 'master_depth'])
            </div>
            <div class="col-xl-4">
                <h4>Case Height</h4>
                @include('partials.review.field', ['field' => 'height', 'model' => $model->masterDimensions, 'suffix' => old('measurement_system') == 'imperial' ? 'in' : 'cm', 'formField' => 'master_height'])
            </div>
        </div>
        <div class="row">
            <div class="col-xl-4">
                <h4>Case Gross Weight</h4>
                @include('partials.review.field', ['field' => 'gross_weight', 'model' => $model->masterDimensions, 'suffix' => old('measurement_system') == 'imperial' ? 'lb' : 'kg', 'formField' => 'master_gross_weight'])
            </div>
        </div>
        <div class="row">
            <div class="col-xl-4">
                <h4>Units Per Case</h4>
                @include('partials.review.field', ['field' => 'master_units'])
            </div>
            <div class="col-xl-4">
                <h4>Cases Per Tie</h4>
                @include('partials.review.field', ['field' => 'cases_per_tie'])
            </div>
            <div class="col-xl-4">
                <h4>Layer Per Skid</h4>
                @include('partials.review.field', ['field' => 'layers_per_skid'])
            </div>
        </div>
        <div class="row">
            <div class="col">
                <h4>Product or Packaging Contains</h4>
                @include('partials.review.many-field', ['relation' => 'packagingMaterials', 'field' => 'name', 'formField' => 'packaging_materials'])
            </div>
        </div>
    </div>
</div>
