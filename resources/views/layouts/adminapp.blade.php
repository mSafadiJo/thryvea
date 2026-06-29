<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Styles -->
    @include('include.include_css')

    <!-- ===== RESPONSIVE SIDEBAR STYLES ===== -->

</head>
<body>

<!-- Mobile Overlay -->
<div class="sidebar-overlay" id="sidebarOverlay"></div>

<!-- ===== WRAPPER ===== -->
<div id="wrapper">

    <!-- ========== LEFT SIDEBAR ========== -->
    <div class="left side-menu" id="sidebar">

        <!-- Sidebar Header -->
        <div class="sidebar-header">
            <a class="navbar-brand" href="{{ url('/') }}">
                <img src="{{ URL::asset('images/Allied/ArtboardBalck.png') }}" alt="Logo"/>
            </a>
            <button class="sidebar-close-btn" id="mobileSidebarClose" aria-label="Close menu">
                <i class="dripicons-cross"></i>
            </button>
        </div>

        <!-- Scrollable Menu -->
        <div class="slimscroll-menu" id="remove-scroll">

            @php
                $permission_users = [];
                if (!empty(Auth::user()->permission_users)) {
                    $permission_users = json_decode(Auth::user()->permission_users, true);
                }
            @endphp

            <div id="sidebar-menu">
                <ul id="side-menu">

                     ==================== SETTINGS ====================
                                        @if(empty($permission_users) || in_array('25-0', $permission_users))
                                            <li class="has-submenu">
                                                <a href="javascript: void(0);">
                                                    <i class="fa fa-cog" aria-hidden="true"></i>
                                                    <span class="menu-text">Settings</span>
                                                    <span class="menu-arrow"> </span>
                                                </a>
{{--                                                <ul class="nav-second-level">--}}
{{--                                                    @if(empty($permission_users) || in_array('25-1', $permission_users))--}}
{{--                                                        <li><a href="{{ route('Admin.Setting.Payment') }}">Payment Methods</a></li>--}}
{{--                                                    @endif--}}
{{--                                                </ul>--}}
                                                <ul class="nav-second-level" aria-expanded="false">
                                                    @if( empty($permission_users) || in_array('25-3', $permission_users) )
                                                        <li><a href="{{ route('Admin.site.setting.show') }}">Site Setting</a></li>
                                                    @endif
                                                </ul>
                                            </li>
                                        @endif

                    {{-- ==================== REPORTS ==================== --}}
                    @if(empty($permission_users) || in_array('3-0', $permission_users))
                        <li class="has-submenu">
                            <a href="javascript: void(0);">
                                <i class="fa fa-pie-chart" aria-hidden="true"></i>
                                <span class="menu-text">Reports</span>
                                <span class="menu-arrow"> </span>
                            </a>
                            <ul class="nav-second-level">
                                {{--                                @if(empty($permission_users) || in_array('3-12', $permission_users))--}}
                                {{--                                    <li><a href="{{ route('Admin.Report.AccountManagerReport') }}">Account Manager Reports</a></li>--}}
                                {{--                                @endif--}}
                                @if(empty($permission_users) || in_array('3-14', $permission_users))
                                    <li><a href="{{ route('FilterZipCodeByServiceShow') }}">Active ZipCodes</a></li>
                                @endif
                                @if(empty($permission_users) || in_array('3-22', $permission_users))
                                    <li><a href="{{ route('AffiliateReport') }}">Affiliate Report</a></li>
                                @endif
                                {{--                                @if(empty($permission_users) || in_array('3-23', $permission_users))--}}
                                {{--                                    <li><a href="{{ route('AgentsCallCenter') }}">Agents CallCenter Report</a></li>--}}
                                {{--                                @endif--}}
                                @if(empty($permission_users) || in_array('3-27', $permission_users))
                                    <li><a href="{{ route('BuyersLocationReport.index') }}">Buyers Location Report</a></li>
                                @endif
                                @if(empty($permission_users) || in_array('3-18', $permission_users))
                                    <li><a href="{{ route('mapBuyer') }}">Buyers Map</a></li>
                                @endif
                                {{--                                @if(empty($permission_users) || in_array('3-25', $permission_users))--}}
                                {{--                                    <li><a href="{{ route('Reports.callCenterProfit') }}">Call Center Profit Report</a></li>--}}
                                {{--                                @endif--}}
                                {{--                                @if(empty($permission_users) || in_array('3-24', $permission_users))--}}
                                {{--                                    <li><a href="{{ route('Admin.CallCenter.Dashboard') }}">CallCenter Target Dashboard</a></li>--}}
                                {{--                                @endif--}}
                                @if(empty($permission_users) || in_array('3-13', $permission_users))
                                    <li><a href="{{ route('FilterCampaignByZipCodeAndService') }}">Campaigns By Zipcode</a></li>
                                @endif
                                @if(empty($permission_users) || in_array('3-16', $permission_users))
                                    <li><a href="{{ route('FilterCRMShow') }}">CRM Responses</a></li>
                                @endif
                                @if(empty($permission_users) || in_array('3-17', $permission_users))
                                    <li><a href="{{ route('map') }}">Lead Map</a></li>
                                @endif
                                @if(empty($permission_users) || in_array('3-5', $permission_users))
                                    <li><a href="{{ route('Admin.Report.lead_report') }}">Lead Report</a></li>
                                @endif
                                @if(empty($permission_users) || in_array('3-6', $permission_users))
                                    <li><a href="{{ route('Admin.Report.lead_volume') }}">Lead Volume</a></li>
                                @endif
                                {{--                                @if(empty($permission_users) || in_array('3-28', $permission_users))--}}
                                {{--                                    <li><a href="{{ route('marketingCost') }}">Marketing Cost</a></li>--}}
                                {{--                                @endif--}}
                                {{--                                @if(empty($permission_users) || in_array('3-8', $permission_users))--}}
                                {{--                                    <li><a href="{{ route('Admin.Report.lead_from_websites') }}">Marketing Reports</a></li>--}}
                                {{--                                @endif--}}
                                {{--                                @if(empty($permission_users) || in_array('3-26', $permission_users))--}}
                                {{--                                    <li><a href="{{ route('payPerClickReport') }}">PayPerClick Campaign Report</a></li>--}}
                                {{--                                @endif--}}
                                @if(empty($permission_users) || in_array('3-7', $permission_users))
                                    <li><a href="{{ route('Admin.Report.performance_reports') }}">Performance Reports</a></li>
                                @endif
                                {{--                                @if(empty($permission_users) || in_array('3-19', $permission_users))--}}
                                {{--                                    <li><a href="{{ route('Reports.salesCommission') }}">Sales Commission</a></li>--}}
                                {{--                                @endif--}}
                                {{--                                @if(empty($permission_users) || in_array('3-11', $permission_users))--}}
                                {{--                                    <li><a href="{{ route('Admin.Report.SalesReport') }}">Sales Reports</a></li>--}}
                                {{--                                @endif--}}
                                {{--                                @if(empty($permission_users) || in_array('3-20', $permission_users))--}}
                                {{--                                    <li><a href="{{ route('Admin.Sales.Dashboard') }}">Sales/Transfers Dashboard</a></li>--}}
                                {{--                                @endif--}}
                                {{--                                @if(empty($permission_users) || in_array('3-10', $permission_users))--}}
                                {{--                                    <li><a href="{{ route('Admin.Report.SDRReport') }}">SDR Reports</a></li>--}}
                                {{--                                @endif--}}
                                @if(empty($permission_users) || in_array('3-9', $permission_users))
                                    <li><a href="{{ route('Admin.Reports.Seller_Lead_Volume') }}">Seller Lead Volume</a></li>
                                @endif
                                {{--                                @if(empty($permission_users) || in_array('3-21', $permission_users))--}}
                                {{--                                    <li><a href="{{ route('Reports.users_payments') }}">Users Payments</a></li>--}}
                                {{--                                @endif--}}
                            </ul>
                        </li>
                    @endif

                    {{-- ==================== ADMINS ==================== --}}
                    @if(empty($permission_users) || in_array('4-0', $permission_users))
                        <li class="has-submenu">
                            <a href="javascript: void(0);">
                                <i class="fa fa-users" aria-hidden="true"></i>
                                <span class="menu-text">Admins</span>
                                <span class="menu-arrow"> </span>
                            </a>
                            <ul class="nav-second-level">
                                <li><a href="{{ route('AdminManagment.index') }}">List Of Admins</a></li>
                                @if(empty($permission_users) || in_array('4-1', $permission_users))
                                    <li><a href="{{ route('AdminManagment.create') }}">Add Admins</a></li>
                                @endif
                            </ul>
                        </li>
                    @endif

                    {{-- ==================== CONTRACTORS ==================== --}}
                    {{--                    @if(empty($permission_users) || in_array('17-0', $permission_users))--}}
                    {{--                        <li>--}}
                    {{--                            <a href="{{ route('Admin.JoinAsAPro.List') }}">--}}
                    {{--                                <img class="menuLogo_svg" src="{{ URL::asset('images/menuLogo/svg/team.svg') }}" alt="Contractors">--}}
                    {{--                                <span class="menu-text">Contractors</span>--}}
                    {{--                            </a>--}}
                    {{--                        </li>--}}
                    {{--                    @endif--}}

                    {{-- ==================== BUYERS ==================== --}}
                    @if(empty($permission_users) || in_array('5-0', $permission_users))
                        <li class="has-submenu">
                            <a href="javascript: void(0);">
                                <i class="fa fa-address-book-o" aria-hidden="true"></i>
                                <span class="menu-text">Buyers</span>
                                <span class="menu-arrow"> </span>
                            </a>
                            <ul class="nav-second-level">
                                <li><a href="{{ route('Admin_Buyers') }}">List Of Buyers</a></li>
                                @if(empty($permission_users) || in_array('5-1', $permission_users))
                                    <li><a href="{{ route('Admin_BuyersAdd') }}">Add Buyers</a></li>
                                @endif
                                <li><a href="{{ route('Old_Buyers') }}">List Of Old Buyers</a></li>
                            </ul>
                        </li>
                    @endif

                    {{-- ==================== SELLERS ==================== --}}
                    @if(empty($permission_users) || in_array('23-0', $permission_users))
                        <li class="has-submenu">
                            <a href="javascript: void(0);">
                                <i class="fa fa-address-card" aria-hidden="true"></i>
                                <span class="menu-text">Sellers</span>
                                <span class="menu-arrow"> </span>
                            </a>
                            <ul class="nav-second-level">
                                <li><a href="{{ route('ListOfSeller') }}">List Of Sellers</a></li>
                            </ul>
                        </li>
                    @endif

                    {{-- ==================== BUYERS CAMPAIGNS ==================== --}}
                    @if(empty($permission_users) || in_array('7-0', $permission_users))
                        <li class="has-submenu">
                            <a href="javascript: void(0);">
                                <i class="fa fa-list" aria-hidden="true"></i>
                                <span class="menu-text">Buyers Campaigns</span>
                                <span class="menu-arrow"> </span>
                            </a>
                            <ul class="nav-second-level">
                                <li><a href="{{ route('Admin_Campaign') }}">List Of Campaigns</a></li>
                                @if(empty($permission_users) || in_array('7-1', $permission_users))
                                    <li><a href="{{ route('Admin.Campaign.Create') }}">Add Campaigns</a></li>
                                @endif
                            </ul>
                        </li>
                    @endif

                    {{-- ==================== SELLERS CAMPAIGNS ==================== --}}
                    @if(empty($permission_users) || in_array('12-0', $permission_users))
                        <li class="has-submenu">
                            <a href="javascript: void(0);">
                                <i class="fa fa-list" aria-hidden="true"></i>
                                <span class="menu-text">Sellers Campaigns</span>
                                <span class="menu-arrow"> </span>
                            </a>
                            <ul class="nav-second-level">
                                <li><a href="{{ route('Campaigns.index') }}">List Of Campaigns</a></li>
                                @if(empty($permission_users) || in_array('12-1', $permission_users))
                                    <li><a href="{{ route('Campaigns.create') }}">Add Campaigns</a></li>
                                @endif
                            </ul>
                        </li>
                    @endif

                    {{-- ==================== ACCOUNT OWNERSHIP ==================== --}}
                    @if(empty($permission_users) || in_array('6-0', $permission_users))
                        <li class="has-submenu">
                            <a href="javascript: void(0);">
                                <i class="fa fa-credit-card" aria-hidden="true"></i>
                                <span class="menu-text">Account Ownership</span>
                                <span class="menu-arrow"> </span>
                            </a>
                            <ul class="nav-second-level">
                                @if(empty($permission_users) || in_array('6-5', $permission_users))
                                    <li><a href="{{ route('Admin.Claim.index') }}">Claim</a></li>
                                @endif
                                @if(empty($permission_users) || in_array('6-6', $permission_users))
                                    <li><a href="{{ route('Admin.AccountOwnerShip.Payment') }}">Payment Term</a></li>
                                @endif
                            </ul>
                        </li>
                    @endif

                    {{-- ==================== SHOP LEADS INFO ==================== --}}
                    @if(empty($permission_users) || in_array('11-0', $permission_users))
                        <li class="has-submenu">
                            <a href="javascript: void(0);">
                                <i class="fa fa-info-circle" aria-hidden="true"></i>
                                <span class="menu-text">Shop Leads Info</span>
                                <span class="menu-arrow"> </span>
                            </a>
                            <ul class="nav-second-level">
                                @if(empty($permission_users) || in_array('11-5', $permission_users))
                                    <li><a href="{{ route('ShopLeads') }}">Sources Percentage</a></li>
                                @endif
                                @if(empty($permission_users) || in_array('11-6', $permission_users))
                                    <li><a href="{{ route('ExcludeAndIncludeSellers') }}">Include/Exclude Sellers</a></li>
                                @endif
                                @if(empty($permission_users) || in_array('11-7', $permission_users))
                                    <li><a href="{{ route('ExcludeBuyers') }}">Exclude Buyers</a></li>
                                @endif
                                @if(empty($permission_users) || in_array('11-8', $permission_users))
                                    <li><a href="{{ route('ExcludeSources') }}">Exclude Sources</a></li>
                                @endif
                                @if(empty($permission_users) || in_array('11-9', $permission_users))
                                    <li><a href="{{ route('ExcludeUrl') }}">Exclude Url</a></li>
                                @endif
                                @if(empty($permission_users) || in_array('11-10', $permission_users))
                                    <li><a href="{{ route('ExcludeSellerSources') }}">Exclude Seller Sources</a></li>
                                @endif
                            </ul>
                        </li>
                    @endif

                    {{-- ==================== LEADS MANAGEMENT ==================== --}}
                    @if(empty($permission_users) || in_array('8-0', $permission_users))
                        <li class="has-submenu">
                            <a href="javascript: void(0);">
                                <i class="fa fa-user-circle-o" aria-hidden="true"></i>
                                <span class="menu-text">Leads Management</span>
                                <span class="menu-arrow"> </span>
                            </a>
                            <ul class="nav-second-level">
                                @if(empty($permission_users) || in_array('8-12', $permission_users))
                                    <li><a href="{{ route('list_of_leads_all') }}">List Of All Leads</a></li>
                                @endif
                                @if(empty($permission_users) || in_array('8-5', $permission_users))
                                    <li><a href="{{ route('list_of_leads_received') }}">List Of Sold Leads</a></li>
                                @endif
                                @if(empty($permission_users) || in_array('8-6', $permission_users))
                                    <li><a href="{{ route('list_of_leads_lost') }}">List Of UnSold Leads</a></li>
                                @endif
                                @if(empty($permission_users) || in_array('8-14', $permission_users))
                                    <li><a href="{{ route('Admin.list_of_leads_PINGs') }}">List Of PING Leads</a></li>
                                @endif
                                @if(empty($permission_users) || in_array('8-16', $permission_users))
                                    <li><a href="{{ route('Admin.list_of_leads_Affiliate') }}">List Of Affiliate Leads</a></li>
                                @endif
                                {{--                                @if(empty($permission_users) || in_array('8-7', $permission_users))--}}
                                {{--                                    <li><a href="{{ route('list_of_leads_Refund') }}">List Of Return Leads</a></li>--}}
                                {{--                                @endif--}}
                                {{--                                @if(empty($permission_users) || in_array('8-9', $permission_users))--}}
                                {{--                                    <li><a href="{{ route('list_of_leads_Archive') }}">List Of Archive Leads</a></li>--}}
                                {{--                                @endif--}}
                                {{--                                @if(empty($permission_users) || in_array('8-13', $permission_users))--}}
                                {{--                                    <li><a href="{{ route('list_of_leads_Review') }}">List Of Leads Review</a></li>--}}
                                {{--                                @endif--}}
                                {{--                                @if(empty($permission_users) || in_array('8-15', $permission_users))--}}
                                {{--                                    <li><a href="{{ route('list_of_leads_SMS_Email') }}">List Of Leads</a></li>--}}
                                {{--                                @endif--}}
                                {{--                                @if(empty($permission_users) || in_array('8-17', $permission_users))--}}
                                {{--                                    <li><a href="{{ route('listOfLeadForm') }}">List Of Leads Form</a></li>--}}
                                {{--                                @endif--}}
                                {{--                                @if(empty($permission_users) || in_array('8-18', $permission_users))--}}
                                {{--                                    <li><a href="{{ route('list_of_leads_receivedCallCenter') }}">List Of Leads Call Center</a></li>--}}
                                {{--                                @endif--}}
                                {{--                                @if(empty($permission_users) || in_array('8-19', $permission_users))--}}
                                {{--                                    <li><a href="{{ route('list_of_leads_CallCenterReturns') }}">List Of Return Leads Call Center</a></li>--}}
                                {{--                                @endif--}}
                                {{--                                @if(empty($permission_users) || in_array('8-20', $permission_users))--}}
                                {{--                                    <li><a href="{{ route('list_of_CallLead') }}">List Of Call Leads</a></li>--}}
                                {{--                                @endif--}}
                            </ul>
                        </li>
                    @endif

                    {{-- ==================== TS MARKETING ==================== --}}
                    @if(empty($permission_users) || in_array('13-0', $permission_users))
                        <li class="has-submenu">
                            <a href="javascript: void(0);">
                                <i class="fa fa-search" aria-hidden="true"></i>
                                <span class="menu-text">TS Marketing</span>
                                <span class="menu-arrow"> </span>
                            </a>
                            <ul class="nav-second-level">
                                @if(empty($permission_users) || in_array('13-5', $permission_users))
                                    <li class="has-submenu">
                                        <a href="javascript: void(0);">
                                            <span>Platforms</span>
                                            <span class="menu-arrow"> </span>
                                        </a>
                                        <ul class="nav-third-level">
                                            <li><a href="{{ route('Platforms.index') }}">List Of Platforms</a></li>
                                            @if(empty($permission_users) || in_array('13-1', $permission_users))
                                                <li><a href="{{ route('Platforms.create') }}">Add Platforms</a></li>
                                            @endif
                                        </ul>
                                    </li>
                                @endif
                                @if(empty($permission_users) || in_array('13-6', $permission_users))
                                    <li class="has-submenu">
                                        <a href="javascript: void(0);">
                                            <span>Traffic Sources</span>
                                            <span class="menu-arrow"> </span>
                                        </a>
                                        <ul class="nav-third-level">
                                            <li><a href="{{ route('TrafficSources.index') }}">List Of TS</a></li>
                                            @if(empty($permission_users) || in_array('13-1', $permission_users))
                                                <li><a href="{{ route('TrafficSources.create') }}">Add TS</a></li>
                                            @endif
                                            {{--                                            @if(empty($permission_users) || in_array('13-8', $permission_users))--}}
                                            {{--                                                <li><a href="{{ route('tsLeadCost') }}">Lead Cost By TS</a></li>--}}
                                            {{--                                            @endif--}}
                                        </ul>
                                    </li>
                                @endif
                                {{--                                @if(empty($permission_users) || in_array('13-7', $permission_users))--}}
                                {{--                                    <li class="has-submenu">--}}
                                {{--                                        <a href="javascript: void(0);">--}}
                                {{--                                            <span>Call Center Sources</span>--}}
                                {{--                                            <span class="menu-arrow"> </span>--}}
                                {{--                                        </a>--}}
                                {{--                                        <ul class="nav-third-level">--}}
                                {{--                                            <li><a href="{{ route('CallCenterSources.index') }}">List Of Call Center Sources</a></li>--}}
                                {{--                                            @if(empty($permission_users) || in_array('13-1', $permission_users))--}}
                                {{--                                                <li><a href="{{ route('CallCenterSources.create') }}">Add Call Center Sources</a></li>--}}
                                {{--                                            @endif--}}
                                {{--                                        </ul>--}}
                                {{--                                    </li>--}}
                                {{--                                @endif--}}
                            </ul>
                        </li>
                    @endif

                    {{-- ==================== BLOCK LEADS INFO ==================== --}}
                    {{--                    @if(empty($permission_users) || in_array('15-0', $permission_users))--}}
                    {{--                        <li>--}}
                    {{--                            <a href="{{ route('Admin.BlockLeadsInfo') }}">--}}
                    {{--                                <img class="menuLogo_svg" src="{{ URL::asset('images/menuLogo/svg/block.svg') }}" alt="Block Leads">--}}
                    {{--                                <span class="menu-text">Block Leads Info</span>--}}
                    {{--                            </a>--}}
                    {{--                        </li>--}}
                    {{--                    @endif--}}

                    {{-- ==================== SEND SMS ==================== --}}
                    {{--                    @if(empty($permission_users) || in_array('16-0', $permission_users))--}}
                    {{--                        <li class="has-submenu">--}}
                    {{--                            <a href="javascript: void(0);">--}}
                    {{--                                <img class="menuLogo_svg" src="{{ URL::asset('images/menuLogo/svg/speech-bubble.svg') }}" alt="Send SMS">--}}
                    {{--                                <span class="menu-text">Send SMS</span>--}}
                    {{--                                <span class="menu-arrow"> </span>--}}
                    {{--                            </a>--}}
                    {{--                            <ul class="nav-second-level">--}}
                    {{--                                @if(empty($permission_users) || in_array('16-1', $permission_users))--}}
                    {{--                                    <li><a href="{{ route('Admin.Marketing.SendSMS') }}">General SMS</a></li>--}}
                    {{--                                @endif--}}
                    {{--                                @if(empty($permission_users) || in_array('16-2', $permission_users))--}}
                    {{--                                    <li><a href="{{ route('Admin.Marketing.SendProSMS') }}">Professional SMS</a></li>--}}
                    {{--                                @endif--}}
                    {{--                                @if(empty($permission_users) || in_array('16-3', $permission_users))--}}
                    {{--                                    <li><a href="{{ route('Admin.Marketing.GenerateURL') }}">Generate Bitly URL</a></li>--}}
                    {{--                                @endif--}}
                    {{--                            </ul>--}}
                    {{--                        </li>--}}
                    {{--                    @endif--}}

                    {{-- ==================== TASK MANAGEMENT ==================== --}}
                    {{--                    @if(empty($permission_users) || in_array('9-0', $permission_users))--}}
                    {{--                        <li class="has-submenu">--}}
                    {{--                            <a href="javascript: void(0);">--}}
                    {{--                                <img class="menuLogo_svg" src="{{ URL::asset('images/menuLogo/svg/process.svg') }}" alt="Task Management">--}}
                    {{--                                <span class="menu-text">Task Management</span>--}}
                    {{--                                <span class="menu-arrow"> </span>--}}
                    {{--                            </a>--}}
                    {{--                            <ul class="nav-second-level">--}}
                    {{--                                <li><a href="{{ route('List_Of_Tasks') }}">List Of Tasks</a></li>--}}
                    {{--                                @if(empty($permission_users) || in_array('9-1', $permission_users))--}}
                    {{--                                    <li><a href="{{ route('Add_Tasks') }}">Add Task</a></li>--}}
                    {{--                                @endif--}}
                    {{--                            </ul>--}}
                    {{--                        </li>--}}
                    {{--                    @endif--}}

                    {{-- ==================== SESSION RECORDING ==================== --}}
                    {{--                    @if(empty($permission_users) || in_array('18-0', $permission_users))--}}
                    {{--                        <li>--}}
                    {{--                            <a href="{{ route('viewSessionRecording') }}">--}}
                    {{--                                <img class="menuLogo_svg" src="{{ URL::asset('images/menuLogo/svg/video-camera.svg') }}" alt="Session Recording">--}}
                    {{--                                <span class="menu-text">Session Recording</span>--}}
                    {{--                            </a>--}}
                    {{--                        </li>--}}
                    {{--                    @endif--}}

                    {{-- ==================== SERVICES ==================== --}}
                    @if(empty($permission_users) || in_array('1-0', $permission_users))
                        <li class="has-submenu">
                            <a href="javascript: void(0);">
                                <i class="fa fa-home" aria-hidden="true"></i>
                                <span class="menu-text">Services</span>
                                <span class="menu-arrow"> </span>
                            </a>
                            <ul class="nav-second-level">
                                <li><a href="{{ route('SuberAdminServices') }}">List Of Services</a></li>
                                @if(empty($permission_users) || in_array('1-1', $permission_users))
                                    <li><a href="{{ route('AdminServicesAddForm') }}">Add Services</a></li>
                                @endif
                            </ul>
                        </li>
                    @endif

                    {{-- ==================== TICKETS ==================== --}}
                    @if(empty($permission_users) || in_array('22-0', $permission_users))
                        <li class="has-submenu">
                            <a href="javascript: void(0);">
                                <i class="fa fa-ticket" aria-hidden="true"></i>
                                <span class="menu-text">Tickets</span>
                                <span class="menu-arrow"> </span>
                            </a>
                            <ul class="nav-second-level">
                                @if(empty($permission_users) || in_array('22-1', $permission_users))
                                    <li><a href="{{ route('ShowIssueTicket') }}">Issue Tickets</a></li>
                                @endif
                                @if(empty($permission_users) || in_array('22-2', $permission_users))
                                    <li><a href="{{ route('ShowReturnTicket') }}">Return Tickets</a></li>
                                @endif
                            </ul>
                        </li>
                    @endif

                    {{-- ==================== ACCESS LOG ==================== --}}
                    @if(empty($permission_users) || in_array('10-0', $permission_users))
                        <li class="has-submenu">
                            <a href="javascript: void(0);">
                                <i class="fa fa-shield" aria-hidden="true"></i>
                                <span class="menu-text">Access Log</span>
                                <span class="menu-arrow"> </span>
                            </a>
                            <ul class="nav-second-level">
                                @if(empty($permission_users) || in_array('10-5', $permission_users))
                                    <li><a href="{{ url('/Admin/Services/AccessLog') }}">Services</a></li>
                                @endif
                                {{--                                @if(empty($permission_users) || in_array('10-20', $permission_users))--}}
                                {{--                                    <li><a href="{{ url('/Admin/ThemeTemplates/AccessLog') }}">Themes</a></li>--}}
                                {{--                                @endif--}}
                                {{--                                @if(empty($permission_users) || in_array('10-21', $permission_users))--}}
                                {{--                                    <li><a href="{{ url('/Admin/DomainTemplates/AccessLog') }}">Domains</a></li>--}}
                                {{--                                @endif--}}
                                {{--                                @if(empty($permission_users) || in_array('10-6', $permission_users))--}}
                                {{--                                    <li><a href="{{ url('/Admin/PromoCode/AccessLog') }}">Promo Codes</a></li>--}}
                                {{--                                @endif--}}
                                @if(empty($permission_users) || in_array('10-7', $permission_users))
                                    <li><a href="{{ url('/Admin/Admin/AccessLog') }}">Admin User</a></li>
                                @endif
                                {{--                                @if(empty($permission_users) || in_array('10-17', $permission_users))--}}
                                {{--                                    <li><a href="{{ url('/Admin/ProspectUsers/AccessLog') }}">Prospect User</a></li>--}}
                                {{--                                @endif--}}
                                @if(empty($permission_users) || in_array('10-8', $permission_users))
                                    <li><a href="{{ url('/Admin/Buyers/AccessLog') }}">Buyers User</a></li>
                                @endif
                                @if(empty($permission_users) || in_array('10-9', $permission_users))
                                    <li><a href="{{ url('/Admin/Campaign/AccessLog') }}">Buyer Campaigns</a></li>
                                @endif
                                @if(empty($permission_users) || in_array('10-14', $permission_users))
                                    <li><a href="{{ url('/Admin/SellerCampaign/AccessLog') }}">Seller Campaigns</a></li>
                                @endif
                                @if(empty($permission_users) || in_array('10-10', $permission_users))
                                    <li><a href="{{ url('/Admin/LeadManagement/AccessLog') }}">Lead Management</a></li>
                                @endif
                                @if(empty($permission_users) || in_array('10-11', $permission_users))
                                    <li><a href="{{ url('/Admin/Authentication/AccessLog') }}">Authentication</a></li>
                                @endif
                                @if(empty($permission_users) || in_array('10-12', $permission_users))
                                    <li><a href="{{ url('/Admin/Ticket/AccessLog') }}">Ticket</a></li>
                                @endif
                                {{--                                @if(empty($permission_users) || in_array('10-13', $permission_users))--}}
                                {{--                                    <li><a href="{{ url('/Admin/Payment/AccessLog') }}">User Payments</a></li>--}}
                                {{--                                @endif--}}
                                @if(empty($permission_users) || in_array('10-15', $permission_users))
                                    <li><a href="{{ url('/Admin/MarketingPlatform/AccessLog') }}">Marketing Platforms</a></li>
                                @endif
                                @if(empty($permission_users) || in_array('10-16', $permission_users))
                                    <li><a href="{{ url('/Admin/MarketingTrafficSources/AccessLog') }}">Marketing TS</a></li>
                                @endif
                                {{--                                @if(empty($permission_users) || in_array('10-18', $permission_users))--}}
                                {{--                                    <li><a href="{{ url('/Admin/BlockLead/AccessLog') }}">Block Leads Info</a></li>--}}
                                {{--                                @endif--}}
                                {{--                                @if(empty($permission_users) || in_array('10-19', $permission_users))--}}
                                {{--                                    <li><a href="{{ url('/Admin/SendSMS/AccessLog') }}">Send SMS</a></li>--}}
                                {{--                                @endif--}}
                                @if(empty($permission_users) || in_array('10-22', $permission_users))
                                    <li><a href="{{ url('/Admin/ShopLeads/AccessLog') }}">Shop Leads</a></li>
                                @endif
                                {{--                                @if(empty($permission_users) || in_array('10-23', $permission_users))--}}
                                {{--                                    <li><a href="{{ url('/Admin/CallCenterSource/AccessLog') }}">Call Center Sources</a></li>--}}
                                {{--                                @endif--}}
                                {{--                                @if(empty($permission_users) || in_array('10-24', $permission_users))--}}
                                {{--                                    <li><a href="{{ url('/Admin/LeadCostByTS/AccessLog') }}">Lead Cost By TS</a></li>--}}
                                {{--                                @endif--}}
                            </ul>
                        </li>
                    @endif

                </ul>
            </div>
        </div>
    </div>
    <!-- Left Sidebar End -->

    <!-- ============================================================== -->
    <!-- Start right Content here -->
    <!-- ============================================================== -->
    <div class="content-page" id="contentPage">

        <!-- Top Bar Start -->
        <div class="topbar">
            <div class="topbar-left">
                <!-- Desktop: collapse sidebar -->
                <button class="sidebar-toggle desktop-toggle" id="desktopSidebarToggle" title="Toggle Sidebar">
                    <i class="dripicons-menu"></i>
                </button>
                <!-- Mobile: open sidebar -->
                <button class="sidebar-toggle mobile-toggle" id="mobileMenuToggle" title="Menu">
                    <i class="dripicons-menu"></i>
                </button>
            </div>

            <nav class="navbar-custom">
                <ul class="list-inline menu-left mb-0 d-flex align-items-center">
                    <li class="list-inline-item">
                        <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <b>{{ Auth::user()->username }}</b>
                            <i class="dripicons-chevron-down"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item" href="{{ route('AdminProfile') }}">
                                <i class="dripicons-user"></i> Profile Setting
                            </a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item text-danger" href="{{ route('logout') }}"
                               onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                <i class="dripicons-power"></i> {{ __('Logout') }}
                            </a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                @csrf
                            </form>
                        </div>
                    </li>
                </ul>
            </nav>
        </div>
        <!-- Top Bar End -->

        @if(empty($permission_users) || in_array('25-2', $permission_users) || Auth::user()->role_id > 2)
            <input type="hidden" id="permission_users" value="upTo30">
        @endif

        <!-- Start content -->
        <div class="content">
            <div class="container-fluid">
                @yield('content')
            </div>
        </div>
        <!-- content -->

    </div>
    <!-- End Right content here -->

