@if(Config::get('app.name') == 'Zone1Remodeling')
    @include('auth.Zone.verifyZone')
@else
    @include('auth.Allied.verifyAllied')
@endif
