<div class="col-xl-6 mt-4">
    <div class="card">
        <div class="card-header d-flex justify-content-between flex-wrap">
            <h2 class="mb-0">MAF Rejections</h2>
        </div>
        <div class="card-body">
            <div class="formContainer">
                <form method="POST" action="{{ route('exports.export', 'mafrejection') }}">
                    @csrf
                    <div class="container">
                        <div class="row">
                            <div class="input-wrap">
                                <div class="input-wrap {{ $errors->has("start_date") ? 'input-danger' : '' }}">
                                    <label>Rejected Since
                                        <div class="icon-input">
                                            <i class="material-icons pre-icon">calendar_today</i>
                                            <input name="start_date" class="js-datepicker" value="{{ old('', ) }}">
                                        </div>
                                    </label>
                                    @if ($errors->has('start_date'))
                                    <small class="info-danger">{{ $errors->first('start_date') }}</small>
                                    @endif
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
