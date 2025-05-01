@if(Config::get('app.name') == 'Zone1Remodeling')
    @include('auth.Zone.resetZone')
@else
    @include('auth.Allied.resetAllied')
@endif
