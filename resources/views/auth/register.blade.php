@if(Config::get('app.name') == 'Zone1Remodeling')
    @include('auth.Zone.registerZone')
@else
    @include('auth.Allied.registerAllied')
@endif
