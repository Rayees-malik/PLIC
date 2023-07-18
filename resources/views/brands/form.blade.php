@if (request()->ajax())
@foreach ($model->steps as $key => $step)
@include("brands.steps.{$key}")
@endforeach
@else
<div class="col" id="brand-catalogue-display">
    @include('brands.catalogue')
</div>
<div class="form-stepper">
    @include('partials.stepper.stepper', ['saveRoute' => route('brands.save')])
    <div class="card">
        <div class="card-body">
            <div class="spinner-container ajax-loader">
                <div class="spinner-moon spinner-item"></div>
            </div>
            @if (!isset($signoffForm))
            <form method="POST" action="{{ route('brands.submit') }}">
                @csrf
                @endif

                @foreach ($model->steps as $key => $step)
                @include("brands.steps.{$key}")
                @endforeach

                @include('partials.stepper.buttons', ['routePrefix' => 'brands'])
                @if (!isset($signoffForm))
            </form>
            @endif
        </div>
    </div>
</div>
@endif

@push('scripts')
@if (!request()->ajax())
<script type="text/javascript" src="{{ mix('js/stepper.js') }}"></script>
<script type="text/javascript" src="{{ mix('js/modules/brands.js') }}"></script>
<script type="text/javascript" src="{{ mix('js/modules/contacts.js') }}"></script>
{!! BladeHelper::includeUploaders('thumbnail') !!}
{!! BladeHelper::initChosenSelect('searchable') !!}
@endif
@endpush
