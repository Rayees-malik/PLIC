<div id="distribution" class="js-stepper-step stepper-step">
    <h3 class="form-section-title">Distribution</h3>


    <div class="row">
        <div class="input-wrap col-xl-4 {{ $errors->distribution->has('allows_amazon_resale') ? 'input-danger' : '' }}">
            <label>Allows Amazon Resale</label>
            <div class="inline-radio-group">
                <div class="radio-wrap">
                    <label class="radio">
                        <input type="radio" name="allows_amazon_resale" value="0" {{ old('allows_amazon_resale', $model->allows_amazon_resale) ? 'checked' : '' }}>
                        <span class="radio-checkmark"></span>
                        <span class="radio-label">No</span>
                    </label>
                </div>
                <div class="radio-wrap">
                    <label class="radio">
                        <input type="radio" name="allows_amazon_resale" value="1" {{ !old('allows_amazon_resale', $model->allows_amazon_resale) ? 'checked' : '' }}>
                        <span class="radio-checkmark"></span>
                        <span class="radio-label">Yes</span>
                    </label>
                </div>
            </div>
            @if ($errors->distribution->has('allows_amazon_resale'))
            <small class="info-danger">{{ $errors->distribution->first('allows_amazon_resale') }}</small>
            @endif
        </div>
        <div class="input-wrap col-xl-4 {{ $errors->distribution->has('map_pricing') ? 'input-danger' : '' }}">
            <label>MAP Pricing</label>
            <div class="inline-radio-group">
                <div class="radio-wrap">
                    <label class="radio">
                        <input type="radio" class="js-cat-field" data-cat-target="map_pricing" data-cat-action="visibility" name="map_pricing" value="0" {{ !old('map_pricing', $model->map_pricing) ? 'checked' : '' }}>
                        <span class="radio-checkmark"></span>
                        <span class="radio-label">No</span>
                    </label>
                </div>
                <div class="radio-wrap">
                    <label class="radio">
                        <input type="radio" class="js-cat-field" data-cat-target="map_pricing" data-cat-action="visibility" name="map_pricing" value="1" {{ old('map_pricing', $model->map_pricing) ? 'checked' : '' }}>
                        <span class="radio-checkmark"></span>
                        <span class="radio-label">Yes</span>
                    </label>
                </div>
            </div>
            @if ($errors->distribution->has('map_pricing'))
            <small class="info-danger">{{ $errors->distribution->first('map_pricing') }}</small>
            @endif
        </div>
    </div>

    <div class="row">
        <div class="input-wrap col-xl-4 {{ $errors->distribution->has('contract_exclusive') ? 'input-danger' : '' }}">
            <label>Contract Exclusive</label>
            <div class="inline-radio-group">
                <div class="radio-wrap">
                    <label class="radio">
                        <input type="radio" name="contract_exclusive" class="js-distribution-status" value="0" {{ !old('contract_exclusive', $model->contract_exclusive) ? 'checked' : '' }}>
                        <span class="radio-checkmark"></span>
                        <span class="radio-label">No</span>
                    </label>
                </div>
                <div class="radio-wrap">
                    <label class="radio">
                        <input type="radio" name="contract_exclusive" class="js-distribution-status" value="1" {{ old('contract_exclusive', $model->contract_exclusive) ? 'checked' : '' }}>
                        <span class="radio-checkmark"></span>
                        <span class="radio-label">Yes</span>
                    </label>
                </div>
            </div>
            @if ($errors->distribution->has('contract_exclusive'))
            <small class="info-danger">{{ $errors->distribution->first('contract_exclusive') }}</small>
            @endif
        </div>
        <div class="col-xl-4 js-other-distributor-wrap {{ $errors->has('no_other_distributors') ? 'input-danger' : '' }}" {!! old('contract_exclusive', $model->contract_exclusive) ? 'style="visibility: hidden;"' : '' !!}>
            <br />
            <div class="checkbox-wrap mt-2">
                <label class="checkbox">
                    <input type="hidden" class="no-history" name="no_other_distributors" value="0">
                    <input type="checkbox" name="no_other_distributors" class="js-distribution-status" value="1" {{ old('no_other_distributors', $model->no_other_distributors) == "1" ? 'checked' : '' }}>
                    <span class="checkbox-checkmark"></span>
                    <span class="checkbox-label">No Other Distributors</span>
                </label>
            </div>
        </div>
        <div class="input-wrap col-xl-4 js-also-distributed {{ $errors->distribution->has('also_distributed_by') ? 'input-danger' : '' }}" {!! old('contract_exclusive', $model->contract_exclusive) || old('no_other_distributors', $model->no_other_distributors) ? 'style="visibility: hidden;"' : '' !!}>
            <label>Also Distributed By
                <div class="icon-input">
                    <i class="material-icons pre-icon">perm_identity</i>
                    <input type="text" name="also_distributed_by" value="{{ old('also_distributed_by', $model->also_distributed_by) }}">
                </div>
            </label>
            @if ($errors->distribution->has('also_distributed_by'))
            <small class="info-danger">{{ $errors->distribution->first('also_distributed_by') }}</small>
            @endif
        </div>
    </div>

    @if (auth()->user()->can('edit', App\Models\Brand::class) && !auth()->user()->isVendor)
    <div class="row">
        <div class="input-wrap col-xl-4 {{ $errors->purchasing->has('in_house_brand') ? 'input-danger' : '' }}">
            <label>In-House Brand</label>
            <div class="inline-radio-group">
                <div class="radio-wrap">
                    <label class="radio">
                        <input type="radio" name="in_house_brand" value="0" {{ !old('in_house_brand', $model->in_house_brand) ? 'checked' : '' }}>
                        <span class="radio-checkmark"></span>
                        <span class="radio-label">No</span>
                    </label>
                </div>
                <div class="radio-wrap">
                    <label class="radio">
                        <input type="radio" name="in_house_brand" value="1" {{ old('in_house_brand', $model->in_house_brand) ? 'checked' : '' }}>
                        <span class="radio-checkmark"></span>
                        <span class="radio-label">Yes</span>
                    </label>
                </div>
            </div>
            @if ($errors->purchasing->has('in_house_brand'))
            <small class="info-danger">{{ $errors->purchasing->first('in_house_brand') }}</small>
            @endif
        </div>
    </div>
    @endif
</div>
