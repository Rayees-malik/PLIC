<ParaStyle:VendorName><CharStyle:No Style>{!! $brand->name !!}{!!
  $brand->made_in_canada ? ' <CharStyle:Leaf>W<CharStyle:No Style>' : ''
!!}{!!
  $brand->education_portal ? ' <cTypeface:Color><cLigatures:0><cCase:Normal><cFont:EmojiOne><cOTFContAlt:0><0xD83C><0xDF93><cLigatures:><cCase:><cFont:><cOTFContAlt:><CharStyle:><CharStyle:No Style>' : '';
!!}{!!
  $brand->grocery_count ? ' <cTypeface:Color><cLigatures:0><cCase:Normal><cFont:EmojiOne><cOTFContAlt:0><0xD83D><0xDED2><cLigatures:><cCase:><cFont:><cOTFContAlt:><CharStyle:><CharStyle:No Style>' : '';
!!}
@if (!$newOnly)
@if ($brand->brokers->count())
<CharStyle:><ParaStyle:BROKERED BY_final><CharStyle:No Style>{!! $english ? 'BROKERED BY' : 'COURTIER ASSIGNÉ' !!}{!! "\t" !!}{!! implode(', ', $brand->brokers->pluck('name')->toArray()) !!}
@endif
@if ($brand->website || $brand->phone)
<CharStyle:><ParaStyle:VendorWeb><CharStyle:No Style>{!! $brand->website !!}{!! "\t" !!}{!! $brand->phone !!}
@endif
@if ($english ? $brand->description : $brand->description_fr)
<CharStyle:><ParaStyle:VendorDescription><CharStyle:No Style>{!! $english ? $brand->description : $brand->description_fr !!}{!! $brand->map_pricing ? '*MAP Policy Applies*' : '' !!}
@endif
@if ($brand->catalogue_notice)
<CharStyle:><ParaStyle:VendorNotice><CharStyle:No Style>{!! $brand->catalogue_notice !!}
@endif
@if ($brand->saveUpTo)
{!! $brand->saveUpTo !!}
@endif
@if ($brand->caseStackDeals->count())
{!! $brand->catalogueCaseStackDealsFormat($period1, $period2, $english) !!}
@endif
@endif