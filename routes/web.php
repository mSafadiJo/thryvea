<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminHomeController;
use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Mail;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
//Auth
Auth::routes(['verify' => false]);
Route::get('logoutMiddleWare', 'Auth\LoginController@logout');

Route::get('/send-test-email', function () {
    $toEmail = 'tech@thryvea.co';  // Replace with your own email address

    Mail::raw('This is a test email from Laravel using Mailtrap SMTP.', function ($message) use ($toEmail) {
        $message->to($toEmail)
            ->subject('Test Email from Laravel');
    });

    return 'Test email sent!';
});

//Home Page
Route::get('/home', 'HomeController@index')->name('home');
Route::get('/', 'HomeController@index');

//Admin
Route::controller(AdminHomeController::class)->group(function () {
    Route::get('/Admin_Home', 'index')->name('AdminHome');
    Route::get('/AdminProfile', 'AdminProfileShow')->name('AdminProfile');
    Route::post('/UpdateUserAdmin', 'updateUser')->name('UpdateUserAdmin');
    Route::post('/UpdatePasswprdUserAdmin', 'updatePasswprdUser')->name('UpdatePasswprdUserAdmin');
});

//Super Admin  Admin Managment
Route::resource('/AdminManagment', "AdminController");

//GetState
Route::post('/getState', 'StateController@getAll')->name('GetStates');
Route::post('/getStateAll', 'StateController@getAllWhithNoFillter')->name('GetStatesAll');
Route::post('/getStateSelected', 'StateController@getAllWithSelect')->name('GetStatesSelected');

//GetCity
Route::post('/getCity', 'cityController@getAll')->name('GetCities');
Route::post('/getCityALL', 'cityController@getAllWhithNoFillter')->name('GetCitiesALL');
Route::post('/getAllWhithFillter', 'cityController@getAllWhithFillter')->name('getAllWhithFillter');
Route::post('/getCountiesALL', 'cityController@getAllWhithNoFillterCounties')->name('getCountiesALL');
Route::post('/getCountieswithFillter', 'cityController@getAllWhithFillterCounties')->name('getCountieswithFillter');
Route::post('/getCitySelected', 'cityController@getAllWithSelect')->name('GetCitiesSelected');
Route::post('/getAllCounties', 'cityController@getAllCounties')->name('getAllCounties');
Route::post('/getCityReview', 'cityController@getCityReview')->name('getCityReview');

//GetZipCodes
Route::post('/getZipCodes', 'ZipCodesListController@getAll')->name('getZipCodes');
Route::post('/getZipCodesAll', 'ZipCodesListController@getAllWhithNoFillterZipCode')->name('getZipCodesAll');
Route::post('/getAllWhithFillterZipCode', 'ZipCodesListController@getAllWhithFillterZipCode')->name('getAllWhithFillterZipCode');
Route::post('/getZipCodesSelected', 'ZipCodesListController@getAllWithSelect')->name('getZipCodesSelected');

Route::post('/getAllZipCodeReview', 'ZipCodesListController@getAllZipCodeReview')->name('getAllZipCodeReview');

//user Profile Buyer
Route::get('/Buyer/UserProfile', 'UserHomePageController@userProfileShow')->name('UserProfileBuyer');
//user Profile Seller
Route::get('/Seller/UserProfile', 'UserHomePageController@userProfileShow')->name('UserProfileSeller');
//user Profile
Route::post('/UpdateUser', 'UserHomePageController@updateUser')->name('UpdateUser');
Route::post('/UpdatePasswprdUser', 'UserHomePageController@updatePasswprdUser')->name('UpdatePasswprdUser');

//user Campain
Route::get('/Campaign_list', 'HomeController@ListOfCampaign')->name('Campaign_List');
Route::get('/Campaign_service', 'CampainController@index')->name('Campaign_service');
Route::get('/addCampaign/{id}', 'CampainController@AddForm')->where('id', '[0-9]+')->name('addCampain');
Route::post('/CampaignStor', 'CampainController@stor')->name('CampaignStor');
Route::get('/Campaign/{id}', 'CampainController@ShowCampaignDetails')->where('id', '[0-9]+')->name('ShowCampaignDetails');
Route::get('/Campaign/Edit/{id}', 'CampainController@EditCampaign')->where('id', '[0-9]+')->name('ShowCampaignEdit');
Route::post('/Campaign/Update', 'CampainController@UpdateCampaign')->name('CampaignUpdate');
Route::post('/Campaign/Delete', 'CampainController@DeleteCampaign')->name('CampaignDelete');
Route::post('/Campaign/AddClone', 'CampainController@CampaignAddClone')->name('CampaignAddClone');
Route::post('/Campaign/ChangeStatus', 'CampainController@CampaignChangeStatus')->name('CampaignChangeStatus');

//user payment
Route::get('/Buyers_Payment', 'BuyersPayment@index')->name('BuyersPayment');
Route::get('/Buyers_Payment/Add', 'BuyersPayment@AddForm')->name('BuyersPaymentADDform');
Route::post('/Buyers_Payment/store', 'BuyersPayment@storePayment')->name('storePayment');
Route::get('/BuyersPayPayment', 'BuyersPayment@buyersPayPayment')->name('BuyersPayPayment');
Route::post('/AddTransactionPayPalAjax','BuyersPayment@addTransactionPayPal')->name('AddTransactionPayPal');
Route::get('/Buyers_Payment/Edit/{id}', 'BuyersPayment@BuyersPaymentEdit')->where('id', '[0-9]+')->name('BuyersPaymentEdit');
Route::post('/Buyers_Payment/Update', 'BuyersPayment@updatePayment')->name('updatePayment');
Route::post('/Buyers_Payment/Delete', 'BuyersPayment@PaymentDelete')->name('PaymentDelete');
Route::post('/Buyers_Payment/GetPaymentDetails', 'BuyersPayment@getPaymentDetailsById')->name('GetPaymentDetailsAjax');

//Super Admin Payments authorize.net
Route::post('/dopay/online', 'AdminPayments\PaymentAuthController@handleonlinepay')->name('dopay.online');
//Route::post('/RefundPayments', 'AdminPayments\RefundAuthController@refundTransaction')->name('refundTrxPaymentsForm');
Route::post('/echeckPayment/{user_id}', 'AdminPayments\EcheckPaymentsController@debitBankAccount')->where('user_id', '[0-9]+')->name('echeckPaymentformAdmin');
//Route::post('echeckPaymentRefund', 'AdminPayments\RefundEcheckController@refund_payments')->name('echeckPaymentRefund');
//Route::post('paypalPaymentRefund', 'AdminPayments\RefundPaypalController@refund_payment')->name('refundTrxPaymentsFormPaypal');



