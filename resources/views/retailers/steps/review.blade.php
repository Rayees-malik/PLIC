<div id="review" class="js-stepper-step stepper-step review-step">
    @foreach ($model->steps as $key => $step)
        @include("retailers.steps-review.{$key}")
    @endforeach
</div>
