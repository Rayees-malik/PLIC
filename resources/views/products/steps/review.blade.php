<div id="review" class="js-stepper-step stepper-step review-step">
    @if (isset($signoffForm) && optional($model->as400StockData ?? $model->as400StockDataClone)->status == 'D')
    <h3>Notice: This is a relist request.</h3>
    @endif
    @foreach ($model->steps as $key => $step)
    @if (!Arr::get($step, 'hidden', false))
    @include("products.steps-review.{$key}", ['categoryName' => $model->category->name ?? ''])
    @endif
    @endforeach

    <h4 class="form-section-title mt-3">Notes for Purity Life Team</h4>
    <div class="col input-wrap mt-3">
        <textarea name="submission_notes">{{ $model->submission_notes }}</textarea>
    </div>
</div>
