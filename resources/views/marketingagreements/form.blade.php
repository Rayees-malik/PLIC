<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ $model->id ? route('marketingagreements.update', $model->id) : route('marketingagreements.store') }}" enctype="multipart/form-data">
            @csrf
            @method($model->id ? 'PATCH' : 'POST')

            @include('marketingagreements.form-controls')

            <div class="bottom-controls">
                <a class="secondary-btn" href="{{ BladeHelper::backOr(route('marketingagreements.index')) }}" title="Cancel">
                    <i class="material-icons">cancel</i>
                    Cancel
                </a>
                <button class="secondary-btn" name="action" value="save" type="submit" title="Save">
                    Save
                </button>
                <button type="submit" class="js-submit accent-btn" name="action" value="submit" type="submit" title="Submit">
                    <i class="material-icons">save</i>
                    Submit
                </button>
            </div>
        </form>
    </div>
</div>
