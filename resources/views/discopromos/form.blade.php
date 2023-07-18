<form method="POST" action="{{ route('discopromos.update') }}">
    @csrf
    @method('patch')
    @can('promo.update.discos')
        <div class="top-controls">
            <a class="secondary-btn" href="{{ BladeHelper::backOr(route('promos.index')) }}" title="Cancel">
                <i class="material-icons">cancel</i>
                Cancel
            </a>
            <button class="secondary-btn" type="submit" title="Save">
                Save
            </button>
        </div>
    @endcan

    <div class="formContainer">
        @include('discopromos.form-controls')
    </div>

    @can('promo.update.discos')
        <div class="bottom-controls">
            <a class="secondary-btn" href="{{ BladeHelper::backOr(route('promos.index')) }}" title="Cancel">
                <i class="material-icons">cancel</i>
                Cancel
            </a>
            <button class="secondary-btn" type="submit" title="Save">
                Save
            </button>
        </div>
    @endcan
</form>
