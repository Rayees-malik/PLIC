<div class="col-xl-6 mt-4">
    <div class="card">
        <div class="card-header d-flex justify-content-between flex-wrap">
            <h2 class="mb-0">Customer Link Images</h2>
        </div>
        <div class="card-body">
            <div class="formContainer">
                <form method="POST" action="{{ route('exports.export', 'customerlinkimages') }}">
                    @csrf
                    <div class="container">
                        @feature('customer-link-images-export-tweaks')
                        <div class="row">
                            <p class="tw-text-gray-500">You must provide either Added Since, one or more Stock IDs or only retrieve active products.</p>
                        </div>
                        @endfeature
                    <div class="row">
                            <div class="input-wrap">
                                <label>Added Since
                                    <div class="icon-input">
                                        <i class="material-icons pre-icon">calendar_today</i>
                                        <input name="date" class="js-datepicker" value="" readonly>
                                    </div>
                                </label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="input-wrap">
                                <label>or by Stock Id
                                    <div class="input">
                                        <textarea type="text" name="stock_ids" autocomplete="off"></textarea>
                                    </div>
                                </label>
                            </div>
                        </div>
                    @feature('customer-link-images-export-tweaks')
                        <div class="row input-wrap">
                            <label>Product Status</label>
                            <div class="inline-radio-group">
                                <div class="radio-wrap">
                                    <label class="radio">
                                        <input type="radio" name="only_active" value="0" checked>
                                        <span class="radio-checkmark"></span>
                                        <span class="radio-label">All</span>
                                    </label>
                                </div>
                                <div class="radio-wrap">
                                    <label class="radio">
                                        <input type="radio" name="only_active" value="1">
                                        <span class="radio-checkmark"></span>
                                        <span class="radio-label">Active Only</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="row input-wrap">
                            <label>Include Original Image?</label>
                            <div class="inline-radio-group">
                                <div class="radio-wrap">
                                    <label class="radio">
                                        <input type="radio" name="include_original_image" value="1">
                                        <span class="radio-checkmark"></span>
                                        <span class="radio-label">Yes</span>
                                    </label>
                                </div>
                                <div class="radio-wrap">
                                    <label class="radio">
                                        <input type="radio" name="include_original_image" value="0" checked>
                                        <span class="radio-checkmark"></span>
                                        <span class="radio-label">No</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="row input-wrap">
                            <label>Include Small Image?</label>
                            <div class="inline-radio-group">
                                <div class="radio-wrap">
                                    <label class="radio">
                                        <input type="radio" name="include_small_image" value="1" checked>
                                        <span class="radio-checkmark"></span>
                                        <span class="radio-label">Yes</span>
                                    </label>
                                </div>
                                <div class="radio-wrap">
                                    <label class="radio">
                                        <input type="radio" name="include_small_image" value="0">
                                        <span class="radio-checkmark"></span>
                                        <span class="radio-label">No</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    <div class="row input-wrap">
                        <label>Include Large Image?</label>
                        <div class="inline-radio-group">
                            <div class="radio-wrap">
                                <label class="radio">
                                    <input type="radio" name="include_large_image" value="1" checked>
                                    <span class="radio-checkmark"></span>
                                    <span class="radio-label">Yes</span>
                                </label>
                            </div>
                            <div class="radio-wrap">
                                <label class="radio">
                                    <input type="radio" name="include_large_image" value="0">
                                    <span class="radio-checkmark"></span>
                                    <span class="radio-label">No</span>
                                </label>
                            </div>
                        </div>
                    </div>
                    @endfeature
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
