<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>
    <!-- Styles -->
    @include('include.include_css')
</head>
<body>

<!-- Begin page -->
<div id="wrapper">

    <!-- Top Bar Start -->
    <div class="topbar">

        <!-- LOGO -->
        <div class="topbar-left">
            <a class="navbar-brand" href="{{ url('/') }}">
                {{--{{ config('app.name', 'Laravel') }}--}}

                @if(Config::get('app.name') == 'Zone1Remodeling')
                    <img src="{{ URL::asset('images/zone1iconblack.svg') }}" style="width: 45%;"/>
                @else
                    <img src="{{ URL::asset('images/Allied/ArtboardBalck.png') }}" width="200px"/>
                @endif
            </a>
        </div>

        <nav class="navbar-custom">
            <ul class="list-inline menu-left mb-0">
                <a id="navbarDropdown" class="nav-link dropdown-toggle pull-right" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre style="color:inherit;">
                    <b>{{ Auth::user()->username }}</b> <i class="dripicons-chevron-down"></i>
                </a>

                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                    <a class="dropdown-item" href="{{ route('AdminProfile') }}">Profile Setting</a>
                    <a class="dropdown-item" href="{{ route('logout') }}"
                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                        {{ __('Logout') }}
                    </a>

                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                </div>
            </ul>
        </nav>

    </div>
    <!-- Top Bar End -->


    @if( empty($permission_users) || in_array('25-2', $permission_users) || Auth::user()->role_id > 2 )
        <input type="hidden" id="permission_users" value="upTo30">
    @endif

    <!-- ========== Left Sidebar Start ========== -->
    <div class="left side-menu">
        <div class="slimscroll-menu" id="remove-scroll">
        @php
            $permission_users = array();
            if( !empty(Auth::user()->permission_users) ){
                $permission_users = json_decode(Auth::user()->permission_users, true);
            }
        @endphp
        <!--- Sidemenu -->
            <div id="sidebar-menu">
                <!-- Left Menu Start -->
                <ul class="metismenu" id="side-menu">
                    @if( empty($permission_users) || in_array('25-0', $permission_users) )
                        <li>
                            <a href="javascript: void(0);">
                                {{--<i class="fi-target"></i>--}}
                                <img class="menuLogo_svg" src="{{ URL::asset('images/menuLogo/svg/setting.svg') }}">
                                <span> Settings </span> <span class="menu-arrow"></span>
                            </a>
                            <ul class="nav-second-level" aria-expanded="false">
                                @if( empty($permission_users) || in_array('25-1', $permission_users) )
                                    <li><a href="{{ route('Admin.Setting.Payment') }}">Payment Methods</a></li>
                                @endif
                            </ul>
                        </li>
                    @endif
{{--                    @if( empty($permission_users) || in_array('2-0', $permission_users) )--}}
{{--                        <li>--}}
{{--                            <a href="javascript: void(0);">--}}
{{--                                --}}{{--<i class="fi-target"></i>--}}
{{--                                <img class="menuLogo_svg" src="{{ URL::asset('images/menuLogo/svg/promocode.svg') }}">--}}
{{--                                <span> Promotional Codes </span> <span class="menu-arrow"></span>--}}
{{--                            </a>--}}
{{--                            <ul class="nav-second-level" aria-expanded="false">--}}
{{--                                <li><a href="{{ route('PromoCode.index') }}">List Of Promotional Codes</a></li>--}}
{{--                                @if( empty($permission_users) || in_array('2-1', $permission_users) )--}}
{{--                                    <li><a href="{{ route('PromoCode.create') }}">Add Promotional Codes</a></li>--}}
{{--                                @endif--}}
{{--                            </ul>--}}
{{--                        </li>--}}
{{--                    @endif--}}
                    @if( empty($permission_users) || in_array('3-0', $permission_users) )
                        <li>
                            <a href="javascript: void(0);">
                                {{--<i class="fi-target"></i>--}}
                                <img class="menuLogo_svg" src="{{ URL::asset('images/menuLogo/svg/reports.jpeg') }}">
                                <span> Reports </span> <span class="menu-arrow"></span>
                            </a>
                            <ul class="nav-second-level" aria-expanded="false">
                                @if( empty($permission_users) || in_array('3-12', $permission_users) )
                                    <li><a href="{{ route('Admin.Report.AccountManagerReport') }}">Account Manager Reports</a></li>
                                @endif
                                @if( empty($permission_users) || in_array('3-14', $permission_users) )
                                    <li><a href="{{ route('FilterZipCodeByServiceShow') }}">Active ZipCodes</a></li>
                                @endif
                                @if( empty($permission_users) || in_array('3-22', $permission_users) )
                                    <li><a href="{{ route('AffiliateReport') }}">Affiliate Report</a></li>
                                @endif
                                @if( empty($permission_users) || in_array('3-23', $permission_users) )
                                    <li><a href="{{ route('AgentsCallCenter') }}">Agents CallCenter Report</a></li>
                                @endif
                                @if( empty($permission_users) || in_array('3-27', $permission_users) )
                                    <li><a href="{{ route('BuyersLocationReport.index') }}">Buyers Location Report</a></li>
                                @endif
                                @if( empty($permission_users) || in_array('3-18', $permission_users) )
                                    <li><a href="{{ route('mapBuyer') }}">Buyers Map</a></li>
                                @endif
                                @if( empty($permission_users) || in_array('3-25', $permission_users) )
                                    <li><a href="{{ route('Reports.callCenterProfit') }}">Call Center Profit Report</a></li>
                                @endif
                                @if( empty($permission_users) || in_array('3-24', $permission_users) )
                                    <li><a href="{{ route('Admin.CallCenter.Dashboard') }}">CallCenter Target Dashboard</a></li>
                                @endif
                                @if( empty($permission_users) || in_array('3-13', $permission_users) )
                                    <li><a href="{{ route('FilterCampaignByZipCodeAndService') }}">Campaigns By Zipcode</a></li>
                                @endif
                                @if( empty($permission_users) || in_array('3-16', $permission_users) )
                                    <li><a href="{{ route('FilterCRMShow') }}">CRM Responses</a></li>
                                @endif
                                @if( empty($permission_users) || in_array('3-17', $permission_users) )
                                    <li><a href="{{ route('map') }}">Lead Map</a></li>
                                @endif
                                @if( empty($permission_users) || in_array('3-5', $permission_users) )
                                    <li><a href="{{ route('Admin.Report.lead_report') }}">Lead Report</a></li>
                                @endif
                                @if( empty($permission_users) || in_array('3-6', $permission_users) )
                                    <li><a href="{{ route('Admin.Report.lead_volume') }}">Lead Volume</a></li>
                                @endif
                                {{--@if( empty($permission_users) || in_array('3-15', $permission_users) )--}}
                                {{--    <li><a href="{{ route('listLostLeadReportShow') }}">Lost Leads Report</a></li>--}}
                                {{--@endif--}}
                                @if( empty($permission_users) || in_array('3-28', $permission_users) )
                                    <li><a href="{{ route('marketingCost') }}">Marketing Cost</a></li>
                                @endif
                                @if( empty($permission_users) || in_array('3-8', $permission_users) )
                                    <li><a href="{{ route('Admin.Report.lead_from_websites') }}">Marketing Reports</a></li>
                                @endif
                                @if( empty($permission_users) || in_array('3-26', $permission_users) )
                                    <li><a href="{{ route('payPerClickReport') }}">PayPerClick Campaign Report</a></li>
                                @endif
                                @if( empty($permission_users) || in_array('3-7', $permission_users) )
                                    <li><a href="{{ route('Admin.Report.performance_reports') }}">Performance Reports</a></li>
                                @endif
                                @if( empty($permission_users) || in_array('3-19', $permission_users) )
                                    <li><a href="{{ route('Reports.salesCommission') }}">Sales Commission</a></li>
                                @endif
                                @if( empty($permission_users) || in_array('3-11', $permission_users) )
                                    <li><a href="{{ route('Admin.Report.SalesReport') }}">Sales Reports</a></li>
                                @endif
                                @if( empty($permission_users) || in_array('3-20', $permission_users) )
                                    <li><a href="{{ route('Admin.Sales.Dashboard') }}">Sales/Transfers Dashboard</a></li>
                                @endif
                                @if( empty($permission_users) || in_array('3-10', $permission_users) )
                                    <li><a href="{{ route('Admin.Report.SDRReport') }}">SDR Reports</a></li>
                                @endif
                                @if( empty($permission_users) || in_array('3-9', $permission_users) )
                                    <li><a href="{{ route('Admin.Reports.Seller_Lead_Volume') }}">Seller Lead Volume</a></li>
                                @endif
                                @if( empty($permission_users) || in_array('3-21', $permission_users) )
                                    <li><a href="{{ route('Reports.users_payments') }}">Users Payments</a></li>
                                @endif
                            </ul>
                        </li>
                    @endif
                    @if( empty($permission_users) || in_array('4-0', $permission_users) )
                        <li>
                            <a href="javascript: void(0);">
                                {{--<i class="fa fa-user"></i>--}}
                                <img class="menuLogo_svg" src="{{ URL::asset('images/menuLogo/svg/manager.svg') }}">
                                <span> Admins </span> <span class="menu-arrow"></span>
                            </a>
                            <ul class="nav-second-level" aria-expanded="false">
                                <li><a href="{{ route('AdminManagment.index') }}">List Of Admins</a></li>
                                @if( empty($permission_users) || in_array('4-1', $permission_users) )
                                    <li><a href="{{ route('AdminManagment.create') }}">Add Admins</a></li>
                                @endif
                            </ul>
                        </li>
                    @endif
                    @if( empty($permission_users) || in_array('17-0', $permission_users) )
                        <li>
                            <a href="{{ route('Admin.JoinAsAPro.List') }}">
                                <img class="menuLogo_svg" src="{{ URL::asset('images/menuLogo/svg/team.svg') }}">
                                <span> Contractors </span>
                            </a>
                        </li>
                    @endif
{{--                    @if( empty($permission_users) || in_array('14-0', $permission_users) )--}}
{{--                        <li>--}}
{{--                            <a href="javascript: void(0);">--}}
{{--                                <img class="menuLogo_svg" src="{{ URL::asset('images/menuLogo/svg/team.svg') }}">--}}
{{--                                <span> Prospects </span> <span class="menu-arrow"></span>--}}
{{--                            </a>--}}
{{--                            <ul class="nav-second-level" aria-expanded="false">--}}
{{--                                <li><a href="{{ route('Prospects.index') }}">List Of Prospects</a></li>--}}
{{--                                @if( empty($permission_users) || in_array('14-1', $permission_users) )--}}
{{--                                    <li><a href="{{ route('Prospects.create') }}">Add Prospects</a></li>--}}
{{--                                @endif--}}
{{--                            </ul>--}}
{{--                        </li>--}}
{{--                    @endif--}}
                    @if( empty($permission_users) || in_array('5-0', $permission_users) )
                        <li>
                            <a href="javascript: void(0);">
                                {{--<i class="fa fa-users"></i> --}}
                                <img class="menuLogo_svg" src="{{ URL::asset('images/menuLogo/svg/team.svg') }}">
                                <span> Buyers </span> <span class="menu-arrow"></span>
                            </a>
                            <ul class="nav-second-level" aria-expanded="false">
                                <li><a href="{{ route('Admin_Buyers') }}">List Of Buyers</a></li>
                                @if( empty($permission_users) || in_array('5-1', $permission_users) )
                                    <li><a href="{{ route('Admin_BuyersAdd') }}">Add Buyers</a></li>
                                @endif
                                <li><a href="{{ route('Old_Buyers') }}">List Of Old Buyers</a></li>
                            </ul>
                        </li>
                    @endif
                    @if( empty($permission_users) || in_array('23-0', $permission_users) )
                        <li>
                            <a href="javascript: void(0);">
                                {{--<i class="fa fa-users"></i> --}}
                                <img class="menuLogo_svg" src="{{ URL::asset('images/menuLogo/svg/team.svg') }}">
                                <span> Sellers </span> <span class="menu-arrow"></span>
                            </a>
                            <ul class="nav-second-level" aria-expanded="false">
                                <li><a href="{{ route('ListOfSeller') }}">List Of Sellers</a></li>
                            </ul>
                        </li>
                    @endif
                    @if( empty($permission_users) || in_array('7-0', $permission_users) )
                        <li>
                            <a href="javascript: void(0);">
                                {{--<i class="fa fa-database"></i> --}}
                                <img class="menuLogo_svg" src="{{ URL::asset('images/menuLogo/svg/speaker.svg') }}">
                                <span> Buyers Campaigns </span> <span class="menu-arrow"></span>
                            </a>
                            <ul class="nav-second-level" aria-expanded="false">
                                <li><a href="{{ route('Admin_Campaign') }}">List Of Campaigns</a></li>
                                @if( empty($permission_users) || in_array('7-1', $permission_users) )
                                    <li><a href="{{ route('Admin.Campaign.Create') }}">Add Campaigns</a></li>
                                @endif
                            </ul>
                        </li>
                    @endif

                    @if( empty($permission_users) || in_array('12-0', $permission_users) )
                        <li>
                            <a href="javascript: void(0);">
                                {{--<i class="fa fa-database"></i> --}}
                                <img class="menuLogo_svg" src="{{ URL::asset('images/menuLogo/svg/speaker.svg') }}">
                                <span> Sellers Campaigns </span> <span class="menu-arrow"></span>
                            </a>
                            <ul class="nav-second-level" aria-expanded="false">
                                <li><a href="{{ route('Campaigns.index') }}">List Of Campaigns</a></li>
                                @if( empty($permission_users) || in_array('12-1', $permission_users) )
                                    <li><a href="{{ route('Campaigns.create') }}">Add Campaigns</a></li>
                                @endif
                            </ul>
                        </li>
                    @endif
                    @if( empty($permission_users) || in_array('6-0', $permission_users) )
                        <li>
                            <a href="javascript: void(0);">
                                {{--<i class="fa fa-user"></i>--}}
                                <img class="menuLogo_svg" src="{{ URL::asset('images/menuLogo/svg/account_ownership.jpeg') }}">
                                <span> Account ownership </span> <span class="menu-arrow"></span>
                            </a>
                            <ul class="nav-second-level" aria-expanded="false">
                                @if( empty($permission_users) || in_array('6-5', $permission_users) )
                                    <li><a href="{{ route('Admin.Claim.index') }}">Claim</a></li>
                                @endif
                                @if( empty($permission_users) || in_array('6-6', $permission_users) )
                                    <li><a href="{{ route('Admin.AccountOwnerShip.Payment') }}">Payment Term</a></li>
                                @endif
                            </ul>
                        </li>
                    @endif

                    {{-- start shop lead --}}
                    @if( empty($permission_users) || in_array('11-0', $permission_users) )
                        <li>
                            <a href="javascript: void(0);">
                                {{--<i class="fa fa-user"></i>--}}
                                <img class="menuLogo_svg" src="{{ URL::asset('images/menuLogo/svg/marketing.svg') }}">
                                <span>Shop Leads Info</span>
                                <span class="menu-arrow"></span>
                            </a>
                            <ul class="nav-second-level" aria-expanded="false">
                                @if( empty($permission_users) || in_array('11-5', $permission_users) )
                                    <li><a href="{{ route('ShopLeads') }}">Sources Percentage</a></li>
                                @endif
                                @if( empty($permission_users) || in_array('11-6', $permission_users) )
                                    <li><a href="{{ route('ExcludeAndIncludeSellers') }}">Include/Exclude Sellers</a></li>
                                @endif
                                @if( empty($permission_users) || in_array('11-7', $permission_users) )
                                    <li><a href="{{ route('ExcludeBuyers') }}">Exclude Buyers</a></li>
                                @endif
                                @if( empty($permission_users) || in_array('11-8', $permission_users) )
                                    <li><a href="{{ route('ExcludeSources') }}">Exclude Sources</a></li>
                                @endif
                                @if( empty($permission_users) || in_array('11-9', $permission_users) )
                                    <li><a href="{{ route('ExcludeUrl') }}">Exclude Url</a></li>
                                @endif
                                @if( empty($permission_users) || in_array('11-10', $permission_users) )
                                    <li><a href="{{ route('ExcludeSellerSources') }}">Exclude Seller Sources</a></li>
                                @endif
                            </ul>
                        </li>
                    @endif
                    {{-- end shop lead --}}

                    @if( empty($permission_users) || in_array('8-0', $permission_users) )
                        <li>
                            <a href="javascript: void(0);">
                                {{--<i class="fa fa-shopping-cart"></i> --}}
                                <img class="menuLogo_svg" src="{{ URL::asset('images/menuLogo/svg/process.svg') }}">
                                <span> Leads Management </span> <span class="menu-arrow"></span>
                            </a>
                            <ul class="nav-second-level" aria-expanded="false">
                                @if( empty($permission_users) || in_array('8-12', $permission_users) )
                                    <li><a href="{{ route('list_of_leads_all') }}">List Of All Leads</a></li>
                                @endif
                                @if( empty($permission_users) || in_array('8-5', $permission_users) )
                                    <li><a href="{{ route('list_of_leads_received') }}">List Of Sold Leads</a></li>
                                @endif
                                @if( empty($permission_users) || in_array('8-6', $permission_users) )
                                    <li><a href="{{ route('list_of_leads_lost') }}">List Of UnSold Leads</a></li>
                                @endif
                                @if( empty($permission_users) || in_array('8-14', $permission_users) )
                                    <li><a href="{{ route('Admin.list_of_leads_PINGs') }}">List Of PING Leads</a></li>
                                @endif
                                @if( empty($permission_users) || in_array('8-16', $permission_users) )
                                    <li><a href="{{ route('Admin.list_of_leads_Affiliate') }}">List Of Affiliate Leads</a></li>
                                @endif
                                @if( empty($permission_users) || in_array('8-7', $permission_users) )
                                    <li><a href="{{ route('list_of_leads_Refund') }}">List Of Return Leads</a></li>
                                @endif
                                @if( empty($permission_users) || in_array('8-9', $permission_users) )
                                    <li><a href="{{ route('list_of_leads_Archive') }}">List Of Archive Leads</a></li>
                                @endif
                                @if( empty($permission_users) || in_array('8-13', $permission_users) )
                                    <li><a href="{{ route('list_of_leads_Review') }}">List Of Leads Review</a></li>
                                @endif
                                @if( empty($permission_users) || in_array('8-15', $permission_users) )
                                    <li><a href="{{ route('list_of_leads_SMS_Email') }}">List Of Leads</a></li>
                                @endif
                                @if( empty($permission_users) || in_array('8-17', $permission_users) )
                                    <li><a href="{{ route('listOfLeadForm') }}">List Of Leads Form</a></li>
                                @endif
                                @if( empty($permission_users) || in_array('8-18', $permission_users) )
                                    <li><a href="{{ route('list_of_leads_receivedCallCenter') }}">List Of Leads Call Center</a></li>
                                @endif
                                @if( empty($permission_users) || in_array('8-19', $permission_users) )
                                    <li><a href="{{ route('list_of_leads_CallCenterReturns') }}">List Of Return Leads Call Center</a></li>
                                @endif
                                @if( empty($permission_users) || in_array('8-20', $permission_users) )
                                    <li><a href="{{ route('list_of_CallLead') }}">List Of Call Leads</a></li>
                                @endif
                            </ul>
                        </li>
                    @endif
{{--                    @if( empty($permission_users) || in_array('13-0', $permission_users) )--}}
{{--                        <li>--}}
{{--                            <a href="javascript: void(0);">--}}
{{--                                <img class="menuLogo_svg" src="{{ URL::asset('images/menuLogo/svg/marketing.svg') }}">--}}
{{--                                <span> TS Marketing </span> <span class="menu-arrow"></span>--}}
{{--                            </a>--}}
{{--                            <ul class="nav-second-level" aria-expanded="false">--}}
{{--                                @if( empty($permission_users) || in_array('13-5', $permission_users) )--}}
{{--                                    <li>--}}
{{--                                        <a href="javascript: void(0);">--}}
{{--                                            <span> Platforms </span> <span class="menu-arrow" ></span>--}}
{{--                                        </a>--}}
{{--                                        <ul class="nav-third-level" aria-expanded="false">--}}
{{--                                            <li><a href="{{ route('Platforms.index') }}">List Of Platforms</a></li>--}}
{{--                                            @if( empty($permission_users) || in_array('13-1', $permission_users) )--}}
{{--                                                <li><a href="{{ route('Platforms.create') }}">Add Platforms</a></li>--}}
{{--                                            @endif--}}
{{--                                        </ul>--}}
{{--                                    </li>--}}
{{--                                @endif--}}
{{--                                @if( empty($permission_users) || in_array('13-6', $permission_users) )--}}
{{--                                    <li>--}}
{{--                                        <a href="javascript: void(0);">--}}
{{--                                            <span> Traffic Sources </span> <span class="menu-arrow"></span>--}}
{{--                                        </a>--}}
{{--                                        <ul class="nav-third-level" aria-expanded="false">--}}
{{--                                            <li><a href="{{ route('TrafficSources.index') }}">List Of TS</a></li>--}}
{{--                                            @if( empty($permission_users) || in_array('13-1', $permission_users) )--}}
{{--                                                <li><a href="{{ route('TrafficSources.create') }}">Add TS</a></li>--}}
{{--                                            @endif--}}
{{--                                            @if( empty($permission_users) || in_array('13-8', $permission_users) )--}}
{{--                                                <li><a href="{{ route('tsLeadCost') }}">Lead Cost By TS</a></li>--}}
{{--                                            @endif--}}
{{--                                        </ul>--}}
{{--                                    </li>--}}
{{--                                @endif--}}
{{--                                @if( empty($permission_users) || in_array('13-7', $permission_users) )--}}
{{--                                    <li>--}}
{{--                                        <a href="javascript: void(0);">--}}
{{--                                            <span> Call Center Sources </span> <span class="menu-arrow"></span>--}}
{{--                                        </a>--}}
{{--                                        <ul class="nav-third-level" aria-expanded="false">--}}
{{--                                            <li><a href="{{ route('CallCenterSources.index') }}">List Of Call Center Sources</a></li>--}}
{{--                                            @if( empty($permission_users) || in_array('13-1', $permission_users) )--}}
{{--                                                <li><a href="{{ route('CallCenterSources.create') }}">Add Call Center Sources</a></li>--}}
{{--                                            @endif--}}
{{--                                        </ul>--}}
{{--                                    </li>--}}
{{--                                @endif--}}
{{--                            </ul>--}}
{{--                        </li>--}}
{{--                    @endif--}}


