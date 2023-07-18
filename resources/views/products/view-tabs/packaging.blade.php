<div id="packaging-view">
    <h3 class="form-section-title">Packaging</h3>
    <div class="row">
        <div class="col-xl-4">
            <div class="info-box">
                <p>Size</p>
                <h4>{{ $model->getLongSize() }}</h4>
            </div>
        </div>
        <div class="col-xl-4">
            <div class="info-box">
                <p>Packaging Language</p>
                <h4>{{ $model->packaging_language ? $model::PACKAGING_LANGUAGES[$model->packaging_language] : '-' }}</h4>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-4">
            <div class="info-box">
                <p>Unit Bought/Sold By Purity</p>
                <h4>{{ App\Helpers\BitArrayHelper::toString($model->purity_sell_by_unit, $model::SELL_BY_UNITS) }}</h4>
            </div>
        </div>
        <div class="col-xl-4">
            <div class="info-box">
                <p>Unit Sold By Retailer</p>
                <h4>{{ App\Helpers\BitArrayHelper::toString($model->retailer_sell_by_unit, $model::SELL_BY_UNITS) }}</h4>
            </div>
        </div>
    </div>

    @if ($model->packagingMaterials)
    <div class="row">
        <div class="col-12">
            <div class="info-box">
                <p>Product or Packaging Contains</p>
                <ul>
                    @foreach ($model->packagingMaterials as $material)
                    <li>{{ $material->name }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
    @endif

    <h3 class="form-section-title">Single Unit</h3>
    <div class="row">
        <div class="col-xl-4">
            <div class="info-box">
                <p>UPC/EAN Code</p>
                <h4>{{ $model->upc }}</h4>
            </div>
        </div>
        <div class="col-xl-4">
            <div class="info-box">
                <p>Dimensions</p>
                <ul>
                    <li>Width: <b>{{ optional($model->dimensions)->width }} cm</b></li>
                    <li>Depth: <b>{{ optional($model->dimensions)->depth }} cm</b></li>
                    <li>Height: <b>{{ optional($model->dimensions)->height }} cm</b></li>
                    <li>Gross Weight: <b>{{ optional($model->dimensions)->gross_weight }} kg</b></li>
                    @if (optional($model->dimensions)->net_weight)<li>Net Weight: <b>{{ optional($model->dimensions)->net_weight }} kg</b></li>@endif
                </ul>
            </div>
        </div>
    </div>

    <h3 class="form-section-title">Inner Case</h3>
    <div class="row">
        <div class="col-xl-4">
            <div class="info-box">
                <p>UPC/EAN Code</p>
                <h4>{{ $model->inner_upc }}</h4>
            </div>
        </div>
        <div class="col-xl-4">
            <div class="info-box">
                <p>Dimensions</p>
                <ul>
                    <li>Width: <b>{{ optional($model->innerDimensions)->width ?? '-' }} cm</b></li>
                    <li>Depth: <b>{{ optional($model->innerDimensions)->depth ?? '-' }} cm</b></li>
                    <li>Height: <b>{{ optional($model->innerDimensions)->height ?? '-' }} cm</b></li>
                    <li>Gross Weight: <b>{{ optional($model->innerDimensions)->gross_weight ?? '-' }} kg</b></li>
                </ul>
            </div>
        </div>
        <div class="col-xl-4">
            <div class="info-box">
                <p>Units Per Inner</p>
                <h4>{{ $model->inner_units ?? '-' }}</h4>
            </div>
        </div>
    </div>

    <h3 class="form-section-title">Master Case</h3>
    <div class="row">
        <div class="col-xl-4">
            <div class="info-box">
                <p>UPC/EAN Code</p>
                <h4>{{ $model->master_upc }}</h4>
            </div>
        </div>
        <div class="col-xl-4">
            <div class="info-box">
                <p>Dimensions</p>
                <ul>
                    <li>Width: <b>{{ optional($model->masterDimensions)->width ?? '-' }} cm</b></li>
                    <li>Depth: <b>{{ optional($model->masterDimensions)->depth ?? '-' }} cm</b></li>
                    <li>Height: <b>{{ optional($model->masterDimensions)->height ?? '-' }} cm</b></li>
                    <li>Gross Weight: <b>{{ optional($model->masterDimensions)->gross_weight ?? '-' }} kg</b></li>
                </ul>
            </div>
        </div>
        <div class="col-xl-4">
            <div class="info-box">
                <p>Units Per Case</p>
                <h4 class="mb-2">{{ $model->master_units ?? '-' }}</h4>
                <p>Cases Per Tie</p>
                <h4 class="mb-2">{{ $model->cases_per_tie ?? '-' }}</h4>
                <p>Layer Per Skid</p>
                <h4>{{ $model->layers_per_skid ?? '-' }}</h4>
            </div>
        </div>
    </div>
</div>
