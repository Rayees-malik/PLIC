@component('mail::message')

**Purity Life Information Centre - Vendor Login / Password**

Below is your Purity Life Information Centre login / password. Please take note that your password is case sensitive.

Login: {{ $user->name }}
Password: {{ $password }}

@component('mail::button', ['url' => route('login'), 'color' => 'blue'])
Login
@endcomponent

@include('mail.footer')
@endcomponent
