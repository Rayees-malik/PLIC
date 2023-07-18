<div class="container">
    <div id="general-view">
        <h3 class="form-section-title mb-4">Country</h3>
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
                        <strong>Alpha-2 Code</strong>
                    </div>
                    <div class="card-body">
                        <span>{{ $model->alpha2 }}</span>
                    </div>
                </div>
            </div>
            <div class="col-xl-4">
                <div class="card">
                    <div class="card-header">
                        <strong>Alpha-3 Code</strong>
                    </div>
                    <div class="card-body">
                        <span>{{ $model->alpha3 }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
