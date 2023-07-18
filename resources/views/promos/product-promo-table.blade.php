<input type="hidden" class="js-exchange-rate" value="{{ $brand->currency->exchange_rate }}">
<input type="hidden" class="js-exchange-currency" value="{{ $brand->currency->name }}">
@forelse ($categories as $category => $products)
<div class="accordion-wrap accordion-small js-promo-category {{ !$model->id || $products->first()->getPromoLineItem($model->period_id)->id ? '' : 'accordion-closed' }}" data-category="{{ $category }}">
    <div class="accordion-header">
        <h3>{{ $category }}</h3>
    </div>
    <div class="accordion-body">
        <div class="accordion-body-content">
            <div class="dataTables_wrapper">
                <table class="table datatable">
                    <thead class="promo-table-header">
                        <tr>
                            <th style="width: 60px;">Stock #</th>
                            <th style="width: 300px;">Product</th>
                            @if ($promoConfig)
                            @foreach (Arr::get($promoConfig, 'lineItemFields', []) as $field)
                            <th {{ Arr::get($field, 'width') ? 'width=' . Arr::get($field, 'width') : '' }}>{{ $field['display'] }}</th>
                            @endforeach
                            @endif
                            <th class="js-oi-column" style="width: 60px; {{ old('oi', $model->oi) ? '' : 'display:none;' }}">OI Discount</th>
                            @if (!$promoConfig || !Arr::get($promoConfig, 'hidePricing'))
                            <th style="width: 60px;">Price</th>
                            @endif
                            @if (!$promoConfig || !Arr::get($promoConfig, 'hideBrandDiscount'))
                            <th style="width: 120px;">Brand Discount</th>
                            @endif
                            @if ((!$promoConfig || !Arr::get($promoConfig, 'hidePLDiscount')) && (auth()->user()->can('promo.monthly.edit') || auth()->user()->can('signoff.retailer.promo')))
                            <th style="width: 120px;">Add'l PL<br>Discount</th>
                            @endif
                            @if ($basePeriodId)
                            <th style="width: 60px;">Base Discount</th>
                            @endif
                            @if (!$promoConfig || !Arr::get($promoConfig, 'hidePricing'))
                            <th style="width: 60px;">Final Price</th>
                            <th style="width: 60px; text-align: right;">Total Discount</th>
                            @endif
                        </tr>
                    </thead>
                    @foreach ($products as $product)
                    <tr class="js-promo-row {{ $loop->iteration % 2 ? 'odd' : 'even' }}">
                        <td class="js-search-field">{{ $product->stock_id }}</td>
                        <td>
                            <strong class="js-search-field">{{ $product->name }}</strong><br>
                            <small class="js-search-field">{{ implode(' | ', array_filter([$product->upc, $product->getSize()])) }}</small>
                            <span class="display-none js-search-field">{{ $category }}</span>
                        </td>
                        @if ($promoConfig)
                        @foreach (Arr::get($promoConfig, 'lineItemFields', []) as $field => $fieldConfig)
                        {!! BladeHelper::promoField($model, $product, $field, $fieldConfig) !!}
                        @endforeach
                        @endif
                        <td class="js-oi-column" style="{{ old('oi', $model->oi) ? '' : 'display:none;' }}">
                            <div class="input-wrap">
                                <div class="checkbox-wrap">
                                    <label class="checkbox">
                                        <input type="hidden" name="lineitem_oi[{{ $product->id }}]" value="0">
                                        <input type="checkbox" name="lineitem_oi[{{ $product->id }}]" value="1" class="js-oi-field" {{ old("lineitem_oi.{$product->id}", $model->id ? $product->getPromoLineItem($model->period_id)->oi : '') ? 'checked' : '' }}>
                                        <span class="checkbox-checkmark"></span>
                                        <span class="checkbox-label">OI</span>
                                    </label>
                                </div>
                            </div>
                        </td>
                        @if (!$promoConfig || !Arr::get($promoConfig, 'hidePricing'))
                        <td class="dt-body-right">
                            @if ($brand->currency->exchange_rate == 1)
                            ${{ number_format($product->getPrice(), 2) }}
                            @else
                            ${{ number_format($product->getPrice(), 2) }} CAD<br />
                            ~${{ number_format($product->getPrice() / $brand->currency->exchange_rate, 2) }} {{ $brand->currency->name }}
                            @endif
                        </td>
                        @endif
                        @if (!$promoConfig || !Arr::get($promoConfig, 'hideBrandDiscount'))
                        <td>
                            <div class="input-wrap {{ $errors->products->has("brand_discount.{$product->id}") ? ' input-danger' : '' }}" style="max-width: 120px">
                                <div class="icon-input">
                                    <i class="pre-icon js-discount-icon">{{ old('dollar_discount', $model->dollar_discount) ? '$' : '%' }}</i>
                                    <input type="text" class="js-anchor-append js-brand-discount js-discount-field js-discount-dynamic {{ old('dollar_discount', $model->dollar_discount) ? 'js-discount-dollar' : 'js-discount-percent' }}" name="brand_discount[{{ $product->id }}]" value="{{ old("brand_discount.{$product->id}", $model->id ? $product->getPromoLineItem($model->period_id)->brand_discount : '') }}">
                                </div>
                                @if ($errors->products->has("brand_discount.{$product->id}"))
                                <small class="info-danger">{{ $errors->products->first("brand_discount.{$product->id}") }}</small>
                                @endif
                            </div>
                        </td>
                        @endif
                        @if ((!$promoConfig || !Arr::get($promoConfig, 'hidePLDiscount')) && (auth()->user()->can('promo.monthly.edit') || auth()->user()->can('signoff.retailer.promo')))
                        <td>
                            <div class="input-wrap {{ $errors->products->has("pl_discount.{$product->id}") ? ' input-danger' : '' }}" style="max-width: 120px">
                                <div class="icon-input">
                                    <i class="pre-icon">%</i>
                                    <input type="text" class="js-anchor-append js-pl-discount js-discount-field js-ignore-oi js-discount-percent" name="pl_discount[{{ $product->id }}]" value="{{ old("pl_discount.{$product->id}", $model->id ? $product->getPromoLineItem($model->period_id)->pl_discount : '') }}">
                                </div>
                                @if ($errors->products->has("pl_discount.{$product->id}"))
                                <small class="info-danger">{{ $errors->products->first("pl_discount.{$product->id}") }}</small>
                                @endif
                            </div>
                        </td>
                        @endif
                        @if (!$promoConfig || !Arr::get($promoConfig, 'hidePricing'))
                        @if ($basePeriodId)
                        <td class="dt-body-right">
                            <div class="pr-3"><span class="js-product-base-discount">{{ BladeHelper::suffixIfValue($product->calculatePromoDiscount($basePeriodId), '%') }}</span></div>
                        </td>
                        @endif
                        <td class="dt-body-right">
                            @if ($brand->currency->exchange_rate == 1 && $product->getPrice() > 0)
                            <div class="pr-3 js-final-price">-</div>
                            @else
                            <div class="pr-3"><span class="js-final-price">-</span> CAD</div>
                            <div class="pr-3"><span class="js-final-price-exchanged">-</span> {{ $brand->currency->name }}</div>
                            @endif
                        </td>
                        <td class="dt-body-right">
                            <div class="pr-3 js-final-discount">-</div>
                            <input type="hidden" name="products[]" value="{{ $product->id }}">
                            <input type="hidden" class="js-product-price" value="{{ $product->getPrice() }}">
                            <input type="hidden" class="js-product-po-price" value="{{ optional($product->as400Pricing)->po_price }}">
                        </td>
                        @else
                        <input type="hidden" name="products[]" value="{{ $product->id }}">
                        <input type="hidden" class="js-product-price" value="{{ $product->getPrice() }}">
                        <input type="hidden" class="js-product-po-price" value="{{ optional($product->as400Pricing)->po_price }}">
                        @endif
                    </tr>
                    @endforeach
                </table>
            </div>
        </div>
    </div>
</div>
@empty
<div class="container text-center mt-3 js-no-products">
    <h3>Selected brand has no active products available for promotions.</h3>
</div>
@endforelse
