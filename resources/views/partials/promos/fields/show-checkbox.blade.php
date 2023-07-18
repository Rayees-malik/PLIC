<td>
    @foreach (Arr::wrap(Arr::get($product->getPromoLineItem($model->period_id)->data, $field)) as $item)
    @if ($item)
    @if (!$loop->first)<br>@endif{{ $item ?? '-' }}
    @endif
    @endforeach
</td>
