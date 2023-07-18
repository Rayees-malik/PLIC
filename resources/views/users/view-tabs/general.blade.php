<div class="container">
    <div id="general-view">
        <h3 class="form-section-title mb-4">General</h3>
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
                        <strong>Email</strong>
                    </div>
                    <div class="card-body">
                        <span>{{ $model->email }}</span>
                    </div>
                </div>
            </div>
            <div class="col-xl-4">
                <div class="card">
                    <div class="card-header">
                        <strong>Roles</strong>
                    </div>
                    <div class="card-body">
                        <ul>
                            @foreach ($model->roles as $role)
                                <li>{{ $role->name }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
