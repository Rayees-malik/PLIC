<ASCII-WIN>
@foreach ($brands as $brand)
<ParaStyle:VendorName><CharStyle:No Style>{!! $brand->name !!}
{!! $brand->catalogueCaseStackDealsFormat($period1, $period2, true) !!}
@endforeach