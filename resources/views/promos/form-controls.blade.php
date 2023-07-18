<input type="hidden" name="updated_at" value="{{ $model->updated_at }}">
<div class="card">
    <div class="card-body">
        <div class="js-promo-form">
            <div class="row">
                <div class="dropdown-wrap col-xl-4 {{ $errors->header->has('brand_id') ? ' dropdown-danger' : '' }}">
                    <label>Brand</label>
                    @if ($brands)
                    <div class="dropdown-icon">
                        <select name="brand_id" class="searchable js-brand-id">
                            @foreach ($brands as $loopBrand)
                            <option value="{{ $loopBrand->id }}" {{ old('brand_id') == $loopBrand->id ? 'selected' : '' }} data-oi="{{ $loopBrand->allow_oi }}">
                                {{ $loopBrand->name }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    @if ($errors->header->has('brand_id'))
                    <small class="info-danger">{{ $errors->header->first('brand_id') }}</small>
                    @endif
                    @else
                    <h2 class="mb-0">{{ $brand->name }}</h2>
                    <input type="hidden" class="js-brand-id" value="{{ $brand->id }}">
                    @endif
                </div>

                <div class="dropdown-wrap col-xl-4 {{ $errors->header->has('period_id') ? ' dropdown-danger' : '' }}">
                    <label>Promo Period</label>
                    <div class="js-period-select">
                        @include('promos.period-select')
                    </div>
                </div>
            </div>

            @if ($promoConfig)
            <div class="row">
                <div class="col-xl-6">
                    <div class="row">
                        @foreach (Arr::get($promoConfig, 'promoFields', []) as $field => $fieldConfig)
                        {!! BladeHelper::promoHeaderField($model, $field, $fieldConfig) !!}
                        @endforeach
                    </div>
                </div>
            </div>
            @endif

            @if (!$promoConfig || !Arr::get($promoConfig, 'onlyPercentDiscount'))
            <div class="row">
                <div class="col-xl-3 input-wrap {{ $errors->header->has('dollar_discount') ? ' dropdown-danger' : '' }}">
                    <label>Discount Type</label>
                    <div class="inline-radio-group">
                        <div class="radio-wrap">
                            <label class="radio">
                                <input type="radio" name="dollar_discount" class="js-discount-type" value="0" {{ !old('dollar_discount', $model->dollar_discount) ? 'checked' : '' }}>
                                <span class="radio-checkmark"></span>
                                <span class="radio-label">Percentage</span>
                            </label>
                        </div>
                        <div class="radio-wrap">
                            <label class="radio">
                                <input type="radio" name="dollar_discount" class="js-discount-type" value="1" {{ old('dollar_discount', $model->dollar_discount) ? 'checked' : '' }}>
                                <span class="radio-checkmark"></span>
                                <span class="radio-label">Dollar</span>
                            </label>
                        </div>
                    </div>
                    @if ($errors->header->has('dollar_discount'))
                    <small class="info-danger">{{ $errors->header->first('dollar_discount') }}</small>
                    @endif
                </div>
            </div>
            @else
            <input type="hidden" name="dollar_discount" class="js-discount-type" value="0">
            @endif

            <div class="row js-oi-row" style="{{ $brand->allow_oi ? '' : 'display:none;' }}">
                <div class="input-wrap col-xl-4 {{ $errors->header->has('oi') ? ' dropdown-danger' : '' }}">
                    <label>Enable OI Discounts</label>
                    <div class="inline-radio-group">
                        <div class="radio-wrap">
                            <label class="radio">
                                <input type="radio" name="oi" class="js-promo-oi" value="0" {{ !old('oi', $model->oi) ? 'checked' : '' }}>
                                <span class="radio-checkmark"></span>
                                <span class="radio-label">MCB</span>
                            </label>
                        </div>
                        <div class="radio-wrap">
                            <label class="radio">
                                <input type="radio" name="oi" class="js-promo-oi" value="1" {{ old('oi', $model->oi) ? 'checked' : '' }}>
                                <span class="radio-checkmark"></span>
                                <span class="radio-label">OI</span>
                            </label>
                        </div>
                    </div>
                    @if ($errors->header->has('oi'))
                    <small class="info-danger">{{ $errors->header->first('oi') }}</small>
                    @endif
                </div>

                <div class="dropdown-wrap js-oi-dates col-xl-4 {{ $errors->header->has('oi_period_dates') ? ' dropdown-danger' : '' }}" style="{{ old('oi', $model->oi) ? '' : 'display:none;' }}">
                    <label>OI Dates</label>
                    <div class="dropdown-icon">
                        <select name="oi_period_dates">
                            <option value="0" {{ !old('oi_period_dates', $model->oi_period_dates) ? 'selected' : '' }}>15th to 15th</option>
                            <option value="1" {{ old('oi_period_dates', $model->oi_period_dates) ? 'selected' : '' }}>First to Last</option>
                        </select>
                    </div>
                    @if ($errors->header->has('oi_period_dates'))
                    <small class="info-danger">{{ $errors->header->first('oi_period_dates') }}</small>
                    @endif
                </div>
            </div>

            <div class="mb-3"></div>

            <div class="row justify-content-between">
                <div class="col-xl-4">
                    <button type="button" class="secondary-btn mt-4" data-toggle="modal" title="Quick Update" data-target="#updateModal">
                        <i class="material-icons">wifi_protected_setup</i>
                        Quick Update
                    </button>
                </div>
                <div class="input-wrap col-xl-4 ">
                    <label>
                        Search
                        <div class="icon-input">
                            <i class="material-icons pre-icon">search</i>
                            <input type="text" class="js-promo-search" placeholder="">
                        </div>
                    </label>
                </div>
            </div>
            <div class="js-promo-container">
                @include('promos.product-promo-table')
            </div>
        </div>
    </div>
</div>
<input type="hidden" class="js-route-prefix" value="{{ $ownerId ? "/{$ownerRoutePrefix}/{$ownerId}" : "" }}">

@include('promos.quick-update')

@push('scripts')
{!! BladeHelper::initChosenSelect('searchable') !!}
<script type="text/javascript" src="{{ mix('js/modules/promos.js') }}"></script>
<script type="text/javascript" src="{{ mix('js/modules/promos-search.js') }}"></script>
@endpush
