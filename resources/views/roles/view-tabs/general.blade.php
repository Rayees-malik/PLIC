<div class="container">
    <div id="general-view">
        <h3 class="form-section-title mb-4">General</h3>
        <div class="row">
            <div class="col-xl-6">
                <div class="card">
                    <div class="card-header">
                        <strong>Role Name</strong>
                    </div>
                    <div class="card-body">
                        <span>{{ $model->title }}</span>
                    </div>
                </div>
            </div>
            <div class="col-xl-6">
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
        <div class="row mt-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <strong>Abilities</strong>
                    </div>
                    <div class="card-body">
                        <ul>
                            @foreach ($abilityCategories as $category => $abilities)
                            <h4>{{ empty($category) ? 'Model Abilities' : $category }}</h4>
                            @foreach ($abilities as $ability)
                            @if ($ability->entity_type) {{ $ability->title }}@else<a href="{{ route('abilities.show', $ability->name) }}">{{ $ability->title }}</a>@endif{{ $loop->last ? '' : ', ' }}
                            @endforeach
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
