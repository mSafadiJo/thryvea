@include('layouts.headerBuyer')

<section class="chooseHomeContent">
    <div class="container">
        <div class="logoContReg">
            <img src="{{asset('images/zone1logo.svg')}}" class="logoLogREG" alt="zone1">
        </div>

        <div class="row">
            <div class="col-sm-12 text-center">
                <div class="topTextLoginBuyer">
                    <h2>Hi {{ Auth::user()->username }}!</h2>
                    <p>Please choose the dashboard you want to go to!</p>
                </div>
            </div>

            <div class="col-sm-12 text-center">
                <div class="topTextLoginBuyer">
                    <a class="btn" href="{{ route('BuyerHome') }}"> Buyer </a>
                    <a class="btn" href="{{ route('SellerHome') }}">  Seller </a>
                </div>
            </div>

        </div>

    </div>

</section>

