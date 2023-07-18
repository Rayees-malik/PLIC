@component('mail::message')

**Purity Life - New Vendor Approved / Brand Manager Assigned**

Vendor: {{ $vendor-> name }}

Brand: {{ $brand->name }}

@component('mail::button', ['url' => route('vendors.show', $vendor->id), 'color' => 'blue'])
View
@endcomponent

@include('mail.footer')
@endcomponent
