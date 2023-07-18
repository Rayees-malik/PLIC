<form method="POST" action="/currencies/{{ $model->id }}">
    @csrf
    @method($model->id ? 'PATCH' : 'POST')

    <div class="formContainer">
        @include('currencies.form-controls')
    </div>

    <div class="bottom-controls">
        <a class="secondary-btn block-btn" href="{{ BladeHelper::backOr(route('currencies.index')) }}" title="Cancel">
            <i class="material-icons">cancel</i>
            Cancel
        </a>
        <button type="submit" class="primary-btn block-btn" title="{{ $model->id ? 'Save changes' : 'Add new currency' }}">
            <i class="material-icons">save</i>
            {{ $model->id ? 'Update' : 'Submit' }}
        </button>
    </div>
</form>