//Admin Buyers
Route::get('/Admin_Buyers', 'AdminBuyersController@index')->name('Admin_Buyers');
Route::get('/Admin_Buyers/Add', 'AdminBuyersController@showFormAddBuyers')->name('Admin_BuyersAdd');
Route::post('/AdminsStore', 'AdminBuyersController@AdminsStore')->name('AdminsStore');
Route::post('/AdminsStore/Delete', 'AdminBuyersController@DeleteBuyers')->name('AdminsStoreDelete');
Route::get('/Admin_Buyers/{id}/Edit', 'AdminBuyersController@editBuyers')->where('id', '[0-9]+')->name('Admin_Buyers.edit');
Route::post('/Admin_Buyers/{id}/Update', 'AdminBuyersController@updateUser')->where('id', '[0-9]+')->name('Admin_Buyers.updateUser');
Route::post('/Admin_Buyers/{id}/Update/Password', 'AdminBuyersController@updateUserPassword')->where('id', '[0-9]+')->name('Admin_Buyers.updateUser.Password');
Route::post('Admin/Buyers/Export', 'AdminBuyersController@buyers_export')->name("Admin.Buyers.Export");

//Admin Campaign
Route::get('/Admin_Campaign', 'AdminCampaignController@listOfBuyers')->name('Admin_Campaign');
Route::get('/Admin/Campaign/{id}', 'AdminCampaignController@index')->where('id', '[0-9]+')->name('Admin_Campaign_buyers');
Route::get('/AdminCampaign/{id}/details', 'AdminCampaignController@ShowCampaignDetails')->where('id', '[0-9]+')->name('ShowAdminCampaignDetails');
Route::get('/AdminCampaign/Edit/{id}', 'AdminCampaignController@EditCampaign')->where('id', '[0-9]+')->name('ShowAdminCampaignEdit');
Route::post('/AdminCampaign/Update', 'AdminCampaignController@UpdateCampaign')->name('AdminCampaignUpdate');
Route::post('/AdminCampaign/AddClone', 'AdminCampaignController@CampaignAddClone')->name('AdminCampaignAddClone');
Route::post('/AdminCampaign/Delete', 'AdminCampaignController@DeleteCampaign')->name('AdminCampaignDelete');
Route::post('/AdminCampaign/ChangeStatus', 'AdminCampaignController@CampaignChangeStatus')->name('AdminCampaignChangeStatus');
Route::get('/AdminCampaignCreate', 'AdminCampaignController@listofcampaignType')->name('Admin.Campaign.Create');
Route::get('/addAdminCampaign/{id}', 'AdminCampaignController@AddForm')->where('id', '[0-9]+')->name('addAdminCampain');
Route::post('/AdminCampaignStor', 'AdminCampaignController@stor')->name('AdminCampaignStor');
Route::post('/Admin_Campaign/Filter', 'AdminCampaignController@filterList')->name('AdminCampaignStor.Filter');
Route::post('/AdminCampaign/AddCallToolsCampaignId', 'AdminCampaignController@AddCallToolsCampaignId')->name('AddCallToolsCampaignId');
Route::get('/Admin/Campaign/{id}/ZipCodes', 'AdminCampaignController@list_of_zipcodes_camp')->name('Admin.Campaign.ZipCodes.List');
Route::post('/AdminCampaign/sendTestLead', 'AdminCampaignController@send_test_lead')->name('AdminCampaign.sendTestLead');

//Super Admin Services
Route::get('/AdminServices', 'SuperAdminController@ListServices')->name('SuberAdminServices');
Route::get('/AdminServices/Edit/{id}', 'SuperAdminController@Edit_Service')->where('id', '[0-9]+')->name('Edit_Service');
Route::post('/AdminServices/Update', 'SuperAdminController@updateServicedUserAdmin')->name('UpdateServicedSuperAdmin');
Route::post('/AdminServices/Update/Status', 'SuperAdminController@updateStatusServicedUserAdmin')->name('UpdateStatusServicedSuperAdmin');
Route::get('/AdminServices/Add', 'SuperAdminController@AddForm')->name('AdminServicesAddForm');
Route::post('/AdminServices/Store', 'SuperAdminController@Store')->name('AdminServicesStore');
Route::post('/AdminServices/Delete/{id}', 'SuperAdminController@DeleteService')->where('id', '[0-9]+')->name('DeleteServiceAdmin');



//General AccessLogs
Route::get('/Admin/{section}/AccessLog', 'AccessLogController@index')->name('AccessLog.index');
Route::post('/Admin/AccessLog/search', 'AccessLogController@search')->name('AccessLog.search');

//Campaign Leads User Buyers
Route::get('/CampaignLead/{id}', 'CampaignLeadsController@ShowCampaignLeadsDetails')->where('id', '[0-9]+')->name('ShowCampaignLeadsDetails');

//Campaign Leads Admin
Route::get('/Admin/list_of_leads_received', 'CampaignLeadsAdminController@list_of_leads_received')->name('list_of_leads_received');
//Route::post('/Admin/list_of_leads_received/ajax', 'CampaignLeadsAdminController@list_of_leads_received_ajax')->name('list_of_leads_receivedAjax');
Route::get('/Admin/lead/{id}/details/Received', 'CampaignLeadsAdminController@ShowCampaignLeads_receivedDetails')->where('id', '[0-9]+')->name('Details_of_leads_received');
Route::get('/Admin/lead/{id}/details/Return', 'CampaignLeadsAdminController@ShowCampaignLeads_receivedDetails')->where('id', '[0-9]+')->name('Details_of_leads_return');
Route::get('/Admin/list_of_leads_lost/Lost', 'CampaignLeadsAdminController@list_of_leads_lost')->name('list_of_leads_lost');
//Route::post('/Admin/list_of_leads_Lost/ajax', 'CampaignLeadsAdminController@list_of_leads_LostAjax')->name('list_of_leads_LostAjax');
Route::get('/Admin/lead/{id}/details/Lost', 'CampaignLeadsAdminController@ShowCampaignLeads_lostDetails')->where('id', '[0-9]+')->name('Details_of_leads_lost');
Route::get('/Admin/list_of_leads_Refund', 'CampaignLeadsAdminController@list_of_leads_Refund')->name('list_of_leads_Refund');
//Route::post('/Admin/list_of_leads_Refund/ajax', 'CampaignLeadsAdminController@list_of_leads_Refund_ajax')->name('list_of_leads_Refund_ajax');

Route::get('/Admin/list_of_leads_receivedCallCenter', 'CampaignLeadsAdminController@list_of_leads_receivedCallCenter')->name('list_of_leads_receivedCallCenter');
//Route::post('/Admin/list_of_leads_receivedCallCenter/ajax', 'CampaignLeadsAdminController@list_of_leads_receivedCallCenter_ajax')->name('list_of_leads_receivedAjaxCallCenter');
Route::get('/Admin/list_of_leads_CallCenterReturns', 'CampaignLeadsAdminController@list_of_leads_CallCenterReturns')->name('list_of_leads_CallCenterReturns');
//Route::post('/Admin/list_of_leads_CallCenterReturns/ajax', 'CampaignLeadsAdminController@list_of_leads_CallCenterReturns_ajax')->name('list_of_leads_CallCenterReturnsAjaxCallCenter');

