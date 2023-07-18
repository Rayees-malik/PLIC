<div id="general-view">
    @if ($model->address)
    <div class="row">
        <div class="col-12">
            {!! $model->address->longFormat() !!}
        </div>
    </div>
    @endif
    <div class="row">
        <div class="col-12">
            <div class="info-box">
                <p>Distribution Type</p>
                <h4>{{ $model->distribution_type ?? '-' }}</h4>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-4">
            <div class="info-box">
                <p>Costing Type</p>
                <h4>{{ $model->costing_type ? $model::COSTING_TYPES[$model->costing_type] : '-' }}</h4>
            </div>
        </div>
        <div class="col-4">
            <div class="info-box">
                <p>Warehouse Number</p>
                <h4>{{ $model->warehouse_number ?? '-' }}</h4>
            </div>
        </div>
        <div class="col-4">
            <div class="info-box">
                <p>Allow Promos</p>
                <h4>{{ $model->allow_promos ? 'Yes' : 'No' }}</h4>
            </div>
        </div>
    </div>
</div>
