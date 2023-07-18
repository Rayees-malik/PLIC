<form method="POST" action="{{ $model->id ? route('promos.update', $model->id) : ($owner ? route("{$owner->routePrefix}.promos.store", $owner->id) : route('promos.store')) }}">
    @csrf
    @method($model->id ? 'patch' : 'post')

    <div class="formContainer">
        <div class="spinner-container ajax-loader" style="display:none">
            <div class="spinner-moon spinner-item"></div>
        </div>
        @include('promos.form-controls')
    </div>

    <div class="bottom-controls">
        <a class="secondary-btn" href="{{ BladeHelper::backOr(route('promos.index')) }}" title="Cancel">
            <i class="material-icons">cancel</i>
            Cancel
        </a>
        <button class="secondary-btn js-promo-product-save" name="action" value="save" type="submit" title="Save">
            Save
        </button>
        <button type="submit" class="js-submit accent-btn" name="action" value="submit" type="submit" title="Submit">
            <i class="material-icons">save</i>
            Submit
        </button>
    </div>
</form>
