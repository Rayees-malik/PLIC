@component('mail::message')

<x-mail.header :header="$header" />

@isset($textDetails)
<div style="font-weight:500;display:flex;justify-content:center;">
    <p style="padding:4px">{{ $textDetails }}</p>
</div>
@endisset

@isset($message)
<div style="color:crimson;font-weight:500;display:flex;justify-content:center;">
    <p style="padding:4px">{{ $message }}</p>
</div>
@endisset

@include('mail.footer')
@endcomponent
