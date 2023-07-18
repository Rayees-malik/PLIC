<div class="col-12">
    <div class="dataTables_wrapper">
        <input type="hidden" name="deleted_items" class="js-delete-input">
        <table class="js-adjustments-table table datatable">
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
                    <th>Stock Id</th>
                    <th>UPC</th>
                    <th>Brand</th>
                    <th>Description</th>
                    <th style="width: 150px;" class="js-discount-title">{{ $model->dollar_discount ? 'Fixed Price' : 'Total Discount' }}</th>
                    <th style="width: 150px;" class="js-mcb_type">{{ $model->dollar_mcb ? 'MCB Dollar Amount' : 'MCB Portion of Total Discount' }}</th>
                    <th style="width: 240px;">Who to MCB</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($model->lineItems as $lineItem)
                <tr>
                    <td>
                        <input type="hidden" name="lineitem_id[]" class="js-id" value="{{ $lineItem->id }}">
                        <input type="hidden" name="morph_id[]" class="js-morph-id" value="{{ $lineItem->item_id }}">
                        <input type="hidden" name="morph_type[]" class="js-morph-type" value="{{ $lineItem->item_type }}">
                        <div class="checkbox-wrap mt-2">
                            <label class="checkbox">
                                <input type="checkbox" class="js-delete-row">
                                <span class="checkbox-checkmark"></span>
                            </label>
                        </div>
                    </td>
                    <td class="js-stockid-row">{{ $lineItem->item->getPAFStockId() }}</td>
                    <td class="js-upc-row">{{ $lineItem->item->getPAFUPC() }}</td>
                    <td class="js-brand-row">{{ $lineItem->item->getPAFBrand() }}</td>
                    <td class="js-description-row">{{ $lineItem->item->getPAFDescription() }}</td>
                    <td>
                        <div class="input-wrap table-input {{ $errors->lineItems->has("total_discount.{$loop->index}") ? ' input-danger' : '' }}">
                            <div class="icon-input">
                                <i class="pre-icon js-discount-icon">{{ old('dollar_discount', $model->dollar_discount) ? '$' : '%' }}</i>
                                <input type="text" name="total_discount[]" class="js-total-discount" value="{{ $lineItem->total_discount }}">
                            </div>
                        </div>
                    </td>
                    <td>
                        <div class="input-wrap table-input {{ $errors->lineItems->has("total_mcb.{$loop->index}") ? ' input-danger' : '' }}">
                            <div class="icon-input">
                                <i class="pre-icon js-mcb-discount-icon">{{ old('dollar_mcb', $model->dollar_mcb) ? '$' : '%' }}</i>
                                <input type="text" name="total_mcb[]" class="js-total-mcb" value="{{ $lineItem->total_mcb }}">
                            </div>
                        </div>
                    </td>
                    <td>
                        <div class="input-wrap table-input {{ $errors->lineItems->has("who_to_mcb.{$loop->index}") ? ' input-danger' : '' }}">
                            <input type="text" name="who_to_mcb[]" class="js-who-to-mcb" value="{{ $lineItem->who_to_mcb }}">
                        </div>
                    </td>
                </tr>
                @endforeach
                <tr class="js-adjustments-template-row" style="display: none;">
                    <td>
                        <input type="hidden" name="lineitem_id[]" class="js-id" disabled>
                        <input type="hidden" name="morph_id[]" class="js-morph-id" disabled>
                        <input type="hidden" name="morph_type[]" class="js-morph-type" disabled>
                        <div class="checkbox-wrap mt-2">
                            <label class="checkbox">
                                <input type="checkbox" class="js-template-delete-row" disabled>
                                <span class="checkbox-checkmark"></span>
                            </label>
                        </div>
                    </td>
                    <td class="js-stockid-row"></td>
                    <td class="js-upc-row"></td>
                    <td class="js-brand-row"></td>
                    <td class="js-description-row"></td>
                    <td>
                        <div class="input-wrap table-input">
                            <div class="icon-input">
                                <i class="pre-icon js-discount-icon">{{ old('dollar_discount', $model->dollar_discount) ? '$' : '%' }}</i>
                                <input type="text" name="total_discount[]" class="js-total-discount" disabled>
                            </div>
                        </div>
                    </td>
                    <td>
                        <div class="input-wrap table-input">
                            <div class="icon-input">
                                <i class="pre-icon js-mcb-discount-icon">{{ old('dollar_mcb', $model->dollar_mcb) ? '$' : '%' }}</i>
                                <input type="text" name="total_mcb[]" class="js-total-mcb" disabled>
                            </div>
                        </div>
                    </td>
                    <td>
                        <div class="input-wrap table-input">
                            <input type="text" name="who_to_mcb[]" class="js-who-to-mcb" disabled>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
        <button type="button" class="link-btn mt-1 delete js-paf-delete" data-toggle="modal" title="Delete Adjustments" data-action="deletePAFRows" data-label="selected adjustments" data-target="#js-deleteModal" style="float: left; display: none;">
            <i class="material-icons">delete_forever</i>
            Delete
        </button>
    </div>
</div>
