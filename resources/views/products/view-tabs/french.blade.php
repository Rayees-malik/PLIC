<div id="french-view">
    <div class="row">
        <div class="col-xl-4">
            <div class="info-box">
                <p>Name</p>
                <h4>{{ $model->name_fr ?? '-' }}</h4>
            </div>
        </div>
        <div class="col-xl-4">
            <div class="info-box">
                <p>Catalogue Category</p>
                <h4>{{ optional($model->catalogueCategory)->name_fr ?? '-' }}</h4>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="info-box">
                <p>Description</p>
                <h4>{{ $model->description_fr ?? '-' }}</h4>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-4">
            <div class="info-box">
                <p>Key Product Features</p>
                <ul>
                    @foreach ($model->featuresFR as $feature)
                    @if ($feature || $loop->first)
                    <li>{{ $feature ?? '-' }}</li>
                    @endif
                    @endforeach
                </ul>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="info-box">
                <p>Ingredients</p>
                <h4>{{ $model->ingredients_fr ?? '-' }}</h4>
            </div>
        </div>
    </div>

    @if ($model->hasRecommendedUse)
    <div class="row">
        <div class="col-12">
            <div class="info-box">
                <p>Recommended Use/Indications</p>
                <h4>{{ $model->recommended_use_fr ?? '-' }}</h4>
            </div>
        </div>
    </div>
    @endif

    @if ($model->hasRecommendedDosage)
    <div class="row">
        <div class="col-12">
            <div class="info-box">
                <p>Recommended Dosage</p>
                <h4>{{ $model->recommended_dosage_fr ?? '-' }}</h4>
            </div>
        </div>
    </div>
    @endif

    @if ($model->hasWarnings)
    <div class="row">
        <div class="col-12">
            <div class="info-box">
                <p>Cautions & Warnings</p>
                <h4>{{ $model->warnings_fr ?? '-' }}</h4>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="info-box">
                <p>Contraindications</p>
                <h4>{{ $model->contraindications_fr ?? '-' }}</h4>
            </div>
        </div>
    </div>
    @endif

    <div class="row">
        <div class="col-12">
            <div class="info-box">
                <p>Benefits</p>
                <h4>{{ $model->benefits_fr ?? '-' }}</h4>
            </div>
        </div>
    </div>
</div>
