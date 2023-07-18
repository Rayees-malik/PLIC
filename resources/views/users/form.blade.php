<form method="POST" action="{{ $model->id ? route('users.update', $model) : route('users.store') }}">
    @csrf
    @method($model->id ? 'PATCH' : 'POST')

    <div class="formContainer">
        @include('users.form-controls')
    </div>

    <div class="bottom-controls">
        <a class="secondary-btn block-btn" href="{{ BladeHelper::backOr(route('users.index')) }}" title="Cancel">
            <i class="material-icons">cancel</i>
            Cancel
        </a>

        <button type="submit" class="primary-btn block-btn" title="{{ $model->id ? 'Save changes' : 'Add user' }}">
            <i class="material-icons">save</i>
            {{ $model->id ? 'Update' : 'Submit' }}
        </button>
    </div>
</form>
