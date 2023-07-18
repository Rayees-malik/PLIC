<form method="POST" action="{{ $model->id ? route('uom.update', $model->id) : route('uom.store') }}">
    @csrf
    @method($model->id ? 'PATCH' : 'POST')

    <div class="formContainer">
        @include('uom.form-controls')
    </div>

    <div class="bottom-controls">
        <a class="secondary-btn block-btn" href="{{ BladeHelper::backOr(route('uom.index')) }}" title="Cancel">
            <i class="material-icons">cancel</i>
            Cancel
        </a>
        <button type="submit" class="primary-btn block-btn" title="{{ $model->id ? 'Add new UOM' : 'Save changes' }}">
            <i class="material-icons">save</i>
            {{ $model->id ? 'Update' : 'Submit' }}
        </button>
    </div>
</form>
