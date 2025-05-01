<?php

use App\Http\Controllers\Api\PingPost\MainApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


// Ping & POST =========================================================================================================

Route::controller(MainApiController::class)->group(function () {
    Route::post('/rest/ping/add_lead', 'ping');
    Route::post('/rest/post/add_lead', 'post');
    Route::post('/rest/direct/add_lead', 'direct_post');
});


//Route::post('/rest/ping/add_lead', 'Api\PingPost\MainApiController@ping');
//Route::post('/rest/post/add_lead', 'Api\PingPost\MainApiController@post');
//Route::post('/rest/direct/add_lead', 'Api\PingPost\MainApiController@direct_post');
// Ping & POST =========================================================================================================

//join_as_a_pro API ==============================================================================================================
Route::post('/rest/pro/join_as_a_pro', 'Api\Pro\GeneralAPIController@join_as_a_pro');
//======================================================================================================================

//Return All Address ===================================================================================================
Route::post('/rest/addressDetails', 'WebSitesAPIController@addressDetails');
//======================================================================================================================




////Start Services Questions =============================================================================================
////Windows
//Route::post('/rest/listOFInstalling', 'Api\Website\QuestionsController@listOFInstalling');
//Route::post('/rest/listOFpriority', 'Api\Website\QuestionsController@listOFpriority');
//Route::post('/rest/listOFNumberOfWindows', 'Api\Website\QuestionsController@listOFNumberOfWindows');
////Solar
//Route::post('/rest/lead_avg_money_electicity_list', 'Api\Website\QuestionsController@lead_avg_money_electicity_list');
//Route::post('/rest/lead_current_utility_provider', 'Api\Website\QuestionsController@lead_current_utility_provider');
//Route::post('/rest/lead_solor_solution_list', 'Api\Website\QuestionsController@lead_solor_solution_list');
//Route::post('/rest/lead_solor_sun_expouser_list', 'Api\Website\QuestionsController@lead_solor_sun_expouser_list');
//Route::post('/rest/property_type_campaign', 'Api\Website\QuestionsController@property_type_campaign');
////Home Security
//Route::post('/rest/lead_installation_preferences', 'Api\Website\QuestionsController@lead_installation_preferences');
////Flooring
//Route::post('/rest/lead_type_of_flooring', 'Api\Website\QuestionsController@lead_type_of_flooring');
//Route::post('/rest/lead_nature_flooring_project', 'Api\Website\QuestionsController@lead_nature_flooring_project');
////Walk-In-Tubs
//Route::post('/rest/lead_walk_in_tub', 'Api\Website\QuestionsController@lead_walk_in_tub');
//Route::post('/rest/lead_desired_featuers', 'Api\Website\QuestionsController@lead_desired_featuers');
////Roofing
//Route::post('/rest/lead_type_of_roofing', 'Api\Website\QuestionsController@lead_type_of_roofing');
//Route::post('/rest/lead_nature_of_roofing', 'Api\Website\QuestionsController@lead_nature_of_roofing');
//Route::post('/rest/lead_property_type_roofing', 'Api\Website\QuestionsController@lead_property_type_roofing');
////Siding
//Route::post('/rest/type_of_siding_lead', 'Api\Website\QuestionsController@type_of_siding_lead');
//Route::post('/rest/nature_of_siding_lead', 'Api\Website\QuestionsController@nature_of_siding_lead');
////Kitchen
//Route::post('/rest/service_kitchen_lead', 'Api\Website\QuestionsController@service_kitchen_lead');
////Bathroom
//Route::post('/rest/campaign_bathroomtype', 'Api\Website\QuestionsController@campaign_bathroomtype');
////Stairs
//Route::post('/rest/stairs_type_lead', 'Api\Website\QuestionsController@stairs_type_lead');
//Route::post('/rest/stairs_reason_lead', 'Api\Website\QuestionsController@stairs_reason_lead');
////HVAC
//Route::post('/rest/furnance_type_lead', 'Api\Website\QuestionsController@furnance_type_lead');
////Plumbing
//Route::post('/rest/plumbing_service_list', 'Api\Website\QuestionsController@plumbing_service_list');
////SunRoom
//Route::post('/rest/sunroom_service_lead', 'Api\Website\QuestionsController@sunroom_service_lead');
////HandyMan
//Route::post('/rest/handyman_service_lead', 'Api\Website\QuestionsController@handyman_service_lead');
//Route::post('/rest/handyman_ammount_work', 'Api\Website\QuestionsController@handyman_ammount_work');
//Route::post('/rest/handyman_childproofing_services', 'Api\Website\QuestionsController@handyman_childproofing_services');
//Route::post('/rest/handyman_doors_windows_many', 'Api\Website\QuestionsController@handyman_doors_windows_many');
//Route::post('/rest/handyman_range_of_age', 'Api\Website\QuestionsController@handyman_range_of_age');
////CounterTops
//Route::post('/rest/countertops_service_lead', 'Api\Website\QuestionsController@countertops_service_lead');
////Doors
//Route::post('/rest/door_typeproject_lead', 'Api\Website\QuestionsController@door_typeproject_lead');
//Route::post('/rest/number_of_door_lead', 'Api\Website\QuestionsController@number_of_door_lead');
////Gutters
//Route::post('/rest/gutters_install_type_leade', 'Api\Website\QuestionsController@gutters_install_type_leade');
//Route::post('/rest/gutters_meterial_lead', 'Api\Website\QuestionsController@gutters_meterial_lead');
////Paving
//Route::post('/rest/paving_service_lead', 'Api\Website\QuestionsController@paving_service_lead');
//Route::post('/rest/paving_asphalt_type', 'Api\Website\QuestionsController@paving_asphalt_type');
//Route::post('/rest/paving_loose_fill_type', 'Api\Website\QuestionsController@paving_loose_fill_type');
//Route::post('/rest/paving_best_describes_priject', 'Api\Website\QuestionsController@paving_best_describes_priject');
////Painting
//Route::post('/rest/painting_service_lead', 'Api\Website\QuestionsController@painting_service_lead');
//Route::post('/rest/painting1_typeof_project', 'Api\Website\QuestionsController@painting1_typeof_project');
//Route::post('/rest/painting1_stories_number', 'Api\Website\QuestionsController@painting1_stories_number');
//Route::post('/rest/painting1_kindsof_surfaces', 'Api\Website\QuestionsController@painting1_kindsof_surfaces');
//Route::post('/rest/painting2_rooms_number', 'Api\Website\QuestionsController@painting2_rooms_number');
//Route::post('/rest/painting2_typeof_paint', 'Api\Website\QuestionsController@painting2_typeof_paint');
//Route::post('/rest/painting3_each_feature', 'Api\Website\QuestionsController@painting3_each_feature');
//Route::post('/rest/painting4_existing_roof', 'Api\Website\QuestionsController@painting4_existing_roof');
//Route::post('/rest/painting5_kindof_texturing', 'Api\Website\QuestionsController@painting5_kindof_texturing');
//Route::post('/rest/painting5_surfaces_textured', 'Api\Website\QuestionsController@painting5_surfaces_textured');
//
//Route::post('/rest/addLeadCustomer', 'WebSitesAPIController@addLeadCustomer');
//Route::post('/rest/add_multi_service_lead', 'WebSitesAPIController@add_multi_service_lead');
//Route::post('/rest/addLeadCustomer2', 'Api\LeadCallToolsController@lead_from_call_tools');
//Route::post('/rest/addLeadCustomer/Ping', 'Api\CallTools\CrmPingsController@lead_from_call_tools');
////End Services Questions ===============================================================================================


