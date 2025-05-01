@if(Config::get('app.name') == 'Zone1Remodeling')
    @include('Buyers.Zone.HomeBuyer')
@else
    @include('Buyers.Allied.HomeBuyer')
@endif
