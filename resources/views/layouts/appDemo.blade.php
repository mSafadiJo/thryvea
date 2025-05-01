<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Styles -->
    @include('include.include_css')
</head>
<body>
<div id="app">
    <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <a class="navbar-brand" href="#">
                        {{--{{ config('app.name', 'Laravel') }}--}}
                        <img src="{{ URL::asset('images/logo22.png') }}" width="200px"/>
                    </a>
                </div>
            </div>
        </div>
    </nav>
    @include('include.include_js')
    <main class="py-12">
        <div class="container">
            @yield('content')
        </div>
    </main>
</div>

</body>
</html>
