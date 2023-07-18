<div class="container">
    <div id="general-view">
        <h3 class="form-section-title mb-4">General</h3>
        <div class="row">
            <div class="col-xl-4">
                <div class="card">
                    <div class="card-header">
                        <strong>Period Name</strong>
                    </div>
                    <div class="card-body">
                        <span>{{ $model->name }}</span>
                    </div>
                </div>
            </div>
            <div class="col-xl-4">
                <div class="card">
                    <div class="card-header">
                        <strong>Start Date</strong>
                    </div>
                    <div class="card-body">
                        <span>{{ $model->start_date }}</span>
                    </div>
                </div>
            </div>
            <div class="col-xl-4">
                <div class="card">
                    <div class="card-header">
                        <strong>End Date</strong>
                    </div>
                    <div class="card-body">
                        <span>{{ $model->end_date }}</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mt-4">
            <div class="col-xl-4">
                <div class="card">
                    <div class="card-header">
                        <strong>Flash Deal (ie. VTS)</strong>
                    </div>
                    <div class="card-body">
                        <span>{{ $model->supersedes->name }}</span>
                    </div>
                </div>
            </div>
            <div class="col-xl-4">
                <div class="card">
                    <div class="card-header">
                        <strong>Country of Origin</strong>
                    </div>
                    <div class="card-body">
                        <span>{{ $model->flash_deal ? 'Yes' : 'No' }}</span>
                    </div>
                </div>
            </div>
            <div class="col-xl-4">
                <div class="card">
                    <div class="card-header">
                        <strong>Base Period</strong>
                    </div>
                    <div class="card-body">
                        <span>{{ optional($model->basePeriod)->name }}</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mt-4">
            <div class="col-xl-4">
                <div class="card">
                    <div class="card-header">
                        <strong>Order Form Header</strong>
                    </div>
                    <div class="card-body">
                        <span>{{ $model->order_form_header }}</span>
                    </div>
                </div>
            </div>
            <div class="col-xl-4">
                <div class="card">
                    <div class="card-header">
                        <strong>Monthly Period</strong>
                    </div>
                    <div class="card-body">
                        <span>{{ $model->monthly_period ? 'Yes' : 'No' }}</span>
                    </div>
                </div>
            </div>
            <div class="col-xl-4">
                <div class="card">
                    <div class="card-header">
                        <strong>Active</strong>
                    </div>
                    <div class="card-body">
                        <span>{{ $model->active ? 'Yes' : 'No' }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
