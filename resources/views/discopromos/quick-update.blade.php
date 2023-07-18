<div class="modal fade" id="updateModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><label>Quick Update</label></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="dropdown-wrap col-8">
                        <label>Brand</label>
                        <div class="dropdown-icon">
                            <select class="js-quick-brand">
                                @foreach ($brands as $brand => $products)
                                <option value="{{ $brand }}">{{ $brand }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="checkbox-wrap col-4">
                        <label class="checkbox" style="margin-top: 33px;">
                            <input type="checkbox" class="js-quick-all-brands">
                            <span class="checkbox-checkmark"></span>
                            <span class="checkbox-label">Apply to all</span>
                        </label>
                    </div>
                </div>
                <div class="row">
                    <div class="input-wrap col-8">
                        <label>Brand Discount</label>
                        <div class="icon-input">
                            <i class="pre-icon">%</i>
                            <input type="text" class="js-quick-field" data-target="js-brand-discount">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="input-wrap col-8">
                        <label>Add'l PL Discount</label>
                        <div class="icon-input">
                            <i class="pre-icon">%</i>
                            <input type="text" class="js-quick-field" data-target="js-pl-discount">
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="secondary-btn" data-dismiss="modal">Cancel</button>
                <span class="pull-right">
                    <button type="button" class="accent-btn js-quick-apply">Apply</button>
                </span>
            </div>
        </div>
    </div>
</div>
