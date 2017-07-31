<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Hiahia') }}</title>

@component("includes.css")
    <!--自定义的CSS-->
    @yield("css")
@endcomponent

<!-- Scripts -->
    <script>
        window.Laravel = {!! json_encode([
            'csrfToken' => csrf_token(),
        ]) !!};
    </script>
</head>
<body>
<div id="app">
    @component("component.nav")
    @endcomponent

    @yield('content')
</div>

<!-- Scripts -->
@component("includes.js")
    <!--自定义的JS-->
    @yield("js")
@endcomponent
</body>
</html>