Route::get('/Admin/list_of_leads_Archive', 'CampaignLeadsAdminController@list_of_leads_Archive')->name('list_of_leads_Archive');
//Route::post('/Admin/list_of_leads_Archive/ajax', 'CampaignLeadsAdminController@list_of_leads_Archive_ajax')->name('list_of_leads_ArchiveAjax');

Route::get('/Admin/Lead/List', 'CampaignLeadsAdminController@list_of_leads_all')->name('list_of_leads_all');
//Route::post('/Admin/list_of_leads_All/ajax', 'CampaignLeadsAdminController@list_of_leads_all_ajax')->name('list_of_leads_allajax');

Route::get('/Admin/Lead/List/SMS_Email', 'CampaignLeadsAdminController@list_of_leads_sms_email')->name('list_of_leads_SMS_Email');
Route::post('/Admin/list_of_leads_SMS_Email/ajax', 'CampaignLeadsAdminController@list_of_leads_sms_email_ajax')->name('list_of_leads_SMS_Email_ajax');

Route::post('/Admin/Leads/Export', 'CampaignLeadsAdminController@export_lead_data')->name('export_lead_data');
Route::post('/Buyers/Leads/Export', 'HomeController@export_lead_data')->name('buyers_export_lead_data');

//Buyers Transactions
Route::get('/Transaction/index', 'TransactionController@index')->name('Transaction.index');
Route::post('paypal', 'PaymentPayPallController@payWithpaypal')->name('paypal');
// route for check status of the payment
Route::get('status', 'PaymentPayPallController@getPaymentStatus');
Route::post('/Transaction/Value/Store', 'TransactionController@storeValue')->name('Transaction.Value.Store');
Route::get('/Transaction/Value/Create/{value}/{type}', 'TransactionController@CreateValue')->name('Transaction.Value.Create');
Route::post('/Transaction/Value/StoreOther', 'TransactionController@storeOtherpaymentVal')->name('Transaction.Value.StoreOther');

//Super Admin Payments
Route::get('Admin/Buyers/{id}/Wallet/', 'BuyersDetailsAdminController@listOfUserWallet')->where('id', '[0-9]+')->name("Admin.Buyers.Wallet");
Route::get('Admin/Buyers/{id}/Wallet/Create', 'BuyersDetailsAdminController@createWallet')->where('id', '[0-9]+')->name("Admin.Buyers.Wallet.create");
Route::post('Admin/Buyers/{id}/Wallet/Store', 'BuyersDetailsAdminController@storeWallet')->where('id', '[0-9]+')->name("Admin.Buyers.Wallet.Store");
Route::get('Admin/Buyers/{id}/Wallet/Edit', 'BuyersDetailsAdminController@editWallet')->where('id', '[0-9]+')->name("Admin.Buyers.Wallet.edit");
Route::post('Admin/Buyers/Wallet/Update', 'BuyersDetailsAdminController@updateWallet')->where('id', '[0-9]+')->name("Admin.Buyers.Wallet.Update");
Route::post('Admin/Buyers/Wallet/delete', 'BuyersDetailsAdminController@PaymentDelete')->where('id', '[0-9]+')->name("Admin.Buyers.Wallet.delete");
Route::get('Admin/Buyers/{id}/payments/', 'BuyersDetailsAdminController@payments')->where('id', '[0-9]+')->name("Admin.Buyers.payments");
Route::get('Admin/Buyers/{id}/Transactions', 'BuyersDetailsAdminController@listOfUserTransactions')->where('id', '[0-9]+')->name("Admin.Buyers.Transactions");
Route::get('Admin/Sellers/{id}/Transactions', 'BuyersDetailsAdminController@listOfSellersTransactions')->where('id', '[0-9]+')->name("Admin.Sellers.Transactions");
Route::post('Admin/addPromotionalCredit/{id}', 'BuyersDetailsAdminController@addPromotionalCredit')->where('id', '[0-9]+')->name("addPromotionalCredit");
Route::post('Admin/addACHCredit/{id}', 'BuyersDetailsAdminController@addACHCredit')->where('id', '[0-9]+')->name("addACHCredit");
Route::post('Admin/addReturnLeadBidPayment/{id}', 'BuyersDetailsAdminController@addReturnLeadBidPayment')->where('id', '[0-9]+')->name("addReturnLeadBidPayment");
Route::get('Admin/Buyers/{id}/Ticket/', 'BuyersDetailsAdminController@listOfUserTicket')->where('id', '[0-9]+')->name("Admin.Buyers.Ticket");
Route::get('Admin/Buyers/{id}/payments/{value}/{type}', 'TransactionController@CreateValueAdmin')->where('id', '[0-9]+')->name('Transaction.Value.Create.Admin');
Route::post('Admin/Buyers/payments/StoreOther', 'TransactionController@storeOtherpaymentValAdmin')->name('Transaction.Value.StoreOtherAdmin');

Route::post('Admin/ReturnLead', 'BuyersDetailsAdminController@store_returnlead')->name("ReturnLeadAdmin");

//Route::get('stripe', 'StripePaymentController@stripe');
Route::post('stripe', 'StripePaymentController@stripePost')->name('stripe.post');
//Route::post('stripeRefund', 'StripePaymentController@refund_payments')->name('stripeRefund');

//Route::get('2checkout', function(){
//    return view('Buyers.Payment.payment_2checkout');
//});

Route::post('pay2checkout', 'payment2checkoutbuyers@pay2checkout')->name('pay2checkout');

Route::post('/paypalPro', 'PayPalProPaymentController@paypalPro')->name('PayPalProPaymentController');

//Route::get('/AddValuePayment', 'PaymentPayPalButtonsConroller@index')->name('AddValuePayment');
Route::get('/AddValuePayment', 'BuyersPayment@buyersPayPayment')->name('AddValuePayment');
Route::post('/TransactionvalueinPayPalButtons', 'PaymentPayPalButtonsConroller@storTransaction')->name('TransactionvalueinPayPalButtons');

Route::get('/Ticket', 'BuyersTicketController@index')->name('buyersTicket');
Route::post('/Ticket/store/issues', 'BuyersTicketController@store_issues')->name('buyersTicket.store.issues');
Route::post('/Ticket/store/returnlead', 'BuyersTicketController@store_returnlead')->name('buyersTicket.store.returnlead');
Route::post('/Ticket/store/changeStatus', 'BuyersDetailsAdminController@ticketChangeStatus')->name('TicketChangeStatus');
//new code for new return lead page
Route::get('/ReturnLeadBuyer', 'BuyersTicketController@ReturnLeadBuyers')->name('ReturnLeadBuyer');
Route::post('/Buyers/ReturnLeadBuyer/ajax', 'BuyersTicketController@ReturnLeadBuyersAjax')->name('ReturnLeadBuyerAjax');

Route::post('/Admin/Claim/Add', 'ClaimController@adminAddClaim')->name('Admin.Claim.Add.Buyer');
Route::get('/Admin/Claim', 'ClaimController@index')->name('Admin.Claim.index');
Route::post('/Admin/Claim/Edit_Status', 'ClaimController@edit_status_Claim')->name('Admin.Claim.Edit_Status');

