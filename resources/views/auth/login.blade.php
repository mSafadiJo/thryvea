@if(Config::get('app.name') == 'Zone1Remodeling')
    @include('auth.Zone.loginZone')
@else
    @include('auth.Allied.loginAllied')
@endif
