@component('mail::message')

<x-mail.header :header="$header" />

@isset($textDetails)
  <div style="font-weight:500;display:flex;justify-content:center;">
    <p style="padding:4px">{{ $textDetails }}</p>
  </div>
@endisset

@component('mail::button', ['url' => $url, 'color' => 'green'])
Download
@endcomponent

@include('mail.footer')
@endcomponent
