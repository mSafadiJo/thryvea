<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Styles -->

    @include('include.include_css')
    <link rel="stylesheet" type="text/css"
          href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700|Roboto+Slab:400,700|Material+Icons"/>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css">
    @if(Config::get('app.name') == 'Zone1Remodeling')
        <link href="{{ asset('css/zoneBuyersDashbord.css') }}" rel="stylesheet" type="text/css" />
    @else
        <link href="{{ asset('css/buyersDashbord.css') }}" rel="stylesheet" type="text/css" />
    @endif

</head>
<body>
<section class="formContainer1">
    <div class="formContainer RegFormContainer">
        <div class="container">
            <div class="row">
                <div class="col-sm-12 mx-auto Home marginCont">
                     <div class="formCont text-center formBigScreen buyerSEHomeDiv">
                        <img class='logo-img-buyer' src="{{asset('images/NewLogin/Group265.svg')}}">
                        <h2>Welcome Back {{ Auth::user()->username }}!</h2>
                        <p class="continue">Please Choose The Section You Want To Go To.</p>
        {{--                <p class="continue">Continue As</p>--}}

                            <div class="BuyerButton">
                                <a href="{{ route('BuyerHome') }}">
                                    <img src="{{asset('images/NewLogin/Group275.svg')}}">
                                            <p class="wordInLinkDiv">Buyer Section</p>
        {{--                             <p class="wordInLinkDiv">Buyer</p>--}}
                                </a>
                            </div>

                            <div class="sellerButton">
                                <a href="{{ route('SellerHome') }}">
                                    <img class="seller-img" src="{{asset('images/NewLogin/Group275.svg')}}">
                                        <p class="sellerwordInLinkDiv">Seller Section</p>
        {{--                            <p class="sellerwordInLinkDiv">Seller</p>--}}
                                </a>
                            </div>
                        <a class="dropdown-item logoutHomeBS" href="{{ route('logout') }}"
                           onclick="event.preventDefault();
                                            document.getElementById('logout-form').submit();">
                            {{ __('Logout') }}
                        </a>

                        <form id="logout-form" action="{{ route('logout') }}" method="POST"
                              style="display: none;">
                            @csrf
                        </form>
                     </div>
                </div>
            </div>
        </div>
    </div>
</section>

</body>
</html>
