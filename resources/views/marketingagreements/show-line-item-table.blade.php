<div class="col-12">
    <div class="dataTables_wrapper">
        <table class="table datatable">
            <thead>
                <tr>
                    <th>Brand</th>
                    <th>Activity</th>
                    <th>Promo Dates</th>
                    <th style="width: 140px;">Cost</th>
                    <th style="width: 140px;">MCB Amount</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($model->lineItems as $lineItem)
                <tr>
                    <td>{{ $lineItem->brand->name }}</td>
                    <td>{{ $lineItem->activity }}</td>
                    <td>{{ $lineItem->promo_dates }}</td>
                    <td>{{ App\Helpers\NumberHelper::toAccountingDollar($lineItem->cost) }}</td>
                    <td>{{ App\Helpers\NumberHelper::toAccountingDollar($lineItem->mcb_amount) }}</td>
                </tr>
                @endforeach
                <tr class="text-right">
                    <td colspan="3"></td>
                    <td><strong>Subtotal:</strong></td>
                    <td>${{ number_format($model->calcSubtotal(), 2) }}</td>
                </tr>
                <tr class="text-right">
                    <td colspan="3"></td>
                    <td><strong>Tax {{ $model->tax_rate }}%:</strong></td>
                    <td>${{ number_format($model->calcTax(), 2) }}</td>
                </tr>
                <tr class="text-right">
                    <td colspan="3"></td>
                    <td><strong>Total:</strong></td>
                    <td><strong>${{ number_format($model->calcTotal(), 2) }}</strong></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
