<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('page', 'PLIC') - Purity Life Information Centre</title>

    @if(app()->environment('production'))
    <link rel="icon" type="image/x-icon" href="{{ url('favicon.ico') }}">
    @else
    <link rel="icon" type="image/x-icon" href="{{ url('favicon-dev.ico') }}">
    @endif

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Ubuntu:300,400,500,700" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Oswald:wght@400;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" />

    <script type="text/javascript">
        Honeybadger.configure({
            apiKey: '{{ config('honeybadger.api_js_key') }}',
            developmentEnvironments: ['development'],
            @if (in_array(config('app.env'), ['production', 'development']))
            reportData: true,
            @endif
            environment: '{{ config('app.env') }}'
        });

        @auth
            Honeybadger.setContext({
                user_id: {{ auth()->user()->id ?? null }},
                user_email: '{{ auth()->user()->email ?? null }}'
            });
        @endauth
    </script>

    <!-- Styles -->
    <link href="{{ mix('css/tailwind.css') }}" rel="stylesheet">
    <link href="{{ mix('css/vendor.css') }}" rel="stylesheet">
    <link href="{{ mix('css/styles.css') }}" rel="stylesheet">

    @livewireStyles

    @stack('styles')
</head>

<body>
    @include('layouts.header')
    <br><br>
    <br><br>
    <x-notification />
    <main id="app">
        @include('layouts.flash')
        @yield('content')
    </main>

    <br><br>

    <!-- Scripts -->
    @livewireScripts

    <script type="text/javascript" src="{{ mix('js/app.js') }}"></script>
    <script type="text/javascript" src="{{ asset('vendor/datatables/buttons.server-side.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/chosen.jquery.min.js') }}"></script>

    @stack('scripts')

    @yield('footer')
</body>

</html>
