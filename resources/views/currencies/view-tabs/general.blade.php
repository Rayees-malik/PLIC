<div class="container">
    <div id="general-view">
        <h3 class="form-section-title mb-4">Currency</h3>
        <div class="row">
            <div class="col-xl-4">
                <div class="card">
                    <div class="card-header">
                        <strong>Name</strong>
                    </div>
                    <div class="card-body">
                        <span>{{ $model->name }}</span>
                    </div>
                </div>
            </div>
            <div class="col-xl-4">
                <div class="card">
                    <div class="card-header">
                        <strong>Exchange Rate</strong>
                    </div>
                    <div class="card-body">
                        <span>{{ $model->exchange_rate }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
