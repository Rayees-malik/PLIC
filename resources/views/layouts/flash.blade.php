@if (flash()->message)
<div class="container pb-2">
    <div class="row justify-content-center">
        <div class="col-8">
            <div class="alert-{{ flash()->class }}">
                @if (flash()->class == "success")
                <i class="material-icons">check</i>
                @else
                <i class="material-icons">error</i>
                @endif
                <p>{!! flash()->message !!}</p>
            </div>
        </div>
    </div>
</div>
@endif
@if (isset($errors) && ($errors->count() || (method_exists($errors, 'allBagsEmpty') && !$errors->allBagsEmpty())))
<div class="container pb-2">
    <div class="row justify-content-center">
        <div class="col-8">
            <div class="alert-danger">
                <i class="material-icons">error</i>
                <p>There are errors with your submission. Please correct any fields in red.</p>
            </div>
        </div>
    </div>
</div>
@endif
