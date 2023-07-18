@can('promo.update.discos')
@foreach ($brands as $brand => $products)
<div class="accordion-wrap accordion-small js-promo-brand {{ $products->first()->discoPromo ? '' : 'accordion-closed' }}" data-brand="{{ $brand }}">
    <div class="accordion-header">
        <h3>{{ $brand }}</h3>
    </div>
    <div class="accordion-body">
        <div class="accordion-body-content">
            <div class="dataTables_wrapper">
                <table class="table datatable">
                    <thead class="promo-table-header">
                        <tr>
                            <th style="width: 60px;">Stock #</th>
                            <th style="width: 300px;">Product</th>
                            <th style="width: 60px;">Price</th>
                            <th style="width: 120px;">Brand Discount</th>
                            <th style="width: 120px;">Add'l PL<br>Discount</th>
                            <th style="width: 60px;">Final Price</th>
                            <th style="width: 60px; text-align: right;">Total Discount</th>
                        </tr>
                    </thead>
                    @foreach ($products as $product)
                    <tr class="js-promo-row {{ $loop->iteration % 2 ? 'odd' : 'even' }}">
                        <td class="js-search-field">{{ $product->stock_id }}</td>
                        <td>
                            <strong class="js-search-field">{{ $product->name }}</strong><br>
                            <small class="js-search-field">{{ implode(' | ', array_filter([$product->upc, $product->getSize()])) }}</small>
                            <span class="display-none js-search-field">{{ $brand }}</span>
                        </td>
                        <td class="dt-body-right">
                            ${{ $product->getPrice() }}
                        </td>
                        <td>
                            <div class="input-wrap {{ $errors->has("brand_discount.{$product->id}") ? ' input-danger' : '' }}" style="max-width: 120px">
                                <div class="icon-input">
                                    <i class="pre-icon">%</i>
                                    <input type="text" class="js-anchor-append js-brand-discount js-discount-field" name="brand_discount[{{ $product->id }}]" value="{{ old("brand_discount.{$product->id}", optional($product->discoPromo)->brand_discount) }}">
                                </div>
                                @if ($errors->has("brand_discount.{$product->id}"))
                                <small class="info-danger">{{ $errors->first("brand_discount.{$product->id}") }}</small>
                                @endif
                            </div>
                        </td>
                        <td>
                            <div class="input-wrap {{ $errors->has("pl_discount.{$product->id}") ? ' input-danger' : '' }}" style="max-width: 120px">
                                <div class="icon-input">
                                    <i class="pre-icon">%</i>
                                    <input type="text" class="js-anchor-append js-pl-discount js-discount-field js-discount-percent" name="pl_discount[{{ $product->id }}]" value="{{ old("pl_discount.{$product->id}", optional($product->discoPromo)->pl_discount) }}">
                                </div>
                                @if ($errors->has("pl_discount.{$product->id}"))
                                <small class="info-danger">{{ $errors->first("pl_discount.{$product->id}") }}</small>
                                @endif
                            </div>
                        </td>
                        <td class="dt-body-right">
                            <div class="pr-3 js-final-price">-</div>
                        </td>
                        <td class="dt-body-right">
                            <div class="pr-3 js-final-discount">-</div>
                            <input type="hidden" name="products[]" value="{{ $product->id }}">
                            <input type="hidden" class="js-product-price" value="{{ $product->getPrice() }}">
                        </td>
                    </tr>
                    @endforeach
                </table>
            </div>
        </div>
    </div>
</div>
@endforeach
@elsecan('promo.view.discos')
@foreach ($brands as $brand => $products)
<div class="accordion-wrap accordion-small js-promo-brand {{ $products->first()->discoPromo ? '' : 'accordion-closed' }}" data-brand="{{ $brand }}">
    <div class="accordion-header">
        <h3>{{ $brand }}</h3>
    </div>
    <div class="accordion-body">
        <div class="accordion-body-content">
            <div class="dataTables_wrapper">
                <table class="table datatable">
                    <thead class="promo-table-header">
                        <tr>
                            <th style="width: 60px;">Stock #</th>
                            <th style="width: 300px;">Product</th>
                            <th style="width: 60px;">Price</th>
                            <th style="width: 120px;">Brand Discount</th>
                            <th style="width: 120px;">Add'l PL<br>Discount</th>
                            <th style="width: 60px;">Final Price</th>
                            <th style="width: 60px; text-align: right;">Total Discount</th>
                        </tr>
                    </thead>
                    @foreach ($products as $product)
                    <tr class="js-promo-row {{ $loop->iteration % 2 ? 'odd' : 'even' }}">
                        <td class="js-search-field">{{ $product->stock_id }}</td>
                        <td>
                            <strong class="js-search-field">{{ $product->name }}</strong><br>
                            <small class="js-search-field">{{ implode(' | ', array_filter([$product->upc, $product->getSize()])) }}</small>
                            <span class="display-none js-search-field">{{ $brand }}</span>
                        </td>
                        <td class="dt-body-right">
                            ${{ $product->getPrice() }}
                        </td>
                        <td>
                            <div style="max-width: 120px">{{ optional($product->discoPromo)->brand_discount }}&nbsp;%</div>
                            <input type="hidden" class="js-brand-discount js-discount-field" value="{{ optional($product->discoPromo)->brand_discount }}">
                        </td>
                        <td>
                            <div style="max-width: 120px">{{ optional($product->discoPromo)->pl_discount }}&nbsp;%</div>
                            <input type="hidden" class="js-brand-discount js-discount-field js-discount-percent" value="{{ optional($product->discoPromo)->pl_discount }}">
                        </td>
                        <td class="dt-body-right">
                            <div class="pr-3 js-final-price">-</div>
                        </td>
                        <td class="dt-body-right">
                            <div class="pr-3 js-final-discount">-</div>
                            <input type="hidden" name="products[]" value="{{ $product->id }}">
                            <input type="hidden" class="js-product-price" value="{{ $product->getPrice() }}">
                        </td>
                    </tr>
                    @endforeach
                </table>
            </div>
        </div>
    </div>
</div>
@endforeach
@endcan
