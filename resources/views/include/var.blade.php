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
