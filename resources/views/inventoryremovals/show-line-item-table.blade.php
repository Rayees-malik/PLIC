<div class="col-12">
    <div class="dataTables_wrapper">
        <table class="table datatable">
            <thead>
                <tr>
                    <th>Description</th>
                    <th>Brand</th>
                    <th style="width: 60px;">Qty</th>
                    <th style="width: 130px; text-align: right">
                      <div class="tw-flex tw-flex-col tw-space-y-4">
                      <div>True Landed Cost</div>
                      <div>Systems Cost</div>
                      </div>
                    </th>
                    <th style="width: 130px; text-align: right">Extended Value</th>
                    <th style="width: 180px;">Expiry</th>
                    <th style="width: 160px;">Options</th>
                    <th style="width: 450px;">Reason/Comment</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($model->lineItems as $lineItem)
                <tr>
                    <td>
                        <strong>{{ $lineItem->product->stock_id }}</strong><br />
                        {{ $lineItem->product->getName() }}
                    </td>
                    <td>{{ $lineItem->product->brand->name }}</td>
                    <td>{{ $lineItem->quantity }}</td>
                    <td>
                      <div class="tw-flex tw-flex-col tw-items-end">
                      <div>{{ App\Helpers\NumberHelper::toAccountingDollar($lineItem->average_landed_cost) }}</div>
                      <div class="tw-italic">{{ App\Helpers\NumberHelper::toAccountingDollar($lineItem->cost) }}</div>
                    </div>
                    </td>
                    <td>
                      <div class="tw-flex tw-flex-col tw-items-end">
                      <div>{{ App\Helpers\NumberHelper::toAccountingDollar($lineItem->quantity * $lineItem->average_landed_cost) }}</div>
                      <div class="tw-italic">{{ App\Helpers\NumberHelper::toAccountingDollar($lineItem->quantity * $lineItem->cost) }}</div>
                      </div>
                    </td>
                    <td>
                        Expiry: {{ $lineItem->expiry }}<br>
                        WHSE: {{ $lineItem->warehouse }}
                    </td>
                    <td>
                        {{ implode(', ', array_filter([
                        $lineItem->full_mcb ? 'Full MCB' : null,
                        $lineItem->reserve ? 'Reserve' : null,
                        $lineItem->vendor_pickup ? 'Vendor Pickup' : null
                        ]))
                      }}
                    </td>
                    <td>
                        {{ \Arr::get(\Config::get('inventory-removals')['reasons'], $lineItem->reason, 'Other, See Notes') }}<br />
                        <em>{{ $lineItem->notes }}</em>
                    </td>
                </tr>
                @endforeach
              </tbody>
              <tfoot>
                <tr class="tw-text-right tw-bg-gray-200">
                    <td colspan="4" class="tw-text-right">
                        <strong>True Landed Cost Total:</strong>
                    </td>
                    <td>
                        <strong>{{ App\Helpers\NumberHelper::toAccountingDollar($model->calculateAverageLandedTotal()) }}</strong>
                    </td>
                    <td colspan="4"></td>
                </tr>
                <tr class="tw-text-right tw-bg-gray-200 tw-italic">
                  <td colspan="4" class="tw-text-right">
                      <strong>Systems Cost Total:</strong>
                  </td>
                  <td>
                      <strong>{{ App\Helpers\NumberHelper::toAccountingDollar($model->calculateTotal()) }}</strong>
                  </td>
                  <td colspan="4"></td>
              </tr>
            </tfoot>
        </table>
    </div>
</div>
