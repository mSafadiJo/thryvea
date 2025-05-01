@if(Config::get('app.name') == 'Zone1Remodeling')
    @include('Buyers.Zone.HomeSeller')
@else
    @include('Buyers.Allied.HomeSeller')
@endif
