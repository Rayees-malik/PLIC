@component('mail::message')

**Purity Life Information Centre - Promo Cancelled**

Vendor: {{ $brand->name }}
Promo Period:: {{ $promo->period->name }} ({{ $promo->period->start_date->format('m/d/Y') }} - {{ $promo->period->end_date->format('m/d/Y') }})
Cancelled By: {{ optional($signoff->responses)->last()->user->name ?? "" }}
Reason: {{ optional($signoff->responses)->last()->comment ?? "" }}

@include('mail.footer')
@endcomponent
