@include('layouts.headerBuyer')

@if(strpos(Request::url(), "Seller") == true)
    <div id="app">
        <div class="sidebar sideBarLarge" data-color="danger" data-background-color="white" data-image="">
            <div class="logo">
                <a href="{{ route('home') }}" class="simple-text logo-normal">
                    @if(Config::get('app.name') == 'Zone1Remodeling')
                        <img src="{{ asset('images/zone1logo.svg') }}" width="200px" />
                    @else
                        <img src="{{ asset('images/Allied/logoBlack.png') }}" width="200px" />
                    @endif
                </a>
            </div>



            @if( empty($permission_users) || in_array('25-2', $permission_users) || Auth::user()->role_id > 2 )
                <input type="hidden" id="permission_users" value="upTo30">
            @endif

            <div class="sidebar-wrapper ps-container ps-theme-default" data-ps-id="edd1ec71-fa12-9cf9-4297-1717346832d3">
                <ul class="nav">
                    @if (Auth::user()->role_id == 7)
                        <li class="nav-item @if( Route::is('RevShareSellerHome') ) active @endif">
                            <a class="nav-link" href="{{ route('RevShareSellerHome') }}">
                                <i class="fa fa-th-large"></i>
                                <p>Dashboard</p>
                            </a>
                        </li>
                        <li class="nav-item @if( Route::is('ListOfLeadsRevShareSeller') ) active @endif ">
                            <a class="nav-link" href="{{ route('ListOfLeadsRevShareSeller') }}">
                                <i class="fa fa-user" aria-hidden="true"></i>
                                <p>List Of Leads</p>
                            </a>
                        </li>
                    @else
                        <li class="nav-item @if( Route::is('SellerHome') ) active @endif">
                            <a class="nav-link" href="{{ route('SellerHome') }}">
                                <i class="fa fa-th-large"></i>
                                <p>Dashboard</p>
                            </a>
                        </li>
                        <li class="nav-item @if( Route::is('ListOfLeadsSellerSection') ) active @endif ">
                            <a class="nav-link" href="{{ route('ListOfLeadsSellerSection') }}">
                                <i class="fa fa-user" aria-hidden="true"></i>
                                <p>List Of Leads</p>
                            </a>
                        </li>
                    @endif
                </ul>
                <div class="ps-scrollbar-x-rail" style="left: 0px; bottom: 0px;">
                    <div class="ps-scrollbar-x" tabindex="0" style="left: 0px; width: 0px;"></div>
                </div>
                <div class="ps-scrollbar-y-rail" style="top: 0px; height: 335px; right: 0px;">
                    <div class="ps-scrollbar-y" tabindex="0" style="top: 36px; height: 217px;"></div>
                </div>
            </div>
            <div class="sidebar-background" style=""></div>
        </div>
        <main class="main-panel ps-container ps-theme-default ps-active-y">
            <!-- Navbar -->
            <nav class="navbar navbar-expand-lg navbar-transparent navbar-absolute fixed-top navbarBigScreeens">
                <div class="container-fluid mx-0">
                    @if (Auth::user()->role_id == 4)
                        <div class="navbar-wrapper sellerLinkCont">
                            <a class="navbar-brand" href="{{ route('BuyerHome') }}">Buyer Section</a>
                        </div>
                    @endif
                    <button class="navbar-toggler" type="button" data-toggle="collapse" aria-controls="navigation-index" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="navbar-toggler-icon icon-bar"></span>
                        <span class="navbar-toggler-icon icon-bar"></span>
                        <span class="navbar-toggler-icon icon-bar"></span>
                    </button>
                    <div class="collapse navbar-collapse justify-content-end nameNavSec">
                        <ul class="navbar-nav">
                            <li class="nav-item">
                                <div class="col-4 col-md-2 col-sm-4">
                                    <a id="navbarDropdown" class="nav-link dropdown-toggle userNavBar" href="#" role="button"
                                       data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre
                                       style="color:inherit;">
                                        <b>{{ Auth::user()->username }}</b> <span class="caret"></span>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-right usersDropDown" aria-labelledby="navbarDropdown">
                                        <a class="dropdown-item" href="{{ route('UserProfileSeller') }}">Profile Setting</a>
                                        <a class="dropdown-item" href="{{ route('logout') }}"
                                           onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                            {{ __('Logout') }}
                                        </a>
                                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                            @csrf
                                        </form>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>
            <nav class="navbar navbar-expand-lg navbar-light bg-light navbarMobile" >
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarTogglerDemo01" aria-controls="navbarTogglerDemo01" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                @if (Auth::user()->role_id == 4)
                    <ul class="navbar-nav mr-auto mt-2 mt-lg-0">
                        <li class="nav-item active">
                            <a class="nav-link" href="{{ route('BuyerHome') }}">Buyer Section</a>
                        </li>
                    </ul>
                @endif
                <div class="collapse navbar-collapse" id="navbarTogglerDemo01">
                    <div class="logo">
                        <a href="{{ route('home') }}" class="simple-text logo-normal">
                            @if(Config::get('app.name') == 'Zone1Remodeling')
                                <img src="{{ asset('images/zone1logo.svg') }}" width="200px" />
                            @else
                                <img src="{{ asset('images/Allied/logoBlack.png') }}" width="200px" />
                            @endif
                        </a>
                    </div>
                    <div class="sidebar-wrapper ps-container ps-theme-default" data-ps-id="edd1ec71-fa12-9cf9-4297-1717346832d3">
                        <ul class="nav">
                            @if (Auth::user()->role_id == 7)
                                <li class="nav-item @if( Route::is('RevShareSellerHome') ) active @endif">
                                    <a class="nav-link" href="{{ route('RevShareSellerHome') }}">
                                        <i class="fa fa-th-large"></i>
                                        <p>Dashboard</p>
                                    </a>
                                </li>
                                <li class="nav-item @if( Route::is('ListOfLeadsRevShareSeller') ) active @endif ">
                                    <a class="nav-link" href="{{ route('ListOfLeadsRevShareSeller') }}">
                                        <i class="fa fa-user" aria-hidden="true"></i>
                                        <p>List Of Leads</p>
                                    </a>
                                </li>
                            @else
                                <li class="nav-item @if( Route::is('SellerHome') ) active @endif">
                                    <a class="nav-link" href="{{ route('SellerHome') }}">
                                        <i class="fa fa-th-large"></i>
                                        <p>Dashboard</p>
                                    </a>
                                </li>
                                <li class="nav-item @if( Route::is('ListOfLeadsSellerSection') ) active @endif ">
                                    <a class="nav-link" href="{{ route('ListOfLeadsSellerSection') }}">
                                        <i class="fa fa-user" aria-hidden="true"></i>
                                        <p>List Of Leads</p>
                                    </a>
                                </li>
                            @endif
                            <li class="nav-item @if( Route::is('UserProfileSeller') ) active @endif">
                                <a class="nav-link" href="{{ route('UserProfileSeller') }}">
                                    <i class="fa fa-user" aria-hidden="true"></i>
                                    <p>Profile Setting</p>
                                </a>
                            </li>
                            <li class="nav-item @if( Route::is('logout') ) active @endif">
                                <a class="nav-link" href="{{ route('logout') }}"
                                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    <i class="fa fa-minus-circle" aria-hidden="true"></i>
                                    <p>{{ __('Logout') }}</p>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>
            <div class="content">
                @yield('content')
            </div>
        </main>
        <div id="overlayDivSB"></div>
    </div>
