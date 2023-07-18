<div id="pricing" class="js-stepper-step stepper-step">
  <h3 class="form-section-title">
    Pricing
    <span class="float-right">{{ $model->name }}{{ isset($signoffForm) && optional($model->as400StockData ?? $model->as400StockDataClone)->status == 'D' ? ' (RELIST)' : '' }}</span>
  </h3>

  <div class="row">
    <div class="col-xl-3 col-sm-6">
      <label>Currency
        <h2>{{ $brand->currency->name ?? 'CAD' }}</h2>
      </label>
    </div>
    @if ($model->purity_sell_by_unit)
    <div class="col-xl-3 col-sm-6">
      <label class="pull-right">Sold By
        <h2>{{ \App\Helpers\BitArrayHelper::toString($model->purity_sell_by_unit, App\Models\Product::SELL_BY_UNITS) }}</h2>
      </label>
    </div>
    @endif
  </div>

  <div class="row">
    <div class="col-xl-6">
      <div class="checkbox-wrap">
        <label class="checkbox">
          <input type="hidden" class="no-history" name="not_for_resale" value="0">
          <input type="checkbox" name="not_for_resale" value="1" class="js-not-for-resale js-landed-field" {{ old('not_for_resale', $model->not_for_resale) ? 'checked' : '' }}>
          <span class="checkbox-checkmark"></span>
          <span class="checkbox-label">Not for Resale</span>
        </label>
      </div>
    </div>
  </div>

  <div class="row">
    @if ($model->isNewSubmission)
    <div class="input-wrap col-xl-4 {{ $errors->pricing->has('unit_cost') ? ' input-danger' : '' }}">
      <label>PO Price to Purity
        <div class="icon-input">
          <i class="material-icons pre-icon">attach_money</i>
          <input name="unit_cost" class="js-unit-cost js-landed-field" value="{{ old('unit_cost', $model->unit_cost) }}" {{ old('not_for_resale', $model->not_for_resale) ? 'readonly' : '' }}>
        </div>
      </label>
      @if ($errors->pricing->has('unit_cost'))
      <small class="info-danger">{{ $errors->pricing->first('unit_cost') }}</small>
      @endif
    </div>
    @else
    <div class="input-wrap col-xl-4 {{ $errors->pricing->has('unit_cost') ? ' input-danger' : '' }}">
      <label>New PO Price to Purity
        <div class="icon-input">
          <i class="material-icons pre-icon">attach_money</i>
          <input name="unit_cost" class="js-unit-cost js-landed-field" value="{{ old('unit_cost', $model->unit_cost) }}" {{ old('not_for_resale', $model->not_for_resale) ? 'readonly' : '' }}>
        </div>
      </label>
      @if ($errors->pricing->has('unit_cost'))
      <small class="info-danger">{{ $errors->pricing->first('unit_cost') }}</small>
      @endif
      Current Cost: <strong>${{ optional($model->as400Pricing ?? $model->as400PricingClone)->po_price }}</strong>
      <input type="hidden" class="js-current-unit-cost" value="{{ optional($model->as400Pricing ?? $model->as400PricingClone)->po_price }}">
    </div>
    <div class="input-wrap col-xl-4 {{ $errors->pricing->has('price_change_reason') ? ' input-danger' : '' }}">
      <label>Price Change Reason
        <div class="icon-input">
          <i class="material-icons pre-icon">change_history</i>
          <input name="price_change_reason" class="js-price-change-reason" value="{{ old('price_change_reason', $model->price_change_reason) }}" {{ old('not_for_resale', $model->not_for_resale) ? 'readonly' : '' }}>
        </div>
      </label>
      @if ($errors->pricing->has('price_change_reason'))
      <small class="info-danger">{{ $errors->pricing->first('price_change_reason') }}</small>
      @endif
    </div>
    <div class="input-wrap col-xl-4 {{ $errors->pricing->has('price_change_date') ? ' input-danger' : '' }}">
      <label>Price Change Date
        <div class="icon-input">
          <i class="material-icons pre-icon">calendar_today</i>
          <input name="price_change_date" class="js-datepicker" value="{{ old('price_change_date', $model->price_change_date) }}">
        </div>
      </label>
      @if ($errors->pricing->has('price_change_date'))
      <small class="info-danger">{{ $errors->pricing->first('price_change_date') }}</small>
      @endif
    </div>
    @endif
  </div>
  <div class="row mt-3">
    <div class="input-wrap col-xl-4 {{ $errors->pricing->has('available_ship_date') ? ' input-danger' : '' }}">
      <label>Available Ship Date
        <div class="icon-input">
          <i class="material-icons pre-icon">calendar_today</i>
          <input type="text" name="available_ship_date" class="js-datepicker" value="{{ old('available_ship_date', $model->available_ship_date) }}">
        </div>
      </label>
      @if ($errors->pricing->has('available_ship_date'))
      <small class="info-danger">{{ $errors->pricing->first('available_ship_date') }}</small>
      @endif
    </div>
    <div class="input-wrap col-xl-4 {{ $errors->pricing->has('minimum_order_units') ? ' input-danger' : '' }}">
      <label>Minimum Order By QTY
        <div class="icon-input">
          <i class="material-icons pre-icon">shopping_cart</i>
          <input type="text" name="minimum_order_units" value="{{ old('minimum_order_units', $model->minimum_order_units) }}">
        </div>
      </label>
      @if ($errors->pricing->has('minimum_order_units'))
      <small class="info-danger">{{ $errors->pricing->first('minimum_order_units') }}</small>
      @endif
    </div>
  </div>
  @if ((Bouncer::can('product.costing') || Bouncer::can('signoff.product.management')) && $model->signoff && ($model->signoff->step == 3 || $model->signoff->step == 4))
  <h4 class="form-section-title mt-3">Landed Cost Calculation</h4>
  <div class="row">
    <div class="input-wrap col-xl-4 {{ $errors->pricing->has('extra_addon_percent') ? ' input-danger' : '' }}">
      <label>Extra Addon Code %
        <div class="icon-input">
          <i class="pre-icon">%</i>
          <input name="extra_addon_percent" class="js-landed-field js-extra-addon-percent" value="{{ old('extra_addon_percent', $model->extra_addon_percent) }}">
        </div>
      </label>
      @if ($errors->pricing->has('extra_addon_percent'))
      <small class="info-danger">{{ $errors->pricing->first('extra_addon_percent') }}</small>
      @endif
    </div>
  </div>
  @if ($model->isNewSubmission)
  <div class="row">
    <div class="input-wrap col-xl-4 {{ $errors->pricing->has('temp_edlp') ? ' input-danger' : '' }}">
      <label>EDLP
        <div class="icon-input">
          <i class="pre-icon">%</i>
          <input name="temp_edlp" class="js-landed-field js-edlp" value="{{ old('temp_edlp', $model->temp_edlp) }}">
        </div>
      </label>
      @if ($errors->pricing->has('temp_edlp'))
      <small class="info-danger">{{ $errors->pricing->first('temp_edlp') }}</small>
      @endif
    </div>
    <div class="input-wrap col-xl-4 {{ $errors->pricing->has('temp_duty') ? ' input-danger' : '' }}">
      <label>Duty
        <div class="icon-input">
          <i class="pre-icon">%</i>
          <input name="temp_duty" class="js-landed-field js-duty" value="{{ old('temp_duty', $model->temp_duty) }}">
        </div>
      </label>
      @if ($errors->pricing->has('temp_duty'))
      <small class="info-danger">{{ $errors->pricing->first('temp_duty') }}</small>
      @endif
    </div>
  </div>
  @endif
  <div class="row">
    <div class="col-xl-6">
      <div class="product-price-breakdown">
        <p><strong>Exchange Rate:</strong> {{ $brand->currency->exchange_rate }} ({{ $brand->currency->name }})</p>
        <p><strong>Freight:</strong> {!! optional($brand->as400Freight)->freight . '%' !!}</p>
        @if (!$model->isNewSubmission)
        <p><strong>Duty:</strong> {{ optional($model->as400Pricing ?? $model->as400PricingClone)->duty ?? '0.00' }}%</p>
        <p><strong>EDLP:</strong> {{ optional($model->as400Pricing ?? $model->as400PricingClone)->edlp_discount ?? '0.00' }}%</p>
        <input type="hidden" class="js-duty" value="{{ optional($model->as400Pricing ?? $model->as400PricingClone)->duty ?? '0' }}">
        <input type="hidden" class="js-edlp" value="{{ optional($model->as400Pricing ?? $model->as400PricingClone)->edlp_discount ?? '0' }}">
        @endif
        <p><strong>Landed Cost:</strong> <span>$<span class="js-landed-cost-display">{{ $model->landed_cost ?? 0 }}</span></span></p>
        <p><strong>Suggested Wholesale Price ({{ round(optional($brand->as400Margin)->margin ?? 0, 2) }}%):</strong> <span>$<span class="js-wholesale-display">{{ $model->wholesale_price ?? 0 }}</span></span></p>
        <p><strong>Suggested SRP (40%):</strong> <span>$<span class="js-srp-display">{{ ($model->wholesale_price / $model->caseSize) * 1.4 }}</span></span></p>

        <input type="hidden" name="landed_cost" class="js-landed-cost" value="{{ $model->landed_cost }}">
        <input type="hidden" class="js-exchange-rate" value="{{ $brand->currency->exchange_rate }}">
        <input type="hidden" class="js-freight" value="{{ optional($brand->as400Freight)->freight }}">
        <input type="hidden" class="js-margin" value="{{ optional($brand->as400Margin)->margin ?? 0 }}">
      </div>
    </div>
  </div>
  <div class="row mt-3">
    <div class="input-wrap col-xl-4 {{ $errors->pricing->has('wholesale_price') ? ' input-danger' : '' }}">
      <label>Wholesale Price (<span class="js-wholesale-margin">{{ $model->wholesale_price > 0 ? round((1 - ($model->landed_cost / $model->wholesale_price)) * 100, 2) : '-' }}</span>%)
        <div class="icon-input">
          <i class="pre-icon">$</i>
          <input name="wholesale_price" class="js-wholesale-price" value="{{ old('wholesale_price', $model->wholesale_price) }}">
        </div>
      </label>
      @if ($errors->pricing->has('wholesale_price'))
      <small class="info-danger">{{ $errors->pricing->first('wholesale_price') }}</small>
      @endif
    </div>
    <div class="input-wrap col-xl-4 {{ $errors->pricing->has('suggested_retail_price') ? ' input-danger' : '' }}">
      <label>SRP (<span class="js-srp-margin">{{ $model->suggested_retail_price > 0 ? round((1 - (($model->wholesale_price / $model->minimumSellBy) / $model->suggested_retail_price)) * 100, 2) : '-' }}</span>%)
        <div class="icon-input">
          <i class="pre-icon">$</i>
          <input name="suggested_retail_price" class="js-srp" value="{{ old('suggested_retail_price', $model->suggested_retail_price) }}">
        </div>
      </label>
      @if ($errors->pricing->has('suggested_retail_price'))
      <small class="info-danger">{{ $errors->pricing->first('suggested_retail_price') }}</small>
      @endif
    </div>
  </div>
  @endif
</div>
