<div class="col-12">
    <div class="dataTables_wrapper">
        <input type="hidden" name="deleted_items" class="js-delete-input">
        <table class="js-removals-table table datatable">
            <thead>
                <tr>
                    @if (!isset($signoffForm))
                    <th style="width: 60px;">
                        <div class="checkbox-wrap">
                            <label class="checkbox">
                                <input type="checkbox" class="js-delete-header">
                                <span class="checkbox-checkmark"></span>
                            </label>
                        </div>
                    </th>
                    @else
                    <th style="width: 0;"></th>
                    @endif
                    <th>Description</th>
                    <th>Brand</th>
                    <th style="width: 110px;">Quantity</th>
                    <th style="width: 130px; text-align: right">
                      <div class="tw-flex tw-flex-col tw-space-y-4">
                        <div>True Landed Cost</div>
                        <div>Systems Cost</div>
                      </div>
                    </th>
                    <th style="width: 130px; text-align: right">Extended Value</th>
                    <th style="width: 300px;">Expiry</th>
                    <th style="width: 180px;">Options</th>
                    <th style="width: 350px;">Reason/Comment</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($model->lineItems as $lineItem)
                <tr>
                    <td>
                        <input type="hidden" name="lineitem_id[]" class="js-id" value="{{ $lineItem->id }}">
                        <input type="hidden" name="product_id[]" class="js-product-id" value="{{ $lineItem->product_id }}">
                        @if (!isset($signoffForm))
                        <div class="checkbox-wrap mt-2">
                            <label class="checkbox">
                                <input type="checkbox" class="js-delete-row">
                                <span class="checkbox-checkmark"></span>
                            </label>
                        </div>
                        @endif
                    </td>
                    <td class="js-description-row">
                        <strong>{{ $lineItem->product->stock_id }}</strong><br />
                        {{ $lineItem->product->getName() }}
                    </td>
                    <td>{{ $lineItem->product->brand->name }}</td>
                    <td>
                        @if (!isset($signoffForm) || (isset($signoff) && $signoff->step == 3))
                        <div class="input-wrap table-input {{ $errors->lineItems->has("quantity.{$loop->index}") ? ' input-danger' : '' }}">
                            <input type="text" name="quantity[]" class="js-quantity-field" value="{{ $lineItem->quantity }}" autocomplete="off">
                            @if ($errors->products->has("quantity.{$loop->index}"))
                            <small class="info-danger">{{ $errors->products->first("quantity.{$loop->index}") }}</small>
                            @endif
                        </div>
                        @else
                        {{ $lineItem->quantity }}
                        @endif
                    </td>
                    <td class="dt-body-right">
                      <div class="tw-flex tw-flex-col tw-items-end">
                      <div>$<span class="js-average-landed-cost">{{ number_format($lineItem->average_landed_cost, 2) }}</span></div>
                      <div class="tw-italic">$<span class="js-cost">{{ number_format($lineItem->cost, 2) }}</span></div>
                      </div>
                    </td>
                    <td class="dt-body-right">
                      <div class="tw-flex tw-flex-col tw-items-end">
                      <div>$<span class="js-adj-average-landed-cost">{{ number_format($lineItem->quantity * $lineItem->average_landed_cost, 2) }}</span></div>
                      <div class="tw-italic">$<span class="js-adj-cost">{{ number_format($lineItem->quantity * $lineItem->cost, 2) }}</span></div>
                      </div>
                    </td>
                    <td>
                        <input type="hidden" name="average_landed_cost[]" value="{{ $lineItem->average_landed_cost }}">
                        <input type="hidden" name="cost[]" value="{{ $lineItem->cost }}">
                        <input type="hidden" name="warehouse[]" value="{{ $lineItem->warehouse }}">
                        <input type="hidden" name="expiry[]" value="{{ $lineItem->expiry }}">
                        Expiry: {{ $lineItem->expiry }}<br>
                        WHSE: {{ $lineItem->warehouse }}
                    </td>
                    <td>
                        @if (!isset($signoffForm))
                        <div class="radio-wrap mt-2">
                            <label class="checkbox">
                                @if ($lineItem->full_mcb)
                                <input type="hidden" value="0">
                                <input type="checkbox" class="js-check-field" value="1" data-name="full_mcb[]" name="full_mcb[]" checked>
                                @else
                                <input type="hidden" value="0" name="full_mcb[]">
                                <input type="checkbox" class="js-check-field" value="1" data-name="full_mcb[]">
                                @endif
                                <span class="checkbox-checkmark"></span>
                                &nbsp;Full MCB
                            </label>
                        </div>
                        <div class="radio-wrap mt-2">
                            <label class="checkbox">
                                @if ($lineItem->reserve)
                                <input type="hidden" value="0">
                                <input type="checkbox" class="js-check-field" value="1" data-name="reserve[]" name="reserve[]" checked>
                                @else
                                <input type="hidden" value="0" name="reserve[]">
                                <input type="checkbox" class="js-check-field" value="1" data-name="reserve[]">
                                @endif
                                <span class="checkbox-checkmark"></span>
                                &nbsp;Reserve
                            </label>
                        </div>
                        <div class="radio-wrap mt-2">
                            <label class="checkbox">
                                @if ($lineItem->vendor_pickup)
                                <input type="hidden" value="0">
                                <input type="checkbox" class="js-check-field" value="1" data-name="vendor_pickup[]" name="vendor_pickup[]" checked>
                                @else
                                <input type="hidden" value="0" name="vendor_pickup[]">
                                <input type="checkbox" class="js-check-field" value="1" data-name="vendor_pickup[]">
                                @endif
                                <span class="checkbox-checkmark"></span>
                                &nbsp;Vendor Pickup
                            </label>
                        </div>
                        @else
                        {{ implode(', ', array_filter([
                          $lineItem->full_mcb ? 'Full MCB' : null,
                          $lineItem->reserve ? 'Reserve' : null,
                          $lineItem->vendor_pickup ? 'Vendor Pickup' : null
                          ]))
                        }}
                        @endif
                    </td>
                    <td>
                        @if (!isset($signoffForm))
                        <div class="dropdown-wrap table-input {{ $errors->lineItems->has("reason.{$loop->index}") ? 'dropdown-danger' : '' }}">
                            <div class="dropdown-icon">
                                <select name="reason[]">
                                    @foreach (Config::get('inventory-removals')['reasons'] as $key => $reason)
                                    <option value="{{ $key }}" {{ $lineItem->reason == $key ? 'selected' : '' }}>{{ $reason }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="input-wrap table-input {{ $errors->lineItems->has("notes.{$loop->index}") ? ' input-danger' : '' }}">
                            <input type="text" name="notes[]" value="{{ $lineItem->notes }}" autocomplete="off">
                        </div>
                        @else
                        {{ \Arr::get(\Config::get('inventory-removals')['reasons'], $lineItem->reason ?? 'other', 'Other, See Notes') }}<br />
                        <em>{{ $lineItem->notes }}</em>
                        @endif
                    </td>
                </tr>
                @endforeach
                @if (!isset($signoffForm))
                <tr class="js-removal-template-row" style="display: none;">
                    <td>
                        <input type="hidden" name="lineitem_id[]" class="js-id" disabled>
                        <input type="hidden" name="product_id[]" class="js-product-id" disabled>
                        <div class="checkbox-wrap mt-2">
                            <label class="checkbox">
                                <input type="checkbox" class="js-delete-row">
                                <span class="checkbox-checkmark"></span>
                            </label>
                        </div>
                    </td>
                    <td class="js-description-row"></td>
                    <td class="js-brand-row"></td>
                    <td>
                        <div class="input-wrap table-input">
                            <input type="text" name="quantity[]" class="js-quantity-field" autocomplete="off" disabled>
                        </div>
                    </td>
                    <td class="dt-body-right">
                      <div class="tw-flex tw-flex-col tw-items-end">
                        <div>$<span class="js-average-landed-cost">0.00</span></div>
                        <div class="tw-italic">$<span class="js-cost">0.00</span></div>
                      </div>
                    </td>
                    <td class="dt-body-right">
                      <div class="tw-flex tw-flex-col tw-items-end">
                      <div>$<span class="js-adj-average-landed-cost">0.00</span></div>
                      <div class="tw-italic">$<span class="js-adj-cost">0.00</span></div>
                      </div>
                    </td>
                    <td>
                        <div class="dropdown-wrap">
                            <input type="hidden" name="average_landed_cost[]" class="js-hidden-average-landed-cost" value="" disabled>
                            <input type="hidden" name="cost[]" class="js-hidden-cost" value="" disabled>
                            <input type="hidden" name="warehouse[]" class="js-hidden-warehouse" value="" disabled>
                            <input type="hidden" name="expiry[]" class="js-hidden-expiry" value="" disabled>
                            <div class="dropdown-icon">
                                <select class="js-expiry-field" data-placeholder="Select Warehouse & Expiry" disabled>
                                </select>
                            </div>
                        </div>
                    </td>
                    <td>
                        <div class="radio-wrap mt-2">
                            <label class="checkbox">
                                <input type="hidden" value="0" name="full_mcb[]" disabled>
                                <input type="checkbox" class="js-check-field" value="1" data-name="full_mcb[]" disabled>
                                <span class="checkbox-checkmark"></span>
                                Full MCB
                            </label>
                        </div>
                        <div class="radio-wrap mt-2">
                            <label class="checkbox">
                                <input type="hidden" value="0" name="reserve[]" disabled>
                                <input type="checkbox" class="js-check-field" value="1" data-name="reserve[]" disabled>
                                <span class="checkbox-checkmark"></span>
                                Reserve
                            </label>
                        </div>
                        <div class="radio-wrap mt-2">
                            <label class="checkbox">
                                <input type="hidden" value="0" name="vendor_pickup[]" disabled>
                                <input type="checkbox" class="js-check-field" value="1" data-name="vendor_pickup[]" disabled>
                                <span class="checkbox-checkmark"></span>
                                Vendor Pickup
                            </label>
                        </div>
                    </td>
                    <td>
                        <div class="dropdown-wrap table-input">
                            <div class="dropdown-icon">
                                <select name="reason[]" disabled>
                                    @foreach (Config::get('inventory-removals')['reasons'] as $key => $reason)
                                    <option value="{{ $key }}">{{ $reason }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="input-wrap table-input">
                            <input type="text" name="notes[]" autocomplete="off" disabled>
                        </div>
                    </td>
                </tr>
                @endif
            </tbody>
            <tfoot>
              <tr class="tw-text-right">
                <td colspan="5">
                  <strong>True Landed Cost Total:</strong>
              </td>
              <td>
                  <strong>$<span class="js-average-landed-total">{{ number_format($model->calculateAverageLandedTotal(), 2) }}</span></strong>
              </td>
              <td colspan="4"></td>
            </tr>
            <tr class="tw-text-right tw-italic">
              <td colspan="5">
                <strong>Systems Total:</strong>
            </td>
            <td>
              <strong>$<span class="js-total">{{ number_format($model->calculateTotal(), 2) }}</span></strong>
          </td>
          <td colspan="4"></td>
        </tr>
            </tfoot>
        </table>

        @if (!isset($signoffForm))
        <button type="button" class="link-btn mt-1 delete js-removal-delete" data-toggle="modal" title="Delete Removal" data-action="deleteRemovalRows" data-label="selected removals" data-target="#js-deleteModal" style="float: left; display: none;">
            <i class="material-icons">delete_forever</i>
            Delete
        </button>
        @endif
    </div>
</div>
