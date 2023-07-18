<div id="review" class="js-stepper-step stepper-step review-step">
    @foreach ($model->steps as $key => $step)
    @if (!Arr::get($step, 'hidden', false))
    @include("brands.steps-review.{$key}")
    @endif
    @endforeach
</div>