{{--                    @if( empty($permission_users) || in_array('15-0', $permission_users) )--}}
{{--                        <li>--}}
{{--                            <a href="{{ route('Admin.BlockLeadsInfo') }}">--}}
{{--                                <img class="menuLogo_svg" src="{{ URL::asset('images/menuLogo/svg/block.svg') }}">--}}
{{--                                <span> Block Leads Info </span>--}}
{{--                            </a>--}}
{{--                        </li>--}}
{{--                    @endif--}}
{{--                    @if( empty($permission_users) || in_array('16-0', $permission_users) )--}}
{{--                        <li>--}}
{{--                            <a href="javascript: void(0);">--}}
{{--                                <img class="menuLogo_svg" src="{{ URL::asset('images/menuLogo/svg/speech-bubble.svg') }}">--}}
{{--                                <span> Send SMS </span> <span class="menu-arrow"></span>--}}
{{--                            </a>--}}
{{--                            <ul class="nav-second-level" aria-expanded="false">--}}
{{--                                @if( empty($permission_users) || in_array('16-1', $permission_users) )--}}
{{--                                    <li><a href="{{ route('Admin.Marketing.SendSMS') }}">General SMS</a></li>--}}
{{--                                @endif--}}
{{--                                @if( empty($permission_users) || in_array('16-2', $permission_users) )--}}
{{--                                    <li><a href="{{ route('Admin.Marketing.SendProSMS') }}">Professional SMS</a></li>--}}
{{--                                @endif--}}
{{--                                @if( empty($permission_users) || in_array('16-3', $permission_users) )--}}
{{--                                    <li><a href="{{ route('Admin.Marketing.GenerateURL') }}">Generate Bitly URL</a></li>--}}
{{--                                @endif--}}
{{--                            </ul>--}}
{{--                        </li>--}}
{{--                    @endif--}}


                    {{--@if( empty($permission_users) || in_array('9-0', $permission_users) )--}}
                    {{--<li>--}}
                    {{--<a href="javascript: void(0);">--}}
                    {{--<i class="fa fa-shopping-cart"></i> --}}
                    {{--<img class="menuLogo_svg" src="{{ URL::asset('images/menuLogo/svg/process.svg') }}">--}}
                    {{--<span> Task Management </span> <span class="menu-arrow"></span>--}}
                    {{--</a>--}}
                    {{--<ul class="nav-second-level" aria-expanded="false">--}}
                    {{--<li><a href="{{ route('List_Of_Tasks') }}">List Of Tasks</a></li>--}}
                    {{--@if( empty($permission_users) || in_array('9-1', $permission_users) )--}}
                    {{--<li><a href="{{ route('Add_Tasks') }}">Add Task</a></li>--}}
                    {{--@endif--}}
                    {{--</ul>--}}
                    {{--</li>--}}
                    {{--@endif--}}
                    {{--                    @if( empty($permission_users) || in_array('18-0', $permission_users) )--}}
                    {{--                        <li>--}}
                    {{--                            <a href="{{ route('viewSessionRecording') }}">--}}
                    {{--                                <img class="menuLogo_svg" src="{{ URL::asset('images/menuLogo/svg/video-camera.svg') }}">--}}
                    {{--                                <span> Session Recording </span>--}}
                    {{--                            </a>--}}
                    {{--                        </li>--}}
                    {{--                    @endif--}}


                    @if( empty($permission_users) || in_array('1-0', $permission_users) )
                        <li>
                            <a href="javascript: void(0);">
                                {{--<i class="fi-target"></i>--}}
                                <img class="menuLogo_svg" src="{{ URL::asset('images/menuLogo/svg/support.svg') }}">
                                <span> Services </span> <span class="menu-arrow"></span>
                            </a>
                            <ul class="nav-second-level" aria-expanded="false">
                                <li><a href="{{ route('SuberAdminServices') }}">List Of Services</a></li>
                                @if( empty($permission_users) || in_array('1-1', $permission_users) )
                                    <li><a href="{{ route('AdminServicesAddForm') }}">Add Services</a></li>
                                @endif
                            </ul>
                        </li>
                    @endif


