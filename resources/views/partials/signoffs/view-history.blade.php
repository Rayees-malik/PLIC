@if (!auth()->user()->isVendor)
<div class="card">
@include('partials.signoffs.history', ['signoff' => $model->signoffs->count() ? $model->signoffs->first() : $model->signoff])
</div>
@endif