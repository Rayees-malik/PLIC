<form method="POST" action="{{ $model->id ? route('promos.periods.update', $model->id) : ($owner ? route("{$owner->routePrefix}.promos.periods.store", $owner->id) : route('promos.periods.store')) }}">
    @csrf
    @method($model->id ? 'patch' : 'post')

    <div class="formContainer">
        @include('promos.periods.form-controls')
    </div>

    <div class="bottom-controls">
        <a class="secondary-btn" href="{{ BladeHelper::backOr(route('promos.periods.index')) }}" title="Cancel">
            <i class="material-icons">cancel</i>
            Cancel
        </a>
        <button type="submit" class="accent-btn" title="Submit"><i class="material-icons">save</i>Submit</button>
    </div>
</form>

@push('scripts')
<script type="text/javascript" src="{{ mix('js/modules/promo-periods.js') }}"></script>
@endpush