{{--                    @if( empty($permission_users) || in_array('19-0', $permission_users) )--}}
{{--                        <li>--}}
{{--                            <a href="javascript: void(0);">--}}
{{--                                <i class="fa fa-picture-o" aria-hidden="true"></i>--}}
{{--                                <span> Themes </span> <span class="menu-arrow"></span>--}}
{{--                            </a>--}}
{{--                            <ul class="nav-second-level" aria-expanded="false">--}}
{{--                                <li><a href="{{route('AllThemes')}}">List Of Themes</a></li>--}}
{{--                                @if( empty($permission_users) || in_array('19-1', $permission_users) )--}}
{{--                                    <li><a href="{{route('ThemeAddForm')}}">Add Theme</a></li>--}}
{{--                                @endif--}}
{{--                            </ul>--}}
{{--                        </li>--}}
{{--                    @endif--}}
{{--                    @if( empty($permission_users) || in_array('20-0', $permission_users) )--}}
{{--                        <li>--}}
{{--                            <a href="javascript: void(0);">--}}
{{--                                <i class="icon-list"></i>--}}
{{--                                <span> Domian </span> <span class="menu-arrow"></span>--}}
{{--                            </a>--}}
{{--                            <ul class="nav-second-level" aria-expanded="false">--}}
{{--                                <li><a href="{{ route('AllDomains') }}">List Of Domains</a></li>--}}
{{--                                @if( empty($permission_users) || in_array('20-1', $permission_users) )--}}
{{--                                    <li><a href="{{ route('DomainAddForm') }}">Add Domains</a></li>--}}
{{--                                @endif--}}
{{--                            </ul>--}}
{{--                        </li>--}}
{{--                    @endif--}}


                        <!-- Pixels -->
{{--                    @if( empty($permission_users) || in_array('21-0', $permission_users) )--}}
{{--                        <li>--}}
{{--                            <a href="javascript: void(0);">--}}
{{--                                <i class="fa fa-bar-chart" aria-hidden="true"></i>--}}
{{--                                <span> Pixels </span> <span class="menu-arrow"></span>--}}
{{--                            </a>--}}
{{--                            <ul class="nav-second-level" aria-expanded="false">--}}
{{--                                <li><a href="{{route('pixels')}}">List Of Pixels</a></li>--}}
{{--                            </ul>--}}
{{--                        </li>--}}
{{--                    @endif--}}


                        <!-- Our Partners -->
{{--                    @if( empty($permission_users) || in_array('24-0', $permission_users) )--}}
{{--                        <li>--}}
{{--                            <a href="javascript: void(0);">--}}
{{--                                <i class="icon-list"></i>--}}
{{--                                <span> Our Partners </span> <span class="menu-arrow"></span>--}}
{{--                            </a>--}}
{{--                            <ul class="nav-second-level" aria-expanded="false">--}}
{{--                                <li><a href="{{ route('OurPartners.index') }}">List Of Partners</a></li>--}}
{{--                                @if( empty($permission_users) || in_array('24-1', $permission_users) )--}}
{{--                                    <li><a href="{{ route('OurPartners.create') }}">Add Partners</a></li>--}}
{{--                                @endif--}}
{{--                            </ul>--}}
{{--                        </li>--}}
{{--                    @endif--}}


                        <!-- Tickets -->
                    @if( empty($permission_users) || in_array('22-0', $permission_users) )
                        <li>
                            <a href="javascript: void(0);">
                                <img class="menuLogo_svg" src="{{ URL::asset('images/menuLogo/svg/support.svg') }}">
                                <span> Tickets </span> <span class="menu-arrow"></span>
                            </a>
                            <ul class="nav-second-level" aria-expanded="false">
                                @if( empty($permission_users) || in_array('22-1', $permission_users) )
                                    <li><a href="{{ route('ShowIssueTicket') }}">Issue Tickets</a></li>
                                @endif
                                @if( empty($permission_users) || in_array('22-2', $permission_users) )
                                    <li><a href="{{ route('ShowReturnTicket') }}">Return Tickets</a></li>
                                @endif
                            </ul>
                        </li>
                    @endif
                        <!-- Access Log -->
                    @if( empty($permission_users) || in_array('10-0', $permission_users) )
                        <li>
                            <a href="javascript: void(0);">
                                {{--<i class="fa fa-calendar"></i>--}}
                                <img class="menuLogo_svg" src="{{ URL::asset('images/menuLogo/svg/remote-access.svg') }}">
                                <span> Access Log </span> <span class="menu-arrow"></span>
                            </a>
                            <ul class="nav-second-level" aria-expanded="false">
                                @if( empty($permission_users) || in_array('10-5', $permission_users) )
                                    <li><a href="{{ url('/Admin/Services/AccessLog') }}">Services</a></li>
                                @endif
                                @if( empty($permission_users) || in_array('10-20', $permission_users) )
                                    <li><a href="{{ url('/Admin/ThemeTemplates/AccessLog') }}">Themes</a></li>
                                @endif
                                @if( empty($permission_users) || in_array('10-21', $permission_users) )
                                    <li><a href="{{ url('/Admin/DomainTemplates/AccessLog') }}">Domains</a></li>
                                @endif
                                @if( empty($permission_users) || in_array('10-6', $permission_users) )
                                    <li><a href="{{ url('/Admin/PromoCode/AccessLog') }}">Promo Codes</a></li>
                                @endif
                                @if( empty($permission_users) || in_array('10-7', $permission_users) )
                                    <li><a href="{{ url('/Admin/Admin/AccessLog') }}">Admin User</a></li>
                                @endif
                                @if( empty($permission_users) || in_array('10-17', $permission_users) )
                                    <li><a href="{{ url('/Admin/ProspectUsers/AccessLog') }}">Prospect User</a></li>
                                @endif
                                @if( empty($permission_users) || in_array('10-8', $permission_users) )
                                    <li><a href="{{ url('/Admin/Buyers/AccessLog') }}">Buyers User</a></li>
                                @endif
                                @if( empty($permission_users) || in_array('10-9', $permission_users) )
                                    <li><a href="{{ url('/Admin/Campaign/AccessLog') }}">Buyer Campaigns</a></li>
                                @endif
                                @if( empty($permission_users) || in_array('10-14', $permission_users) )
                                    <li><a href="{{ url('/Admin/SellerCampaign/AccessLog') }}">Seller Campaigns</a></li>
                                @endif
                                @if( empty($permission_users) || in_array('10-10', $permission_users) )
                                    <li><a href="{{ url('/Admin/LeadManagement/AccessLog') }}">Lead Management</a></li>
                                @endif
                                @if( empty($permission_users) || in_array('10-11', $permission_users) )
                                    <li><a href="{{ url('/Admin/Authentication/AccessLog') }}">Authentication</a></li>
                                @endif
                                @if( empty($permission_users) || in_array('10-12', $permission_users) )
                                    <li><a href="{{ url('/Admin/Ticket/AccessLog') }}">Ticket</a></li>
                                @endif
                                @if( empty($permission_users) || in_array('10-13', $permission_users) )
                                    <li><a href="{{ url('/Admin/Payment/AccessLog') }}">User Payments</a></li>
                                @endif
                                @if( empty($permission_users) || in_array('10-15', $permission_users) )
                                    <li><a href="{{ url('/Admin/MarketingPlatform/AccessLog') }}">Marketing Platforms</a></li>
                                @endif
                                @if( empty($permission_users) || in_array('10-16', $permission_users) )
                                    <li><a href="{{ url('/Admin/MarketingTrafficSources/AccessLog') }}">Marketing TS</a></li>
                                @endif
                                @if( empty($permission_users) || in_array('10-18', $permission_users) )
                                    <li><a href="{{ url('/Admin/BlockLead/AccessLog') }}">Block Leads Info</a></li>
                                @endif
                                @if( empty($permission_users) || in_array('10-19', $permission_users) )
                                    <li><a href="{{ url('/Admin/SendSMS/AccessLog') }}">Send SMS</a></li>
                                @endif
                                @if( empty($permission_users) || in_array('10-22', $permission_users) )
                                    <li><a href="{{ url('/Admin/ShopLeads/AccessLog') }}">Shop Leads</a></li>
                                @endif
                                @if( empty($permission_users) || in_array('10-23', $permission_users) )
                                    <li><a href="{{ url('/Admin/CallCenterSource/AccessLog') }}">Call Center Sources</a></li>
                                @endif
                                @if( empty($permission_users) || in_array('10-24', $permission_users) )
                                    <li><a href="{{ url('/Admin/LeadCostByTS/AccessLog') }}">Lead Cost By TS</a></li>
                                @endif
                            </ul>
                        </li>
                    @endif

                </ul>
            </div>
            <!-- Sidebar -->
            <div class="clearfix"></div>

        </div>
        <!-- Sidebar -left -->

    </div>
    <!-- Left Sidebar End -->

    <!-- ============================================================== -->
    <!-- Start right Content here -->
    <!-- ============================================================== -->
    @include('include.include_js')
    <div class="content-page">
        <!-- Start content -->
        <div class="content">
            <div class="container-fluid">

                @yield('content')

            </div> <!-- container -->

        </div> <!-- content -->

    </div>
    <!-- ============================================================== -->
    <!-- End Right content here -->
    <!-- ============================================================== -->
</div>

</body>
</html>
