<div class="container">
    <div id="general-view">
        <h3 class="form-section-title mb-4">Ability</h3>
        <div class="row">
            <div class="col-xl-4">
                <div class="card">
                    <div class="card-header">
                        <strong>Name</strong>
                    </div>
                    <div class="card-body">
                        <span>{{ $model->title }}</span>
                    </div>
                </div>
            </div>
            <div class="col-xl-4">
                <div class="card">
                    <div class="card-header">
                        <strong>Category</strong>
                    </div>
                    <div class="card-body">
                        <span>{{ $model->category }}</span>
                    </div>
                </div>
            </div>
            <div class="col-xl-4">
                <div class="card">
                    <div class="card-header">
                        <strong>Description</strong>
                    </div>
                    <div class="card-body">
                        <span>{{ $model->description }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
