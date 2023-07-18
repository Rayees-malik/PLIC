@component('mail::message')

The following case stack deals have been created or updated for {{ $brand->name }}.

Period: { Period ID }
Entered By: { User }
Deal: { Case Stack Deal Name }
Deal (FR): { Case Stack Deal Name (French) }

@include('mail.footer')
@endcomponent