Route::post('Admin/Buyers/AddPaymentMethod', 'AdminBuyersController@addPaymentMethod')->name("Admin.Buyers.AddPaymentMethod");
Route::get('/Admin/AccountOwnerShip/Payment', 'AccountownershipController@index')->name('Admin.AccountOwnerShip.Payment');
Route::post('/Admin/AccountOwnerShip/Payment/Edit', 'AccountownershipController@changePayment_typeStatus')->name('Admin.AccountOwnerShip.Payment.Edit');

//Reports
Route::get('/Admin/Report/Lead_Volume', 'ReportsController@lead_volume')->name('Admin.Report.lead_volume');
Route::post('/Admin/Report/Lead_Volume/Data', 'ReportsController@lead_volume_data')->name('Admin.Report.lead_volume.data');

Route::get('/Admin/Report/Performance_Reports', 'ReportsController@performance_reports')->name('Admin.Report.performance_reports');
Route::post('/Admin/Report/Performance_Reports/Data', 'ReportsController@Performance_Reports_data')->name('Admin.Report.Performance_Reports.data');

Route::get('/Admin/Report/Lead_From_Websites', 'ReportsController@lead_from_websites')->name('Admin.Report.lead_from_websites');
Route::post('/Admin/Report/Lead_From_Websites/Data', 'ReportsController@lead_from_websites_data')->name('Admin.Report.lead_from_websites.data');

Route::get('/Admin/Report/Lead_report', 'ReportsController@Lead_report')->name('Admin.Report.lead_report');
Route::post('/Admin/Report/Lead_report/Data', 'ReportsController@Lead_report_data')->name('Admin.Report.lead_report.data');

//Sales/SDR/Account manager Report
Route::get('/Admin/Report/Sales', 'ReportsController@sales_report')->name('Admin.Report.SalesReport');
Route::post('/Admin/Report/Sales/Data', 'ReportsController@sales_report_data')->name('Admin.Report.SalesReport.data');

Route::get('/Admin/Report/SDR', 'ReportsController@sdr_report')->name('Admin.Report.SDRReport');
Route::post('/Admin/Report/SDR/Data', 'ReportsController@sdr_report_data')->name('Admin.Report.SDRReport.data');

Route::get('/Admin/Report/AccountManager', 'ReportsController@accountManager_report')->name('Admin.Report.AccountManagerReport');
Route::post('/Admin/Report/AccountManager/Data', 'ReportsController@accountManager_report_data')->name('Admin.Report.AccountManagerReport.data');

Route::resource('Admin/PromoCode', 'PromoCodeController');

//For Report Search Fillter Data
//================================================================
//DataAdmin
Route::post('Report/GetAdmins', 'Reports\FilterReportController@admins')->name('Report.GetAdmins.data');
Route::post('Report/GetServices', 'Reports\FilterReportController@services')->name('Report.GetServices.data');
Route::post('Report/GetStates', 'Reports\FilterReportController@states')->name('Report.GetStates.data');
Route::post('Report/GetCounties', 'Reports\FilterReportController@counties')->name('Report.GetCounties.data');
Route::post('Report/GetCities', 'Reports\FilterReportController@cities')->name('Report.GetCities.data');
Route::post('Report/GetZipcodes', 'Reports\FilterReportController@zipcodes')->name('Report.GetZipcodes.data');
Route::post('Report/getBuyers', 'Reports\FilterReportController@buyers')->name('Report.getBuyers.data');
Route::post('Report/getEnvironments', 'Reports\FilterReportController@environments')->name('Report.getEnvironments.data');
Route::post('Report/getTraffic_source', 'Reports\FilterReportController@traffic_source')->name('Report.getTraffic_source.data');
Route::post('Report/getSellers', 'Reports\FilterReportController@sellers')->name('Report.getSellers.data');
//================================================================

//Push lead
//================================================================
Route::get('/Admin/PushLead/{id}', 'PushLeadController@index')->where('id', '[0-9]+')->name('Admin.PushLead');
Route::post('/Admin/PushLead/{id}/submit', 'PushLeadController@addLead')->where('id', '[0-9]+')->name('Admin.PushLead.submit');
//================================================================

//Campaigns Area
//================================================================
//Route::post('Admin/Campaign/deleteAllStateFilter', 'CampaignMainController@deleteAllStateFilter')->name('Admin.Campaign.deleteAllStateFilter');
//Route::post('Admin/Campaign/deleteAllState', 'CampaignMainController@deleteAllState')->name('Admin.Campaign.deleteAllState');
Route::post('Admin/Campaign/deleteAllZipcode2', 'CampaignMainController@deleteAllZipcode2')->name('Admin.Campaign.deleteAllZipcode2');
Route::post('Admin/Campaign/deleteAllCounty', 'CampaignMainController@deleteAllCounty')->name('Admin.Campaign.deleteAllCounty');
Route::post('Admin/Campaign/deleteAllCity', 'CampaignMainController@deleteAllCity')->name('Admin.Campaign.deleteAllCity');
Route::post('Admin/Campaign/deleteAllZipcode', 'CampaignMainController@deleteAllZipcode')->name('Admin.Campaign.deleteAllZipcode');
Route::post('Admin/Campaign/deleteAllCountyExpect', 'CampaignMainController@deleteAllCountyExpect')->name('Admin.Campaign.deleteAllCountyExpect');
Route::post('Admin/Campaign/deleteAllCityExpect', 'CampaignMainController@deleteAllCityExpect')->name('Admin.Campaign.deleteAllCityExpect');
Route::post('Admin/Campaign/deleteAllZipcodeExpect', 'CampaignMainController@deleteAllZipcodeExpect')->name('Admin.Campaign.deleteAllZipcodeExpect');
Route::post('Admin/Campaign/exportCampaignTarget', 'CampaignMainController@exportCampaignTarget')->name('Admin.Campaign.exportCampaignTarget');
Route::post('Admin/Campaign/exportCampZipcodes', 'CampaignMainController@exportCampZipcodes')->name('Admin.Campaign.exportCampZipcodes');
Route::post('Admin/Campaign/exportCampExpectZipcodes', 'CampaignMainController@exportCampExpectZipcodes')->name('Admin.Campaign.exportCampExpectZipcodes');

Route::post('Admin/Campaign/sendTestLead', 'CampaignMainController@sendTestLead')->name('Admin.Campaign.sendTestLead');
Route::post('/Admin/CampaignExport', 'AdminCampaignController@ListOfCampaignsExports')->where('id', '[0-9]+')->name('Admin_Campaign_Export');
//================================================================
//Forms
//================================================================
//Route::get('/Forms/Solar', 'FormsController@index');
//Route::get('/Forms', 'FormsController@index');
//Route::get('/forms', 'FormsController@index');
//Route::get('/Form', 'FormsController@index');
//Route::get('/form', 'FormsController@index');
//================================================================
//Route::post('Forms/Submit', 'FormsBackendController@addLeadCustomer')->name('Forms.Submit');
//================================================================

