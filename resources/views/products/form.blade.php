@if (request()->ajax())
@foreach ($model->steps as $key => $step)
@include("products.steps.{$key}")
@endforeach
@else
<div class="col" id="brand-catalogue-display">
    @include('products.catalogue')
</div>
<div class="form-stepper">
    @include('partials.stepper.stepper', ['saveRoute' => route('products.save') ])
    <div class="card">
        <div class="card-body">
            <div class="spinner-container ajax-loader">
                <div class="spinner-moon spinner-item"></div>
            </div>
            @if (!isset($signoffForm))
            <form method="POST" action="{{ route('products.submit') }}">
                @csrf
                @endif

                @foreach ($model->steps as $key => $step)
                @include("products.steps.{$key}")
                @endforeach

                @include('partials.stepper.buttons', ['routePrefix' => 'products'])
                @if (!isset($signoffForm))
            </form>
            @endif
        </div>
    </div>
</div>
@endif

@push('scripts')
@if (!request()->ajax())
{!! BladeHelper::includeUploaders(['thumbnail', 'label']) !!}
{!! BladeHelper::initChosenSelect(['searchable', 'ajax']) !!}
<script type="text/javascript" src="{{ mix('js/stepper.js') }}"></script>
<script type="text/javascript" src="{{ mix('js/modules/products.js') }}"></script>
@endif
@endpush
