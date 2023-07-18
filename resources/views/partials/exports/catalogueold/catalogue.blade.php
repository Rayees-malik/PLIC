<ASCII-WIN>
@foreach ($brands as $brand)
@include('partials.exports.catalogueold.brand')
@foreach ($brand->catalogueCategories as $category)
@if ($category->products->count())
<CharStyle:><ParaStyle:ProductCategory><CharStyle:No Style>{!! $english ? $category->name : $category->getNameFr() !!}
@if ($english ? $category->description : $category->description_fr)
<CharStyle:><ParaStyle:CategoryDescription><CharStyle:No Style>{!! $english ? $category->description : $category->description_fr !!}
@endif
@foreach ($category->products as $product)
{!! $product->catalogueFormatOld($period1, $period2, $startDate, $cutoffStart, $cutoffEnd, $english) !!}
@if ($product->is_display && ($english ? $product->description : $product->description_fr))
<CharStyle:><ParaStyle:ProductDescription><CharStyle:No Style>{!! str_replace("\r\n", "\n", $english ? $product->description : $product->description_fr) !!}
@endif
@endforeach
@endif
@endforeach
@endforeach