Route::get('/Campaign/{id}/Builder/Form', 'AdminCampaignController@BuilderTrendForm')->where('id', '[0-9]+')->name('ShowBuilderTrendForm');

Route::get('/Admin/list_of_tasks', 'Task\TaskManagementController@index')->name('List_Of_Tasks');
Route::get('/Admin/add_tasks', 'Task\TaskManagementController@showFormAdd')->name('Add_Tasks');
Route::post('/TasksStore', 'Task\TaskManagementController@TasksStore')->name('TasksStore');
Route::delete('/deleteTask/{id}', 'Task\TaskManagementController@deleteTask')->name('users.destroy');
Route::post('/ChangeTaskStatus', 'Task\TaskManagementController@TaskStatus')->name('TaskStatus');
Route::post('/ChangeTaskSignedTo', 'Task\TaskManagementController@TaskSignedTo')->name('TaskSignedTo');
Route::get('/Admin/Tasks/{id}/Edit', 'Task\TaskManagementController@edit')->name('Edit_Tasks_Management');
Route::post('/Admin/Tasks/{id}/Update', 'Task\TaskManagementController@update')->name('Update_Tasks_Management');

Route::get('/Admin/DeleteLead/{id}', 'CampaignLeadsAdminController@deletelead')->where('id', '[0-9]+')->name('Admin.DeleteLead');

Route::get('/Admin/DeleteSoldLead/{id}', 'CampaignLeadsAdminController@deleteSoldLead')->where('id', '[0-9]+')->name('Admin.DeleteSoldLead');

//Sellers Campaigns
Route::resource('/Admin/Seller/Campaigns', 'Seller\CampaignController');
Route::get('/Admin/Seller/{id}/Campaigns', 'Seller\CampaignController@list')->name('Admin.Seller.Campaigns.list');
Route::post('/Admin/Seller/Campaigns/Filter', 'Seller\CampaignController@filterList')->name('Admin.Seller.Campaigns.filter');
Route::post('/Admin/Seller/Campaigns/AddClone', 'Seller\CampaignController@AddClone')->name('Admin.Seller.Campaigns.AddClone');

Route::get('/Admin/list_of_PING_leads', 'PingDataController@index')->name('Admin.list_of_leads_PINGs');
//Route::post('/Admin/list_of_PING_leads/list', 'PingDataController@list')->name('Admin.list_of_leads_PINGs.list');
Route::get('/Admin/lead/{id}/details/PING', 'PingDataController@details')->where('id', '[0-9]+')->name('Admin.list_of_leads_PINGs_details');

Route::get('/Admin/list_of_leads_Affiliate', 'CampaignLeadsAdminController@AffiliateLeads')->name('Admin.list_of_leads_Affiliate');
//Route::post('/Admin/list_of_leads_Affiliate/list', 'CampaignLeadsAdminController@listofAffiliateLeads')->name('Admin.list_of_leads_Affiliate.list');

//Sellers Reports
Route::get('/Admin/Report/Seller_Lead_Volume', 'Super_admin\ReportsController@seller_lead_volume')->name('Admin.Reports.Seller_Lead_Volume');
Route::post('/Admin/Report/Seller_Lead_Volume/search', 'Super_admin\ReportsController@seller_lead_volume_data')->name('Admin.Reports.Seller_Lead_Volume.Search');

// Leads review
Route::get('/Admin/list_of_leads_Review', 'LeadsReviewController@list_of_leads_review')->name('list_of_leads_Review');
//Route::post('/Admin/list_of_leads_Review/ajax', 'LeadsReviewController@list_of_leads_ReviewAjax')->name('list_of_leads_ReviewAjax');
Route::get('/Admin/lead/{id}/details/review', 'LeadsReviewController@Leads_ReviewDetails')->where('id', '[0-9]+')->name('Details_of_leads_review');
Route::get('/Admin/DeleteLead/{id}/review', 'LeadsReviewController@deletelead')->where('id', '[0-9]+')->name('DeleteLeadReview');
Route::get('/Admin/EditLead/{id}/review', 'LeadsReviewController@Editlead')->where('id', '[0-9]+')->name('EditLeadReview');
Route::post('/Admin/UpdateleadsReview', 'LeadsReviewController@UpdateLeadReview')->name('UpdateLeadReview');


///update lead customer
Route::get('/Admin/EditLead/{id}/CustomerLead', 'CampaignLeadsAdminController@EditCustomerLead')->where('id', '[0-9]+')->name('EditCustomerLead');
Route::post('/Admin/UpdateCustomerLead', 'CampaignLeadsAdminController@UpdateCustomerLead')->name('UpdateCustomerLead');

//Lead Marketing TS
Route::resource('/LeadMarketing/Platforms', 'LeadMarketing\PlatformsController');
Route::resource('/LeadMarketing/TrafficSources', 'LeadMarketing\TrafficSourcesController');

//Block Leads Info
Route::get('/Admin/BlockLeadsInfo', 'BlockLeadsInfoController@index')->name('Admin.BlockLeadsInfo');
Route::post('/Admin/BlockLeadsInfo/logic_data', 'BlockLeadsInfoController@logic_data')->name('Admin.BlockLeadsInfo.logic_data');

//Marketing Sections
Route::get('/Admin/Marketing/SendSMS', 'MarketingSectionController@sms_index')->name('Admin.Marketing.SendSMS');
Route::post('/Admin/Marketing/SendSMS/submit', 'MarketingSectionController@sms_submit')->name('Admin.Marketing.SendSMS.submit');

Route::get('/Admin/Marketing/SendProSMS', 'MarketingSectionController@pro_sms_index')->name('Admin.Marketing.SendProSMS');
Route::post('/Admin/Marketing/SendProSMS/submit', 'MarketingSectionController@pro_sms_submit')->name('Admin.Marketing.SendProSMS.submit');

Route::get('/Admin/Marketing/GenerateURL', 'MarketingSectionController@generateURL_index')->name('Admin.Marketing.GenerateURL');
Route::post('/Admin/Marketing/GenerateURL/submit', 'MarketingSectionController@generateURL_submit')->name('Admin.Marketing.GenerateURL.submit');

//Prospects
Route::resource('/Admin/Prospects', 'ProspectUserController');
Route::get('/Admin/Prospects/{id}/Convert', 'ProspectUserController@convert')->where('id', '[0-9]+')->name('Prospects.convert');
Route::get('/Admin/Prospects/{id}/Transactions', 'ProspectUserController@transaction')->where('id', '[0-9]+')->name('Prospects.transaction');
Route::post('/Admin/Prospects/Transaction/Store', 'ProspectUserController@transaction_store')->name('Prospects.transaction_store');
Route::post('Admin/Prospects/Export', 'ProspectUserController@prospects_export')->name("Admin.Prospects.Export");
Route::post('Admin/Prospects/Search', 'ProspectUserController@search')->name("Admin.Prospects.Search");

