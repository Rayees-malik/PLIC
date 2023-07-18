<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Confirm Delete</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Please confirm you would like to delete
                <b><span id="deleteModalId"></span></b>.
            </div>
            <div class="modal-footer">
                <button type="button" class="secondary-btn" data-dismiss="modal">Cancel</button>
                <span class="pull-right">
                    <form id="deleteModalForm" method="POST" action="">
                        @csrf
                        @method('delete')
                        <button type="submit" class="warning-btn">
                            Delete
                        </button>
                    </form>
                </span>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script type="text/javascript">
        $(function() {
            $('#deleteModal').on("show.bs.modal", function (e) {
                $("#deleteModalForm").attr('action', $(e.relatedTarget).data('action'));
                $("#deleteModalLabel").html($(e.relatedTarget).data('label'));
                $("#deleteModalId").html($(e.relatedTarget).data('label'));
            });
        });
    </script>
@endpush
