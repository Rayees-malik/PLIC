<div id="media-view">
    <div class="row">
        <div class="col-xl-4 ">
            <div class="info-box">
                <p>Product Images</p>
                <form method="GET" action="{{ route('exports.brandimages', $model->id) }}">
                    @csrf
                    <button type="submit" class="primary-btn" title="Export">
                        <i class="material-icons">save_alt</i>
                        Download
                    </button>
                </form>
            </div>
        </div>
    </div>

    <h3>Primary Logo</h3>
    <div class="row">
        <div class="col-xl-4 ">
            <figure class="view-figure">
                <div class="d-flex align-items-center justify-content-center">
                    {!! BladeHelper::showFirstImage($model, 'logo', 'thumb') !!}
                </div>
            </figure>
        </div>
    </div>

    @if ($model->getMedia('alternative_logo')->count())
    <h3>Alternative Logos</h3>
    <div class="row">
        @foreach ($model->getMedia('alternative_logo') as $media)
        <div class="col-sm-6 col-xl-4">
            <figure class="view-figure">
                <div style="height: 256px;" class="d-flex align-items-center justify-content-center">
                    {!! BladeHelper::showMedia($media, 'thumb') !!}
                </div>
            </figure>
        </div>
        @endforeach
    </div>
    @endif

    <div class="row">
        @if ($model->getMedia('food_administration_license')->first())
        <div class="col-xl-4">
            <h3>Food Administration License</h3>
            <figure class="view-figure">
                <div style="height: 48px;" class="d-flex align-items-center justify-content-center">
                    {!! $model->getMedia('food_administration_license')->first()->getDownloadLink(null, 'h4'); !!}
                </div>
            </figure>
        </div>
        @endif
        @if ($model->getMedia('facility_certification')->first())
        <div class="col-xl-4">
            <h3>Third Party Facility Certification</h3>
            <figure class="view-figure">
                <div style="height: 48px;" class="d-flex align-items-center justify-content-center">
                    {!! $model->getMedia('facility_certification')->first()->getDownloadLink(null, 'h4'); !!}
                </div>
            </figure>
        </div>
        @endif
    </div>
</div>
