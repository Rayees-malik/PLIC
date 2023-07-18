<div class="col-xl-6 mt-4">
    <div class="card">
        <div class="card-header d-flex justify-content-between flex-wrap">
            <h2 class="mb-0">Catalogue Change Summary</h2>
        </div>
        <div class="card-body">
            <div class="formContainer">
                <form method="POST" action="{{ route('exports.export', 'cataloguechangesummary') }}">
                    @csrf
                    <div class="container">
                        <div class="row">
                            <div class="input-wrap">
                                <div class="input-wrap">
                                    <label>Start Date
                                        <div class="icon-input">
                                            <i class="material-icons pre-icon">calendar_today</i>
                                            <input name="start_date" class="js-datepicker" value="{{ \Carbon\Carbon::parse('30 days ago') }}">
                                        </div>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="primary-btn block-btn mt-3" title="Export">
                        <i class="material-icons">save_alt</i>
                        Export
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
