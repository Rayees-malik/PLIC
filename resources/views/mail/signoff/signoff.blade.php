@component('mail::message')

<x-mail.header :header="$header" />

@isset($textDetails)
  <div style="font-weight:500;display:flex;justify-content:center;">
    <p style="padding:4px">{{ $textDetails }}</p>
  </div>
@endisset

<div style="display:flex;justify-content:center;">
@component('mail::table')
| | |
| -- | -- |
@foreach ($summaryData as $display => $value)
| **{{ $display }}**&nbsp; | {{ $value }} |
@endforeach
@endcomponent
</div>

@component('mail::button', ['url' => $url, 'color' => 'green'])
View
@endcomponent

<div style="font-weight:300;display:flex;justify-content:center;">
  <p style="padding:4px">Ability to view is dependent on your access within PLIC</p>
</div>


@include('mail.footer')
@endcomponent
