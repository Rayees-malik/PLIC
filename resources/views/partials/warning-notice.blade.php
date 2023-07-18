@if ($model->{$model->stateField()} !== \App\Helpers\SignoffStateHelper::INITIAL)
<div class="container pb-2">
    <div class="row justify-content-center">
        <div class="col-8">
            <div class="alert-warning">
                <i class="material-icons">error</i>
                @if ($model->isCompletedProposed)
                <p>This is an approved submission or change request and not the actual item.</p>
                @elseif ($model->{$model->stateField()} == \App\Helpers\SignoffStateHelper::PENDING)
                <p>This is a pending submission or change request.</p>
                @elseif ($model->{$model->stateField()} == \App\Helpers\SignoffStateHelper::REJECTED)
                <p>This is a rejected submission or change request.</p>
                @elseif ($model->{$model->stateField()} == \App\Helpers\SignoffStateHelper::IN_PROGRESS)
                <p>This is a saved draft of a submission or change request.</p>
                @elseif ($model->{$model->stateField()} == \App\Helpers\SignoffStateHelper::ARCHIVED)
                <p>This is an archived submission or change request.</p>
                @elseif ($model->{$model->stateField()} == \App\Helpers\SignoffStateHelper::UNSUBMITTED)
                <p>This is an unsubmitted submission.</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endif
