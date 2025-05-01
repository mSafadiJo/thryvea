<script>
    var resizefunc = [];

    var urlstates = "{{ route('GetStates') }}";
    var urlstatesAll = "{{ route('GetStatesAll') }}";
    var urlstatesSelected = "{{ route('GetStatesSelected') }}";
    var urlcities = "{{ route('GetCities') }}";
    var urlcitiesALL = "{{ route('GetCitiesALL') }}";
    var urlCountiesALL = "{{ route('getCountiesALL') }}";
    var urlcitiesSelected = "{{ route('GetCitiesSelected') }}";
    var urlzipCodes = "{{ route('getZipCodes') }}";
    var getAllZipCodeReview = "{{ route('getAllZipCodeReview') }}";
    var urlzipCodesAll = "{{ route('getZipCodesAll') }}";
    var urlzipCodesSelected = "{{ route('getZipCodesSelected') }}";
    var CampaignChangeStatus = "{{ route('CampaignChangeStatus') }}";
    var AdminCampaignChangeStatus = "{{ route('AdminCampaignChangeStatus') }}";
    var getCountieswithFillter = "{{ route('getCountieswithFillter') }}";
    var getAllCounties = "{{ route('getAllCounties') }}";
    var getAllWhithFillter = "{{ route('getAllWhithFillter') }}";
    var getAllWhithFillterZipCode = "{{ route('getAllWhithFillterZipCode') }}";
    var AddTransactionPayPal = "{{ route('AddTransactionPayPal') }}";
    var TransactionValueStore = "{{ route('Transaction.Value.Store') }}";
    var GetPaymentDetailsAjax = "{{ route('GetPaymentDetailsAjax') }}";
    var TransactionvalueinPayPalButtons = "{{ route('TransactionvalueinPayPalButtons') }}";
    var TicketChangeStatus = "{{ route('TicketChangeStatus') }}";
    var adminClaimChangeStatus = "{{ route('Admin.Claim.Edit_Status') }}";
    var adminAccountOwnerShipChangeStatus = "{{ route('Admin.AccountOwnerShip.Payment.Edit') }}";
    var getCityReview = "{{ route('getCityReview') }}";

    //Reports URL
    var AdminReportlead_volumedata = "{{ route('Admin.Report.lead_volume.data') }}";
    var AdminReportPerformance_Reportsdata = "{{ route('Admin.Report.Performance_Reports.data') }}";
    var AdminReportlead_from_websitesdata = "{{ route('Admin.Report.lead_from_websites.data') }}";
    var AdminReportlead_reportdata = "{{ route('Admin.Report.lead_report.data') }}";

    //Filters URL
    var AdminCampaignStorFilter = "{{ route('AdminCampaignStor.Filter') }}";
    var list_of_leads_SMS_Email_ajax = "{{ route('list_of_leads_SMS_Email_ajax') }}";
    var UpdateStatusServicedSuperAdmin = "{{ route('UpdateStatusServicedSuperAdmin') }}";

    var ExportlistZipCodeByService = "{{ route('ExportlistZipCodeByService') }}";
    var ExportlistZipCodeByService = "{{ route('ExportlistZipCodeByService') }}";

    //new var for buyer and seller page
    var list_of_leads_BuyersAjax = "{{ route('list_of_leads_BuyersAjax') }}";
    var list_of_leads_SellerAjax = "{{ route('list_of_leads_Seller') }}";
    var BuyersListOfClickLeadsAjax = "{{ route('Buyers.ListOfClickLeads.Ajax') }}";
    var ReturnLeadBuyerAjax = "{{ route('ReturnLeadBuyerAjax') }}";

    //filiter Zipcde
    var FilterLostLeadReportAjax = "{{ route('FilterLostLeadReportAjax') }}";

    //new ticket
    var ShowReturnTicketAjax = "{{ route('ShowReturnTicketAjax') }}";

    var ShowIssueTicketAjax = "{{ route('ShowIssueTicketAjax') }}";

    {{--var AdminCampaigndeleteAllStateFilter = "{{ route('Admin.Campaign.deleteAllStateFilter') }}";--}}
    {{--var AdminCampaigndeleteAllState = "{{ route('Admin.Campaign.deleteAllState') }}";--}}
    var AdminCampaigndeleteAllCounty = "{{ route('Admin.Campaign.deleteAllCounty') }}";
    var AdminCampaigndeleteAllCity = "{{ route('Admin.Campaign.deleteAllCity') }}";
    var AdminCampaigndeleteAllZipcode = "{{ route('Admin.Campaign.deleteAllZipcode') }}";
    var AdminCampaigndeleteAllCountyExpect = "{{ route('Admin.Campaign.deleteAllCountyExpect') }}";
    var AdminCampaigndeleteAllCityExpect = "{{ route('Admin.Campaign.deleteAllCityExpect') }}";
    var AdminCampaigndeleteAllZipcodeExpect = "{{ route('Admin.Campaign.deleteAllZipcodeExpect') }}";

    var AdminCampaignsendTestLead = "{{ route('Admin.Campaign.sendTestLead') }}";
    var AdminSellerCampaignsfilter = "{{ route('Admin.Seller.Campaigns.filter') }}";
    var AdminReportsSeller_Lead_VolumeSearch = "{{ route('Admin.Reports.Seller_Lead_Volume.Search') }}";
    var AdminReportSalesReportdata = "{{ route('Admin.Report.SalesReport.data') }}";
    var AdminReportSDRReportdata = "{{ route('Admin.Report.SDRReport.data') }}";
    var AdminReportAccountManagerReportdata = "{{ route('Admin.Report.AccountManagerReport.data') }}";
    var updateStatusThemesUserAdmin = "{{route('updateStatusThemesUserAdmin')}}";
    var updateStatusDomainsUserAdmin = "{{route('updateStatusDomainsUserAdmin')}}";

    var accessLogSearchGeneral = "{{ route('AccessLog.search') }}";

    //Block Lead Info
    var block_lead_info_ajax = "{{ route('Admin.BlockLeadsInfo.logic_data') }}";

    //Change Lead Status on buyer dashboard
    var buyerLeadChangeStatus = "{{ route('buyer.LeadChangeStatus') }}";

    //Send Test Lead
    var adminCampaign_sendTestLead = "{{ route('AdminCampaign.sendTestLead') }}";

    ///get all trific soruce
    var getAllTrafficSorce = "{{ route('getAllTrafficSorce') }}";
    var trafficSourceAjax = "{{ route('trafficSourceAjax') }}";
    var ListImageThemes = "{{route('ListImageThemes')}}";

    {{--var list_of_leads_receivedAjax = "{{ route('list_of_leads_receivedAjax') }}";--}}
    {{--var list_of_leads_receivedAjaxCallCenter = "{{ route('list_of_leads_receivedAjaxCallCenter') }}";--}}
    {{--var list_of_leads_receivedAjaxCallCenterReturns = "{{ route('list_of_leads_CallCenterReturnsAjaxCallCenter') }}";--}}
    {{--var list_of_leads_Refund_ajax = "{{ route('list_of_leads_Refund_ajax') }}";--}}
    {{--var list_of_leads_LostAjax = "{{ route('list_of_leads_LostAjax') }}";--}}
    {{--var list_of_leads_ArchiveAjax = "{{ route('list_of_leads_ArchiveAjax') }}";--}}
    {{--var list_of_leads_allajax = "{{ route('list_of_leads_allajax') }}";--}}
    {{--var list_of_leads_ReviewAjax = "{{ route('list_of_leads_ReviewAjax') }}";--}}
    {{--var Adminlist_of_leads_Affiliatelist = "{{route('Admin.list_of_leads_Affiliate.list')}}";--}}
    {{--var Adminlist_of_leads_PINGslist = "{{ route('Admin.list_of_leads_PINGs.list') }}";--}}
    {{--var Adminlist_of_leads_PINGs = "{{ route('Admin.list_of_leads_PINGs.list') }}";--}}
    {{--var crmResponse = "{{ route('crmResponse') }}";--}}

    var export_lead_data = "{{ route('export_lead_data') }}";

    var AdminProspectsSearch = "{{ route('Admin.Prospects.Search') }}";

    var token = "{{ Session::token() }}"

    var citysearch = "{{ route('cityResult') }}";
    var countysearch = "{{ route('countyResult') }}";
    var zipsearch = "{{ route('zipResult') }}";
    var ReportsSalesCommissionSearch = "{{ route('Reports.salesCommission.Search') }}";

    //For Shop Leads
    var getCampaignsByBuyer = "{{ route('ShopLeadsCampaigns') }}";
    var getCampaignsByBuyerEx = "{{ route('ShopLeadsCampaignsEx') }}";

    var getAllSourceByCampaign = "{{ route('ShopLeadsSource') }}";

    //Sales Dashboard
    var AdminSalesStoreSetting = "{{ route('Admin.Sales.StoreSetting') }}";
    var AdminSalesStoreTransfers = "{{ route('Admin.Sales.StoreTransfers') }}";
    var SalesDashboardReload = "{{ route('Sales.Dashboard.Reload') }}";
    var CallCenterDashboardReload = "{{ route('CallCenter.Dashboard.Reload') }}";

    //Lead Form
    var list_of_leads_FormAjax = "{{ route('list_of_leads_FormAjax') }}";

    //Call Leads
    var list_of_CallLead_ajax = "{{ route('list_of_CallLead_ajax') }}";

    //report
    var AffiliateReportAjax = "{{ route('AffiliateReportAjax') }}";
    var AgentsCallCenterAjax = "{{ route('AgentsCallCenterAjax') }}";
    var ReportscallCenterProfitSearch = "{{ route('Reports.callCenterProfit.Search') }}";
    var QAChangeStatusLead = "{{ route('QAChangeStatusLead') }}";
    var MarketingCostReport = "{{ route('marketingCostData') }}";
    var buyer_lead_note_update = "{{ route('buyer_lead_note_update') }}";

    //Lead RevShareSeller
    var HomeRevShareSellerAjax = "{{ route('HomeRevShareSellerAjax') }}";
    //Delete ACH Payments
    var Delete_ACH_Credit = "{{ route('DeleteACHCredit') }}";
