<div id="packaging" class="js-stepper-step stepper-step">
    <h3 class="form-section-title">
        Packaging
        <span class="float-right">{{ $model->name }}{{ isset($signoffForm) && optional($model->as400StockData ?? $model->as400StockDataClone)->status == 'D' ? ' (RELIST)' : '' }}</span>
    </h3>
    <div class="row">
        <div class="dropdown-wrap col-xl-4">
            <label>Measurement System</label>
            <div class="dropdown-icon">
                <select name="measurement_system" class="js-measurement">
                    <option value="metric">
                        Metric
                    </option>
                    <option value="imperial" {{ old('measurement_system') == 'imperial' ? 'selected' : '' }}>
                        Imperial
                    </option>
                </select>
            </div>
        </div>
        <div class="dropdown-wrap col-xl-4 {{ $errors->packaging->has('purity_sell_by_unit') ? ' dropdown-danger' : '' }}">
            <label>Unit Bought/Sold By Purity</label>
            <div class="dropdown-icon">
                <select class="js-purity-sell-by" name="purity_sell_by_unit">
                    @foreach ($model::SELL_BY_UNITS as $value => $label)
                    <option value="{{ $value }}" {{ old('purity_sell_by_unit', $model->purity_sell_by_unit) & $value ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            @if ($errors->packaging->has('purity_sell_by_unit'))
            <small class="info-danger">{{ $errors->packaging->first('purity_sell_by_unit') }}</small>
            @endif
        </div>
        <div class="dropdown-wrap col-xl-4 {{ $errors->packaging->has('retailer_sell_by_unit') ? ' dropdown-danger' : '' }}">
            <label>Unit Sold By Retailer</label>
            <div class="dropdown-icon">
                <select name="retailer_sell_by_unit[]" class="searchable" multiple data-placeholder="Select Unit(s)">
                    @foreach ($model::SELL_BY_UNITS as $value => $label)
                    <option value="{{ $value }}" {{ array_sum(Arr::wrap(old('retailer_sell_by_unit', $model->retailer_sell_by_unit))) & $value ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            @if ($errors->packaging->has('retailer_sell_by_unit'))
            <small class="info-danger">{{ $errors->packaging->first('retailer_sell_by_unit') }}</small>
            @endif
        </div>
    </div>

    <h4 class="form-section-title mt-3">Single Unit</h4>
    <div class="row">
        <div class="input-wrap col-xl-4 {{ $errors->packaging->has('upc') ? ' input-danger' : '' }}">
            <label>UPC/EAN Code
                <div class="icon-input">
                    <i class="material-icons pre-icon">crop_free</i>
                    <input type="text" name="upc" class="js-cat-field" data-cat-target="upc" data-cat-action="upc" value="{{ old('upc', $model->upc) }}" {{ !$errors->packaging->has('upc') && auth()->user()->isVendor && !$model->isNewSubmission && $model->upc ? 'readonly' : '' }}>
                </div>
            </label>
            @if ($errors->packaging->has('upc'))
            <small class="info-danger">{{ $errors->packaging->first('upc') }}</small>
            @endif
        </div>
        <div class="input-wrap col-xl-4 {{ $errors->packaging->has('size') ? ' input-danger' : '' }}">
            <label>Product Size
                <div class="icon-input">
                    <i class="material-icons pre-icon">straighten</i>
                    <input type="text" name="size" class="js-cat-field" data-cat-target="size_amount" value="{{ old('size', round($model->size, 2)) }}">
                </div>
            </label>
            @if ($errors->packaging->has('size'))
            <small class="info-danger">{{ $errors->packaging->first('size') }}</small>
            @endif
        </div>
        <div class="dropdown-wrap col-xl-4 {{ $errors->packaging->has('uom_id') ? ' dropdown-danger' : '' }}">
            <label>Unit of Measure</label>
            <div class="dropdown-icon">
                <select name="uom_id" class="js-cat-field" data-cat-target="size_uom" data-cat-action="select">
                    <option value="">Select...</option>
                    @foreach ($uoms as $uom)
                    <option value="{{ $uom->id }}" {{ old('uom_id', $model->uom_id) == $uom->id ? 'selected' : '' }} data-cat-val="{{ $uom->unit }}">
                        {{ $uom->description }} ({{ $uom->unit }})
                    </option>
                    @endforeach
                </select>
            </div>
            @if ($errors->packaging->has('uom_id'))
            <small class="info-danger">{{ $errors->packaging->first('uom_id') }}</small>
            @endif
        </div>
    </div>

    <div class="row">
        <div class="input-wrap col-xl-4 {{ $errors->packaging->has('unit_width') ? ' input-danger' : '' }}">
            <label>Unit Width
                <div class="icon-input">
                    <div class="placeholder js-cm" data-placeholder="cm">
                        <i class="material-icons pre-icon">straighten</i>
                        <input type="text" name="unit_width" value="{{ old('unit_width', optional($model->dimensions)->width ?? '') }}">
                    </div>
                </div>
            </label>
            @if ($errors->packaging->has('unit_width'))
            <small class="info-danger">{{ $errors->packaging->first('unit_width') }}</small>
            @endif
        </div>
        <div class="input-wrap col-xl-4 {{ $errors->packaging->has('unit_depth') ? ' input-danger' : '' }}">
            <label>Unit Depth
                <div class="icon-input">
                    <div class="placeholder js-cm" data-placeholder="cm">
                        <i class="material-icons pre-icon">straighten</i>
                        <input type="text" name="unit_depth" value="{{ old('unit_depth', optional($model->dimensions)->depth ?? '') }}">
                    </div>
                </div>
            </label>
            @if ($errors->packaging->has('unit_depth'))
            <small class="info-danger">{{ $errors->packaging->first('unit_depth') }}</small>
            @endif
        </div>
        <div class="input-wrap col-xl-4 {{ $errors->packaging->has('unit_height') ? ' input-danger' : '' }}">
            <label>Unit Height
                <div class="icon-input">
                    <div class="placeholder js-cm" data-placeholder="cm">
                        <i class="material-icons pre-icon">straighten</i>
                        <input type="text" name="unit_height" value="{{ old('unit_height', optional($model->dimensions)->height ?? '') }}">
                    </div>
                </div>
            </label>
            @if ($errors->packaging->has('unit_height'))
            <small class="info-danger">{{ $errors->packaging->first('unit_height') }}</small>
            @endif
        </div>
    </div>
    <div class="row">
        <div class="input-wrap col-xl-4 {{ $errors->packaging->has('unit_gross_weight') ? ' input-danger' : '' }}">
            <label>Unit Gross Weight
                <div class="icon-input">
                    <div class="placeholder js-kg" data-placeholder="kg">
                        <i class="material-icons pre-icon">straighten</i>
                        <input type="text" name="unit_gross_weight" value="{{ old('unit_gross_weight', optional($model->dimensions)->gross_weight ?? '') }}">
                    </div>
                </div>
            </label>
            @if ($errors->packaging->has('unit_gross_weight'))
            <small class="info-danger">{{ $errors->packaging->first('unit_gross_weight') }}</small>
            @endif
        </div>
        <div class="input-wrap col-xl-4 {{ $errors->packaging->has('unit_net_weight') ? ' input-danger' : '' }}" {!! $model->hasNetWeight ? '' : 'style="display: none;"'
            !!}
            <label>Unit Net Weight
                <div class="icon-input js-kg">
                    <div class="placeholder js-kg" data-placeholder="kg">
                        <i class="material-icons pre-icon">straighten</i>
                        <input type="text" name="unit_net_weight" value="{{ old('unit_net_weight', optional($model->dimensions)->net_weight ?? '') }}">
                    </div>
                </div>
            </label>
            @if ($errors->packaging->has('unit_net_weight'))
            <small class="info-danger">{{ $errors->packaging->first('unit_net_weight') }}</small>
            @endif
        </div>
    </div>

    <h4 class="form-section-title mt-3">Inner Case</h4>
    <div class="row">
        <div class="input-wrap col-xl-4 {{ $errors->packaging->has('inner_upc') ? ' input-danger' : '' }}">
            <label>UPC/EAN Code
                <div class="icon-input">
                    <i class="material-icons pre-icon">crop_free</i>
                    <input type="text" name="inner_upc" value="{{ old('inner_upc', $model->inner_upc) }}" {{ !$errors->packaging->has('inner_upc') && auth()->user()->isVendor && !$model->isNewSubmission && $model->inner_upc ? 'readonly' : '' }}>
                </div>
            </label>
            @if ($errors->packaging->has('inner_upc'))
            <small class="info-danger">{{ $errors->packaging->first('inner_upc') }}</small>
            @endif
        </div>
    </div>

    <div class="row">
        <div class="input-wrap col-xl-4 {{ $errors->packaging->has('inner_width') ? ' input-danger' : '' }}">
            <label>Inner Width
                <div class="icon-input">
                    <div class="placeholder js-cm" data-placeholder="cm">
                        <i class="material-icons pre-icon">straighten</i>
                        <input type="text" name="inner_width" value="{{ old('inner_width', optional($model->innerDimensions)->width ?? '') }}">
                    </div>
                </div>
            </label>
            @if ($errors->packaging->has('inner_width'))
            <small class="info-danger">{{ $errors->packaging->first('inner_width') }}</small>
            @endif
        </div>
        <div class="input-wrap col-xl-4 {{ $errors->packaging->has('inner_depth') ? ' input-danger' : '' }}">
            <label>Inner Depth
                <div class="icon-input">
                    <div class="placeholder js-cm" data-placeholder="cm">
                        <i class="material-icons pre-icon">straighten</i>
                        <input type="text" name="inner_depth" value="{{ old('inner_depth', optional($model->innerDimensions)->depth ?? '') }}">
                    </div>
                </div>
            </label>
            @if ($errors->packaging->has('inner_depth'))
            <small class="info-danger">{{ $errors->packaging->first('inner_depth') }}</small>
            @endif
        </div>
        <div class="input-wrap col-xl-4 {{ $errors->packaging->has('inner_height') ? ' input-danger' : '' }}">
            <label>Inner Height
                <div class="icon-input">
                    <div class="placeholder js-cm" data-placeholder="cm">
                        <i class="material-icons pre-icon">straighten</i>
                        <input type="text" name="inner_height" value="{{ old('inner_height', optional($model->innerDimensions)->height ?? '') }}">
                    </div>
                </div>
            </label>
            @if ($errors->packaging->has('inner_height'))
            <small class="info-danger">{{ $errors->packaging->first('inner_height') }}</small>
            @endif
        </div>
    </div>

    <div class="row">
        <div class="input-wrap col-xl-4 {{ $errors->packaging->has('inner_gross_weight') ? ' input-danger' : '' }}">
            <label>Inner Gross Weight
                <div class="icon-input">
                    <div class="placeholder js-kg" data-placeholder="kg">
                        <i class="material-icons pre-icon">straighten</i>
                        <input type="text" name="inner_gross_weight" value="{{ old('inner_gross_weight', optional($model->innerDimensions)->gross_weight ?? '') }}">
                    </div>
                </div>
            </label>
            @if ($errors->packaging->has('inner_gross_weight'))
            <small class="info-danger">{{ $errors->packaging->first('inner_gross_weight') }}</small>
            @endif
        </div>
        <div class="input-wrap col-xl-4 {{ $errors->packaging->has('inner_units') ? ' input-danger' : '' }}">
            <label>Units Per Inner
                <div class="icon-input">
                    <i class="material-icons pre-icon">unfold_more</i>
                    <input type="text" name="inner_units" class="js-cat-field js-inner-units" data-cat-target="case_size" value="{{ old('inner_units', $model->inner_units) }}">
                </div>
            </label>
            @if ($errors->packaging->has('inner_units'))
            <small class="info-danger">{{ $errors->packaging->first('inner_units') }}</small>
            @endif
        </div>
    </div>

    <h4 class="form-section-title mt-3">Master Case <small class="subnote">If product will be listed into FDM accounts, all Master Case details must be completed.</small></h4>
    <div class="row">
        <div class="input-wrap col-xl-4 {{ $errors->packaging->has('master_upc') ? ' input-danger' : '' }}">
            <label>UPC/EAN Code
                <div class="icon-input">
                    <i class="material-icons pre-icon">crop_free</i>
                    <input type="text" name="master_upc" value="{{ old('master_upc', $model->master_upc) }}" {{ !$errors->packaging->has('master_upc') && auth()->user()->isVendor && !$model->isNewSubmission && $model->master_upc ? 'readonly' : '' }}>
                </div>
            </label>
            @if ($errors->packaging->has('master_upc'))
            <small class="info-danger">{{ $errors->packaging->first('master_upc') }}</small>
            @endif
        </div>
    </div>

    <div class="row">
        <div class="input-wrap col-xl-4 {{ $errors->packaging->has('master_width') ? ' input-danger' : '' }}">
            <label>Case Width
                <div class="icon-input">
                    <div class="placeholder js-cm" data-placeholder="cm">
                        <i class="material-icons pre-icon">straighten</i>
                        <input type="text" name="master_width" value="{{ old('master_width', optional($model->masterDimensions)->width ?? '') }}">
                    </div>
                </div>
            </label>
            @if ($errors->packaging->has('master_width'))
            <small class="info-danger">{{ $errors->packaging->first('master_width') }}</small>
            @endif
        </div>
        <div class="input-wrap col-xl-4 {{ $errors->packaging->has('master_depth') ? ' input-danger' : '' }}">
            <label>Case Depth
                <div class="icon-input">
                    <div class="placeholder js-cm" data-placeholder="cm">
                        <i class="material-icons pre-icon">straighten</i>
                        <input type="text" name="master_depth" value="{{ old('master_depth', optional($model->masterDimensions)->depth ?? '') }}">
                    </div>
                </div>
            </label>
            @if ($errors->packaging->has('master_depth'))
            <small class="info-danger">{{ $errors->packaging->first('master_depth') }}</small>
            @endif
        </div>
        <div class="input-wrap col-xl-4 {{ $errors->packaging->has('master_height') ? ' input-danger' : '' }}">
            <label>Case Height
                <div class="icon-input">
                    <div class="placeholder js-cm" data-placeholder="cm">
                        <i class="material-icons pre-icon">straighten</i>
                        <input type="text" name="master_height" value="{{ old('master_height', optional($model->masterDimensions)->height ?? '') }}">
                    </div>
                </div>
            </label>
            @if ($errors->packaging->has('master_height'))
            <small class="info-danger">{{ $errors->packaging->first('master_height') }}</small>
            @endif
        </div>
    </div>

    <div class="row">
        <div class="input-wrap col-xl-4 {{ $errors->packaging->has('master_gross_weight') ? ' input-danger' : '' }}">
            <label>Case Gross Weight
                <div class="icon-input">
                    <div class="placeholder js-kg" data-placeholder="kg">
                        <i class="material-icons pre-icon">straighten</i>
                        <input type="text" name="master_gross_weight" value="{{ old('master_gross_weight', optional($model->masterDimensions)->gross_weight ?? '') }}">
                    </div>
                </div>
            </label>
            @if ($errors->packaging->has('master_gross_weight'))
            <small class="info-danger">{{ $errors->packaging->first('master_gross_weight') }}</small>
            @endif
        </div>
    </div>

    <div class="row">
        <div class="input-wrap col-xl-4 {{ $errors->packaging->has('master_units') ? ' input-danger' : '' }}">
            <label>Units Per Case
                <div class="icon-input">
                    <i class="material-icons pre-icon">unfold_more</i>
                    <input type="text" name="master_units" class="js-master-units" value="{{ old('master_units', $model->master_units) }}">
                </div>
            </label>
            @if ($errors->packaging->has('master_units'))
            <small class="info-danger">{{ $errors->packaging->first('master_units') }}</small>
            @endif
        </div>
        <div class="input-wrap col-xl-4 {{ $errors->packaging->has('cases_per_tie') ? ' input-danger' : '' }}">
            <label>Cases Per Tie
                <div class="icon-input">
                    <i class="material-icons pre-icon">height</i>
                    <input type="text" name="cases_per_tie" value="{{ old('cases_per_tie', $model->cases_per_tie) }}">
                </div>
            </label>
            @if ($errors->packaging->has('cases_per_tie'))
            <small class="info-danger">{{ $errors->packaging->first('cases_per_tie') }}</small>
            @endif
        </div>
        <div class="input-wrap col-xl-4 {{ $errors->packaging->has('layers_per_skid') ? ' input-danger' : '' }}">
            <label>Layers Per Skid
                <div class="icon-input">
                    <i class="material-icons pre-icon">menu</i>
                    <input type="text" name="layers_per_skid" value="{{ old('layers_per_skid', $model->layers_per_skid) }}">
                </div>
            </label>
            @if ($errors->packaging->has('layers_per_skid'))
            <small class="info-danger">{{ $errors->packaging->first('layers_per_skid') }}</small>
            @endif
        </div>
    </div>

    <h4 class="form-section-title mt-3">Product or Packaging Contains</h4>
    <div class="row {{ $errors->packaging->has('packaging_materials') ? ' input-danger' : '' }}">
        @foreach ($packagingMaterials as $material)
        <div class="col-xl-4">
            <div class="checkbox-wrap">
                <label class="checkbox">
                    <input type="checkbox" name="packaging_materials[]" value="{{ $material->id }}" {{
                        in_array($material->id, Arr::wrap(old('packaging_materials', $model->packagingMaterials->contains($material->id) ? $material->id : null))) ? 'checked' : ''
                    }}>
                    <span class="checkbox-checkmark"></span>
                    <span class="checkbox-label">{{ $material->name }}</span>
                </label>
            </div>
        </div>
        @endforeach
    </div>
</div>
