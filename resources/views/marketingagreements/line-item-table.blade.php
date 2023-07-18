<div class="col-12">
    <div class="dataTables_wrapper">
        <input type="hidden" name="deleted_items" class="js-delete-input">
        <table class="js-agreements-table table datatable">
            <thead>
                <tr>
                    <th style="width: 60px;">
                        <div class="checkbox-wrap">
                            <label class="checkbox">
                                <input type="checkbox" class="js-delete-header">
                                <span class="checkbox-checkmark"></span>
                            </label>
                        </div>
                    </th>
                    <th style="width: 360px;">Brand</th>
                    <th>Activity</th>
                    <th>Promo Dates</th>
                    <th style="width: 150px;">Cost</th>
                    <th style="width: 150px;">MCB Amount</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($model->lineItems as $lineItem)
                <tr>
                    <td>
                        <input type="hidden" name="lineitem_id[]" class="js-id" value="{{ $lineItem->id }}">
                        <div class="checkbox-wrap mt-2">
                            <label class="checkbox">
                                <input type="checkbox" class="js-delete-row">
                                <span class="checkbox-checkmark"></span>
                            </label>
                        </div>
                    </td>
                    <td>
                        <div class="dropdown-wrap table-input {{ $errors->header->has("brand_id.{$loop->index}") ? 'dropdown-danger' : '' }}">
                            <div class="dropdown-icon">
                                <select name="brand_id[]" class="searchable js-brand-id" data-placeholder="Select Brand">
                                    <option></option>
                                    @foreach ($brands as $brand)
                                    <option value="{{ $brand->id }}" {{ $lineItem->brand_id == $brand->id ? 'selected' : '' }}>{{ $brand->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </td>
                    <td>
                        <div class="input-wrap table-input {{ $errors->lineItems->has("activity.{$loop->index}") ? ' input-danger' : '' }}">
                            <input type="text" name="activity[]" class="js-activity" value="{{ $lineItem->activity }}">
                        </div>
                    </td>
                    <td>
                        <div class="input-wrap table-input {{ $errors->lineItems->has("promo_dates.{$loop->index}") ? ' input-danger' : '' }}">
                            <input type="text" name="promo_dates[]" class="js-promo_dates" value="{{ $lineItem->promo_dates }}">
                        </div>
                    </td>
                    <td>
                        <div class="input-wrap table-input {{ $errors->lineItems->has("cost.{$loop->index}") ? ' input-danger' : '' }}">
                            <div class="icon-input">
                                <i class="pre-icon">$</i>
                                <input type="text" name="cost[]" class="js-cost-field" value="{{ $lineItem->cost }}">
                            </div>
                        </div>
                    </td>
                    <td>
                        <div class="input-wrap table-input {{ $errors->lineItems->has("mcb_amount.{$loop->index}") ? ' input-danger' : '' }}">
                            <div class="icon-input">
                                <i class="pre-icon">$</i>
                                <input type="text" name="mcb_amount[]" class="js-mcb-field" value="{{ $lineItem->mcb_amount }}">
                            </div>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td>
                        <input type="hidden" name="lineitem_id[]" class="js-id">
                        <div class="checkbox-wrap mt-2">
                            <label class="checkbox">
                                <input type="checkbox" class="js-delete-row">
                                <span class="checkbox-checkmark"></span>
                            </label>
                        </div>
                    </td>
                    <td>
                        <div class="dropdown-wrap">
                            <div class="dropdown-icon">
                                <select name="brand_id[]" class="searchable js-brand-id" data-placeholder="Select Brand">
                                    <option></option>
                                    @foreach ($brands as $brand)
                                    <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </td>
                    <td>
                        <div class="input-wrap">
                            <input type="text" name="activity[]">
                        </div>
                    </td>
                    <td>
                        <div class="input-wrap">
                            <input type="text" name="promo_dates[]">
                        </div>
                    </td>
                    <td>
                        <div class="input-wrap">
                            <div class="icon-input">
                                <i class="pre-icon">$</i>
                                <input type="text" name="cost[]" class="js-cost-field">
                            </div>
                        </div>
                    </td>
                    <td>
                        <div class="input-wrap">
                            <div class="icon-input">
                                <i class="pre-icon">$</i>
                                <input type="text" name="mcb_amount[]" class="js-mcb-field">
                            </div>
                        </div>
                    </td>
                </tr>
                @endforelse
                <tr class="js-agreement-template-row" style="display: none;">
                    <td>
                        <input type="hidden" name="lineitem_id[]" class="js-id" disabled>
                        <div class="checkbox-wrap mt-2">
                            <label class="checkbox">
                                <input type="checkbox" class="js-template-delete-row" disabled>
                                <span class="checkbox-checkmark"></span>
                            </label>
                        </div>
                    </td>
                    <td>
                        <div class="dropdown-wrap">
                            <div class="dropdown-icon">
                                <select name="brand_id[]" class="js-brand-id" data-placeholder="Select Brand" disabled>
                                    <option></option>
                                    @foreach ($brands as $brand)
                                    <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </td>
                    <td>
                        <div class="input-wrap">
                            <input type="text" name="activity[]" disabled>
                        </div>
                    </td>
                    <td>
                        <div class="input-wrap">
                            <input type="text" name="promo_dates[]" disabled>
                        </div>
                    </td>
                    <td>
                        <div class="input-wrap">
                            <div class="icon-input">
                                <i class="pre-icon">$</i>
                                <input type="text" name="cost[]" class="js-cost-field" disabled>
                            </div>
                        </div>
                    </td>
                    <td>
                        <div class="input-wrap">
                            <div class="icon-input">
                                <i class="pre-icon">$</i>
                                <input type="text" name="mcb_amount[]" class="js-mcb-field" disabled>
                            </div>
                        </div>
                    </td>
                </tr>
                <tr class="text-right">
                    <td colspan="4"></td>
                    <td>
                        <strong>Subtotal:</strong>
                    </td>
                    <td>
                        $<span class="js-subtotal">{{ number_format($model->calcSubtotal(), 2) }}</span>
                    </td>
                </tr>
                <tr class="text-right">
                    <td colspan="4"></td>
                    <td>
                        <div class="input-wrap table-input">
                            <div class="icon-input">
                                <i class="pre-icon">Tax %</i>
                                <input type="text" name="tax_rate" class="js-tax-field" style="padding-left: 55px; width: 180px;" value="{{ old('tax_rate', $model->tax_rate) ?? '0' }}">
                            </div>
                        </div>
                    </td>
                    <td>
                        $<span class="js-tax-total">{{ number_format($model->calcTax(), 2) }}</span>
                    </td>
                </tr>
                <tr class="text-right">
                    <td colspan="4"></td>
                    <td>
                        <strong>Total:</strong>
                    </td>
                    <td>
                        <strong>$<span class="js-total">{{ number_format($model->calcTotal(), 2) }}</span></strong>
                    </td>
                </tr>
            </tbody>
        </table>

        <button type="button" class="link-btn js-maf-add" title="Add Row" style="float: right;">
            <i class="material-icons">playlist_add</i>
            Add Row
        </button>
        <button type="button" class="link-btn mt-1 delete js-maf-delete" data-toggle="modal" title="Delete Agreements" data-action="deleteMAFRows" data-label="selected agreements" data-target="#js-deleteModal" style="float: left; display: none;">
            <i class="material-icons">delete_forever</i>
            Delete
        </button>
    </div>
</div>
