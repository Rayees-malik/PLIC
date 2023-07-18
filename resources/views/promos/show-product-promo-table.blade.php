@forelse ($categories as $category => $products)
<div class="accordion-wrap accordion-small js-promo-category {{ $products->first()->getPromoLineItem($model->period_id)->id ? '' : 'accordion-closed' }}" data-category="{{ $category }}" data-has-content="{{ $products->first()->getPromoLineItem($model->period_id)->id ? 'true' : '' }}">
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
                            <th style="width: 60px;">MCB/OI Discount</th>
                            @if (!$promoConfig || !Arr::get($promoConfig, 'hidePricing'))
                            <th style="width: 60px;">Price</th>
                            @endif
                            @if (!$promoConfig || !Arr::get($promoConfig, 'hideBrandDiscount'))
                            <th style="width: 90px;">Brand Discount</th>
                            @endif
                            @if (!$promoConfig || !Arr::get($promoConfig, 'hidePLDiscount'))
                            <th style="width: 90px;">Add'l PL<br>Discount</th>
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
                        {!! BladeHelper::promoField($model, $product, $field, $fieldConfig, true) !!}
                        @endforeach
                        @endif
                        <td class="dt-body-right">
                            @if (!$basePeriodId || $product->getPromoLineItem($model->period_id)->brand_discount)
                            {{ $product->getPromoLineItem($model->period_id)->oi ? 'OI' : 'MCB' }}
                            @endif
                        </td>
                        @if (!$promoConfig || !Arr::get($promoConfig, 'hidePricing'))
                        <td class="dt-body-right">
                            <div class="pr-3">${{ number_format($product->getPrice(), 2) }}</div>
                        </td>
                        @endif
                        @if (!$promoConfig || !Arr::get($promoConfig, 'hideBrandDiscount'))
                        <td class="dt-body-right">
                            @if ($product->getPromoLineItem($model->period_id)->brand_discount)
                            @if ($model->dollar_discount)
                            <div class="pr-3">${{ number_format($product->getPromoLineItem($model->period_id)->brand_discount, 2) }} ({{ $product->calculatePromoDiscount($model->period_id, null, true) }}%)</div>
                            @else
                            <div class="pr-3">{{ round($product->getPromoLineItem($model->period_id)->brand_discount) }}%</div>
                            @endif
                            @endif
                        </td>
                        @endif
                        @if (!$promoConfig || !Arr::get($promoConfig, 'hidePLDiscount'))
                        <td class="dt-body-right">
                            @if ($product->getPromoLineItem($model->period_id)->pl_discount)
                            <div class="pr-3">{{ round($product->getPromoLineItem($model->period_id)->pl_discount) }}%</div>
                            @endif
                        </td>
                        @endif
                        @if ($basePeriodId)
                        <td class="dt-body-right">
                            <div class="pr-3">{{ BladeHelper::suffixIfValue(round($product->calculatePromoDiscount($basePeriodId)), '%') }}</div>
                        </td>
                        @endif
                        @if (!$promoConfig || !Arr::get($promoConfig, 'hidePricing'))
                        <td class="dt-body-right">
                            <div class="pr-3">
                                <strong>{{ BladeHelper::prefixIfValue(number_format($product->calculatePromoPrice($model->period_id), 2), '$') }}</strong>
                            </div>
                        </td>
                        <td class="dt-body-right">
                            <div class="pr-3">
                                <strong>{{ BladeHelper::suffixIfValue(round($product->calculateCombinedPromoDiscount($model->period_id, $basePeriodId)), '%') }}</strong>
                            </div>
                        </td>
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
