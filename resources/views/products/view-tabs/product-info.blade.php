<div id="product-information-view">
    <div class="row">
        <div class="col-xl-4">
            <div class="info-box">
                <p>Category</p>
                <h4>{{ optional($model->category)->name }}</h4>
            </div>
        </div>
        <div class="col-xl-4">
            <div class="info-box">
                <p>Sub Category</p>
                <h4>{{ optional($model->subCategory)->name }}</h4>
            </div>
        </div>
        <div class="col-xl-4">
            <div class="info-box">
                <p>Catalogue Category</p>
                <h4>{{ optional($model->catalogueCategory)->name }}</h4>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="info-box">
                <p>Key Product Features</p>
                <ul>
                    @foreach ($model->features as $feature)
                    @if ($feature || $loop->first)
                    <li>{{ $feature ?? '-' }}</li>
                    @endif
                    @endforeach
                </ul>
            </div>
        </div>
    </div>

    @if ($model->flags)
    <div class="row">
        <div class="col-12">
            <div class="info-box">
                <p>Flags</p>
                <h4>{{ implode(', ', $model->flags->pluck('name')->toArray()) }}</h4>
            </div>
        </div>
    </div>
    @endif

    @if ($model->hasRecommendedUse)
    <div class="row">
        <div class="col-12">
            <div class="info-box">
                <p>Recommended Use/Indications</p>
                <h4>{{ $model->recommended_use ?? '-' }}</h4>
            </div>
        </div>
    </div>
    @endif

    @if ($model->hasRecommendedDosage)
    <div class="row">
        <div class="col-12">
            <div class="info-box">
                <p>Recommended Dosage</p>
                <h4>{{ $model->recommended_dosage ?? '-' }}</h4>
            </div>
        </div>
    </div>
    @endif

    @if ($model->hasWarnings)
    <div class="row">
        <div class="col-12">
            <div class="info-box">
                <p>Cautions & Warnings</p>
                <h4>{{ $model->warnings ?? '-' }}</h4>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="info-box">
                <p>Contraindications</p>
                <h4>{{ $model->contraindications ?? '-' }}</h4>
            </div>
        </div>
    </div>
    @endif

    <div class="row">
        <div class="col-12">
            <div class="info-box">
                <p>Benefits</p>
                <h4>{{ $model->benefits ?? '-' }}</h4>
            </div>
        </div>
    </div>
</div>