</script>

<!-- jQuery  -->
<script src="{{ asset('js/jquery.min.js') }}"></script>
<script src="{{ asset('js/tether.min.js') }}"></script><!-- Tether for Bootstrap -->
<script src="{{ asset('js/bootstrap.min.js') }}"></script>
<script src="{{ asset('js/metisMenu.min.js') }}"></script>
<script src="{{ asset('js/waves.js') }}"></script>
<script src="{{ asset('js/jquery.slimscroll.js') }}"></script>
<script src="{{ asset('js/modernizr.min.js') }}"></script>

<!-- App js -->
<script src="{{ asset('js/jquery.core.js') }}"></script>
<script src="{{ asset('js/jquery.app.js') }}"></script>
<!-- themes js -->
<script src="{{ asset('js/themes/theme.js') }}"></script>
<!-- domains js -->
<script src="{{ asset('js/domains/domain.js') }}"></script>

<script src="{{ asset('plugins/jquery-validation/js/jquery.validate.min.js') }}"></script>
<script src="{{ asset('plugins/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('plugins/datatables/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('plugins/datatables/jszip.min.js') }}"></script>
<script src="{{ asset('plugins/datatables/pdfmake.min.js') }}"></script>
<script src="{{ asset('plugins/datatables/responsive.bootstrap4.min.js') }}"></script>
<script src="{{ asset('plugins/datatables/vfs_fonts.js') }}"></script>

