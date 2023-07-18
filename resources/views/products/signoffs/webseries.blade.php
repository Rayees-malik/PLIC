<div class="view-page-body">
    <div class="view-page-header">
        <div>
            <h3 class="form-section-title">Brand</h3>
            <div class="row">
                <div class="col-xl-4">
                    <div class="info-box">
                        <p>Brand</p>
                        <h4>{{ $brand->name }}</h4>
                    </div>
                </div>
                <div class="col-xl-4">
                    <div class="info-box">
                        <p>Brand Number</p>
                        <h4>{{ $model->number }}</h4>
                    </div>
                </div>
                <div class="col-xl-4">
                    <div class="info-box">
                        <p>Brand Stock Id</p>
                        <h4>{{ $model->brand_stock_id }}</h4>
                    </div>
                </div>
            </div>

            <h3 class="form-section-title">Category</h3>
            <div class="row">
                <div class="col-xl-4">
                    <div class="info-box">
                        <p>Category</p>
                        <h4>{{ $model->category->name }}</h4>
                    </div>
                </div>
                <div class="col-xl-4">
                    <div class="info-box">
                        <p>Subline</p>
                        <h4>{{ $model->subcategory->code }} - {{ $model->subcategory->name }}</h4>
                    </div>
                </div>
                @if ($model->supersedes)
                <div class="col-xl-4">
                    <div class="info-box">
                        <p>Supersedes</p>
                        <h4 style="color: red;">{{ $model->supersedes->getName() }} (#{{ $model->supersedes->stock_id }})</h4>
                    </div>
                </div>
                @endif
            </div>

            <h3 class="form-section-title">Product</h3>
            <div class="row">
                <div class="col-xl-4">
                    <div class="info-box">
                        <p>Stock Id</p>
                        <h4>{{ $model->stock_id }}</h4>
                    </div>
                </div>
                <div class="col-xl-4">
                    <div class="info-box">
                        <p>UPC</p>
                        <h4>{{ $model->upc }}</h4>
                    </div>
                </div>
                <div class="col-xl-4">
                    <div class="info-box">
                        <p>Size</p>
                        <h4>{{ $model->getLongSize() }}</h4>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-xl-4">
                    <div class="info-box">
                        <p>Brand Stock Id</p>
                        <h4>{{ $model->brand_stock_id }}</h4>
                    </div>
                </div>
                <div class="col-xl-4">
                    <div class="info-box">
                        <p>Country of Origin</p>
                        <h4>{{ $model->countryOrigin->name }} [{{ $model->countryOrigin->alpha3 }}]</h4>
                    </div>
                </div>
                <div class="col-xl-4">
                    <div class="info-box">
                        <p>Tariff Code</p>
                        <h4>{{ $model->tariff_code }}</h4>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-xl-4">
                    <div class="info-box">
                        <p>Inner UPC</p>
                        <h4>{{ $model->inner_upc }}</h4>
                    </div>
                </div>
                <div class="col-xl-4">
                    <div class="info-box">
                        <p>Inner Units</p>
                        <h4>{{ $model->inner_units }}</h4>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-xl-4">
                    <div class="info-box">
                        <p>Master UPC</p>
                        <h4>{{ $model->master_upc }}</h4>
                    </div>
                </div>
                <div class="col-xl-4">
                    <div class="info-box">
                        <p>Master Units</p>
                        <h4>{{ $model->master_units }}</h4>
                    </div>
                </div>
            </div>

            <h3 class="form-section-title">Regulatory</h3>
            @include('products.view-tabs.regulatory')

            <h3 class="form-section-title">Pricing</h3>
            <div class="row">
                <div class="col-xl-4">
                    <div class="info-box">
                        <p>Minimum Order By QTY</p>
                        <h4>{{ $model->minimum_order_units }}</h4>
                    </div>
                </div>
                <div class="col-xl-4">
                    <div class="info-box">
                        <p>Not for Resale</p>
                        <h4>{{ $model->not_for_resale ? 'Yes' : 'No' }}</h4>
                    </div>
                </div>
            </div>

            @if ($model->isNewSubmission)
            <div class="row">
                <div class="col-xl-4">
                    <div class="info-box">
                        <p>PO Price to Purity</p>
                        <h4>{{ $model->unit_cost }}</h4>
                    </div>
                </div>
            </div>
            @else
            <div class="row">
                <div class="col-xl-4">
                    <div class="info-box">
                        <p>New PO Price to Purity</p>
                        <h4>{{ $model->unit_cost }}</h4>
                    </div>
                </div>
                <div class="col-xl-4">
                    <div class="info-box">
                        <p>Price Change Reason</p>
                        <h4>{{ $model->price_change_reason }}</h4>
                    </div>
                </div>
                <div class="col-xl-4">
                    <div class="info-box">
                        <p>Price Change Date</p>
                        <h4>{{ $model->price_change_date }}</h4>
                    </div>
                </div>
            </div>
            @endif

            <div class="row">
                <div class="col-xl-6">
                    <div class="product-price-breakdown ml-2 mb-4">
                        <h4>Sold By: <span>{{ \App\Helpers\BitArrayHelper::toString($model->purity_sell_by_unit, App\Models\Product::SELL_BY_UNITS) }}</span></h4>
                        <h4>Current Cost: <span>$<span>{{ $model->getPrice() }}</span></span></h4>
                        <p><strong>Exchange Rate:</strong> {{ $brand->currency->exchange_rate }} ({{ $brand->currency->name }})</p>
                        <p><strong>Extra Addon Code %:</strong> {{ $model->extra_addon_percent }}</p>
                        <p><strong>Freight:</strong> {!! optional($brand->as400Freight)->freight . '%' !!}</p>
                        @if ($model->isNewSubmission)
                        <p><strong>Duty:</strong> {{ $model->temp_duty ?? '0.00' }}%</p>
                        <p><strong>EDLP:</strong> {{ $model->temp_edlp ?? '0.00' }}%</p>
                        @else
                        <p><strong>Duty:</strong> {{ optional($model->as400Pricing ?? $model->as400PricingClone)->duty ?? '0.00' }}%</p>
                        <p><strong>EDLP:</strong> {{ optional($model->as400Pricing ?? $model->as400PricingClone)->edlp_discount ?? '0.00' }}%</p>
                        @endif
                        <hr>
                        <h4>Landed Cost: ${{ $model->landed_cost }}</h4>
                        <h4>Wholesale Price ({{ $model->wholesale_price > 0 ? round((1 - ($model->landed_cost / $model->wholesale_price)) * 100, 2) : '-' }}%): ${{ $model->wholesale_price }}</h4>
                        <h4>SRP ({{ $model->suggested_retail_price > 0 ? round((1 - (($model->wholesale_price / $model->minimumSellBy) / $model->suggested_retail_price)) * 100, 2) : '-' }}%): ${{ $model->suggested_retail_price }}</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