@else
    <div id="app">
        <div class="sidebar sidebar-large sideBarLarge" data-color="danger" data-background-color="white" data-image="">
            <div class="logo">
                <a href="{{ route('home') }}" class="simple-text logo-normal">
                    @if(Config::get('app.name') == 'Zone1Remodeling')
                        <img src="{{ asset('images/zone1logo.svg') }}" width="200px" />
                    @else
                        <img src="{{ asset('images/Allied/logoBlack.png') }}" width="200px" />
                    @endif
                </a>
            </div>

            @if( empty($permission_users) || in_array('25-2', $permission_users) || Auth::user()->role_id > 2 )
                <input type="hidden" id="permission_users" value="upTo30">
            @endif
            <div class="sidebar-wrapper ps-container ps-theme-default" data-ps-id="edd1ec71-fa12-9cf9-4297-1717346832d3">
                <ul class="nav">
                    <li class="nav-item @if( Route::is('BuyerHome') ) active @endif">
                        <a class="nav-link" href="{{ route('BuyerHome') }}">
                            <i class="fa fa-th-large"></i>
                            <p>Dashboard</p>
                        </a>
                    </li>
                    <li class="nav-item @if( Route::is('Campaign_List') ) active @endif">
                        <a class="nav-link" href="{{ route('Campaign_List') }}" >
                            <i class="fa fa-building-o" aria-hidden="true"></i>
                            <p>List Of Campaigns</p>
                        </a>
                    </li>
                    <li class="nav-item @if( Route::is('Campaign_service') ) active @endif">
                        <a class="nav-link" href="{{ route('Campaign_service') }}">
                            <i class="fa fa-plus" aria-hidden="true"></i>
                            <p>Create Campaigns</p>
                        </a>
                    </li>
                    <li class="nav-item @if( Route::is('ListOfLeadsBuyersSection') ) active @endif ">
                        <a class="nav-link" href="{{ route('ListOfLeadsBuyersSection') }}">
                            <i class="fa fa-user" aria-hidden="true"></i>
                            <p>List Of Leads</p>
                        </a>
                    </li>
                    <li class="nav-item @if( Route::is('ReturnLeadBuyer') ) active @endif">
                        <a class="nav-link" href="{{ route('ReturnLeadBuyer') }}">
                            <i class="fa fa-user-times" aria-hidden="true"></i>
                            <p>List Of Returned Leads</p>
                        </a>
                    </li>
                    <li class="nav-item @if( Route::is('Buyers.ListOfClickLeads') ) active @endif ">
                        <a class="nav-link" href="{{ route('Buyers.ListOfClickLeads') }}">
                            <i class="fa fa-hand-pointer-o" aria-hidden="true"></i>
                            <p>Number Of Click Leads</p>
                        </a>
                    </li>
                    <li class="nav-item @if( Route::is('BuyersPayment') ) active @endif">
                        <a class="nav-link" href="{{ route('BuyersPayment') }}">
                            <i class="fa fa-credit-card" aria-hidden="true"></i>
                            <p>Wallet</p>
                        </a>
                    </li>
                    <li class="nav-item @if( Route::is('AddValuePayment') ) active @endif">
                        <a class="nav-link" href="{{ route('AddValuePayment') }}">
                            <i class="fa fa-money" aria-hidden="true"></i>
                            <p>Payment</p>
                        </a>
                    </li>
                    <li class="nav-item @if( Route::is('Transaction.index') ) active @endif">
                        <a class="nav-link" href="{{ route('Transaction.index') }}">
                            <i class="fa fa-refresh" aria-hidden="true"></i>
                            <p>Transaction</p>
                        </a>
                    </li>
                    <li class="nav-item @if( Route::is('buyersTicket') ) active @endif">
                        <a class="nav-link" href="{{ route('buyersTicket') }}">
                            <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                            <p>Ticket</p>
                        </a>
                    </li>
                </ul>
                <div class="ps-scrollbar-x-rail" style="left: 0px; bottom: 0px;">
                    <div class="ps-scrollbar-x" tabindex="0" style="left: 0px; width: 0px;"></div>
                </div>
                <div class="ps-scrollbar-y-rail" style="top: 0px; height: 335px; right: 0px;">
                    <div class="ps-scrollbar-y" tabindex="0" style="top: 36px; height: 217px;"></div>
                </div>
            </div>
        </div>
        <main class="main-panel ps-container ps-theme-default ps-active-y ">
            <!-- Navbar -->
            <nav class="navbar navbar-expand-lg navbar-transparent navbar-absolute fixed-top navbar-large navbarBigScreeens">
                <div class="container-fluid mx-0">
                    @if (Auth::user()->role_id == 4)
                        <div class="navbar-wrapper sellerLinkCont">
                            <a class="navbar-brand" href="{{ route('SellerHome') }}">Seller Section</a>
                        </div>
                    @endif
                    <div class="collapse navbar-collapse justify-content-end nameNavSec">
                        <ul class="navbar-nav">
                            <li class="nav-item">
                                <div class="col-4 col-md-2 col-sm-4">
                                    <a id="navbarDropdown" class="nav-link dropdown-toggle userNavBar" href="#" role="button"
                                       data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre
                                       style="color:inherit;">
                                        <b>{{ Auth::user()->username }}</b> <span class="caret"></span>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-right usersDropDown" aria-labelledby="navbarDropdown">
                                        <a class="dropdown-item" href="{{ route('UserProfileBuyer') }}">Profile Setting</a>
                                        <a class="dropdown-item" href="{{ route('logout') }}"
                                           onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                            {{ __('Logout') }}
                                        </a>
                                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                            @csrf
                                        </form>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>
            <nav class="navbar navbar-expand-lg navbar-light bg-light navbarMobile" >
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarTogglerDemo01" aria-controls="navbarTogglerDemo01" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                @if (Auth::user()->role_id == 4)
                    <ul class="navbar-nav mr-auto mt-2 mt-lg-0">
                        <li class="nav-item active">
                            <a class="nav-link" href="{{ route('SellerHome') }}">Seller Section</a>
                        </li>
                    </ul>
                @endif
                <div class="collapse navbar-collapse" id="navbarTogglerDemo01">
                    <div class="logo">
                        <a href="{{ route('home') }}" class="simple-text logo-normal">
                            @if(Config::get('app.name') == 'Zone1Remodeling')
                                <img src="{{ asset('images/zone1logo.svg') }}" width="200px" />
                            @else
                                <img src="{{ asset('images/Allied/logoBlack.png') }}" width="200px" />
                            @endif
                        </a>
                    </div>

                    <div class="sidebar-wrapper ps-container ps-theme-default" data-ps-id="edd1ec71-fa12-9cf9-4297-1717346832d3">
                        <ul class="nav">
                            <li class="nav-item @if( Route::is('BuyerHome') ) active @endif">
                                <a class="nav-link" href="{{ route('BuyerHome') }}">
                                    <i class="fa fa-th-large"></i>
                                    <p>Dashboard</p>
                                </a>
                            </li>
                            <li class="nav-item @if( Route::is('Campaign_List') ) active @endif">
                                <a class="nav-link" href="{{ route('Campaign_List') }}" >
                                    <i class="fa fa-building-o" aria-hidden="true"></i>
                                    <p>List Of Campaigns</p>
                                </a>
                            </li>
                            <li class="nav-item @if( Route::is('Campaign_service') ) active @endif">
                                <a class="nav-link" href="{{ route('Campaign_service') }}">
                                    <i class="fa fa-plus" aria-hidden="true"></i>
                                    <p>Create Campaigns</p>
                                </a>
                            </li>
                            <li class="nav-item @if( Route::is('ListOfLeadsBuyersSection') ) active @endif ">
                                <a class="nav-link" href="{{ route('ListOfLeadsBuyersSection') }}">
                                    <i class="fa fa-user" aria-hidden="true"></i>
                                    <p>List Of Leads</p>
                                </a>
                            </li>
                            <li class="nav-item @if( Route::is('ReturnLeadBuyer') ) active @endif">
                                <a class="nav-link" href="{{ route('ReturnLeadBuyer') }}">
                                    <i class="fa fa-user-times" aria-hidden="true"></i>
                                    <p>List Of Returned Leads</p>
                                </a>
                            </li>
                            <li class="nav-item @if( Route::is('Buyers.ListOfClickLeads') ) active @endif">
                                <a class="nav-link" href="{{ route('Buyers.ListOfClickLeads') }}">
                                    <i class="fa fa-hand-pointer-o" aria-hidden="true"></i>
                                    <p>Number Of Click Leads</p>
                                </a>
                            </li>
                            <li class="nav-item @if( Route::is('BuyersPayment') ) active @endif">
                                <a class="nav-link" href="{{ route('BuyersPayment') }}">
                                    <i class="fa fa-credit-card" aria-hidden="true"></i>
                                    <p>Wallet</p>
                                </a>
                            </li>
                            <li class="nav-item @if( Route::is('AddValuePayment') ) active @endif">
                                <a class="nav-link" href="{{ route('AddValuePayment') }}">
                                    <i class="fa fa-money" aria-hidden="true"></i>
                                    <p>Payment</p>
                                </a>
                            </li>
                            <li class="nav-item @if( Route::is('Transaction.index') ) active @endif">
                                <a class="nav-link" href="{{ route('Transaction.index') }}">
                                    <i class="fa fa-refresh" aria-hidden="true"></i>
                                    <p>Transaction</p>
                                </a>
                            </li>
                            <li class="nav-item @if( Route::is('buyersTicket') ) active @endif">
                                <a class="nav-link" href="{{ route('buyersTicket') }}">
                                    <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                                    <p>Ticket</p>
                                </a>
                            </li>
                            <li class="nav-item @if( Route::is('UserProfileBuyer') ) active @endif">
                                <a class="nav-link" href="{{ route('UserProfileBuyer') }}">
                                    <i class="fa fa-user" aria-hidden="true"></i>
                                    <p>Profile Setting</p>
                                </a>
                            </li>
                            <li class="nav-item @if( Route::is('logout') ) active @endif">
                                <a class="nav-link" href="{{ route('logout') }}"
                                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    <i class="fa fa-minus-circle" aria-hidden="true"></i>
                                    <p>{{ __('Logout') }}</p>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>

            <div class="content">
                @yield('content')
            </div>
        </main>
        <div id="overlayDivSB"></div>
    </div>
@endif

@include('layouts.footerBuyer')
