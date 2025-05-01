@if(Config::get('app.name') == 'Zone1Remodeling')
    @include('Buyers.Zone.HomeBuyerAndSeller')
@else
    @include('Buyers.Allied.HomeBuyerAndSeller')
@endif