//Join As A Pro
Route::get('Admin/JoinAsAPro/List', 'AdminBuyersController@join_as_apro_list')->name("Admin.JoinAsAPro.List");
Route::post('Admin/JoinAsAPro/Export', 'AdminBuyersController@join_as_apro_export')->name("Admin.JoinAsAPro.Export");

//Change Lead Status On Buyer Dashboard
Route::post('/buyer/LeadChangeStatus', 'HomeController@lead_change_status')->name('buyer.LeadChangeStatus');

//Route::get("/migrate", function(){
//    \Illuminate\Support\Facades\Artisan::call("migrate");
//});

//SessionRecording
Route::get('/viewSessionRecording', 'Recording\SessionRecordingController@viewSessionRecordingTable')->name('viewSessionRecording');
Route::get('/viewSessionRecordingVideo/{id}', 'Recording\SessionRecordingController@viewSessionRecordingVideo')->name('viewSessionRecordingVideo');

//new routs for buyers sections
Route::get('/Buyers/ListOfLeadsBuyersSection', 'HomeController@ListOfLeadsBuyers')->name('ListOfLeadsBuyersSection');

Route::get('/BuyerAndSellerHome', 'HomeController@HomeBuyerAndSeller')->name('BuyerAndSellerHome');
Route::get('/Buyers/BuyerHome', 'HomeController@HomeBuyer')->name('BuyerHome');
Route::get('/Seller/SellerHome', 'HomeController@HomeSeller')->name('SellerHome');

Route::get('/Seller/ListOfLeadsSellerSection', 'HomeController@ListOfLeadsSeller')->name('ListOfLeadsSellerSection');

Route::post('/Buyers/list_of_leads_Buyers/ajax', 'HomeController@list_of_leads_BuyersAjax')->name('list_of_leads_BuyersAjax');

Route::post('/Seller/list_of_leads_Seller/ajax', 'HomeController@list_of_leads_SellerAjax')->name('list_of_leads_Seller');

//new routs for filter campaign by zipcode and service
Route::get('/Admin/FilterCampaign', 'NewFilter\FilterCampaignController@index')->name('FilterCampaignByZipCodeAndService');

Route::get('/Admin/FilterZipCodeByServiceShow', 'NewFilter\FilterCampaignController@listZipCodeByServiceShow')->name('FilterZipCodeByServiceShow');
Route::post('/Admin/FilterZipCode', 'NewFilter\FilterCampaignController@ExportlistZipCodeByService')->name('ExportlistZipCodeByService');

Route::get('/Admin/FilterLostLeadReportShow', 'NewFilter\FilterCampaignController@listLostLeadReportShow')->name('listLostLeadReportShow');
Route::post('/Admin/FilterLostLeadReportAjax', 'NewFilter\FilterCampaignController@filterLostLeadReport')->name('FilterLostLeadReportAjax');

//new routs for Ticket
Route::get('/Admin/Ticket/Return', 'Ticket\TicketController@ShowReturnTicket')->name('ShowReturnTicket');
Route::get('/Admin/Ticket/Issue', 'Ticket\TicketController@ShowIssueTicket')->name('ShowIssueTicket');

Route::post('/Admin/Ticket/ReturnAll', 'Ticket\TicketController@ShowReturnTicketAjax')->name('ShowReturnTicketAjax');
Route::post('/Admin/Ticket/IssueAll', 'Ticket\TicketController@ShowIssueTicketAjax')->name('ShowIssueTicketAjax');

// new route for CRM report
Route::get('/Admin/FilterCRMShow', 'NewFilter\CrmFiliterController@listCRMReportShow')->name('FilterCRMShow');
Route::post('/Admin/FilterCRM/Export', 'NewFilter\CrmFiliterController@export')->name('FilterCRMExport');

//bandWidth
Route::get('/Admin/BandWidth', 'BandWidth\BandWidthController@SendMessage')->name('BandWidthMessage');
Route::get('/Admin/BandWidthFro', 'BandWidth\BandWidthController@forward')->name('BandWidthFro');
Route::get('/Admin/BandWidthOrder', 'BandWidth\BandWidthController@makeOrder')->name('BandWidthOrder');

Route::get('/map', 'Map\mapController@map')->name('map');
Route::get('/map_search', 'Map\mapController@map_search')->name('map_search');

Route::get('/BuyersMap', 'Map\mapController@mapBuyer')->name('mapBuyer');

Route::get('/Buyers_map_search', 'Map\mapController@Buyers_map_search')->name('Buyers_map_search');

//for get all city and zipcode when user search
Route::post('/cityResult', 'cityController@citySearchResult')->name('cityResult');
Route::post('/zipResult', 'ZipCodesListController@zipSearchResult')->name('zipResult');
Route::post('/countyResult', 'cityController@countiesSearchResult')->name('countyResult');

//Sales Commissions Report
Route::get('/Report/SalesCommission', 'Reports\SalesCommissionController@salesCommission')->name('Reports.salesCommission');
Route::post('/Report/SalesCommission/Search', 'Reports\SalesCommissionController@salesCommissionSearch')->name('Reports.salesCommission.Search');

//Shop Lead Managements=======================================================================
//Sources Percentage
Route::get('/ShopLeads', 'ShopLeads\ShopLeadsController@index')->name('ShopLeads');
Route::post('/ShopLeadsSource', 'ShopLeads\ShopLeadsController@getAllSourceByCampaign')->name('ShopLeadsSource');
Route::post('/ShopLeadsCampaigns', 'ShopLeads\ShopLeadsController@getAllCampaignsByBuyer')->name('ShopLeadsCampaigns');
Route::post('/ShopLeadsSave', 'ShopLeads\ShopLeadsController@saveShopLead')->name('saveShopLead');
Route::get('/ShopLeadsEdit/{id}', 'ShopLeads\ShopLeadsController@EditShopLead')->name('ShopLeadsEdit');
Route::post('/ShopLeadsUpdate', 'ShopLeads\ShopLeadsController@UpdateShopLead')->name('UpdateShopLead');
Route::post('/ShopLeadsDelete/{id}', 'ShopLeads\ShopLeadsController@DeleteShopLead')->name('ShopLeadsDelete');

//ExcludeSellers
Route::get('/ExcludeAndIncludeSellers', 'ShopLeads\ExcludeSellersController@index')->name('ExcludeAndIncludeSellers');
Route::post('/ExcludeAndIncludeSellersSave', 'ShopLeads\ExcludeSellersController@saveShopLead')->name('ExcludeAndIncludeSellersSave');
Route::get('/ExcludeAndIncludeSellersEdit/{id}', 'ShopLeads\ExcludeSellersController@EditShopLead')->name('ExcludeAndIncludeSellersEdit');
Route::post('/ExcludeAndIncludeSellersUpdate', 'ShopLeads\ExcludeSellersController@UpdateShopLead')->name('ExcludeAndIncludeSellersUpdate');
Route::post('/ExcludeAndIncludeSellersDelete/{id}', 'ShopLeads\ExcludeSellersController@DeleteShopLead')->name('ExcludeAndIncludeSellersDelete');
Route::post('/ShopLeadsCampaignsEx', 'ShopLeads\ExcludeSellersController@getAllCampaignsByBuyer')->name('ShopLeadsCampaignsEx');

