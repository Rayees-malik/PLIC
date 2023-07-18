<div id="distribution-view">
    <div class="row">
        <div class="col-xl-4">
            <div class="info-box">
                <p>Contract Exclusive</p>
                <h4>{{ $model->contract_exclusive ? 'Yes' : 'No' }}</h4>
            </div>
        </div>
        <div class="col-xl-4">
            @if (!$model->contract_exclusive)
            <div class="info-box">
                <p>Also Distributed By</p>
                <h4>{{ $model->no_other_distributors ? 'No other distributors' : $model->also_distributed_by ?? '-' }}</h4>
            </div>
            @endif
        </div>
        <div class="col-xl-4">
            <div class="info-box">
                <p>In-House Brand</p>
                <h4>{{ $model->in_house_brand ? 'Yes' : 'No' }}</h4>
            </div>
        </div>
    </div>
    <div class="row mt-4">
        <div class="col-xl-4">
            <div class="info-box">
                <p>Allows Amazon Resale</p>
                <h4>{{ $model->allows_amazon_resale ? 'Yes' : 'No' }}</h4>
            </div>
        </div>
        <div class="col-xl-4">
            <div class="info-box">
                <p>MAP Pricing</p>
                <h4>{{ $model->map_pricing ? 'Yes' : 'No' }}</h4>
            </div>
        </div>
    </div>
</div>
