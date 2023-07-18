<div class="col-12">
    <div class="dataTables_wrapper">
        <table class="table datatable">
            <thead>
                <tr>
                    <th>Stock Id</th>
                    <th>UPC</th>
                    <th>Brand</th>
                    <th>Description</th>
                    <th style="width: 120px;">{{ $model->dollar_discount ? 'Fixed Price' : 'Total Discount' }}</th>
                    <th style="width: 120px;">MCB Portion of Total Discount</th>
                    <th style="width: 240px;">Who to MCB</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($model->lineItems as $lineItem)
                <tr>
                    <td>{{ $lineItem->item->getPAFStockId() }}</td>
                    <td>{{ $lineItem->item->getPAFUPC() }}</td>
                    <td>{{ $lineItem->item->getPAFBrand() }}</td>
                    <td>{{ $lineItem->item->getPAFDescription() }}</td>
                    <td>{{ $lineItem->total_discount }}</td>
                    <td>{{ $lineItem->total_mcb }}</td>
                    <td>{{ $lineItem->who_to_mcb }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
