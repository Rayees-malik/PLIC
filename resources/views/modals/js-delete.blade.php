<div class="modal fade" id="js-deleteModal" tabindex="-1" role="dialog" aria-labelledby="js-deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="js-deleteModalLabel">Confirm Delete</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Please confirm you would like to delete
                <b><span id="js-deleteModalId"></span></b>.
            </div>
            <div class="modal-footer">
                <button type="button" class="secondary-btn" data-dismiss="modal">Cancel</button>
                <span class="pull-right">
                    <button type="button" id="js-deleteModalButton" class="warning-btn">
                        Delete
                    </button>
                </span>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script type="text/javascript">
    $(function () {
        $('#js-deleteModal').on("show.bs.modal", function (e) {
            $("#js-deleteModalLabel").html($(e.relatedTarget).data('label'));
            $("#js-deleteModalId").html($(e.relatedTarget).data('label'));

            $("#js-deleteModalButton").on('click', function () {
                window[$(e.relatedTarget).data('action')]();
                $('#js-deleteModal').modal('hide');
            });
        });
    });

</script>
@endpush
