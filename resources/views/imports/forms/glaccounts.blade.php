@can('imports.glaccounts')
<div class="row justify-content-center">
    <div class="col-xl-6">
        <div class="card">
            <div class="card-header">
                <h2 class="mb-0">Customer GL Accounts</h2>
            </div>
            <div class="card-body">
                <div class="formContainer">
                    <form method="POST" action="{{ route('imports.glaccounts') }}" enctype="multipart/form-data">
                        @csrf
                        <input type="file" name="accounts" accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel">
                        <button type="submit" class="primary-btn block-btn mt-3" title="Import">
                            <i class="material-icons">save_alt</i>
                            Import GL Accounts File
                        </button>
                        <small><em>This will overwrite any existing gl data from the previous import.</em></small>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endcan
