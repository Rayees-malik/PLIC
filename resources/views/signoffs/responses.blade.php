<div class="mb-2 text-center">
    <p class="mb-0">Proceed to next stage?</p>
    <p class="lead mb-0">Approve {{ $model::getShortClassName() }}</p>
</div>

<div class="row mt-1">
    <div class="col-6">
        <button class="warning-btn block-btn" type="button" data-toggle="modal" title="Reject Signoff" data-target="#rejectModal">No</button>
    </div>
    <div class="col-6">
        <button class="success-btn block-btn" type="button" data-toggle="modal" title="Approve Signoff" data-target="#approveModal">Yes</button>
    </div>
</div>

@include('modals.approve')
@include('modals.reject')