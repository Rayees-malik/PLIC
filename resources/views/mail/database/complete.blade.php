@component('mail::message')

Database update for Kyolic and Moducare has been run.

{{ $moducare ?? 0 }} records have been inserted for Moducare and {{ $kyolic ?? 0 }} records have been inserted for Kyolic.

@include('mail.footer')
@endcomponent
