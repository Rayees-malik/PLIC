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
                        <label>Category</label>
                        <div class="dropdown-icon">
                            <select class="js-quick-category">
                                @foreach ($categories as $category => $products)
                                <option value="{{ $category }}">{{ $category }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="checkbox-wrap col-4">
                        <label class="checkbox" style="margin-top: 33px;">
                            <input type="checkbox" class="js-quick-all-categories">
                            <span class="checkbox-checkmark"></span>
                            <span class="checkbox-label">Apply to all</span>
                        </label>
                    </div>
                </div>
                @if ($promoConfig)
                @foreach (Arr::get($promoConfig, 'lineItemFields') as $field => $fieldConfig)
                {!! BladeHelper::quickPromoField($field, $fieldConfig) !!}
                @endforeach
                @endif
                @if (!$promoConfig || !Arr::get($promoConfig, 'hideBrandDiscount'))
                <div class="row">
                    <div class="input-wrap col-8">
                        <label>Brand Discount</label>
                        <div class="icon-input">
                            <i class="pre-icon js-discount-icon">{{ old('dollar_discount', $model->dollar_discount) ? '$' : '%' }}</i>
                            <input type="text" class="js-quick-field" data-target="js-brand-discount">
                        </div>
                    </div>
                </div>
                @endif
                @if ((!$promoConfig || !Arr::get($promoConfig, 'hidePLDiscount')) && (auth()->user()->can('promo.monthly.edit') || auth()->user()->can('signoff.retailer.promo')))
                <div class="row">
                    <div class="input-wrap col-8">
                        <label>Add'l PL Discount</label>
                        <div class="icon-input">
                            <i class="pre-icon">%</i>
                            <input type="text" class="js-quick-field" data-target="js-pl-discount">
                        </div>
                    </div>
                </div>
                @endif
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
