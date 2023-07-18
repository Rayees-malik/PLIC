<div id="pricing-view">
  <h3 class="form-section-title">Pricing</h3>
  @if ($model->not_for_resale)
  <div class="row">
    <div class="col-xl-4">
      <strong>Not For Resale</strong>
    </div>
  </div>
  @endif
  <div class="row">
    <div class="col-xl-4">
      <div class="info-box">
        <p>Currency</p>
        <h4>{{ optional($model->brand->currency)->name }}</h4>
      </div>
    </div>
    <div class="col-xl-4">
      <div class="info-box">
        <p>Tax</p>
        <h4>{{ optional($model->as400Pricing)->taxable ? 'Taxable' : 'Non-taxable' }}</h4>
      </div>
    </div>
    @if ($model->available_ship_date)
    <div class="col-xl-4">
      <div class="info-box">
        <p>Product First Available</p>
        <h4>{{ $model->available_ship_date }}</h4>
      </div>
    </div>
    @endif
  </div>

  <div class="row">
    <div class="col-xl-4">
      <div class="info-box">
        <p>PO Price</p>
        <h4>${{ number_format(optional($model->as400Pricing)->po_price, 2) }}</h4>
      </div>
    </div>
    <div class="col-xl-4">
      <div class="info-box">
        <p>Wholesale Price</p>
        <h4>${{ number_format($model->getPrice(), 2) }} CAD</h4>
      </div>
    </div>
  </div>

  @if (!auth()->user()->isVendor)
  <div class="row">
    <div class="col-xl-4">
      <div class="info-box">
        <p>Fixed Landed Cost</p>
        <h4>${{ number_format($model->landed_cost, 2) }} CAD</h4>
      </div>
    </div>
    <div class="col-xl-4">
      <div class="info-box">
        <p>Average Landed Cost</p>
        <h4>${{ number_format(optional($model->as400Pricing)->average_landed_cost, 2) }} CAD</h4>
      </div>
    </div>
  </div>
  @endif

  @if (optional($model->as400Pricing)->next_po_price && optional($model->as400Pricing)->po_price_expiry)
  <h3 class="form-section-title">Upcoming PO Price</h3>
  <div class="row">
    <div class="col-4 info-box">
      <p>{{ $model->as400Pricing->po_price_expiry->toFormattedDateString() }}</p>
      <h4>${{ number_format($model->as400Pricing->next_po_price, 2) }}</h4>
    </div>
  </div>
  @endif

  @if ($model->futureLandedCosts->count() && !auth()->user()->isVendor)
  <h3 class="form-section-title">Upcoming Landed Cost</h3>
  <div class="row">
    @foreach ($model->futureLandedCosts as $futureLandedCost)
    <div class="col-4 info-box">
      <p>{{ $futureLandedCost->change_date->toFormattedDateString() }}</p>
      <h4>${{ number_format($futureLandedCost->landed_cost, 2) }} CAD</h4>
    </div>
    @endforeach
  </div>
  @endif

  @if ($model->as400UpcomingPriceChanges->count())
  <h3 class="form-section-title">Upcoming Wholesale Price</h3>
  <div class="row">
    @foreach ($model->as400UpcomingPriceChanges as $priceChange)
    <div class="col-4 info-box">
      <p>{{ $priceChange->change_date->toFormattedDateString() }}</p>
      <h4>${{ number_format($priceChange->wholesale_price, 2) }}</h4>
    </div>
    @endforeach
  </div>
  @endif

  <h3 class="form-section-title">Purchasing</h3>
  <div class="row">
    <div class="col-xl-4">
      <div class="info-box">
        <p>Brand Stock #</p>
        <h4>{{ $model->brand_stock_id }}</h4>
      </div>
    </div>
    <div class="col-xl-4">
      <div class="info-box">
        <p>Minimum Order</p>
        <h4>{{ $model->minimum_order_type == '$' ? "\${$model->minimum_order_units}" : "{$model->minimum_order_units} " . Str::plural('Unit', $model->minimum_order_units) }}</h4>
      </div>
    </div>
    <div class="col-xl-4">
      <div class="info-box">
        <p>Shelf Life</p>
        <h4>{{ $model->getShelfLife() }}</h4>
      </div>
    </div>
  </div>

  <div class="row">
    <div class="col-xl-4">
      <div class="info-box">
        <p>Country of Origin</p>
        <h4>{{ $model->countryOrigin->name }}</h4>
      </div>
    </div>
    <div class="col-xl-4">
      <div class="info-box">
        <p>Country Shipped From</p>
        <h4>{{ $model->countryShipped->name }}</h4>
      </div>
    </div>
    <div class="col-xl-4">
      <div class="info-box">
        <p>Tariff Code</p>
        <h4>{{ $model->tariff_code }}</h4>
      </div>
    </div>
  </div>

  @if ($model->as400WarehouseStock->count())
  <h3 class="form-section-title">
    Warehouse Stock
    <small class="subnote d-inline">** Inventory is refreshed daily and is not live **</small>
  </h3>
  <div class="row">
    @foreach (App\Models\Warehouse::ordered()->get() as $warehouse)
    <div class="col-4 info-box">
      <ul>
        <li><b>{{ strtoupper($warehouse->name) }} (WHSE {{ $warehouse->number }})</b></li>
        @if (!auth()->user()->isVendor)
        <li>Price: <b>${{ optional($model->as400WarehouseStock->where('warehouse', $warehouse->number)->first())->unit_cost ?? '-' }}</b></li>
        @endif
        @forelse ($model->as400WarehouseStock->where('warehouse', $warehouse->number)->all() as $stock)
        <li>Stock: <b>{{ $stock->quantity }}</b> (Expiry: <b>{{ $stock->expiry }})</b></li>
        @empty
        <li>Stock: <b>0</b></li>
        @endforelse
      </ul>
    </div>
    @endforeach
  </div>
  @endif

  {{-- @if ($model->certifications->count())
  <h3 class="form-section-title">Certifications</h3>
  <div class="row">
    @foreach ($model->certifications as $certification)
    <div class="col-4">
      @if ($model->getMedia("certifications_{$certification->id}")->first())
      {!! $model->getMedia("certifications_{$certification->id}")->first()->getDownloadLink($certification->name, 'h4'); !!}
      @else
      <h4>{{ $certification->name }}</h4>
      @endif
    </div>
    @endforeach
  </div>
  @endif --}}

  @if ($model->hasRegulations)
  <h3 class="form-section-title">Regulatory Information</h3>
  @include('products.view-tabs.regulatory')
  @endif
</div>
