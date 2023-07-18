<div class="modal fade" id="unsubmitModal" tabindex="-1" role="dialog" aria-labelledby="unsubmitModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="unsubmitModalLabel">Confirm Unsubmit</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Please confirm you would like to unsubmit
                <b><span id="unsubmitModalId"></span></b>.
            </div>
            <div class="modal-footer">
                <button type="button" class="secondary-btn" data-dismiss="modal">Cancel</button>
                <span class="pull-right">
                    <form id="unsubmitModalForm" method="GET" action="">
                        @csrf
                        <button type="submit" class="warning-btn">
                            Unsubmit
                        </button>
                    </form>
                </span>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script type="text/javascript">
    $(function () {
        $('#unsubmitModal').on("show.bs.modal", function (e) {
            $("#unsubmitModalForm").attr('action', $(e.relatedTarget).data('action'));
            $("#unsubmitModalLabel").html($(e.relatedTarget).data('label'));
            $("#unsubmitModalId").html($(e.relatedTarget).data('label'));
        });
    });

</script>
@endpush