//ExcludeBuyers
Route::get('/ExcludeBuyers', 'ShopLeads\ExcludeBuyersController@index')->name('ExcludeBuyers');
Route::post('/ExcludeBuyersSave', 'ShopLeads\ExcludeBuyersController@saveShopLead')->name('ExcludeBuyersSave');
Route::post('/ExcludeBuyersDelete/{id}', 'ShopLeads\ExcludeBuyersController@DeleteShopLead')->name('ExcludeBuyersDelete');

//Lead Form =======================================================================
Route::get('/LeadForm', 'LeadForm\LeadFormController@listOfLeadForm')->name('listOfLeadForm');
Route::get('/LeadFormDetails/{id}', 'LeadForm\LeadFormController@leadDetails')->name('LeadFormDetails');
Route::post('/list_of_leads_Form/ajax', 'LeadForm\LeadFormController@list_of_leads_FormAjax')->name('list_of_leads_FormAjax');
Route::post('/LeadsForm/Export', 'LeadForm\LeadFormController@export_leadForm_data')->name('export_leadForm_data');

//Sales Dashboard  =====================================================
Route::get('/Admin/Sales/Dashboard', 'AgentDashboard\AdminDashboardController@index')->name('Admin.Sales.Dashboard');
Route::post('/Admin/Sales/storeSetting', 'AgentDashboard\AdminDashboardController@storeSetting')->name('Admin.Sales.StoreSetting');
Route::post('/Admin/Sales/storeTransfers', 'AgentDashboard\AdminDashboardController@storeTransfers')->name('Admin.Sales.StoreTransfers');
//CallCenter Dashboard  =====================================================
Route::get('/Admin/CallCenter/Dashboard', 'AgentDashboard\AdminDashboardController@index_callCenter')->name('Admin.CallCenter.Dashboard');

Route::get('/Sales/Dashboard', 'AgentDashboard\SalesDashboardController@index')->name('Sales.Dashboard');
Route::post('/Sales/Dashboard/Reload', 'AgentDashboard\SalesDashboardController@reload')->name('Sales.Dashboard.Reload');

Route::get('/CallCenter/Dashboard', 'AgentDashboard\SalesDashboardController@index_callCenter')->name('CallCenter.Dashboard');
Route::post('/CallCenter/Dashboard/Reload', 'AgentDashboard\SalesDashboardController@reload_callCenter')->name('CallCenter.Dashboard.Reload');

//For Power Solar Slots
Route::get('/PowerSolarSlot', 'AgentDashboard\SalesDashboardController@powerSolarSlot');

//List Of Seller
Route::get('/ListOfSeller', 'Seller\ListOfSellerController@ListOfSellers')->name('ListOfSeller');
Route::post('/ListOfSeller', 'Seller\ListOfSellerController@seller_return')->name('returnSeller');

//Our Partners Pages route
Route::resource('/Admin/OurPartners', 'Ourpartners\OurPartnersController');
Route::get('/OurPartners/FetchData', 'Ourpartners\OurPartnersController@fetch_data');
Route::post('/OurPartners/Export', 'Ourpartners\OurPartnersController@exportPartners')->name('ExportPartners');

//Admin Payments
Route::post('/Admin/Payments/Add', 'AdminPayments\PaymentsController@add')->name('Admin.Payments.Add');
Route::post('/Admin/Payments/Refund', 'AdminPayments\PaymentsController@refund')->name('Admin.Payments.Refund');

//Setting
Route::get('/Admin/Setting/Payment', 'Setting\PaymentsController@index')->name('Admin.Setting.Payment');
Route::post('/Admin/Setting/Payment/Store', 'Setting\PaymentsController@store')->name('Admin.Setting.Payment.Store');

//Old or Inactive Buyers
Route::get('/Old_Buyers', 'AdminBuyersController@listOfOldBuyers')->name('Old_Buyers');

//Users Payments
Route::get('/Report/users_payments', 'Reports\PaymentsReportController@users_payments')->name('Reports.users_payments');

//Call Leads
Route::get('/Admin/CallLead/List', 'CallLeads\CallLeadsController@index')->name('list_of_CallLead');
Route::post('/Admin/CallLead/List/ajax', 'CallLeads\CallLeadsController@search')->name('list_of_CallLead_ajax');
Route::get('/Admin/CallLead/{id}/details', 'CallLeads\CallLeadsController@show')->where('id', '[0-9]+')->name('Details_of_CallLead');
Route::post('/Admin/CallLead/Export', 'CallLeads\CallLeadsController@export')->name('export_CallLead_data');

//Affiliate Sellers Profit Reports
Route::get('/Report/Affiliate', 'Reports\AffiliateReportController@index')->name('AffiliateReport');
Route::post('/Report/AffiliateAjax', 'Reports\AffiliateReportController@listAffiliateReportShow')->name('AffiliateReportAjax');
//Agents CallCenter Reports (
Route::get('/Report/AgentsCallCenter', 'Reports\AgentsCallCenterReportController@index')->name('AgentsCallCenter');
Route::post('/Report/AgentsCallCenterAjax', 'Reports\AgentsCallCenterReportController@listAgentsReportShow')->name('AgentsCallCenterAjax');
//CallCenter leads profit reports
Route::get('/Report/CallCenterProfit', 'Reports\CallCenterLeadsProfitController@index')->name('Reports.callCenterProfit');
Route::post('/Report/CallCenterProfit/Search', 'Reports\CallCenterLeadsProfitController@searchCallCenterReports')->name('Reports.callCenterProfit.Search');

//Search Lead Managements
Route::get('/AllLeadList/fetch_data', 'CampaignLeadsAdminController@fetch_data_all_list_lead');
Route::get('/LeadReceived/fetch_data', 'CampaignLeadsAdminController@fetch_data_lead_received');
Route::get('/LeadLost/fetch_data', 'CampaignLeadsAdminController@fetch_data_lead_Lost');
Route::get('/LeadAffiliate/fetch_data', 'CampaignLeadsAdminController@fetch_data_lead_Affiliate');
Route::get('/LeadRefund/fetch_data', 'CampaignLeadsAdminController@fetch_data_lead_Refund');
Route::get('/LeadArchive/fetch_data', 'CampaignLeadsAdminController@fetch_data_lead_Archive');
Route::get('/LeadPing/fetch_data', 'PingDataController@fetch_data_lead_Ping');
Route::get('/LeadReview/fetch_data', 'LeadsReviewController@fetch_data_lead_Review');
Route::get('/LeadCallCenter/fetch_data', 'CampaignLeadsAdminController@fetch_data_lead_CallCenter');
Route::get('/LeadCallCenterReturn/fetch_data', 'CampaignLeadsAdminController@fetch_data_lead_CallCenter_Returns');
Route::get('/CRMResponse/fetch_data', 'NewFilter\CrmFiliterController@ShowCRMAjax');