//// JOBs API=============================================================================================================
//Route::get('/jobs/sendLeadUnSoldToCallTools', 'Api\Jobs\SendUnSoldLeadToCollToolsController@index');
//======================================================================================================================

//// save data api (Leads Review) ========================================================================================
//Route::post('/rest/savezipcode', 'Api\LeadReview\LeadReviewController@SaveZipCode');
//Route::post('/rest/savename', 'Api\LeadReview\LeadReviewController@SaveName');
//Route::post('/rest/savephoneemail', 'Api\LeadReview\LeadReviewController@SavePhoneEmail');
//Route::post('/rest/saveQuestions', 'Api\LeadReview\LeadReviewController@saveQuestions');
//Route::post('/rest/saveStreetAddress', 'Api\LeadReview\LeadReviewController@saveStreetAddress');
//Route::post('/rest/saveVisitors', 'Api\LeadReview\LeadReviewController@saveVisitors');
////======================================================================================================================

//
////PayPerCall ===========================================================================================================
//Route::post('/PayPerCallApi', 'Api\PayPerCall\PayPerCallController@PayPerCallApi');
//Route::post('/PayPerCallApiPay', 'Api\PayPerCall\PayPerCallController@PayPerCallApiPay');
//Route::post('/PayPerScheduleLeadApiPay', 'Api\PayPerCall\PayPerCallController@PayPerScheduleLeadApiPay');
//Route::get('/SaveDurationTime', 'Api\PayPerCall\PayPerCallController@saveDurationTime');
////======================================================================================================================
//
////Lead Form ============================================================================================================
//Route::post('/saveLeadForm', 'LeadForm\StoreLeadFormController@saveLeadForm');
//Route::post('/form_lead/submit', 'LeadForm\StoreLeadFormController@saveLeadCallTools');
////======================================================================================================================
//
////Our Partners API =====================================================================================================
//Route::post('/getPartnerListSend', 'Ourpartners\OurPartnersController@sendPartners');
////======================================================================================================================
//
////Website Call Leads ===================================================================================================
//Route::post('/rest/CallLeads/Store', 'Api\CallLeads\CallLeadsController@storeCallLeads');
////======================================================================================================================
//
////Tracking the conversion Leads ========================================================================================
//Route::get('/conversion/tracking/{id}/{token}', 'Api\PayPerCall\PayPerCallController@conversionLeads');
////======================================================================================================================
//
//Route::post('/addRecord', 'Api\AddRecordController@addRecord');
//
//Route::post('/rest/IsVerifiedPone', 'WebSitesAPIController@lead_verified_phone');
//Route::post('/rest/Leads/Add', 'WebSitesAPIController@addLeadCustomer');
//
//Route::group(['namespace' => "Api\Domains"], function (){
//    Route::post('/domain', 'domainController@index');
//});

////Send Schedule SMS Job ================================================================================================
//Route::get('/jobs/ScheduleSMS', 'Api\Jobs\ScheduleSmsController@index');
////======================================================================================================================
//
////Send RevShare Job ====================================================================================================
//Route::get('/jobs/rev_share', 'Api\Jobs\RevShareJobController@index');
////======================================================================================================================