<script src="{{ asset('plugins/datatables/buttons.colVis.min.js') }}"></script>
<script src="{{ asset('plugins/datatables/buttons.html5.min.js') }}"></script>
<script src="{{ asset('plugins/datatables/buttons.print.min.js') }}"></script>
<script src="{{ asset('plugins/datatables/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ asset('plugins/datatables/dataTables.buttons.min.js') }}"></script>
<script src="{{ asset('plugins/datatables/buttons.bootstrap4.min.js') }}"></script>

<script src="{{ asset('ajax/allAjaxPages.js') }}"></script>
<script src="{{ asset('js/AllJs.js') }}"></script>
<script src="{{ asset('js/newLogIn.js') }}"></script>
<script src="{{ asset('js/campaign_services.js') }}"></script>
<script src="{{ asset('js/formsjs.js') }}"></script>

<!-- map files -->
<script src="{{ asset('js/raphael.js') }}"></script>
<script src="{{ asset('js/jquery.usmap.js') }}"></script>
<script src="{{ asset('js/map.js') }}"></script>
<script src="{{ asset('js/ShopLead/ShopLead.js') }}"></script>

<!-- campaign  City And Zipcode Filter files -->
<script src="{{ asset('js/campaignCityAndZipcodeFilter.js') }}"></script>

