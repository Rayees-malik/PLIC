<form method="POST" action="/brokers/{{ $model->id }}">
    @csrf
    @method($model->id ? 'patch' : 'post')

    <div class="formContainer">
        @include('brokers.form-controls')
    </div>

    <div class="bottom-controls">
        <a class="secondary-btn block-btn" href="{{ BladeHelper::backOr(route('brokers.index')) }}" title="Return">
            <i class="material-icons">cancel</i>
            Cancel
        </a>

        <button type="submit" class="primary-btn block-btn" title="{{ $model->id ? 'Add new broker' : 'Save changes' }}">
            <i class="material-icons">save</i>
            {{ $model->id ? 'Update' : 'Submit' }}
        </button>
    </div>
</form>
