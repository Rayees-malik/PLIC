<form method="POST" action="/abilities/{{ $model->name }}">
    @csrf
    @method($model->id ? 'PATCH' : 'POST')

    <div class="formContainer">
        @include('abilities.form-controls')
    </div>

    <div class="bottom-controls">
        <a class="secondary-btn block-btn" href="{{ BladeHelper::backOr(route('abilities.index')) }}" title="Cancel">
            <i class="material-icons">cancel</i>
            Cancel
        </a>

        <button type="submit" class="primary-btn block-btn" title="{{ $model->id ? 'Save Changes' : 'Add New Ability' }}">
            <i class="material-icons">save</i>
            {{ $model->id ? 'Update' : 'Submit' }}
        </button>
    </div>
</form>
