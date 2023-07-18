@component('mail::message')

An approved {{ strtolower($signoff->proposed->getShortClassName()) }} has been unsubmitted.

@component('mail::table')
| | | |
| --: |---|---|
@foreach ($signoff->getSummaryArray() as $display => $value)
|**{{ $display }}**||{{ $value }}|
@endforeach
@endcomponent

@component('mail::button', ['url' => route('signoffs.show', $signoff->id)])
View {{ $signoff->proposed->getShortClassName() }}
@endcomponent

@include('mail.footer')
@endcomponent
