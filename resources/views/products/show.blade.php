@extends('layouts.app')

@section('page', 'View Product')

@section('content')
@include('partials.warning-notice')
<div class="container view-page-wrap">
    <div class="view-page-sticky">
        <div class="info-box">
            <p>Product</p>
            <h2>{{ $model->getName() }}</h2>
        </div>
        <div class="info-box">
            <p>Brand</p>
            <a href="{{ route('brands.show', optional($model->brand)->id) }}">
                <h3>{{ optional($model->brand)->name }}</h3>
            </a>
        </div>
        <div class="info-box">
            <p>Status</p>
            <h3>{{ optional($model->as400StockData)->status }}</h3>
        </div>
        <div class="info-box">
            <p>Size</p>
            <h3>{{ $model->getSizeWithUnits() }}</h3>
        </div>
        <div class="info-box">
            <p>UPC</p>
            <h3>{{ $model->sellByUPC }}</h3>
        </div>

        <div class="info-box info-box-pull-right">
            <p>Stock Id</p>
            <h3>{{ $model->stock_id }}</h3>
        </div>

        @if (Bouncer::can('edit', $model))
        <div class="info-box-actions info-box-pull-right">
            <div class="info-box-actions__btn">
                <span>EDIT</span>
                <span class="material-icons">expand_more</span>
            </div>

            <div class="info-box-actions__dropdown">
                @if ($model->canUpdate)
                <a class="primary-btn" href="{{ route('products.edit', $model->id) }}">
                    Edit
                </a>
                @endif
                <a class="primary-btn" href="{{ route('products.copy', $model->id) }}">
                    Copy
                </a>
                @if (optional($model->as400StockData)->status == 'A' || optional($model->as400StockData)->status == 'S')
                <a class="primary-btn" href="{{ route('productdelists.create', $model->id) }}">
                    Delist
                </a>
                @endif
            </div>
        </div>
        @endif
    </div>

    <div class="view-page-body">
        <div class="view-page-header">
            <div class="row">
                <div class="col-xl-4">
                    <figure class="view-figure">
                        <div style="height: 100%;" class="d-flex align-items-center justify-content-center">
                            {!! BladeHelper::showFirstImage($model, 'product', 'thumb') !!}
                        </div>
                    </figure>
                </div>
                <div class="col-xl-8">
                    <div class="row">
                        <div class="col-xl-4">
                            <div class="info-box">
                                <p>Wholesale</p>
                                <h4>${{ number_format($model->getPrice(), 2) }}</h4>
                            </div>
                        </div>
                        <div class="col-xl-4">
                            <div class="info-box">
                                <p>Catalogue Category</p>
                                <h4>{{ optional($model->catalogueCategory)->name }}</h4>
                            </div>
                        </div>
                        <div class="col-xl-4">
                            <div class="info-box">
                                <p>Sold By Case</p>
                                <h4>{{ $model->soldByCase ? "Yes" : "No" }}</h4>
                            </div>
                        </div>
                        <div class="col-xl-4">
                            <div class="info-box">
                                <p>Is Display</p>
                                <h4>{{ $model->is_display ? "Yes" : "No" }}</h4>
                            </div>
                        </div>
                        <div class="col-xl-4">
                          <div class="info-box">
                              <p>Packaging Language</p>
                              <h4>{{ $model->packaging_language ? $model::PACKAGING_LANGUAGES[$model->packaging_language] : '-' }}</h4>
                          </div>
                      </div>
                    </div>

                    <div class="row">
                        <div class="col-xl-12">
                            <div class="info-box">
                                <p>Description</p>
                                <h4>{{ $model->description }}</h4>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xl-4">
                            <div class="info-box">
                                <p>Single Unit Dimensions</p>
                                <ul>
                                    <li>Width: <b>{{ optional($model->dimensions)->width ?? '-' }} cm</b></li>
                                    <li>Depth: <b>{{ optional($model->dimensions)->depth ?? '-' }} cm</b></li>
                                    <li>Height: <b>{{ optional($model->dimensions)->height ?? '-' }} cm</b></li>
                                    <li>Gross Weight: <b>{{ optional($model->dimensions)->gross_weight ?? '-' }} kg</b></li>
                                    @if (optional($model->dimensions)->net_weight)<li>Net Weight: <b>{{ optional($model->dimensions)->net_weight }} kg</b></li>@endif
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        @if ($model->as400SupersededBy->count())
                        <div class="col-xl-6">
                            <div class="info-box">
                                <p>Superseded By</p>
                                @foreach ($model->as400SupersededBy as $supersededBy)
                                <a href="{{ route('products.show', $supersededBy->id) }}">
                                    <h4>{{ $supersededBy->stock_id }} - {{ $supersededBy->getName() }}</h4>
                                </a>
                                @endforeach
                            </div>
                        </div>
                        @endif
                        @if ($model->as400Supersedes->count())
                        <div class="col-xl-6">
                            <div class="info-box">
                                <p>Supersedes</p>
                                @foreach ($model->as400Supersedes as $supersedes)
                                <a href="{{ route('products.show', $supersedes->id) }}">
                                    <h4>{{ $supersedes->stock_id }} - {{ $supersedes->name }}</h4>
                                </a>
                                @endforeach
                            </div>
                        </div>
                        @endif
                        {{-- TODO: Link to Tester --}}
                        @if ($model->tester)
                        <div class="col-xl-6">
                            <div class="info-box">
                                <p>Tester Available</p>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="tabs-wrap mt-5">
            <div class="tabs-header">
                <div class="tab-btn tab-selected" name="product-info">Product Info</div>
                <div class="tab-btn" name="ingredients-allergens">Ingredients/Allergens</div>
                <div class="tab-btn" name="pricing">Pricing</div>
                <div class="tab-btn" name="packaging">Packaging</div>
                <div class="tab-btn" name="media">Media</div>
                <div class="tab-btn" name="french">French</div>
                <div class="tab-btn" name="administrative">Admin</div>
                @if ($model->{$model->stateField()} === \App\Helpers\SignoffStateHelper::INITIAL && $model->signoffs->count())
                <div class="tab-btn" name="history">History</div>
                @endif
            </div>
            <div class="tabs-body">
                <div class="product-info-tab">
                    @include("products.view-tabs.product-info")
                </div>
                <div class="ingredients-allergens-tab" style="display:none;">
                    @include("products.view-tabs.ingredients-allergens")
                </div>
                <div class="pricing-tab" style="display:none;">
                    @include("products.view-tabs.pricing")
                </div>
                <div class="packaging-tab" style="display:none;">
                    @include("products.view-tabs.packaging")
                </div>
                <div class="media-tab" style="display:none;">
                    @include("products.view-tabs.media")
                </div>
                <div class="french-tab" style="display:none;">
                    @include("products.view-tabs.french")
                </div>
                <div class="administrative-tab" style="display:none;">
                    @include("products.view-tabs.administrative")
                </div>
                @if ($model->{$model->stateField()} === \App\Helpers\SignoffStateHelper::INITIAL && $model->signoffs->count())
                <div class="history-tab" style="display:none;">
                    @include("products.view-tabs.history")
                </div>
                @endif
            </div>
        </div>
    </div>

    @includeWhen(!auth()->user()->isVendor && $model->{$model->stateField()} !== \App\Helpers\SignoffStateHelper::INITIAL, 'partials.signoffs.view-history')
</div>

@endsection

@push('scripts')
<script type="text/javascript" src="{{ mix('js/view-tabs.js') }}"></script>
@endpush
