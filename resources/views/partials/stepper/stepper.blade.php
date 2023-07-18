<div class="stepper-dot-wrap" data-route="{{ $saveRoute }}" data-class="{{ get_class($model) }}">
    @foreach ($model->steps as $step)
    <div class="js-stepper-dot stepper-dot" data-step="{{ $loop->iteration - 1 }}" {!! Arr::get($step, 'hidden' , false) ? 'style="display: none;"' : '' !!}>
        <h5 class="title">{{ $step['display'] }}</h5>
        <div class="checkpoint"></div>
    </div>
    @endforeach
</div>
