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
    <main class="py-12">
        <div class="container">

            @yield('content')

            <!--Jornaya Script-->
            <script id="LeadiDscript" type="text/javascript">
                (function() {
                    var s = document.createElement('script');
                    s.id = 'LeadiDscript_campaign';
                    s.type = 'text/javascript';
                    s.async = true;
                    s.src = '//create.lidstatic.com/campaign/4c7c15c5-f2b5-1399-b3eb-b0a41189b209.js?snippet_version=2';
                    var LeadiDscript = document.getElementById('LeadiDscript');
                    LeadiDscript.parentNode.insertBefore(s, LeadiDscript);
                })();
            </script>
            <noscript><img src='//create.leadid.com/noscript.gif?lac=27c94b3f-338d-43b6-b881-02bf972941ba&lck=4c7c15c5-f2b5-1399-b3eb-b0a41189b209&snippet_version=2' /></noscript>
            <!--End Jornaya Script-->
        </div>
    </main>
</div>
@include('include.include_js')
@include('include.include_reports')

<!--Start xxTrustedFormCertUrl Script-->
<script type="text/javascript">
    (function() {
            var field = 'xxTrustedFormCertUrl';
            var provideReferrer = false;
            var invertFieldSensitivity = false;
            var tf = document.createElement('script');
            tf.type = 'text/javascript'; tf.async = true;
            tf.src = 'http' + ('https:' == document.location.protocol ? 's' : '') +
                '://api.trustedform.com/trustedform.js?provide_referrer=' + escape(provideReferrer) + '&field=' + escape(field) + '&l='+new Date().getTime()+Math.random() + '&invert_field_sensitivity=' + invertFieldSensitivity;
            var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(tf, s); }
    )();
</script>
<!--End xxTrustedFormCertUrl Script-->
</body>
</html>