//Call Center Sources
Route::resource('/LeadMarketing/CallCenterSources', 'LeadMarketing\CallCenterSourcesController');

// change lead status by QA
Route::post('/Lead/ChangeStatus', 'CampaignLeadsAdminController@QAChangeStatusLead')->name('QAChangeStatusLead');

//Pay Per Click Report
Route::get('/Report/PayPerClick', 'Reports\PayPerClickReport@index')->name('payPerClickReport');
Route::get('/Report/PayPerClick_fetch_data', 'Reports\PayPerClickReport@fetch_data_payPerClick');

//PayPerClick Buyer Dashboard
Route::get('/Buyers/ListOfClickLeads', 'HomeController@listOfClickLeads')->name('Buyers.ListOfClickLeads');
Route::post('/Buyers/ListOfClickLeads_data', 'HomeController@listOfClickLeadsAjax')->name('Buyers.ListOfClickLeads.Ajax');

//Rev share editing
Route::get('/Admin/Campaign/Rev_Share/{id}', 'AdminBuyersController@Rev_Share')->where('id', '[0-9]+')->name('Rev_Share');
Route::post('/Admin/Campaign/Rev_ShareSave', 'AdminBuyersController@Rev_Share_Save')->name('Rev_ShareSave');

//Exclude Sources
Route::get('/ExcludeSources', 'ShopLeads\ExcludeSourcesController@index')->name('ExcludeSources');
Route::post('/ExcludeSourcesSave', 'ShopLeads\ExcludeSourcesController@saveSources')->name('ExcludeSourcesSave');
Route::get('/ExcludeSourcesEdit/{id}', 'ShopLeads\ExcludeSourcesController@EditSources')->name('ExcludeSourcesEdit');
Route::post('/ExcludeSourcesUpdate', 'ShopLeads\ExcludeSourcesController@UpdateSources')->name('ExcludeSourcesUpdate');
Route::post('/ExcludeSourcesDelete/{id}', 'ShopLeads\ExcludeSourcesController@DeleteSources')->name('ExcludeSourcesDelete');

//Get Buyers Locations Reports
Route::get('/Report/BuyersLocation', 'Reports\BuyersLocationReportController@index')->name('BuyersLocationReport.index');
Route::post('/Report/BuyersLocation/GetData', 'Reports\BuyersLocationReportController@getData')->name('BuyersLocationReport.getData');

//fetch transactions data
Route::get('/Admin/Transactions/fetch_data', 'BuyersDetailsAdminController@fetch_data_listOfUserTransactions');
Route::get('/Admin/Transactions/fetch_data_seller', 'BuyersDetailsAdminController@fetch_data_Transactions_seller');
Route::get('/Transaction/fetch_data', 'TransactionController@fetch_data_BuyerTransactions');

//lead cost by TS
Route::get('/TSLeadCost', 'LeadMarketing\TrafficSourcesController@ts_lead_cost')->name('tsLeadCost');
Route::post('/TSLeadCost/save', 'LeadMarketing\TrafficSourcesController@ts_lead_cost_save')->name('tsLeadCostSave');

//marketing cost Report
Route::get('/Admin/Report/MarketingCost', 'ReportsController@marketing_cost')->name('marketingCost');
Route::post('/Admin/Report/MarketingCost/Data', 'ReportsController@marketing_cost_data')->name('marketingCostData');

//Update Buyer Lead Note
Route::post('/buyer_lead_note_update', 'HomeController@buyer_lead_note_update')->name('buyer_lead_note_update');

//Export List of transactions buyer payments
Route::post('/Admin/Transactions/Export', 'BuyersDetailsAdminController@listOfUserTransactionsExport')->name('listOfUserTransactionsExport');
Route::post('/transaction/buyer/export', 'TransactionController@export')->name('transaction.buyer.export');

//To RevShare Seller
Route::get('/Seller/RevShareSeller/Home', 'HomeController@HomeRevShareSeller')->name('RevShareSellerHome');
Route::post('/Seller/RevShareSellerHome/ajax', 'HomeController@HomeRevShareSellerAjax')->name('HomeRevShareSellerAjax');
Route::get('/Seller/RevShareSeller/Leads', 'HomeController@ListOfLeadsRevShare')->name('ListOfLeadsRevShareSeller');
Route::get('/Seller/list_of_leads_RevShare/ajax', 'HomeController@list_of_LeadsRevShareAjax');

//Delete ACH Payments
Route::post('Admin/DeleteACHCredit', 'BuyersDetailsAdminController@DeleteACHCredit')->name("DeleteACHCredit");

//Exclude URL
Route::get('/ExcludeUrl', 'ShopLeads\ExcludeUrlController@index')->name('ExcludeUrl');
Route::post('/ExcludeUrlSave', 'ShopLeads\ExcludeUrlController@saveUrl')->name('ExcludeUrlSave');
Route::get('/ExcludeUrlEdit/{id}', 'ShopLeads\ExcludeUrlController@EditUrl')->name('ExcludeUrlEdit');
Route::post('/ExcludeUrlUpdate', 'ShopLeads\ExcludeUrlController@UpdateUrl')->name('ExcludeUrlUpdate');
Route::post('/ExcludeUrlDelete/{id}', 'ShopLeads\ExcludeUrlController@DeleteUrl')->name('ExcludeUrlDelete');

//Exclude Seller Sources
Route::get('/ExcludeSellerSources', 'ShopLeads\ExcludeSellerSourcesController@index')->name('ExcludeSellerSources');
Route::post('/ExcludeSellerSourcesSave', 'ShopLeads\ExcludeSellerSourcesController@saveSellerSources')->name('ExcludeSellerSourcesSave');
Route::get('/ExcludeSellerSourcesEdit/{id}', 'ShopLeads\ExcludeSellerSourcesController@EditSellerSources')->name('ExcludeSellerSourcesEdit');
Route::post('/ExcludeSellerSourcesUpdate', 'ShopLeads\ExcludeSellerSourcesController@UpdateSellerSources')->name('ExcludeSellerSourcesUpdate');
Route::post('/ExcludeSellerSourcesDelete/{id}', 'ShopLeads\ExcludeSellerSourcesController@DeleteSellerSources')->name('ExcludeSellerSourcesDelete');

// LeadsBridgeExport
Route::get('/Export/ExportLeadsData', 'Api\LeadsBridgeExports\LeadsBridgeExportController@downloadCSVLeadsBridge')->name('exportLeadsBridge');


Route::post('/Seller/RevShareSeller/ExportData', 'HomeController@exportRevShareData')->name('exportRevShareData');
Route::post('/Seller/RevShareSeller/ExportDataLeadsTable', 'HomeController@ExportDataLeadsTable')->name('ExportDataLeadsTable');

Route::post('Admin/Ticket/ReturnExport', 'Ticket\TicketController@returnTicketsExport')->name('exportReturnTicket');
