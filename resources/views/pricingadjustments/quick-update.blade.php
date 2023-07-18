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
                    <div class="input-wrap col-6">
                        <label>Total Discount</label>
                        <div class="icon-input">
                            <i class="pre-icon js-discount-icon">{{ old('dollar_discount', $model->dollar_discount) ? '$' : '%' }}</i>
                            <input type="text" class="js-quick-field" data-target="js-total-discount">
                        </div>
                    </div>
                    <div class="input-wrap col-6">
                        <label>Total MCB</label>
                        <div class="icon-input">
                            <i class="pre-icon js-mcb-discount-icon">{{ old('dollar_mcb', $model->dollar_mcb) ? '$' : '%' }}</i>
                            <input type="text" class="js-quick-field" data-target="js-total-mcb">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="input-wrap col-12">
                        <label>Who to MCB</label>
                        <input type="text" class="js-quick-field" data-target="js-who-to-mcb">
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
