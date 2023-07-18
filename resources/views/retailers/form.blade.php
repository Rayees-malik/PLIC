@if (request()->ajax())
@foreach ($model->steps as $key => $step)
@include("retailers.steps.{$key}")
@endforeach
@else
<div class="form-stepper">
    @include('partials.stepper.stepper', ['saveRoute' => route('retailers.save')])
    <div class="card">
        <div class="card-body">
            <div class="spinner-container ajax-loader">
                <div class="spinner-moon spinner-item"></div>
            </div>
            @if (!isset($signoffForm))
            <form method="POST" action="{{ route('retailers.submit') }}">
                @csrf
                @endif

                @foreach ($model->steps as $key => $step)
                @include("retailers.steps.{$key}")
                @endforeach

                @include('partials.stepper.buttons', [ 'routePrefix' => 'retailers'])
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
<script type="text/javascript" src="{{ mix('js/modules/contacts.js') }}"></script>
{!! BladeHelper::initChosenSelect('searchable') !!}
@endif
@endpush
