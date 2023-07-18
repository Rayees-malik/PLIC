<div class="col-xl-6 mt-4">
    <div class="card">
        <div class="card-header d-flex justify-content-between flex-wrap">
            <h2 class="mb-0">Product Images</h2>
        </div>
        <div class="card-body">
            <div class="formContainer">
                <form method="POST" action="{{ route('exports.export', 'productimages') }}">
                    @csrf
                    <div class="container">
                        <div class="row">
                            <div class="input-wrap">
                                <label>Stock Ids
                                    <div class="input">
                                        <textarea type="text" name="stock_ids" autocomplete="off"></textarea>
                                    </div>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="row input-wrap">
                        <label>Options</label>
                        <div class="checkbox-wrap">
                            <label class="checkbox">
                                <input type="checkbox" name="images" value="1" checked>
                                <span class="checkbox-checkmark"></span>
                                <span class="checkbox-label">Images</span>
                            </label>
                        </div>
                        <div class="checkbox-wrap">
                            <label class="checkbox">
                                <input type="checkbox" name="labelflats" value="1">
                                <span class="checkbox-checkmark"></span>
                                <span class="checkbox-label">Label Flats</span>
                            </label>
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