</div>
<!-- END wrapper -->

<!-- Scripts -->
@include('include.include_js')

<!-- ===== RESPONSIVE SIDEBAR SCRIPT ===== -->
<script>
    (function() {
        'use strict';

        var sidebar = document.getElementById('sidebar');
        var contentPage = document.getElementById('contentPage');
        var sidebarOverlay = document.getElementById('sidebarOverlay');
        var desktopToggle = document.getElementById('desktopSidebarToggle');
        var mobileToggle = document.getElementById('mobileMenuToggle');
        var mobileClose = document.getElementById('mobileSidebarClose');

        // ========================================
        // MOBILE SIDEBAR TOGGLE
        // ========================================
        function showMobileSidebar() {

            document.querySelectorAll('#side-menu li.open').forEach(function(item) {
                item.classList.remove('open');
            });

            sidebar.classList.add('mobile-show');
            sidebarOverlay.classList.add('show');
            document.body.style.overflow = 'hidden';
        }

        function hideMobileSidebar() {

            // Hide sidebar
            sidebar.classList.remove('mobile-show');
            sidebarOverlay.classList.remove('show');
            document.body.style.overflow = '';

            // Close all opened menus
            document.querySelectorAll('#side-menu li.open').forEach(function(item) {
                item.classList.remove('open');
            });
        }

        if (mobileToggle) {
            mobileToggle.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                showMobileSidebar();
            });
        }

        if (mobileClose) {
            mobileClose.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                hideMobileSidebar();
            });
        }

        if (sidebarOverlay) {
            sidebarOverlay.addEventListener('click', function(e) {
                e.preventDefault();
                hideMobileSidebar();
            });
        }

        // ========================================
        // DESKTOP SIDEBAR COLLAPSE
        // ========================================
        if (desktopToggle) {
            desktopToggle.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                sidebar.classList.toggle('collapsed');
                contentPage.classList.toggle('expanded');
                localStorage.setItem('sidebarCollapsed', sidebar.classList.contains('collapsed'));
            });
        }

        // Restore desktop sidebar state
        var savedState = localStorage.getItem('sidebarCollapsed');
        if (savedState === 'true' && window.innerWidth >= 1200) {
            sidebar.classList.add('collapsed');
            contentPage.classList.add('expanded');
        }

        // ========================================
        // HANDLE WINDOW RESIZE
        // ========================================
        var resizeTimer;
        window.addEventListener('resize', function() {
            clearTimeout(resizeTimer);
            resizeTimer = setTimeout(function() {
                if (window.innerWidth >= 1200) {
                    hideMobileSidebar();
                } else {
                    sidebar.classList.remove('collapsed');
                    contentPage.classList.remove('expanded');
                }
            }, 250);
        });

        // ========================================
        // CLOSE MOBILE SIDEBAR ON LINK CLICK
        // ========================================
        document.querySelectorAll(
            '#side-menu .nav-second-level a, #side-menu .nav-third-level a'
        ).forEach(function(link) {
            link.addEventListener('click', function() {
                if (window.innerWidth < 1200) {
                    hideMobileSidebar();
                }
            });
        });

        // ========================================
        // CUSTOM TOGGLE SYSTEM
        // Rules:
        // 1. All menus start closed
        // 2. Click to open one menu
        // 3. Click again to close it
        // 4. Only one menu open at a time (auto-close others)
        // ========================================
        (function initCustomToggle() {
            var allMenuItems = document.querySelectorAll('#side-menu li.has-submenu');

            allMenuItems.forEach(function(menuItem) {
                var anchor = menuItem.querySelector(':scope > a');
                var submenu = menuItem.querySelector(':scope > ul');

                if (!anchor || !submenu) return;

                anchor.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();

                    var isOpen = menuItem.classList.contains('open');

                    // Close ALL open menus at the same level (auto-collapse siblings)
                    var siblings = menuItem.parentElement.querySelectorAll(':scope > li.has-submenu.open');
                    siblings.forEach(function(sibling) {
                        if (sibling !== menuItem) {
                            sibling.classList.remove('open');
                        }
                    });

                    // Toggle current menu
                    if (isOpen) {
                        // Close this menu and all nested opens inside it
                        menuItem.classList.remove('open');
                        menuItem.querySelectorAll('li.open').forEach(function(nested) {
                            nested.classList.remove('open');
                        });
                    } else {
                        menuItem.classList.add('open');
                    }
                });
            });
        })();

    })();
</script>

</body>
</html>
