@if(Config::get('app.name') == 'Zone1Remodeling')
    @include('auth.Zone.emailZone')
@else
    @include('auth.Allied.emailAllied')
@endif
