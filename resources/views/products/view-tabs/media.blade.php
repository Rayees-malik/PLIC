<div id="media-view">
    @if ($model->stock_id && $model->getMedia('product')->count())
    <div class="row">
        <div class="col">
            <div class="info-box">
                <p>Public Primary Image URL</p>
                <h4><a href="{{ route('products.image', $model->stock_id) }}" alt="Public Image Link">{{ route('products.image', $model->stock_id) }}</a></h4>
            </div>
        </div>
    </div>
    @endif
    <div class="row">
        @if ($model->getMedia('label_flat')->count())
        <div class="col-xl-4">
            <h3>Label Flat</h3>
            <figure class="view-figure">
                <div style="height: 48px;" class="d-flex align-items-center justify-content-center">
                    {!! $model->getMedia('label_flat')->first()->getDownloadLink(null, 'h4'); !!}
                </div>
            </figure>
        </div>
        @endif
        @if ($model->getMedia('nutritional_facts')->count())
        <div class="col-xl-4">
            <h3>Nutritional Facts</h3>
            <figure class="view-figure">
                <div style="height: 48px;" class="d-flex align-items-center justify-content-center">
                    {!! $model->getMedia('nutritional_facts')->first()->getDownloadLink(null, 'h4'); !!}
                </div>
            </figure>
        </div>
        @endif
        @if ($model->getMedia('ingredient_panel')->count())
        <div class="col-xl-4">
            <h3>Ingredient Panel</h3>
            <figure class="view-figure">
                <div style="height: 48px;" class="d-flex align-items-center justify-content-center">
                    {!! $model->getMedia('ingredient_panel')->first()->getDownloadLink(null, 'h4'); !!}
                </div>
            </figure>
        </div>
        @endif
    </div>

    @if ($model->getMedia('additional')->count())
    <div class="row">
        <div class="col-12">
            <div class="info-box">
                <h3>Additional Images</h3>
                <div class="row">
                    @foreach ($model->getMedia('additional') as $media)
                    <div class="col-sm-6 col-xl-3">
                        <figure class="view-figure">
                            <div style="height: 256px;" class="d-flex align-items-center justify-content-center">
                                {!! BladeHelper::showMedia($media, 'thumb') !!}
                            </div>
                            <figcaption>{{ $media->getCustomProperty('label') }}</figcaption>
                        </figure>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