<script src="{{ asset('js/jquery.creditCardValidator.js') }}"></script>
<script src="{{ asset('js/credit-card-js.js') }}"></script>
<script src="{{ asset('js/timeslotCampaign.js') }}"></script>
{{--<script src="{{ asset('ajax/campainaddress.js') }}"></script>--}}
<script src="{{ asset('ajax/reports.js') }}"></script>
<script src="{{ asset('ajax/fillterAdmin.js') }}"></script>
<script src="{{ asset('js/dillerProject.js') }}"></script>

<!--Form Wizard-->
@if( Request::is("register") )
    <script src="{{ asset('plugins/jquery.stepy.regestration/jquery.stepy.js') }}" type="text/javascript"></script>
@else
    <script src="{{ asset('plugins/jquery.stepy/jquery.stepy.js') }}" type="text/javascript"></script>
@endif

<!--wizard initialization-->
<script src="{{ asset('pages/jquery.wizard-init.js') }}" type="text/javascript"></script>

<!-- plugin js -->
<script src="{{ asset('plugins/moment/moment.js') }}"></script>
<script src="{{ asset('plugins/bootstrap-colorpicker/js/bootstrap-colorpicker.min.js') }}"></script>
<script src="{{ asset('plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js') }}"></script>
<script src="{{ asset('plugins/clockpicker/js/bootstrap-clockpicker.min.js') }}"></script>
<script src="{{ asset('plugins/bootstrap-daterangepicker/daterangepicker.js') }}"></script>

<!-- select 2-->
<script src="//cdnjs.cloudflare.com/ajax/libs/lodash.js/4.15.0/lodash.min.js"></script>
<script src="{{ asset('plugins/select2/js/select2.min.js') }}" type="text/javascript"></script>
{{--<!-- tags input0-->--}}

@if( !(Request::is("BuyersPayPayment") || Route::is("Transaction.Value.Create") || Route::is('Transaction.Value.StoreOther')
     || Route::is('AddValuePayment') || Route::is('Admin.Buyers.payments') || Route::is('Transaction.Value.Create.Admin')
     || Route::is('Transaction.Value.StoreOtherAdmin')) )

    <script src="{{ asset('plugins/bootstrap-tagsinput/js/bootstrap-tagsinput.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('pages/jquery.form-advanced.init.js') }}" type="text/javascript"></script>
@endif

<script src="{{ asset('plugins/magnific-popup/js/jquery.magnific-popup.min.js') }}" type="text/javascript"></script>

{{--<!-- Init js -->--}}
<script src="{{ asset('pages/jquery.form-pickers.init.js') }}"></script>

<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.css">

<script src="//cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.js"></script>

{{--Date Time Picker--}}
<script src="{{ asset('js/bootstrap-datetimepicker.min.js') }}"></script>

@if( Route::is("Sales.Dashboard") )
    <script src="{{ asset('js/SalesDashboard/dashboard.js') }}"></script>
@endif
@if( Route::is("CallCenter.Dashboard") )
    <script src="{{ asset('js/SalesDashboard/callCenterDashboard.js') }}"></script>
@endif
