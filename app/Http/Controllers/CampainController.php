<?php

namespace App\Http\Controllers;

use App\AccessLog;
use App\AcculynxCrm;
use App\CallTools;
use App\Campaign;
use App\CampaignType;
use App\City;
use App\County;
use App\Five9;
use App\Improveit360Crm;
use App\Jangle;
use App\LeadConduit;
use App\leadPerfectionCrm;
use App\LeadPortal;
use App\Leads_Pedia;
use App\leads_pedia_track;
use App\Marketsharpm;
use App\Models\Builder_Prime_CRM;
use App\Models\HatchCrm;
use App\Models\job_nimbus;
use App\Models\SalesforceCrm;
use App\Models\SetShapeCrm;
use App\Models\Sunbase;
use App\Models\ZapierCrm;
use App\Service_Campaign;
use App\Services\AllServicesQuestions;
use App\State;
use App\User;
use App\ZipCodesList;
use App\ZohoCrm;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\SalesOrder;
use App\hubspot ;
use App\BuilderTrend ;
use App\Pipe_Drive;
use App\Services\ApiMain;
use jeremykenedy\Slack\Client;

class CampainController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'buyersCustomer']);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $types = CampaignType::where('buyers_status', 1)->get();
        return view('Buyers.Campaign.AddCampain')->with('types', $types);
    }

    public function AddForm($type_id){
        if(Cache::has('AllState')){
            $states = Cache::get("AllState");
        } else {
            $states = State::All();
        }

        $address = array(
            'states' => $states
        );

        $websites = DB::table('domains')->where('status', 1)->get()->All();
        $services = DB::table('service__campaigns')->where('service_is_active', 1)->get()->All();
        $utility_providers = DB::table('lead_current_utility_provider')->groupBy('lead_current_utility_provider_name')->get()->All();

        //For check the campaign type
        switch($type_id){
            case 2:
                $view = "Buyers.Campaign.PayPerCall.Creat";
                break;
            case 3:
                $view = "Buyers.Campaign.PayPerClick.create";
                break;
            case 5:
                $view = "Buyers.Campaign.PayPerScheduleLead.create";
                break;
            case 8:
                $view = "Buyers.Campaign.PayPerRevShare.create";
                break;
            default:
                $view = "Buyers.Campaign.AddCampainForm";
        }

        return view($view)
            ->with('services', $services)
            ->with('websites', $websites)
            ->with('type_id', $type_id)
            ->with('address', $address)
            ->with('utility_providers', $utility_providers);
    }

    public function stor(Request $request){
        $this->validate($request, [
            'Campaign_name' => ['required', 'string', 'max:255'],
            'service_id' => ['required', 'string', 'max:255'],
            'propertytype' => ['required'],
            'Installings' => ['required'],
            'homeowned' => ['required'],
            'type_id' => ['required'],
        ]);

        $crm = $request['crm_type'];
        if( $request->is_multi_crms != 1 ){
            $crm = array($crm);
        }

        if( empty($request['is_ping_account']) ){
            $request['is_ping_account'] = 0;
        }

        $budget_bid_shared = 0;
        $budget_must_be_sh = 0;
        if( !empty($request['budget_bid_shared']) ){
            $budget_bid_shared = $request['budget_bid_shared'];
            $number_of_lead_sh = $request['numberOfCustomerCampaign'];
            $number_of_lead_p_sh = $request['numberOfCustomerCampaign_period'];
            $budget_period_sh = $request['budget_period'];

            if( $request['is_ping_account'] == 0 ){
                $budget_must_be_sh = $number_of_lead_sh * $request['budget_bid_shared'];
                if( $budget_period_sh != $number_of_lead_p_sh){
                    if( $number_of_lead_p_sh == 1 ){
                        if( $budget_period_sh == 2 ){
                            $budget_must_be_sh = $budget_must_be_sh * 7;
                        } else if( $budget_period_sh == 3 ){
                            $budget_must_be_sh = $budget_must_be_sh * 30;
                        }
                    } else  if( $number_of_lead_p_sh == 2 ){
                        if( $budget_period_sh == 1 ){
                            $budget_must_be_sh = ceil($budget_must_be_sh / 7);
                        } else if( $budget_period_sh == 3 ){
                            $budget_must_be_sh = ceil(($budget_must_be_sh / 7) * 30);
                        }
                    } else if( $number_of_lead_p_sh == 3 ){
                        if( $budget_period_sh == 1 ){
                            $budget_must_be_sh = ceil($budget_must_be_sh / 30);
                        } else if( $budget_period_sh == 2 ){
                            $budget_must_be_sh = ceil(($budget_must_be_sh / 30) * 7);
                        }
                    }
                }
            } else {
                $budget_must_be_sh = $request['budget'];
            }
        } elseif($request['type_id'] == 3 && $request['budget_bid_shared'] == 0){
            $budget_bid_shared = $request['budget_bid_shared'];
            $budget_must_be_sh = $request['budget'];
        }

        $budget_bid_exclusive = 0;
        $budget_must_be_ex = 0;
        if( !empty($request['budget_bid_exclusive']) ){
            $budget_bid_exclusive = $request['budget_bid_exclusive'];
            $number_of_lead_ex = $request['numberOfCustomerCampaign_exclusive'];
            $number_of_lead_p_ex = $request['numberOfCustomerCampaign_period_exclusive'];
            $budget_period_ex = $request['budget_period_exclusive'];

            if( $request['is_ping_account'] == 0 ) {
                $budget_must_be_ex = $number_of_lead_ex * $request['budget_bid_exclusive'];
                if ($budget_period_ex != $number_of_lead_p_ex) {
                    if ($number_of_lead_p_ex == 1) {
                        if ($budget_period_ex == 2) {
                            $budget_must_be_ex = $budget_must_be_ex * 7;
                        } else if ($budget_period_ex == 3) {
                            $budget_must_be_ex = $budget_must_be_ex * 30;
                        }
                    } else if ($number_of_lead_p_ex == 2) {
                        if ($budget_period_ex == 1) {
                            $budget_must_be_ex = ceil($budget_must_be_ex / 7);
                        } else if ($budget_period_ex == 3) {
                            $budget_must_be_ex = ceil(($budget_must_be_ex / 7) * 30);
                        }
                    } else if ($number_of_lead_p_ex == 3) {
                        if ($budget_period_ex == 1) {
                            $budget_must_be_ex = ceil($budget_must_be_ex / 30);
                        } else if ($budget_period_ex == 2) {
                            $budget_must_be_ex = ceil(($budget_must_be_ex / 30) * 7);
                        }
                    }
                }
            } else {
                $budget_must_be_ex = $request['budget_exclusive'];
            }
        } elseif($request['type_id'] == 3 && $request['budget_bid_shared'] == 0){
            $budget_bid_exclusive = $request['budget_bid_exclusive'];
            $budget_must_be_ex = $request['budget_exclusive'];
        }

        if( empty($request['is_utility_providers']) ){
            $request['is_utility_providers'] = 0;
        }

        $campaign = new Campaign();

        $campaign->campaign_name = $request['Campaign_name'];
        $campaign->campaign_count_lead = $request['numberOfCustomerCampaign'];
        $campaign->campaign_budget = $budget_must_be_sh;
        $campaign->period_campaign_count_lead_id = $request['numberOfCustomerCampaign_period'];
        $campaign->period_campaign_budget_id = $request['budget_period'];
        $campaign->campaign_count_lead_exclusive = $request['numberOfCustomerCampaign_exclusive'];
        $campaign->campaign_budget_exclusive = $budget_must_be_ex;
        $campaign->period_campaign_count_lead_id_exclusive = $request['numberOfCustomerCampaign_period_exclusive'];
        $campaign->period_campaign_budget_id_exclusive = $request['budget_period_exclusive'];
        $campaign->service_campaign_id = $request['service_id'];
        $campaign->campaign_status_id = 3;
        $campaign->user_id = Auth::user()->id;
        $campaign->campaign_distance_area = $request['distance_area'];
//        $campaign->campaign_distance_area_expect = $request['distance_area_expect'];
        $campaign->campaign_budget_bid_exclusive = $budget_bid_exclusive;
        $campaign->campaign_budget_bid_shared = $budget_bid_shared;
        $campaign->campaign_Type = $request['type_id'];
        $campaign->sec_service_accept = 1;
        $campaign->multi_service_accept = 1;

        $campaign->crm                                       = (!empty($crm) ?  json_encode($crm) : "[]");
        $campaign->is_multi_crms                             = ($request['is_multi_crms'] == 1 ?  $request['is_multi_crms'] : 0);
        $campaign->email1                                    = $request['FirstEmail'];
        $campaign->email2                                    = $request['SecondEmail'];
        $campaign->email3                                    = $request['ThirdEmail'];
        $campaign->email4                                    = $request['FourthEmail'];
        $campaign->email5                                    = $request['FifthEmail'];
        $campaign->email6                                    = $request['SixthEmail'];
        $campaign->phone1                                    = trim(str_replace([' ', '(', ')', '-'], '', $request['FirstPhone']));
        $campaign->phone2                                    = trim(str_replace([' ', '(', ')', '-'], '', $request['SecondPhone']));
        $campaign->phone3                                    = trim(str_replace([' ', '(', ')', '-'], '', $request['ThirdPhone']));
        $campaign->subject_email                             = $request['SubjectEmail'];
        $campaign->lead_source                               = '["All Source"]';
        $campaign->is_ping_account                           = $request['is_ping_account'];
        $campaign->is_utility_solar_filter                   = $request['is_utility_providers'];
        $campaign->vendor_id                                 = time();
        $campaign->website                                   = json_encode($request['website']);
        $campaign->transfer_numbers                          = $request['transfer_numbers'];
        $campaign->delivery_Method_id                        = json_encode(array_values(array_unique(!empty($request['deliveryMethod']) ? $request['deliveryMethod'] : array())));
        $campaign->custom_paid_campaign_id                   = json_encode(array_values(array_unique(!empty($request['customPaid']) ? $request['customPaid'] : array())));
        $campaign->click_url                                 = $request['landingPageUrl'];
        $campaign->click_text                                = $request['click_text'];
        $campaign->save();
        $campaign_id = DB::getPdo()->lastInsertId();

        if (config('app.env', 'local') != "local") {
            if( config('app.name', '') == "Zone1Remodeling" ){
                $admin_email1 = "jim@zone1remodeling.com";
            } else {
                $admin_email1 = "logan@allieddigitalmedia.com";
            }
            $admin_email2 = "mike@allieddigitalmedia.com";
            $admin_name = "Team";
            $subject_email = "New Campaign";
            $data_msg = array(
                'name' => $admin_name,
                'campaign' => $request['Campaign_name'],
                'url' => 'Admin/Campaign/' . Auth::user()->id
            );
            Mail::send(['text' => 'Mail.campaign_approved'], $data_msg, function ($message) use ($admin_email1, $admin_name, $subject_email, $admin_email2) {
                $message->to($admin_email1, $admin_name)->cc($admin_email2)->subject($subject_email);
                $message->from(config('mail.from.address', ''), config('mail.from.name', ''));
            });
        }

        DB::table('campaign_time_delivery')->insert([
            'campaign_id'                       => $campaign_id,
            'campaign_time_delivery_status'     => 1,
            'campaign_time_delivery_timezone'   => 5,
            'status_sun'                        => 0,
            'status_mon'                        => 0,
            'status_tus'                        => 0,
            'status_wen'                        => 0,
            'status_thr'                        => 0,
            'status_fri'                        => 0,
            'status_sat'                        => 0,
        ]);

        $campaignsQuestionsDB = DB::table('campaigns_questions');

        $allServicesQuestion = new AllServicesQuestions();

        $allServicesQuestion->insertFromCampaigns($campaignsQuestionsDB, $request, $campaign_id);



        try {
            if (in_array(1, $crm)) {
                $callTools = new CallTools();
                $callTools->api_key = $request['ApiKey'];
                $callTools->file_id = $request['FileId'];
                $callTools->campaign_id = $campaign_id;
                $callTools->save();
            }
            if (in_array(2, $crm)) {
                $Five9 = new Five9();
                $Five9->five9_domian = $request['Five9Domian'];
                $Five9->five9_list = $request['Five9List'];
                $Five9->campaign_id = $campaign_id;
                $Five9->save();
            }
            if (in_array(3, $crm)) {
                $leadsPedia = new Leads_Pedia();
                $leadsPedia->leads_pedia_url = $request['LeadsPediaUrl'];
                $leadsPedia->campine_key = $request['CampineKey'];
                $leadsPedia->campaign_id = $campaign_id;
                $leadsPedia->IP_Campaign_ID = $request['IP_Campaign_ID'];
                $leadsPedia->leads_pedia_url_ping = $request['leads_pedia_url_ping'];
                $leadsPedia->save();
            }
            if (in_array(4, $crm)) {
                $hubspot = new hubspot();
                $hubspot->Api_Key = $request['hubspotKey'];
                $hubspot->key_type = ($request['hubspot_key_type'] == 1 ? $request['hubspot_key_type'] : 0);
                $hubspot->campaign_id = $campaign_id;
                $hubspot->save();
            }
            if (in_array(5, $crm)) {
                $BuilderTrend = new BuilderTrend();
                $BuilderTrend->builder_id = $request['builderId'];
                $BuilderTrend->campaign_id = $campaign_id;
                $BuilderTrend->save();
            }
            if (in_array(6, $crm)) {
                $PipeDrive = new Pipe_Drive();
                $PipeDrive->api_token = $request['api_token'];
                $PipeDrive->persons_domain = $request['persons_domain'];
                $PipeDrive->persons = ($request['pipedrive_person'] == 1 ? $request['pipedrive_person'] : 0);
                $PipeDrive->deals_leads = ($request['pipedrive_deals_leads'] == 1 ? $request['pipedrive_deals_leads'] : 0);
                $PipeDrive->campaign_id = $campaign_id;
                $PipeDrive->save();
            }
            if (in_array(7, $crm)) {
                $jangle = new Jangle();
                $jangle->Authorization = $request['Authorization'];
                $jangle->PingUrl = $request['PingUrl'];
                $jangle->PostUrl = $request['PostUrl'];
                $jangle->campaign_id = $campaign_id;
                $jangle->save();
            }
            if (in_array(8, $crm)) {
                $leadPerfectionCrm = new leadPerfectionCrm();
                $leadPerfectionCrm->lead_perfection_url = $request['lead_perfection_url'];
                $leadPerfectionCrm->lead_perfection_srs_id = $request['lead_perfection_srs_id'];
                $leadPerfectionCrm->lead_perfection_pro_id = $request['lead_perfection_pro_id'];
                $leadPerfectionCrm->lead_perfection_pro_desc = $request['lead_perfection_pro_desc'];
                $leadPerfectionCrm->lead_perfection_sender = $request['lead_perfection_sender'];
                $leadPerfectionCrm->campaign_id = $campaign_id;
                $leadPerfectionCrm->save();
            }
            if (in_array(9, $crm)) {
                $Improveit360Crm = new Improveit360Crm();
                $Improveit360Crm->improveit360_url = $request['improveit360_url'];
                $Improveit360Crm->improveit360_source = $request['improveit360_source'];
                $Improveit360Crm->market_segment = $request['improveit360_market_segment'];
                $Improveit360Crm->source_type = $request['improveit360_source_type'];
                $Improveit360Crm->project = $request['improveit360_project'];
                $Improveit360Crm->campaign_id = $campaign_id;
                $Improveit360Crm->save();
            }
            if (in_array(10, $crm)) {
                $LeadConduitCrm = new LeadConduit();
                $LeadConduitCrm->post_url = $request['leadconduit_url'];
                $LeadConduitCrm->campaign_id = $campaign_id;
                $LeadConduitCrm->save();
            }
            if (in_array(11, $crm)) {
                $Marketsharpm = new Marketsharpm();
                $Marketsharpm->MSM_source = $request['MSM_source'];
                $Marketsharpm->MSM_coy = $request['MSM_coy'];
                $Marketsharpm->MSM_formId = $request['MSM_formId'];
                $Marketsharpm->campaign_id = $campaign_id;
                $Marketsharpm->save();
            }
            if (in_array(12, $crm)) {
                $LeadPortal = new LeadPortal();
                $LeadPortal->key = $request['leadPortal_Key'];
                $LeadPortal->SRC = $request['leadPortal_SRC'];
                $LeadPortal->api_url = $request['leadPortal_api_url'];
                $LeadPortal->type = $request['leadPortal_type'];
                $LeadPortal->campaign_id = $campaign_id;
                $LeadPortal->save();
            }
            if (in_array(13, $crm)) {
                $leads_pedia_track = new leads_pedia_track();
                $leads_pedia_track->lp_campaign_id = $request['lp_campaign_id'];
                $leads_pedia_track->lp_campaign_key = $request['lp_campaign_key'];
                $leads_pedia_track->ping_url = $request['leads_pedia_track_ping_url'];
                $leads_pedia_track->post_url = $request['leads_pedia_track_post_url'];
                $leads_pedia_track->campaign_id = $campaign_id;
                $leads_pedia_track->save();
            }
            if (in_array(14, $crm)) {
                $AcculynxCrm = new AcculynxCrm();
                $AcculynxCrm->api_key = $request['acculynx_api_key'];
                $AcculynxCrm->campaign_id = $campaign_id;
                $AcculynxCrm->save();
            }
            if (in_array(15, $crm)) {
                $ZohoCrm = new ZohoCrm();
                $ZohoCrm->refresh_token = $request['refresh_token'];
                $ZohoCrm->client_id = $request['client_id'];
                $ZohoCrm->client_secret = $request['client_secret'];
                $ZohoCrm->redirect_url = $request['redirect_url'];
                $ZohoCrm->Lead_Source = $request['Lead_Source'];
                $ZohoCrm->campaign_id = $campaign_id;
                $ZohoCrm->save();
            }
            if (in_array(16, $crm)) {
                $HatchCrm = new HatchCrm();
                $HatchCrm->dep_id = $request['HatchDeptID'];
                $HatchCrm->org_token = $request['HatchOrgToken'];
                $HatchCrm->URL_sub = $request['Hatch_URL_sub'];
                $HatchCrm->campaign_id = $campaign_id;
                $HatchCrm->save();
            }
            if (in_array(17, $crm)) {
                $salesforceCRM = new SalesforceCrm();
                $salesforceCRM->url = $request['salesforce_url'];
                $salesforceCRM->lead_source = $request['salesforce_lead_source'];
                $salesforceCRM->retURL = $request['salesforce_retURL'];
                $salesforceCRM->oid = $request['salesforce_oid'];
                $salesforceCRM->campaign_id = $campaign_id;
                $salesforceCRM->save();
            }
            if (in_array(18, $crm)){
                $builderPrimeCRM = new Builder_Prime_CRM();
                $builderPrimeCRM->post_url = $request['builderPrimePostURL'];
                $builderPrimeCRM->secret_key = $request['builderPrimeSecretKey'];
                $builderPrimeCRM->campaign_id = $campaign_id;
                $builderPrimeCRM->save();
            }
            if (in_array(19, $crm)){
                $zapier_crm = new ZapierCrm();
                $zapier_crm->post_url = $request['zapier_url'];
                $zapier_crm->campaign_id = $campaign_id;
                $zapier_crm->save();
            }
            if (in_array(20, $crm)){
                $set_shape_crm = new SetShapeCrm();
                $set_shape_crm->post_url = $request['set_shape_url'];
                $set_shape_crm->campaign_id = $campaign_id;
                $set_shape_crm->save();
            }
            if (in_array(21, $crm)){
                $set_job_nimbus_crm = new job_nimbus();
                $set_job_nimbus_crm->api_key = $request['set_nimbus_key'];
                $set_job_nimbus_crm->campaign_id = $campaign_id;
                $set_job_nimbus_crm->save();
            }
            if (in_array(22, $crm)){
                $set_sunbase_crm = new Sunbase();
                $set_sunbase_crm->url = $request['sunbase_url'];
                $set_sunbase_crm->schema_name = $request['sunbase_schema_name'];
                $set_sunbase_crm->campaign_id = $campaign_id;
                $set_sunbase_crm->save();
            }
        }
        catch (\Illuminate\Database\QueryException $ex){

        }

        //** import Zipcods from excel ***/

        $array_zipcode_distance         = array();
        $zip_codes_array_saved          = array();
        $zipcode_id_array               = array();
        $array_zipcode_distance_final   = array();

        if ($request->hasFile('listOfzipcode')) {
            $dataexcel = Excel::toArray(new SalesOrder, $request->file('listOfzipcode'));
            $dataexcel = array_unique($dataexcel[0], SORT_REGULAR);

            foreach ($dataexcel as $item) {
//                if (Cache::has("AllZipCode")) {
//                    $zipcode_data_cache = Cache::get("AllZipCode")->where('zip_code_list', $item[0]);
//                    foreach ($zipcode_data_cache as $data) {
//                        $zipcode_id = $data->zip_code_list_id;
//                    }
//                } else {
                $zipcode_id = DB::table('zip_codes_lists')->where('zip_code_list', $item[0])->first(['zip_code_list_id']);
                // $zipcode_id = $zipcode_id->zip_code_list_id;
                //  }
                if (!empty($zipcode_id)) {
                    //** to save all zip code from excel to array */
                    $zipcode_id_array[] = $zipcode_id->zip_code_list_id;
                }
            }
        }
        if (!empty($request['zipcode'])) {
            if( is_array($request['zipcode']) ){
                foreach($request['zipcode'] as $zipcodeIN){
                    $zipcode_id_array[]= $zipcodeIN;
                }
            }

            if (count($request['zipcode']) == 1 && !empty($request['distance_area']) && $request['distance_area'] != 0) {
                $distancekm = $request['distance_area'];
                // To fetch zipcode
                if(Cache::has("AllZipCode")){
                    $zipcode_data_cache = Cache::get("AllZipCode")->where('zip_code_list_id', $request['zipcode']['0']);
                    foreach($zipcode_data_cache as $item){
                        $zipcode = $item->zip_code_list;
                    }
                } else {
                    $zipcode_data = DB::table('zip_codes_lists')
                        ->where('zip_code_list_id', $request['zipcode']['0'])
                        ->first(['zip_code_list']);
                    $zipcode = $zipcode_data->zip_code_list;
                }

                if (!empty($zipcode)) {
                    $main_api_file = new ApiMain();
                    $decoded_result = $main_api_file->zipcodes_distance_filter($zipcode, $distancekm);

                    if (!empty($decoded_result['zip_codes'])) {
                        foreach ($decoded_result['zip_codes'] as $val) {
                            $array_zipcode_distance [] = $val['zip_code'];
                        }
                    }
                }
                //get all zipcode_id from zip code dis
                if(!empty($array_zipcode_distance)) {
                    // To fetch zip_codes_array
                    if(Cache::has('AllZipCode')){
                        $array_zipcode_distance_final = Cache::get("AllZipCode")->whereIn('zip_code_list', $array_zipcode_distance)->pluck('zip_code_list_id')->toArray();
                        $array_zipcode_distance_final = array_unique($array_zipcode_distance_final);
                    } else {
                        $array_zipcode_distance_final = DB::table('zip_codes_lists')->whereIn('zip_code_list', $array_zipcode_distance)->distinct('zip_code_list')->pluck('zip_code_list_id')->toArray();

                    }
                }
            } else {
                $array_zipcode_distance_final = array();
            }
        }

        //import Zipcods from excel EX
        $dataexcel_expect = array();
        $array_zipcodeEx_id = array();
        if( $request->hasFile('listOfzipcode_expect') ){
            $dataexcel_expect = Excel::toArray(new SalesOrder, $request->file('listOfzipcode_expect'));
            $dataexcel = array_unique($dataexcel_expect[0], SORT_REGULAR);

            foreach( $dataexcel as $item ){
                // To fetch zipcode
//                if(Cache::has('AllZipCode')){
//                    $zipcode_id_arrayEX = Cache::get("AllZipCode")->where('zip_code_list', $item[0]);
//                    foreach($zipcode_id_arrayEX as $item){
//                        $zipcode_idEX = $item->zip_code_list_id;
//                    }
//                } else {
                $zipcode_idEX = DB::table('zip_codes_lists')->where('zip_code_list', $item[0])->first(['zip_code_list_id']);
                // $zipcode_idEX = $zipcode_idEX->zip_code_list_id;
                //  }
                if( !empty($zipcode_idEX) ) {
                    $array_zipcodeEx_id[]= $zipcode_idEX->zip_code_list_id;
                }
            }
        }

        //Zip_Cods not in Campaign
        if( !empty($request['zipcode_expect']) ){
            if( is_array($request['zipcode_expect']) ){
                foreach($request['zipcode_expect'] as $zipcode){
                    $array_zipcodeEx_id[]= $zipcode;
                }
            }
        }

        //marge zipcode id from input and distance
        $final_zip_code = array_merge($array_zipcode_distance_final , $zipcode_id_array);

        //get all state_id from zipcode_id
        if(Cache::has('AllZipCode')){
            $state_id_array =Cache::get('AllZipCode')->whereIn('zip_code_list_id', $final_zip_code)->pluck('state_id')->toArray();
            $state_id_array = array_unique($state_id_array);
        } else {
            $state_id_array =  ZipCodesList::join('states', 'states.state_id', '=', 'zip_codes_lists.state_id')
                ->whereIn('zip_codes_lists.zip_code_list_id', $final_zip_code)->distinct('states.state_id')->pluck('states.state_id')->toArray();
        }

        //get all state_id from city_id
        if (!empty($request['city'])) {
            if (Cache::has('AllCities')) {
                $city_id_array = Cache::get("AllCities")->whereIn('city_id', $request['city'])->pluck('state_id')->toArray();
                $city_id_array = array_unique($city_id_array);
            } else {
                $city_id_array = City::join('states', 'states.state_id', '=', 'cities.state_id')
                    ->whereIn('cities.city_id', $request['city'])->distinct('states.state_id')->pluck('states.state_id')->toArray();
            }
        } else {
            $city_id_array = array();
        }

        //get all state_id from county_id
        if(!empty($request['county'])) {
            if (Cache::has('AllCounty')) {
                $county_id_array = Cache::get("AllCounty")->whereIn('county_id', $request['county'])->pluck('state_id')->toArray();
                $county_id_array = array_unique($county_id_array);
            } else {
                $county_id_array = County::join('states', 'states.state_id', '=', 'counties.state_id')
                    ->whereIn('counties.county_id', $request['county'])->distinct('states.state_id')->pluck('states.state_id')->toArray();
            }
        } else {
            $county_id_array = array();
        }

        if(empty($request['state'])){
            $state_array = array();
        } else {
            $state_array = $request['state'];
        }

        //State Filter Campaign
        $state_id_array_final = array_merge($state_array , $state_id_array);
        $state_id_array_final = array_merge($state_id_array_final , $city_id_array);
        $state_id_array_final = array_merge($state_id_array_final , $county_id_array);
        $state_id_array_final = array_unique($state_id_array_final);

        // To fetch all State
        if(Cache::has('AllState')){
            $states = Cache::get("AllState");
        } else {
            $states = State::All();
        }

        $stateCode = array();
        $stateName = array();
        foreach ($states as $state1) {
            if(in_array($state1->state_id, $state_id_array_final)) {
                $stateCode []= $state1->state_code;
                $stateName []= $state1->state_name;
            }
        }

        DB::table('campaign_target_area')->insert([
            'campaign_id'               => $campaign_id,
            'stateFilter_id'            => json_encode(array_values(array_unique(!empty($state_id_array_final) ? $state_id_array_final : array()))),
            'state_id'                  => json_encode(!empty($request['state']) ? $request['state'] : array()),
            'county_id'                 => json_encode(!empty($request['county']) ? $request['county'] : array()),
            'city_id'                   => json_encode(!empty($request['city']) ? $request['city'] : array()),
            'zipcode_id'                => json_encode(array_values(array_unique(!empty($final_zip_code) ? $final_zip_code : array()))),
            'county_ex_id'              => json_encode(!empty($request['county_expect']) ? $request['county_expect'] : array()),
            'city_ex_id'                => json_encode(!empty($request['city_expect']) ? $request['city_expect'] : array()),
            'zipcode_ex_id'             => json_encode(array_values(array_unique(!empty($array_zipcodeEx_id) ? $array_zipcodeEx_id : array()))),
            'stateFilter_name'          => json_encode(array_values(array_unique(!empty($stateName) ? $stateName : array()))),
            'stateFilter_code'          => json_encode(array_values(array_unique(!empty($stateCode) ? $stateCode : array()))),
        ]);

        //Access LOG
        AccessLog::create([
            'user_id' => Auth::user()->id,
            'user_name' => Auth::user()->username,
            'section_id' => $campaign_id,
            'section_name' => $request['Campaign_name'],
            'user_role' => Auth::user()->role_id,
            'section'   => 'Campaign',
            'action'    => 'Created',
            'ip_address' => request()->ip(),
            'location' => json_encode(\Location::get(request()->ip())),
            'request_method' => json_encode($request->all())
        ]);

        return redirect()->route('Campaign_List');
    }

    public function ShowCampaignDetails($campaign_id){
        $campaign_visibility = Campaign::where('campaign_id', $campaign_id)
            ->first(['campaign_visibility']);

        $campaign_visibility = json_decode($campaign_visibility);
        if( $campaign_visibility->campaign_visibility == 0 ){
            return redirect()->route('home');
        }

        $compaign = Campaign::where('campaigns.campaign_id', $campaign_id)
            ->join('service__campaigns', 'service__campaigns.service_campaign_id', '=', 'campaigns.service_campaign_id')
            ->join('campaign_types', 'campaign_types.campaign_types_id', '=', 'campaigns.campaign_Type')
            ->join('campaign_time_delivery', 'campaign_time_delivery.campaign_id', '=', 'campaigns.campaign_id')
            ->where('campaigns.campaign_visibility', 1)
            ->first();

        $periods = DB::table('period_campaign')->get();
        $deliveryMethods = DB::table('delivery_method')->get();
        $campain_inistallings = DB::table('installing_type_campaign')->get();
        $property_type_campains = DB::table('property_type_campaign')->get();
        $custom_paid_campains = DB::table('custom_paid_campaign')->get();
        $numberOfWindows = DB::table('number_of_windows_c')->get();
        $solorBill = DB::table('lead_avg_money_electicity_list')->get()->All();
        $roofingtype = DB::table('lead_type_of_roofing')->get()->All();
        $flooringtype = DB::table('lead_type_of_flooring')->get()->All();
        $securityInstalling = DB::table('lead_installation_preferences')->get()->All();
        $lead_walk_in_tub = DB::table('lead_walk_in_tub')->get()->All();
        $type_of_siding_lead = DB::table('type_of_siding_lead')->get()->All();
        $service_kitchen_lead = DB::table('service_kitchen_lead')->get()->All();
        $campaign_bathroomtype = DB::table('_campaign_bathroomtype')->get()->All();
        $stairs_type_lead = DB::table('stairs_type_lead')->get()->All();
        $stairs_reason_lead = DB::table('stairs_reason_lead')->get()->All();
        $furnance_type_lead = DB::table('furnance_type_lead')->get()->All();
        $plumbing_service_list = DB::table('plumbing_service_list')->get()->All();
        $sunroom_service_lead = DB::table('sunroom_service_lead')->get()->All();

        $handyman_ammount_work = DB::table('handyman_ammount_work')->get()->All();
        $countertops_service_lead = DB::table('countertops_service_lead')->get()->All();
        $door_typeproject_lead = DB::table('door_typeproject_lead')->get()->All();
        $number_of_door_lead = DB::table('number_of_door_lead')->get()->All();
        $gutters_install_type_leade = DB::table('gutters_install_type_leade')->get()->All();
        $gutters_meterial_lead = DB::table('gutters_meterial_lead')->get()->All();

        $lead_solor_solution_list = DB::table('lead_solor_solution_list')->get()->All();
        $lead_solor_sun_expouser_list = DB::table('lead_solor_sun_expouser_list')->get()->All();
        $paving_service_lead = DB::table('paving_service_lead')->get()->All();
        $paving_asphalt_type = DB::table('paving_asphalt_type')->get()->All();
        $paving_loose_fill_type = DB::table('paving_loose_fill_type')->get()->All();
        $paving_best_describes_priject = DB::table('paving_best_describes_priject')->get()->All();

        $painting_service_lead = DB::table('painting_service_lead')->get()->All();
        $painting1_typeof_project = DB::table('painting1_typeof_project')->get()->All();
        $painting1_stories_number = DB::table('painting1_stories_number')->get()->All();
        $painting1_kindsof_surfaces = DB::table('painting1_kindsof_surfaces')->get()->All();
        $painting2_rooms_number = DB::table('painting2_rooms_number')->get()->All();
        $painting2_typeof_paint = DB::table('painting2_typeof_paint')->get()->All();
        $painting3_each_feature = DB::table('painting3_each_feature')->get()->All();
        $painting4_existing_roof = DB::table('painting4_existing_roof')->get()->All();
        $painting5_kindof_texturing = DB::table('painting5_kindof_texturing')->get()->All();
        $painting5_surfaces_textured = DB::table('painting5_surfaces_textured')->get()->All();

        $deliveryMethodsSelected_arr = DB::table('delivery_method_campaign')
            ->where('campaign_id', $campaign_id)
            ->pluck('delivery_Method_id')->toArray();
        $campain_inistallingsSelected_arr = DB::table('installing_campaign')
            ->where('campaign_id', $campaign_id)
            ->pluck('installing_type_campaign_id')->toArray();
        $property_type_campainsSelected_arr = DB::table('property_type_many_campaign')
            ->where('campaign_id', $campaign_id)
            ->pluck('property_type_campaign_id')->toArray();
        $custom_paid_campainsSelected_arr = DB::table('custom_bid_campaign')
            ->where('campaign_id', $campaign_id)
            ->pluck('custom_paid_campaign_id')->toArray();
        $homeOwnedSelected_arr = DB::table('home_owned_campaign')
            ->where('campaign_id', $campaign_id)
            ->pluck('campaign_home_owned')->toArray();
        $numberOfWindowsSelected_arr = DB::table('number_of_window_campaign')
            ->where('campaign_id', $campaign_id)
            ->pluck('number_of_windows_c_id')->toArray();
        $solorBill_arr = DB::table('_campaign_solor_bill')
            ->where('campaign_id', $campaign_id)
            ->pluck('lead_avg_money_electicity_list_id')->toArray();
        $roofingtype_arr = DB::table('_campaign_roofingtype')
            ->where('campaign_id', $campaign_id)
            ->pluck('lead_type_of_roofing_id')->toArray();
        $flooringtype_arr = DB::table('_campaign_flooringtype')
            ->where('campaign_id', $campaign_id)
            ->pluck('lead_type_of_flooring_id')->toArray();
        $securityInstalling_arr = DB::table('_campaign_security_installing')
            ->where('campaign_id', $campaign_id)
            ->pluck('lead_installation_id')->toArray();
        $lead_walk_in_tub_arr = DB::table('campaign_walkintupfilter')
            ->where('campaign_id', $campaign_id)
            ->pluck('lead_walk_in_tub_id')->toArray();
        $type_of_siding_lead_arr = DB::table('_campaign_sidingtype')
            ->where('campaign_id', $campaign_id)
            ->pluck('type_of_siding_lead_id')->toArray();
        $campaign_kitchen_service_arr = DB::table('campaign_kitchen_service')
            ->where('campaign_id', $campaign_id)
            ->pluck('service_kitchen_lead_id')->toArray();
        $campaign_kitchen_r_a_walls_arr = DB::table('campaign_kitchen_r_a_walls')
            ->where('campaign_id', $campaign_id)
            ->pluck('campaign_kitchen_r_a_walls_status')->toArray();
        $campaign_bathroomtype_id_arr = DB::table('_campaign_bathroomtype_integrate')
            ->where('campaign_id', $campaign_id)
            ->pluck('bathroomtype_id')->toArray();
        $campaign_stairs_type_arr = DB::table('_campaign_stairs_type')
            ->where('campaign_id', $campaign_id)
            ->pluck('stairs_type_lead_id')->toArray();
        $campaign_stairs_reason_arr = DB::table('_campaign_stairs_reason')
            ->where('campaign_id', $campaign_id)
            ->pluck('stairs_reason_lead_id')->toArray();
        $campaign_furnance_type_arr = DB::table('_campaign_furnance_type')
            ->where('campaign_id', $campaign_id)
            ->pluck('furnance_type_lead_id')->toArray();
        $campaign_plumbing_service_arr = DB::table('_campaign_plumbing_service_lead')
            ->where('campaign_id', $campaign_id)
            ->pluck('plumbing_service_list_id')->toArray();
        $campaign_sunroom_service_arr = DB::table('_campaign_sunroom_service_lead')
            ->where('campaign_id', $campaign_id)
            ->pluck('sunroom_service_lead_id')->toArray();

        $campaign_handyman_ammountwork_arr = DB::table('_campaign_handyman_ammountwork')
            ->where('campaign_id', $campaign_id)
            ->pluck('handyman_ammount_work_id')->toArray();
        $campaign_countertops_service_arr = DB::table('_campaign_countertops_service')
            ->where('campaign_id', $campaign_id)
            ->pluck('countertops_service_lead_id')->toArray();
        $campaign_door_typeproject_arr = DB::table('_campaign_door_typeproject')
            ->where('campaign_id', $campaign_id)
            ->pluck('door_typeproject_lead_id')->toArray();
        $campaign_numberof_door_arr = DB::table('_campaign_numberof_door')
            ->where('campaign_id', $campaign_id)
            ->pluck('number_of_door_lead_id')->toArray();
        $campaign_gutters_install_type_arr = DB::table('_campaign_gutters_install_type')
            ->where('campaign_id', $campaign_id)
            ->pluck('gutters_install_type_leade_id')->toArray();
        $campaign_gutters_meterial_arr = DB::table('_campaign_gutters_meterial')
            ->where('campaign_id', $campaign_id)
            ->pluck('gutters_meterial_lead_id')->toArray();

        $campaign__solarpowersolution_arr = DB::table('_campaign__solarpowersolution')
            ->where('campaign_id', $campaign_id)
            ->pluck('lead_solor_solution_list_id')->toArray();
        $campaign__roof_shade_arr = DB::table('_campaign__roof_shade')
            ->where('campaign_id', $campaign_id)
            ->pluck('lead_solor_sun_expouser_list_id')->toArray();
        $campaign__existing_monitoring_system_arr = DB::table('_campaign__existing_monitoring_system')
            ->where('campaign_id', $campaign_id)
            ->pluck('existing_monitoring_system')->toArray();

        $campaign_paving_service_arr = DB::table('_campaign_paving_service')
            ->where('campaign_id', $campaign_id)
            ->pluck('paving_service_lead_id')->toArray();
        $campaign_paving_asphalt_arr = DB::table('_campaign_paving_asphalt')
            ->where('campaign_id', $campaign_id)
            ->pluck('paving_asphalt_type_id')->toArray();
        $campaign_paving_loose_fill_arr = DB::table('_campaign_paving_loose_fill')
            ->where('campaign_id', $campaign_id)
            ->pluck('paving_loose_fill_type_id')->toArray();
        $campaign_paving_best_desc_arr = DB::table('_campaign_paving_best_desc')
            ->where('campaign_id', $campaign_id)
            ->pluck('paving_best_describes_priject_id')->toArray();

        $campaign_painting_service_arr = DB::table('_campaign_painting_service')
            ->where('campaign_id', $campaign_id)
            ->pluck('painting_service_lead_id')->toArray();
        $campaign_painting1_projecttyp_arr = DB::table('_campaign_painting1_projecttype')
            ->where('campaign_id', $campaign_id)
            ->pluck('painting1_typeof_project_id')->toArray();
        $campaign_p1_kindsof_surfaces_arr = DB::table('_campaign_p1_kindsof_surfaces')
            ->where('campaign_id', $campaign_id)
            ->pluck('painting1_kindsof_surfaces_id')->toArray();
        $campaign_p2_rooms_number_arr = DB::table('_campaign_p2_rooms_number')
            ->where('campaign_id', $campaign_id)
            ->pluck('painting2_rooms_number_id')->toArray();
        $campaign_p2_typeof_paint_arr = DB::table('_campaign_p2_typeof_paint')
            ->where('campaign_id', $campaign_id)
            ->pluck('painting2_typeof_paint_id')->toArray();
        $campaign_p3_each_feature_arr = DB::table('_campaign_p3_each_feature')
            ->where('campaign_id', $campaign_id)
            ->pluck('painting3_each_feature_id')->toArray();
        $campaign_p4_existing_roof_arr = DB::table('_campaign_p4_existing_roof')
            ->where('campaign_id', $campaign_id)
            ->pluck('painting4_existing_roof_id')->toArray();
        $campaign_p5_kindof_texturing_arr = DB::table('_campaign_p5_kindof_texturing')
            ->where('campaign_id', $campaign_id)
            ->pluck('painting5_kindof_texturing_id')->toArray();
        $campaign_p5_surfaces_textured_arr = DB::table('_campaign_p5_surfaces_textured')
            ->where('campaign_id', $campaign_id)
            ->pluck('painting5_surfaces_textured_id')->toArray();
        $campaign_p_stories_number_arr = DB::table('_campaign_p_stories_number')
            ->where('campaign_id', $campaign_id)
            ->pluck('painting1_stories_number_id')->toArray();
        $campaign_p_historical_structure_arr = DB::table('_campaign_p_historical_structure')
            ->where('campaign_id', $campaign_id)
            ->pluck('historical_structure')->toArray();

        $states_in_campaign = DB::table('state_campaigns')
            ->join('states', 'states.state_id', '=', 'state_campaigns.state_id')
            ->where('campaign_id', $campaign_id)
            ->where('state_campaigns_active', 1)
            ->get()->all();
        $states_not_in_campaign = DB::table('state_campaigns')
            ->join('states', 'states.state_id', '=', 'state_campaigns.state_id')
            ->where('campaign_id', $campaign_id)
            ->where('state_campaigns_active', 0)
            ->get()->all();

        $cities_in_campaign = DB::table('city__campaigns')
            ->join('cities', 'cities.city_id', '=', 'city__campaigns.city_id')
            ->where('campaign_id', $campaign_id)
            ->where('city_campaigns_active', 1)
            ->get()->all();
        $cities_not_in_campaign = DB::table('city__campaigns')
            ->join('cities', 'cities.city_id', '=', 'city__campaigns.city_id')
            ->where('campaign_id', $campaign_id)
            ->where('city_campaigns_active', 0)
            ->get()->all();

        $counties_in_campaign = DB::table('county__campaigns')
            ->join('counties', 'counties.county_id', '=', 'county__campaigns.county_id')
            ->where('campaign_id', $campaign_id)
            ->where('county_campaigns_active', 1)
            ->get()->all();
        $counties_not_in_campaign = DB::table('county__campaigns')
            ->join('counties', 'counties.county_id', '=', 'county__campaigns.county_id')
            ->where('campaign_id', $campaign_id)
            ->where('county_campaigns_active', 0)
            ->get()->all();

        $zipcods_in_campaign_arr = DB::table('zipcode__campaigns')
            ->join('zip_codes_lists', 'zip_codes_lists.zip_code_list_id', '=', 'zipcode__campaigns.zipcode_campaigns')
            ->where('campaign_id', $campaign_id)
            ->where('zipcode_campaigns_active', 1)
            ->pluck('zip_codes_lists.zip_code_list')->toArray();
        $zipcods_not_in_campaign_arr = DB::table('zipcode__campaigns')
            ->join('zip_codes_lists', 'zip_codes_lists.zip_code_list_id', '=', 'zipcode__campaigns.zipcode_campaigns')
            ->where('campaign_id', $campaign_id)
            ->where('zipcode_campaigns_active', 0)
            ->pluck('zip_codes_lists.zip_code_list')->toArray();

        $address = array(
            'states_in_campaign'        => $states_in_campaign,
            'states_not_in_campaign'    => $states_not_in_campaign,
            'cities_in_campaign'        => $cities_in_campaign,
            'cities_not_in_campaign'    => $cities_not_in_campaign,
            'counties_in_campaign'      => $counties_in_campaign,
            'counties_not_in_campaign'  => $counties_not_in_campaign,
            'zipcods_in_campaign'       => $zipcods_in_campaign_arr,
            'zipcods_not_in_campaign'   => $zipcods_not_in_campaign_arr
        );

        $campain_status = array(
            'periods' => $periods,
            'deliveryMethods' => $deliveryMethods,
            'campain_inistallings' => $campain_inistallings,
            'property_type_campains' => $property_type_campains,
            'custom_paid_campains' => $custom_paid_campains,
            'numberOfWindows'  => $numberOfWindows,
            'solorBill'    => $solorBill,
            'roofingtype'  => $roofingtype,
            'flooringtype' => $flooringtype,
            'securityInstalling' => $securityInstalling,
            'lead_walk_in_tub'         => $lead_walk_in_tub,
            "type_of_siding_lead" => $type_of_siding_lead,
            'service_kitchen_lead'  => $service_kitchen_lead,
            'campaign_bathroomtype' => $campaign_bathroomtype,
            'stairs_type_lead' => $stairs_type_lead,
            'stairs_reason_lead' => $stairs_reason_lead,
            'furnance_type_lead' => $furnance_type_lead,
            'plumbing_service_list' => $plumbing_service_list,
            'sunroom_service_lead' => $sunroom_service_lead,
            'handyman_ammount_work' => $handyman_ammount_work,
            'countertops_service_lead' => $countertops_service_lead,
            'door_typeproject_lead' => $door_typeproject_lead,
            'number_of_door_lead' => $number_of_door_lead,
            'gutters_install_type_leade' => $gutters_install_type_leade,
            'gutters_meterial_lead' => $gutters_meterial_lead,

            'lead_solor_solution_list' => $lead_solor_solution_list,
            'lead_solor_sun_expouser_list' => $lead_solor_sun_expouser_list,
            'paving_service_lead' => $paving_service_lead,
            'paving_asphalt_type' => $paving_asphalt_type,
            'paving_loose_fill_type' => $paving_loose_fill_type,
            'paving_best_describes_priject' => $paving_best_describes_priject,

            'painting_service_lead' => $painting_service_lead,
            'painting1_typeof_project' => $painting1_typeof_project,
            'painting1_stories_number' => $painting1_stories_number,
            'painting1_kindsof_surfaces' => $painting1_kindsof_surfaces,
            'painting2_rooms_number' => $painting2_rooms_number,
            'painting2_typeof_paint' => $painting2_typeof_paint,
            'painting3_each_feature' => $painting3_each_feature,
            'painting4_existing_roof' => $painting4_existing_roof,
            'painting5_kindof_texturing' => $painting5_kindof_texturing,
            'painting5_surfaces_textured' => $painting5_surfaces_textured,
        );

        $campain_status_selected = array(
            'deliveryMethodsSelected_arr' => $deliveryMethodsSelected_arr,
            'campain_inistallingsSelected_arr' => $campain_inistallingsSelected_arr,
            'property_type_campainsSelected_arr' => $property_type_campainsSelected_arr,
            'custom_paid_campainsSelected_arr' => $custom_paid_campainsSelected_arr,
            'homeOwnedSelected_arr'    => $homeOwnedSelected_arr,
            'numberOfWindowsSelected_arr' => $numberOfWindowsSelected_arr,
            'solorBill_arr'    => $solorBill_arr,
            'roofingtype_arr'  => $roofingtype_arr,
            'flooringtype_arr' => $flooringtype_arr,
            'securityInstalling_arr' => $securityInstalling_arr,
            'lead_walk_in_tub_arr' => $lead_walk_in_tub_arr,
            "type_of_siding_lead_arr" => $type_of_siding_lead_arr,
            'campaign_kitchen_service_arr' => $campaign_kitchen_service_arr,
            'campaign_kitchen_r_a_walls_arr' => $campaign_kitchen_r_a_walls_arr,
            'campaign_bathroomtype_id_arr' => $campaign_bathroomtype_id_arr,
            'campaign_stairs_type_arr' => $campaign_stairs_type_arr,
            'campaign_stairs_reason_arr' => $campaign_stairs_reason_arr,
            'campaign_furnance_type_arr' => $campaign_furnance_type_arr,
            'campaign_plumbing_service_arr' => $campaign_plumbing_service_arr,
            'campaign_sunroom_service_arr' => $campaign_sunroom_service_arr,
            'campaign_handyman_ammountwork_arr' => $campaign_handyman_ammountwork_arr,
            'campaign_countertops_service_arr' => $campaign_countertops_service_arr,
            'campaign_door_typeproject_arr' => $campaign_door_typeproject_arr,
            'campaign_numberof_door_arr' => $campaign_numberof_door_arr,
            'campaign_gutters_install_type_arr' => $campaign_gutters_install_type_arr,
            'campaign_gutters_meterial_arr' => $campaign_gutters_meterial_arr,

            'campaign__solarpowersolution_arr' => $campaign__solarpowersolution_arr,
            'campaign__roof_shade_arr' => $campaign__roof_shade_arr,
            'campaign__existing_monitoring_system_arr' => $campaign__existing_monitoring_system_arr,
            'campaign_paving_service_arr' => $campaign_paving_service_arr,
            'campaign_paving_asphalt_arr' => $campaign_paving_asphalt_arr,
            'campaign_paving_loose_fill_arr' => $campaign_paving_loose_fill_arr,
            'campaign_paving_best_desc_arr' => $campaign_paving_best_desc_arr,

            'campaign_painting_service_arr' => $campaign_painting_service_arr,
            'campaign_painting1_projecttyp_arr' => $campaign_painting1_projecttyp_arr,
            'campaign_p1_kindsof_surfaces_arr' => $campaign_p1_kindsof_surfaces_arr,
            'campaign_p2_rooms_number_arr' => $campaign_p2_rooms_number_arr,
            'campaign_p2_typeof_paint_arr' => $campaign_p2_typeof_paint_arr,
            'campaign_p3_each_feature_arr' => $campaign_p3_each_feature_arr,
            'campaign_p4_existing_roof_arr' => $campaign_p4_existing_roof_arr,
            'campaign_p5_kindof_texturing_arr' => $campaign_p5_kindof_texturing_arr,
            'campaign_p5_surfaces_textured_arr' => $campaign_p5_surfaces_textured_arr,
            'campaign_p_stories_number_arr' => $campaign_p_stories_number_arr,
            'campaign_p_historical_structure_arr' => $campaign_p_historical_structure_arr,
        );

        //fetchData from calltools model

        $callToolsData = new CallTools();
        $callToolsTabel = $callToolsData->fetchWithWhere('campaign_id',$campaign_id,'','first');

        //fetchData from $five9 model

        $five9Data = new Five9();
        $five9Tabel = $five9Data->fetchWithWhere('campaign_id',$campaign_id,'','first');

        //fetchData from leads_pedia model

        $LeadsPediaData = new Leads_Pedia();
        $LeadsPediaTabel = $LeadsPediaData->fetchWithWhere('campaign_id',$campaign_id,'','first');

        //jangel from model

        $jangel = new Jangle();
        $jangelTabel = $jangel->fetchWithWhere('campaign_id',$campaign_id,'','first');

        // builder prime from model
        $builderPrime = new Builder_Prime_CRM();
        $builderPrimeTabel = $builderPrime->fetchWithWhere('campaign_id',$campaign_id,'','first');

        return view('Buyers.Campaign.CampaignDetails')
            ->with('compaign' , $compaign)
            ->with('campain_status', $campain_status)
            ->with('campain_status_selected', $campain_status_selected)
            ->with('address', $address)
            ->with('callToolsTabel', $callToolsTabel)
            ->with('five9Tabel', $five9Tabel)
            ->with('LeadsPediaTabel', $LeadsPediaTabel)
            ->with('builderPrimeTabel', $builderPrimeTabel)
            ->with('jangelTabel', $jangelTabel);
    }

    public function EditCampaign($campaign_id){
        $campaign = Campaign::join('campaign_time_delivery', 'campaign_time_delivery.campaign_id', '=', 'campaigns.campaign_id')
            ->join('campaigns_questions', 'campaigns_questions.campaign_id', '=', 'campaigns.campaign_id')
            ->join('campaign_target_area', 'campaign_target_area.campaign_id', '=', 'campaigns.campaign_id')
            ->join('users', 'campaigns.user_id', '=', 'users.id')
            ->where('campaigns.campaign_id', $campaign_id)
            ->first();

        if( $campaign->campaign_visibility == 0 ){
            return redirect()->route('home');
        }

        $services = Service_Campaign::where('service_is_active', 1)->get()->all();
        $utility_providers = DB::table('lead_current_utility_provider')->groupBy('lead_current_utility_provider_name')->get()->All();
        $websites = DB::table('domains')->where('status', 1)->get()->All();
        $websitesCompaign = json_decode($campaign['website'], true);

        $campain_status_selected = array(
            'exclude_sellers_campaigns' => json_decode($campaign->exclude_include_campaigns),
            'exclude_sellers_campaigns_type' => $campaign->exclude_include_type
        );

        // To fetch all State
        if(Cache::has('AllState')){
            $states = Cache::get("AllState");
        } else {
            $states = State::All();
        }

        // To fetch all cities EX & IN selected by the user
        if(Cache::has('AllCities')){
            $cities_in_campaign_arr = Cache::get('AllCities')->whereIn('city_id', json_decode($campaign->city_id, true));
            $cities_not_in_campaign_arr = Cache::get('AllCities')->whereIn('city_id', json_decode($campaign->city_ex_id, true));
        } else {
            $cities_in_campaign_arr = DB::table('cities')->whereIn('city_id', json_decode($campaign->city_id, true))->get(['city_name', 'city_id']);
            $cities_not_in_campaign_arr = DB::table('cities')->whereIn('city_id', json_decode($campaign->city_ex_id, true))->get(['city_name', 'city_id']);
        }

        // To fetch all counties
        if(Cache::has('AllCounty')){
            $counties_in_campaign_arr = Cache::get('AllCounty')->whereIn('county_id', json_decode($campaign->county_id, true));
            $counties_not_in_campaign_arr = Cache::get('AllCounty')->whereIn('county_id', json_decode($campaign->county_ex_id, true));
        } else {
            $counties_in_campaign_arr = DB::table('counties')->whereIn('county_id', json_decode($campaign->county_id, true))->get(['county_id', 'county_name']);
            $counties_not_in_campaign_arr = DB::table('counties')->whereIn('county_id', json_decode($campaign->county_ex_id, true))->get(['county_id', 'county_name']);
        }

        // To fetch zip_codes_array_in_campaign
        if(Cache::has('AllZipCode')){
            $zip_codes_array = Cache::get("AllZipCode")->whereIn('zip_code_list_id', json_decode($campaign->zipcode_id, true))->pluck('zip_code_list')->toArray();
            $zipcods_not_in_campaign_arr = Cache::get("AllZipCode")->whereIn('zip_code_list_id', json_decode($campaign->zipcode_ex_id, true))->pluck('zip_code_list')->toArray();
        } else {
            $zip_codes_array = DB::table('zip_codes_lists')
                ->whereIn('zip_code_list_id', json_decode($campaign->zipcode_id, true))
                ->pluck('zip_code_list')->toArray();

            $zipcods_not_in_campaign_arr = DB::table('zip_codes_lists')
                ->whereIn('zip_code_list_id', json_decode($campaign->zipcode_ex_id, true))
                ->pluck('zip_code_list')->toArray();
        }

        $states_in_campaign_arr = json_decode($campaign->state_id, true);
        $states_Filter_campaign_arr = json_decode($campaign->stateFilter_id, true);
        $zipcods_in_campaign_arr = json_decode($campaign->zipcode_id, true);
        $zipcods_counts = count(json_decode($campaign->zipcode_id, true));

        $address = array(
            'states' => $states,
            'states_in_campaign' => $states_in_campaign_arr,
            'states_Filter_campaign' => $states_Filter_campaign_arr,
            'cities_in_campaign' => $cities_in_campaign_arr,
            'cities_not_in_campaign' => $cities_not_in_campaign_arr,
            'counties_in_campaign' => $counties_in_campaign_arr,
            'counties_not_in_campaign' => $counties_not_in_campaign_arr,
            'zipcods_in_campaign' => $zipcods_in_campaign_arr,
            'zipcods_not_in_campaign' => $zipcods_not_in_campaign_arr,
            'zipcods_counts' => $zipcods_counts,
            'zip_codes_array' => $zip_codes_array,
        );

        $callToolsTabel = $five9Tabel = $LeadsPediaTabel = $hubspotTabel = $BuilderTrendTabel = $PipeDriveTabel = $jangelTabel = $lead_perfectionTabel =
        $improveit360Tabel = $leadconduitTabel = $marketsharpmTabel = $LeadPortalTabel = $leadsPediaTrackTabel = $AcculynxCrm = $ZohoCrm = $HatchCrm =
        $salesforceCRM = $builderPrimetable = $zapier_crm_table = $set_shape_crm_table = $set_job_nimbus_crm_table = $set_sunbase_crm_table = array();

        $Crm_Array = json_decode($campaign->crm,true);
        if(!empty($Crm_Array)) {
            if(in_array("1",$Crm_Array)) {
                //fetchData from calltools model
                $callToolsData = new CallTools();
                $callToolsTabel = $callToolsData->fetchWithWhere('campaign_id',$campaign_id,'','first');
            }
            if(in_array("2",$Crm_Array)) {
                //fetchData from $five9 model
                $five9Data = new Five9();
                $five9Tabel = $five9Data->fetchWithWhere('campaign_id',$campaign_id,'','first');
            }
            if(in_array("3",$Crm_Array)) {
                //fetchData from leads_pedia model
                $LeadsPediaData = new Leads_Pedia();
                $LeadsPediaTabel = $LeadsPediaData->fetchWithWhere('campaign_id',$campaign_id,'','first');
            }
            if(in_array("4",$Crm_Array)) {
                //fetchData from hubspot model
                $hubspotData = new hubspot();
                $hubspotTabel = $hubspotData->fetchWithWhere('campaign_id',$campaign_id,'','first');
            }
            if(in_array("5",$Crm_Array)) {
                //fetchData from builderTrend model
                $BuilderTrendData = new BuilderTrend();
                $BuilderTrendTabel = $BuilderTrendData->fetchWithWhere('campaign_id',$campaign_id,'','first');
            }
            if(in_array("6",$Crm_Array)) {
                //fetchData from pipeDrive model
                $PipeDriveData = new Pipe_Drive();
                $PipeDriveTabel = $PipeDriveData->fetchWithWhere('campaign_id',$campaign_id,'','first');
            }
            if(in_array("7",$Crm_Array)) {
                //jangel from leads_pedia model
                $jangel = new Jangle();
                $jangelTabel = $jangel->fetchWithWhere('campaign_id',$campaign_id,'','first');
            }
            if(in_array("8",$Crm_Array)) {
                //leadperfection
                $lead_perfection = new leadPerfectionCrm();
                $lead_perfectionTabel = $lead_perfection->fetchWithWhere('campaign_id',$campaign_id,'','first');
            }
            if(in_array("9",$Crm_Array)) {
                //Improveit360Crm
                $improveit360 = new Improveit360Crm();
                $improveit360Tabel = $improveit360->fetchWithWhere('campaign_id',$campaign_id,'','first');
            }
            if(in_array("10",$Crm_Array)) {
                //LeadConduitCRM
                $leadconduit = new LeadConduit();
                $leadconduitTabel = $leadconduit->fetchWithWhere('campaign_id',$campaign_id,'','first');
            }
            if(in_array("10",$Crm_Array)) {
                //LeadConduitCRM
                $leadconduit = new LeadConduit();
                $leadconduitTabel = $leadconduit->fetchWithWhere('campaign_id',$campaign_id,'','first');
            }
            if(in_array("11",$Crm_Array)) {
                //marketsharpm
                $marketsharpm = new Marketsharpm();
                $marketsharpmTabel = $marketsharpm->fetchWithWhere('campaign_id',$campaign_id,'','first');
            }
            if(in_array("12",$Crm_Array)) {
                //LeadPortal
                $LeadPortal  = new LeadPortal();
                $LeadPortalTabel = $LeadPortal->fetchWithWhere('campaign_id',$campaign_id,'','first');
            }
            if(in_array("13",$Crm_Array)) {
                //leads_pedia_track
                $leads_pedia_track = new leads_pedia_track();
                $leadsPediaTrackTabel = $leads_pedia_track->fetchWithWhere('campaign_id',$campaign_id,'','first');
            }
            if(in_array("14",$Crm_Array)) {
                //AcculynxCrm
                $AcculynxCrm = new AcculynxCrm();
                $AcculynxCrm = $AcculynxCrm->fetchWithWhere('campaign_id',$campaign_id,'','first');
            }
            if(in_array("15",$Crm_Array)) {
                //Zoho CRM
                $ZohoCrm = new ZohoCrm();
                $ZohoCrm = $ZohoCrm->fetchWithWhere('campaign_id',$campaign_id,'','first');
            }
            if(in_array("16",$Crm_Array)) {
                //Hatch Crm
                $HatchCrm = new HatchCrm();
                $HatchCrm = $HatchCrm->fetchWithWhere('campaign_id',$campaign_id,'','first');
            }
            if(in_array("17",$Crm_Array)) {
                //SalesForce Crm
                $salesforceCRM = new SalesforceCrm();
                $salesforceCRM = $salesforceCRM->fetchWithWhere('campaign_id',$campaign_id,'','first');
            }
            if(in_array("18",$Crm_Array)) {
                //Builder Prime Crm
                $builderPrime = new Builder_Prime_CRM();
                $builderPrimetable = $builderPrime->fetchWithWhere('campaign_id',$campaign_id,'','first');
            }
            if (in_array("19", $Crm_Array)) {
                //Zapier Crm
                $zapier_crm = new ZapierCrm();
                $zapier_crm_table = $zapier_crm->fetchWithWhere('campaign_id', $campaign_id, '', 'first');
            }
            if (in_array("20", $Crm_Array)) {
                //SetShape Crm
                $set_shape_crm = new SetShapeCrm();
                $set_shape_crm_table = $set_shape_crm->fetchWithWhere('campaign_id', $campaign_id, '', 'first');
            }
            if (in_array("21", $Crm_Array)) {
                //job_nimbus Crm
                $set_job_nimbus_crm = new job_nimbus();
                $set_job_nimbus_crm_table = $set_job_nimbus_crm->fetchWithWhere('campaign_id', $campaign_id, '', 'first');
            }
            if (in_array("22", $Crm_Array)){
                //Sunbase Crm
                $set_sunbase_crm = new Sunbase();
                $set_sunbase_crm_table = $set_sunbase_crm->fetchWithWhere('campaign_id', $campaign_id, '', 'first');
            }
        }

        $crms_array = array(
            "callToolsTabel" => $callToolsTabel,
            "five9Tabel" => $five9Tabel,
            "LeadsPediaTabel" => $LeadsPediaTabel,
            "hubspotTabel" => $hubspotTabel,
            "BuilderTrendTabel" => $BuilderTrendTabel,
            "PipeDriveTabel" => $PipeDriveTabel,
            "jangelTabel" => $jangelTabel,
            "lead_perfectionTabel" => $lead_perfectionTabel,
            "improveit360Tabel" => $improveit360Tabel,
            "leadconduitTabel" => $leadconduitTabel,
            "marketsharpmTabel" => $marketsharpmTabel,
            "LeadPortalTabel" => $LeadPortalTabel,
            "leadsPediaTrackTabel" => $leadsPediaTrackTabel,
            "AcculynxCrm" => $AcculynxCrm,
            "ZohoCrm" => $ZohoCrm,
            "HatchCrm" => $HatchCrm,
            "salesforceCRM" => $salesforceCRM,
            "builderPrimetable" => $builderPrimetable,
            "zapier_crm" => $zapier_crm_table,
            "set_shape_crm" => $set_shape_crm_table,
            "set_job_nimbus_crm" => $set_job_nimbus_crm_table,
            "set_sunbase_crm" => $set_sunbase_crm_table,
        );

        //For check the campaign type
        switch($campaign->campaign_Type){
            case 2:
                $view = "Buyers.Campaign.PayPerCall.Edit";
                break;
            case 3:
                $view = "Buyers.Campaign.PayPerClick.edit";
                break;
            case 5:
                $view = "Buyers.Campaign.PayPerScheduleLead.edit";
                break;
            case 8:
                $view = "Buyers.Campaign.PayPerRevShare.edit";
                break;
            default:
                $view = "Buyers.Campaign.CampaignUpdate";
        }

        return view($view)
            ->with('campaign', $campaign)
            ->with('services', $services)
            ->with('address', $address)
            ->with('websites', $websites)
            ->with('websitesCompaign', $websitesCompaign)
            ->with('campain_status_selected', $campain_status_selected)
            ->with('crms_array', $crms_array)
            ->with('utility_providers', $utility_providers);
    }

    public function UpdateCampaign(Request $request){
        $this->validate($request, [
            'Campaign_id' => ['required', 'string', 'max:255'],
            'Campaign_name' => ['required', 'string', 'max:255'],
            'propertytype' => ['required'],
            'Installings' => ['required'],
            'homeowned' => ['required'],
            'service_id'    => ['required'],
        ]);

        $campaign_id = $request['Campaign_id'];

        $crm = $request['crm_type'] ;
        if( $request->is_multi_crms != 1 ){
            $crm = array($crm);
        }

        DB::table('calltools')->where('campaign_id', $campaign_id)->Delete();
        DB::table('five9')->where('campaign_id', $campaign_id)->Delete();
        DB::table('leads_pedia')->where('campaign_id', $campaign_id)->Delete();
        DB::table('hubspot')->where('campaign_id', $campaign_id)->Delete();
        DB::table('builder_trend')->where('campaign_id', $campaign_id)->Delete();
        DB::table('pipe_drive')->where('campaign_id', $campaign_id)->Delete();
        DB::table('jangle')->where('campaign_id', $campaign_id)->Delete();
        DB::table('lead_perfection_crms')->where('campaign_id', $campaign_id)->Delete();
        DB::table('improveit360_crms')->where('campaign_id', $campaign_id)->Delete();
        DB::table('lead_conduits')->where('campaign_id', $campaign_id)->Delete();
        DB::table('marketsharpm')->where('campaign_id', $campaign_id)->Delete();
        DB::table('lead_portal')->where('campaign_id', $campaign_id)->Delete();
        DB::table('leads_pedia_track')->where('campaign_id', $campaign_id)->Delete();
        DB::table('acculynx_crms')->where('campaign_id', $campaign_id)->Delete();
        DB::table('zoho_crms')->where('campaign_id', $campaign_id)->Delete();
        DB::table('hatch_crms')->where('campaign_id', $campaign_id)->Delete();
        DB::table('salesforce_crms')->where('campaign_id', $campaign_id)->Delete();
        DB::table('builder_prime')->where('campaign_id', $campaign_id)->Delete();
        DB::table('zapier_crms')->where('campaign_id', $campaign_id)->Delete();
        DB::table('set_shape_crms')->where('campaign_id', $campaign_id)->Delete();
        DB::table('job_nimbus')->where('campaign_id', $campaign_id)->Delete();
        DB::table('sunbase_data')->where('campaign_id', $campaign_id)->Delete();

        try {
            if (in_array(1, $crm)) {
                $callTools = new CallTools();
                $callTools->api_key = $request['ApiKey'];
                $callTools->file_id = $request['FileId'];
                $callTools->campaign_id = $campaign_id;
                $callTools->save();
            }
            if (in_array(2, $crm)) {
                $Five9 = new Five9();
                $Five9->five9_domian = $request['Five9Domian'];
                $Five9->five9_list = $request['Five9List'];
                $Five9->campaign_id = $campaign_id;
                $Five9->save();
            }
            if (in_array(3, $crm)) {
                $leadsPedia = new Leads_Pedia();
                $leadsPedia->leads_pedia_url = $request['LeadsPediaUrl'];
                $leadsPedia->campine_key = $request['CampineKey'];
                $leadsPedia->campaign_id = $campaign_id;
                $leadsPedia->IP_Campaign_ID = $request['IP_Campaign_ID'];
                $leadsPedia->leads_pedia_url_ping = $request['leads_pedia_url_ping'];
                $leadsPedia->save();
            }
            if (in_array(4, $crm)) {
                $hubspot = new hubspot();
                $hubspot->Api_Key = $request['hubspotKey'];
                $hubspot->key_type = ($request['hubspot_key_type'] == 1 ? $request['hubspot_key_type'] : 0);
                $hubspot->campaign_id = $campaign_id;
                $hubspot->save();
            }
            if (in_array(5, $crm)) {
                $BuilderTrend = new BuilderTrend();
                $BuilderTrend->builder_id = $request['builderId'];
                $BuilderTrend->campaign_id = $campaign_id;
                $BuilderTrend->save();
            }
            if (in_array(6, $crm)) {
                $PipeDrive = new Pipe_Drive();
                $PipeDrive->api_token = $request['api_token'];
                $PipeDrive->persons_domain = $request['persons_domain'];
                $PipeDrive->persons = ($request['pipedrive_person'] == 1 ? $request['pipedrive_person'] : 0);
                $PipeDrive->deals_leads = ($request['pipedrive_deals_leads'] == 1 ? $request['pipedrive_deals_leads'] : 0);
                $PipeDrive->campaign_id = $campaign_id;
                $PipeDrive->save();
            }
            if (in_array(7, $crm)) {
                $jangle = new Jangle();
                $jangle->Authorization = $request['Authorization'];
                $jangle->PingUrl = $request['PingUrl'];
                $jangle->PostUrl = $request['PostUrl'];
                $jangle->campaign_id = $campaign_id;
                $jangle->save();
            }
            if (in_array(8, $crm)) {
                $leadPerfectionCrm = new leadPerfectionCrm();
                $leadPerfectionCrm->lead_perfection_url = $request['lead_perfection_url'];
                $leadPerfectionCrm->lead_perfection_srs_id = $request['lead_perfection_srs_id'];
                $leadPerfectionCrm->lead_perfection_pro_id = $request['lead_perfection_pro_id'];
                $leadPerfectionCrm->lead_perfection_pro_desc = $request['lead_perfection_pro_desc'];
                $leadPerfectionCrm->lead_perfection_sender = $request['lead_perfection_sender'];
                $leadPerfectionCrm->campaign_id = $campaign_id;
                $leadPerfectionCrm->save();
            }
            if (in_array(9, $crm)) {
                $Improveit360Crm = new Improveit360Crm();
                $Improveit360Crm->improveit360_url = $request['improveit360_url'];
                $Improveit360Crm->improveit360_source = $request['improveit360_source'];
                $Improveit360Crm->market_segment = $request['improveit360_market_segment'];
                $Improveit360Crm->source_type = $request['improveit360_source_type'];
                $Improveit360Crm->project = $request['improveit360_project'];
                $Improveit360Crm->campaign_id = $campaign_id;
                $Improveit360Crm->save();
            }
            if (in_array(10, $crm)) {
                $LeadConduitCrm = new LeadConduit();
                $LeadConduitCrm->post_url = $request['leadconduit_url'];
                $LeadConduitCrm->campaign_id = $campaign_id;
                $LeadConduitCrm->save();
            }
            if (in_array(11, $crm)) {
                $Marketsharpm = new Marketsharpm();
                $Marketsharpm->MSM_source = $request['MSM_source'];
                $Marketsharpm->MSM_coy = $request['MSM_coy'];
                $Marketsharpm->MSM_formId = $request['MSM_formId'];
                $Marketsharpm->campaign_id = $campaign_id;
                $Marketsharpm->save();
            }
            if (in_array(12, $crm)) {
                $LeadPortal = new LeadPortal();
                $LeadPortal->key = $request['leadPortal_Key'];
                $LeadPortal->SRC = $request['leadPortal_SRC'];
                $LeadPortal->api_url = $request['leadPortal_api_url'];
                $LeadPortal->type = $request['leadPortal_type'];
                $LeadPortal->campaign_id = $campaign_id;
                $LeadPortal->save();
            }
            if (in_array(13, $crm)) {
                $leads_pedia_track = new leads_pedia_track();
                $leads_pedia_track->lp_campaign_id = $request['lp_campaign_id'];
                $leads_pedia_track->lp_campaign_key = $request['lp_campaign_key'];
                $leads_pedia_track->ping_url = $request['leads_pedia_track_ping_url'];
                $leads_pedia_track->post_url = $request['leads_pedia_track_post_url'];
                $leads_pedia_track->campaign_id = $campaign_id;
                $leads_pedia_track->save();
            }
            if (in_array(14, $crm)) {
                $AcculynxCrm = new AcculynxCrm();
                $AcculynxCrm->api_key = $request['acculynx_api_key'];
                $AcculynxCrm->campaign_id = $campaign_id;
                $AcculynxCrm->save();
            }
            if (in_array(15, $crm)) {
                $ZohoCrm = new ZohoCrm();
                $ZohoCrm->refresh_token = $request['refresh_token'];
                $ZohoCrm->client_id = $request['client_id'];
                $ZohoCrm->client_secret = $request['client_secret'];
                $ZohoCrm->redirect_url = $request['redirect_url'];
                $ZohoCrm->Lead_Source = $request['Lead_Source'];
                $ZohoCrm->campaign_id = $campaign_id;
                $ZohoCrm->save();
            }
            if (in_array(16, $crm)) {
                $HatchCrm = new HatchCrm();
                $HatchCrm->dep_id = $request['HatchDeptID'];
                $HatchCrm->org_token = $request['HatchOrgToken'];
                $HatchCrm->URL_sub = $request['Hatch_URL_sub'];
                $HatchCrm->campaign_id = $campaign_id;
                $HatchCrm->save();
            }
            if (in_array(17, $crm)) {
                $salesforceCRM = new SalesforceCrm();
                $salesforceCRM->url = $request['salesforce_url'];
                $salesforceCRM->lead_source = $request['salesforce_lead_source'];
                $salesforceCRM->retURL = $request['salesforce_retURL'];
                $salesforceCRM->oid = $request['salesforce_oid'];
                $salesforceCRM->campaign_id = $campaign_id;
                $salesforceCRM->save();
            }
            if (in_array(18, $crm)){
                $builderPrimeCRM = new Builder_Prime_CRM();
                $builderPrimeCRM->post_url = $request['builderPrimePostURL'];
                $builderPrimeCRM->secret_key = $request['builderPrimeSecretKey'];
                $builderPrimeCRM->campaign_id = $campaign_id;
                $builderPrimeCRM->save();
            }
            if (in_array(19, $crm)){
                $zapier_crm = new ZapierCrm();
                $zapier_crm->post_url = $request['zapier_url'];
                $zapier_crm->campaign_id = $campaign_id;
                $zapier_crm->save();
            }
            if (in_array(20, $crm)){
                $set_shape_crm = new SetShapeCrm();
                $set_shape_crm->post_url = $request['set_shape_url'];
                $set_shape_crm->campaign_id = $campaign_id;
                $set_shape_crm->save();
            }
            if (in_array(21, $crm)){
                $set_job_nimbus_crm = new job_nimbus();
                $set_job_nimbus_crm->api_key = $request['set_nimbus_key'];
                $set_job_nimbus_crm->campaign_id = $campaign_id;
                $set_job_nimbus_crm->save();
            }
            if (in_array(22, $crm)){
                $set_sunbase_crm = new Sunbase();
                $set_sunbase_crm->url = $request['sunbase_url'];
                $set_sunbase_crm->schema_name = $request['sunbase_schema_name'];
                $set_sunbase_crm->campaign_id = $campaign_id;
                $set_sunbase_crm->save();
            }
        }
        catch (\Illuminate\Database\QueryException $ex){

        }

        $campaign_Data = Campaign::where('campaign_id', $campaign_id)->first(["virtual_price"]);
        $virtual_price = (!empty($campaign_Data->virtual_price) ? $campaign_Data->virtual_price : 0);

        if( empty($request['is_ping_account']) ){
            $request['is_ping_account'] = 0;
        }

        $budget_bid_shared = 0;
        $budget_must_be_sh = 0;
        if( !empty($request['budget_bid_shared']) ){
            $budget_bid_shared = $request['budget_bid_shared'] + $virtual_price;
            $number_of_lead_sh = $request['numberOfCustomerCampaign'];
            $number_of_lead_p_sh = $request['numberOfCustomerCampaign_period'];
            $budget_period_sh = $request['budget_period'];

            if( $request['is_ping_account'] == 0 ){
                $budget_must_be_sh = $number_of_lead_sh * $request['budget_bid_shared'];
                if( $budget_period_sh != $number_of_lead_p_sh){
                    if( $number_of_lead_p_sh == 1 ){
                        if( $budget_period_sh == 2 ){
                            $budget_must_be_sh = $budget_must_be_sh * 7;
                        } else if( $budget_period_sh == 3 ){
                            $budget_must_be_sh = $budget_must_be_sh * 30;
                        }
                    } else  if( $number_of_lead_p_sh == 2 ){
                        if( $budget_period_sh == 1 ){
                            $budget_must_be_sh = ceil($budget_must_be_sh / 7);
                        } else if( $budget_period_sh == 3 ){
                            $budget_must_be_sh = ceil(($budget_must_be_sh / 7) * 30);
                        }
                    } else if( $number_of_lead_p_sh == 3 ){
                        if( $budget_period_sh == 1 ){
                            $budget_must_be_sh = ceil($budget_must_be_sh / 30);
                        } else if( $budget_period_sh == 2 ){
                            $budget_must_be_sh = ceil(($budget_must_be_sh / 30) * 7);
                        }
                    }
                }
            } else {
                $budget_must_be_sh = $request['budget'];
            }
        } elseif($request['type_id'] == 3 && $request['budget_bid_shared'] == 0){
            $budget_bid_shared = $request['budget_bid_shared'] + $virtual_price;
            $budget_must_be_sh = $request['budget'];
        }

        $budget_bid_exclusive = 0;
        $budget_must_be_ex = 0;
        if( !empty($request['budget_bid_exclusive']) ){
            $budget_bid_exclusive = $request['budget_bid_exclusive'] + $virtual_price;
            $number_of_lead_ex = $request['numberOfCustomerCampaign_exclusive'];
            $number_of_lead_p_ex = $request['numberOfCustomerCampaign_period_exclusive'];
            $budget_period_ex = $request['budget_period_exclusive'];

            if( $request['is_ping_account'] == 0 ) {
                $budget_must_be_ex = $number_of_lead_ex * $request['budget_bid_exclusive'];
                if ($budget_period_ex != $number_of_lead_p_ex) {
                    if ($number_of_lead_p_ex == 1) {
                        if ($budget_period_ex == 2) {
                            $budget_must_be_ex = $budget_must_be_ex * 7;
                        } else if ($budget_period_ex == 3) {
                            $budget_must_be_ex = $budget_must_be_ex * 30;
                        }
                    } else if ($number_of_lead_p_ex == 2) {
                        if ($budget_period_ex == 1) {
                            $budget_must_be_ex = ceil($budget_must_be_ex / 7);
                        } else if ($budget_period_ex == 3) {
                            $budget_must_be_ex = ceil(($budget_must_be_ex / 7) * 30);
                        }
                    } else if ($number_of_lead_p_ex == 3) {
                        if ($budget_period_ex == 1) {
                            $budget_must_be_ex = ceil($budget_must_be_ex / 30);
                        } else if ($budget_period_ex == 2) {
                            $budget_must_be_ex = ceil(($budget_must_be_ex / 30) * 7);
                        }
                    }
                }
            } else {
                $budget_must_be_ex = $request['budget_exclusive'];
            }
        } elseif($request['type_id'] == 3 && $request['budget_bid_shared'] == 0){
            $budget_bid_exclusive = $request['budget_bid_exclusive'] + $virtual_price;
            $budget_must_be_ex = $request['budget_exclusive'];
        }

        if( empty($request['is_utility_providers']) ){
            $request['is_utility_providers'] = 0;
        }

        if ($request['append_zip_codes'] == 1) {
            //to get all save zipcode
            $campaign_distance_area = $request['distance_area'];
        } else {
            $campaign_distance_area = "";
        }

        DB::table('campaigns')->where('campaign_id', $campaign_id)
            ->update([
                'campaign_name'                           => $request['Campaign_name'],
                'campaign_count_lead'                     => $request['numberOfCustomerCampaign'],
                'period_campaign_count_lead_id'           => $request['numberOfCustomerCampaign_period'],
                'campaign_budget'                         => $budget_must_be_sh,
                'period_campaign_budget_id'               => $request['numberOfCustomerCampaign_period'],
                'campaign_count_lead_exclusive'           => $request['numberOfCustomerCampaign_exclusive'],
                'period_campaign_count_lead_id_exclusive' => $request['numberOfCustomerCampaign_period_exclusive'],
                'campaign_budget_exclusive'               => $budget_must_be_ex,
                'period_campaign_budget_id_exclusive'     => $request['budget_period_exclusive'],
                'campaign_distance_area'                  => $campaign_distance_area,
                'campaign_budget_bid_shared'              => $budget_bid_shared,
                'campaign_budget_bid_exclusive'           => $budget_bid_exclusive,
                'virtual_price'                           => $virtual_price,
                'service_campaign_id'                     => $request['service_id'],
                'crm'                                     => (!empty($crm) ?  json_encode($crm) : "[]"),
                'is_multi_crms'                           => ($request['is_multi_crms'] == 1 ?  $request['is_multi_crms'] : 0),
                'email1'                                  => $request['FirstEmail'],
                'email2'                                  => $request['SecondEmail'],
                'email3'                                  => $request['ThirdEmail'],
                'email4'                                  => $request['FourthEmail'],
                'email5'                                  => $request['FifthEmail'],
                'email6'                                  => $request['SixthEmail'],
                'phone1'                                  => trim(str_replace([' ', '(', ')', '-'], '', $request['FirstPhone'])),
                'phone2'                                  => trim(str_replace([' ', '(', ')', '-'], '', $request['SecondPhone'])),
                'phone3'                                  => trim(str_replace([' ', '(', ')', '-'], '', $request['ThirdPhone'])),
                'Subject_Email'                           => $request['SubjectEmail'],
                'is_ping_account'                         => $request['is_ping_account'],
                'is_utility_solar_filter'                 => $request['is_utility_providers'],
                'website'                                 => json_encode($request['website']),
                'transfer_numbers'                        => $request['transfer_numbers'],
                'delivery_Method_id'                      => json_encode(array_values(array_unique(!empty($request['deliveryMethod']) ? $request['deliveryMethod'] : array()))),
                'custom_paid_campaign_id'                 => json_encode(array_values(array_unique(!empty($request['customPaid']) ? $request['customPaid'] : array()))),
                'click_url'                               => $request['landingPageUrl'],
                'click_text'                              => $request['click_text'],
            ]);

        $dbQuery = DB::table('campaigns_questions')->where('campaign_id', $campaign_id);

        $allSErvicesQuestions = new AllServicesQuestions();

        $allSErvicesQuestions->updateFromCampaigns($dbQuery, $request);

        //** import Zipcods from excel ***/
        $array_zipcode_distance         = array();
        $zip_codes_array_saved          = array();
        $zipcode_id_array               = array();
        $array_zipcode_distance_final   = array();

        //if append checked get all save zip code
        if (!empty($request['append_zip_codes'])) {
            if ($request['append_zip_codes'] == 1) {
                //to get all save zipcode
                $zip_codes_array_saved = DB::table('campaign_target_area')
                    ->where('campaign_id', $campaign_id)->first(['zipcode_id']);
            }
        }
        if (empty($zip_codes_array_saved->zipcode_id)) {
            $zip_codes_array_saved = array();
        } else {
            $zip_codes_array_saved = json_decode($zip_codes_array_saved->zipcode_id);
        }

        //** import Zipcods from excel ***/
        if ($request->hasFile('listOfzipcode')) {
            $dataexcel = Excel::toArray(new SalesOrder, $request->file('listOfzipcode'));
            $dataexcel = array_unique($dataexcel[0], SORT_REGULAR);

            foreach ($dataexcel as $item) {
                // To fetch zipcode
//                if (Cache::has("AllZipCode")) {
//                    $zipcode_data_cache = Cache::get("AllZipCode")->where('zip_code_list', $item[0]);
//                    foreach ($zipcode_data_cache as $data) {
//                        $zipcode_id = $data->zip_code_list_id;
//                    }
//                } else {
                $zipcode_id = DB::table('zip_codes_lists')->where('zip_code_list', $item[0])->first(['zip_code_list_id']);
                //   $zipcode_id = $zipcode_id->zip_code_list_id;
                //}
                if (!empty($zipcode_id)) {
                    //** to save all zip code from excel to array */
                    $zipcode_id_array[] = $zipcode_id->zip_code_list_id;
                }
            }
        }
        if (!empty($request['zipcode'])) {
            if( is_array($request['zipcode']) ){
                foreach($request['zipcode'] as $zipcodeIN){
                    $zipcode_id_array[]= $zipcodeIN;
                }
            }
            if (count($request['zipcode']) == 1 && !empty($request['distance_area']) && $request['distance_area'] != 0) {
                $distancekm = $request['distance_area'];
                // To fetch zipcode
                if(Cache::has("AllZipCode")){
                    $zipcode_data_cache = Cache::get("AllZipCode")->where('zip_code_list_id', $request['zipcode']['0']);
                    foreach($zipcode_data_cache as $item){
                        $zipcode = $item->zip_code_list;
                    }
                } else {
                    $zipcode_data = DB::table('zip_codes_lists')
                        ->where('zip_code_list_id', $request['zipcode']['0'])
                        ->first(['zip_code_list']);
                    $zipcode = $zipcode_data->zip_code_list;
                }

                if (!empty($zipcode)) {
                    $main_api_file = new ApiMain();
                    $decoded_result = $main_api_file->zipcodes_distance_filter($zipcode, $distancekm);

                    if (!empty($decoded_result['zip_codes'])) {
                        foreach ($decoded_result['zip_codes'] as $val) {
                            $array_zipcode_distance [] = $val['zip_code'];
                        }
                    }
                }
                //get all zipcode_id from zip code dis
                if(!empty($array_zipcode_distance)) {
                    // To fetch zip_codes_array
                    if(Cache::has('AllZipCode')){
                        $array_zipcode_distance_final = Cache::get("AllZipCode")->whereIn('zip_code_list', $array_zipcode_distance)->pluck('zip_code_list_id')->toArray();
                        $array_zipcode_distance_final = array_unique($array_zipcode_distance_final);
                    } else {
                        $array_zipcode_distance_final = DB::table('zip_codes_lists')->whereIn('zip_code_list', $array_zipcode_distance)->distinct('zip_code_list')->pluck('zip_code_list_id')->toArray();
                    }
                }
            } else {
                $array_zipcode_distance_final = array();
            }
        }

        //Zip_Cods not in Campaign
        $dataexcel_expect = array();
        $array_zipcodeEx_id = array();
        ////if append checked get all save zip code Excluded in  table
        if (!empty($request['Append_All_Excluded_Zip_Codes'])) {
            if ($request['Append_All_Excluded_Zip_Codes'] == 1) {
                //to get all save zipcode
                $zip_codes_array_saved_Excluded = DB::table('campaign_target_area')
                    ->where('campaign_id', $campaign_id)->first(['zipcode_ex_id']);
            }
        }
        if (empty($zip_codes_array_saved_Excluded->zipcode_ex_id)) {
            $zip_codes_array_saved_Excluded = array();
        } else {
            $zip_codes_array_saved_Excluded = json_decode($zip_codes_array_saved_Excluded->zipcode_ex_id);
        }

        //import Zipcods from excel EX
        if( $request->hasFile('listOfzipcode_expect') ){
            $dataexcel_expect = Excel::toArray(new SalesOrder, $request->file('listOfzipcode_expect'));
            $dataexcel = array_unique($dataexcel_expect[0], SORT_REGULAR);
            foreach( $dataexcel as $item ){
                // To fetch zipcode
//                if(Cache::has('AllZipCode')){
//                    $zipcode_id_arrayEX = Cache::get("AllZipCode")->where('zip_code_list', $item[0]);
//                    foreach($zipcode_id_arrayEX as $item){
//                        $zipcode_idEX = $item->zip_code_list_id;
//                    }
//                } else {
                $zipcode_idEX = DB::table('zip_codes_lists')->where('zip_code_list', $item[0])->first(['zip_code_list_id']);
                //  $zipcode_idEX = $zipcode_idEX->zip_code_list_id;
                // }
                if( !empty($zipcode_idEX) ) {
                    $array_zipcodeEx_id[]= $zipcode_idEX->zip_code_list_id;
                }
            }
        }

        //Zip_Cods not in Campaign
        if( !empty($request['zipcode_expect']) ){
            if( is_array($request['zipcode_expect']) ){
                foreach($request['zipcode_expect'] as $zipcode){
                    $array_zipcodeEx_id[]= $zipcode;
                }
            }
        }

        //marge zipcode id from input and distance
        $final_zip_code = array_merge($array_zipcode_distance_final , $zipcode_id_array);
        $final_zip_code = array_merge($final_zip_code , $zip_codes_array_saved);
        $final_zip_code_Excluded = array_merge($zip_codes_array_saved_Excluded , $array_zipcodeEx_id);

        //get all state_id from zipcode_id
        if(Cache::has('AllZipCode')){
            $state_id_array = Cache::get("AllZipCode")->whereIn('zip_code_list_id', $final_zip_code)->pluck('state_id')->toArray();
            $state_id_array = array_unique($state_id_array);
        } else {
            $state_id_array =  ZipCodesList::join('states', 'states.state_id', '=', 'zip_codes_lists.state_id')
                ->whereIn('zip_codes_lists.zip_code_list_id', $final_zip_code)->distinct('states.state_id')->pluck('states.state_id')->toArray();
        }

        //get all state_id from city_id
        if (!empty($request['city'])) {
            if (Cache::has('AllCities')) {
                $city_id_array = Cache::get("AllCities")->whereIn('city_id', $request['city'])->pluck('state_id')->toArray();
                $city_id_array = array_unique($city_id_array);
            } else {
                $city_id_array = City::join('states', 'states.state_id', '=', 'cities.state_id')
                    ->whereIn('cities.city_id', $request['city'])->distinct('states.state_id')->pluck('states.state_id')->toArray();
            }
        } else {
            $city_id_array = array();
        }

        //get all state_id from county_id
        if(!empty($request['county'])) {
            if (Cache::has('AllCounty')) {
                $county_id_array = Cache::get("AllCounty")->whereIn('county_id', $request['county'])->pluck('state_id')->toArray();
                $county_id_array = array_unique($county_id_array);
            } else {
                $county_id_array = County::join('states', 'states.state_id', '=', 'counties.state_id')
                    ->whereIn('counties.county_id', $request['county'])->distinct('states.state_id')->pluck('states.state_id')->toArray();
            }
        } else {
            $county_id_array = array();
        }

        if(empty($request['state'])){
            $state_array = array();
        } else {
            $state_array = $request['state'];
        }

        //State Filter Campaign
        $state_id_array_final = array_merge($state_array , $state_id_array);
        $state_id_array_final = array_merge($state_id_array_final , $city_id_array);
        $state_id_array_final = array_merge($state_id_array_final , $county_id_array);
        $state_id_array_final = array_unique($state_id_array_final);

        // To fetch all State
        if(Cache::has('AllState')){
            $states = Cache::get("AllState");
        } else {
            $states = State::All();
        }

        $stateCode = array();
        $stateName = array();
        foreach ($states as $state1) {
            if(in_array($state1->state_id, $state_id_array_final)) {
                $stateCode []= $state1->state_code;
                $stateName []= $state1->state_name;
            }
        }

        DB::table('campaign_target_area')->where('campaign_id', $campaign_id)
            ->update([
                'stateFilter_id'         => json_encode(array_values(array_unique(!empty($state_id_array_final) ? $state_id_array_final : array()))),
                'state_id'               => json_encode(!empty($request['state']) ? $request['state'] : array()),
                'county_id'              => json_encode(!empty($request['county']) ? $request['county'] : array()),
                'city_id'                => json_encode(!empty($request['city']) ? $request['city'] : array()),
                'zipcode_id'             => json_encode(array_values(array_unique(!empty($final_zip_code) ? $final_zip_code : array()))),
                'county_ex_id'           => json_encode(!empty($request['county_expect']) ? $request['county_expect'] : array()),
                'city_ex_id'             => json_encode(!empty($request['city_expect']) ? $request['city_expect'] : array()),
                'zipcode_ex_id'          => json_encode(array_values(array_unique(!empty($final_zip_code_Excluded) ? $final_zip_code_Excluded : array()))),
                'stateFilter_name'       => json_encode(array_values(array_unique(!empty($stateName) ? $stateName : array()))),
                'stateFilter_code'       => json_encode(array_values(array_unique(!empty($stateCode) ? $stateCode : array()))),
            ]);

        //Access LOG
        AccessLog::create([
            'user_id' => Auth::user()->id,
            'user_name' => Auth::user()->username,
            'section_id' => $campaign_id,
            'section_name' => $request['Campaign_name'],
            'user_role' => Auth::user()->role_id,
            'section'   => 'Campaign',
            'action'    => 'Updated',
            'ip_address' => request()->ip(),
            'location' => json_encode(\Location::get(request()->ip())),
            'request_method' => json_encode($request->all())
        ]);

        return redirect()->back();
    }

    public function CampaignAddClone(Request $request){
        $this->validate($request, [
            'campaign_id' => ['required', 'string', 'max:255'],
            'campaign_name' => ['required', 'string', 'max:255'],
        ]);

        $campaign_id = $request['campaign_id'];

        $campaign_Data = Campaign::join('campaign_time_delivery', 'campaign_time_delivery.campaign_id', '=', 'campaigns.campaign_id')
            ->join('campaigns_questions', 'campaigns_questions.campaign_id', '=', 'campaigns.campaign_id')
            ->join('campaign_target_area', 'campaign_target_area.campaign_id', '=', 'campaigns.campaign_id')
            ->where('campaigns.campaign_id', $campaign_id)
            ->first();

        $campaign = new Campaign();

        $campaign->campaign_name = $request['campaign_name'];
        $campaign->campaign_count_lead = $campaign_Data->campaign_count_lead;
        $campaign->campaign_budget = $campaign_Data->campaign_budget;
        $campaign->period_campaign_count_lead_id = $campaign_Data->period_campaign_count_lead_id;
        $campaign->period_campaign_budget_id = $campaign_Data->period_campaign_budget_id;
        $campaign->campaign_count_lead_exclusive = $campaign_Data->campaign_count_lead_exclusive;
        $campaign->campaign_budget_exclusive = $campaign_Data->campaign_budget_exclusive;
        $campaign->period_campaign_count_lead_id_exclusive = $campaign_Data->period_campaign_count_lead_id_exclusive;
        $campaign->period_campaign_budget_id_exclusive = $campaign_Data->period_campaign_budget_id_exclusive;
        $campaign->service_campaign_id = $campaign_Data->service_campaign_id;
        $campaign->multi_service_accept = $campaign_Data->multi_service_accept;
        $campaign->sec_service_accept = $campaign_Data->sec_service_accept;

        $campaign->campaign_status_id =  3;
        $campaign->user_id = $campaign_Data->user_id;
        $campaign->campaign_distance_area = $campaign_Data->campaign_distance_area;
        $campaign->campaign_distance_area_expect = $campaign_Data->campaign_distance_area_expect;
        $campaign->campaign_budget_bid_shared = ($campaign_Data->campaign_budget_bid_shared - $campaign_Data->virtual_price);
        $campaign->campaign_budget_bid_exclusive = ($campaign_Data->campaign_budget_bid_exclusive - $campaign_Data->virtual_price);
        $campaign->campaign_Type = $campaign_Data->campaign_Type;
        $campaign->file_calltools_id = $campaign_Data->file_calltools_id;
        $campaign->crm = $campaign_Data->crm;
        $campaign->is_multi_crms = $campaign_Data->is_multi_crms;
        $campaign->band_width_accept_record = $campaign_Data->band_width_accept_record;

        $campaign->phone1 = $campaign_Data->phone1;
        $campaign->phone2 = $campaign_Data->phone2;
        $campaign->phone3 = $campaign_Data->phone3;
        $campaign->email1 = $campaign_Data->email1;
        $campaign->email2 = $campaign_Data->email2;
        $campaign->email3 = $campaign_Data->email3;
        $campaign->email4 = $campaign_Data->email4;
        $campaign->email5 = $campaign_Data->email5;
        $campaign->email6 = $campaign_Data->email6;
        $campaign->subject_email = $campaign_Data->subject_email;

        $campaign->lead_source = $campaign_Data->lead_source;
        $campaign->is_ping_account = $campaign_Data->is_ping_account;
        $campaign->is_utility_solar_filter = $campaign_Data->is_utility_solar_filter;
        $campaign->vendor_id = time();
        $campaign->website = $campaign_Data->website;
        $campaign->transfer_numbers = $campaign_Data->transfer_numbers;
        $campaign->exclude_include_type = $campaign_Data->exclude_include_type;
        $campaign->exclude_include_campaigns = $campaign_Data->exclude_include_campaigns;
        $campaign->custom_paid_campaign_id = $campaign_Data->custom_paid_campaign_id;
        $campaign->delivery_Method_id = $campaign_Data->delivery_Method_id;
        $campaign->click_url = $campaign_Data->click_url;
        $campaign->click_text = $campaign_Data->click_text;

        $campaign->save();
        $new_campaign_id = DB::getPdo()->lastInsertId();

        DB::table('campaign_time_delivery')->insert([
            'campaign_id'                       => $new_campaign_id,
            'campaign_time_delivery_status'     => $campaign_Data->campaign_time_delivery_status,
            'campaign_time_delivery_timezone'   => $campaign_Data->campaign_time_delivery_timezone,

            'start_sun'                         => $campaign_Data->start_sun,
            'end_sun'                           => $campaign_Data->end_sun,
            'status_sun'                        => $campaign_Data->status_sun,

            'start_mon'                         => $campaign_Data->start_mon,
            'end_mon'                           => $campaign_Data->end_mon,
            'status_mon'                        => $campaign_Data->status_mon,

            'start_tus'                         => $campaign_Data->start_tus,
            'end_tus'                           => $campaign_Data->end_tus,
            'status_tus'                        => $campaign_Data->status_tus,

            'start_wen'                         => $campaign_Data->start_wen,
            'end_wen'                           => $campaign_Data->end_wen,
            'status_wen'                        => $campaign_Data->status_wen,

            'start_thr'                         => $campaign_Data->start_thr,
            'end_thr'                           => $campaign_Data->end_thr,
            'status_thr'                        => $campaign_Data->status_thr,

            'start_fri'                         => $campaign_Data->start_fri,
            'end_fri'                           => $campaign_Data->end_fri,
            'status_fri'                        => $campaign_Data->status_fri,

            'start_sat'                         => $campaign_Data->start_sat,
            'end_sat'                           => $campaign_Data->end_sat,
            'status_sat'                        => $campaign_Data->status_sat,
        ]);

        $dbQuery = DB::table('campaigns_questions');

        $allServicesQuestions = new AllServicesQuestions();

        $allServicesQuestions->insertFromCloneCampaigns($dbQuery, $campaign_Data, $new_campaign_id);

        DB::table('campaign_target_area')->insert([
            'campaign_id'               => $new_campaign_id,
            'stateFilter_id'            => $campaign_Data->stateFilter_id,
            'state_id'                  => $campaign_Data->state_id,
            'county_id'                 => $campaign_Data->county_id,
            'city_id'                   => $campaign_Data->city_id,
            'zipcode_id'                => $campaign_Data->zipcode_id,
            'county_ex_id'              => $campaign_Data->county_ex_id,
            'city_ex_id'                => $campaign_Data->city_ex_id,
            'zipcode_ex_id'             => $campaign_Data->zipcode_ex_id,
            'stateFilter_name'          => $campaign_Data->stateFilter_name,
            'stateFilter_code'          => $campaign_Data->stateFilter_code,
        ]);

        if (config('app.env', 'local') != "local") {
            if( config('app.name', '') == "Zone1Remodeling" ){
                $admin_email1 = "jim@zone1remodeling.com";
            } else {
                $admin_email1 = "logan@allieddigitalmedia.com";
            }
            $admin_email2 = "mike@allieddigitalmedia.com";
            $admin_name = "Team";
            $subject_email = "New Campaign";
            $data_msg = array(
                'name' => $admin_name,
                'campaign' => $request['campaign_name'],
                'url' => 'Admin/Campaign/' . Auth::user()->id
            );
            Mail::send(['text' => 'Mail.campaign_approved'], $data_msg, function ($message) use ($admin_email1, $admin_name, $subject_email, $admin_email2) {
                $message->to($admin_email1, $admin_name)->cc($admin_email2)->subject($subject_email);
                $message->from(config('mail.from.address', ''), config('mail.from.name', ''));
            });
        }

        //Access LOG
        AccessLog::create([
            'user_id' => Auth::user()->id,
            'user_name' => Auth::user()->username,
            'section_id' => $campaign_id,
            'section_name' => $request['campaign_name'],
            'user_role' => Auth::user()->role_id,
            'section'   => 'Campaign',
            'action'    => 'Created',
            'ip_address' => request()->ip(),
            'location' => json_encode(\Location::get(request()->ip())),
            'request_method' => json_encode($request->all())
        ]);

        return redirect()->back();
    }

    public function DeleteCampaign(Request $request){
        $this->validate($request, [
            'campaign_id' => ['required', 'string', 'max:255']
        ]);
        $campaign_id = $request['campaign_id'];

        DB::table('campaigns')->where('campaign_id', $campaign_id)
            ->update([
                'campaign_visibility'=>0,
                'campaign_status_id'=>2
            ]);

        $campaign_name = DB::table('campaigns')->where('campaign_id', $campaign_id)->first(['campaign_name']);

        //Access LOG
        AccessLog::create([
            'user_id' => Auth::user()->id,
            'user_name' => Auth::user()->username,
            'section_id' => $campaign_id,
            'section_name' => $campaign_name->campaign_name,
            'user_role' => Auth::user()->role_id,
            'section'   => 'Campaign',
            'action'    => 'Deleted',
            'ip_address' => request()->ip(),
            'location' => json_encode(\Location::get(request()->ip())),
            'request_method' => json_encode($request->all())
        ]);

        return redirect()->back();
    }

    public function CampaignChangeStatus(Request $request){
        $data = explode("-",$request->status);
        $campaign_id = $data[1];
        $status = $data[0];

        $change = DB::table('campaigns')->where('campaign_id', $campaign_id)->update(['campaign_status_id'=> $status]);
        $campaign_name = DB::table('campaigns')->where('campaign_id', $campaign_id)->first(['campaign_name', 'campaign_Type']);

        $All_Campaign_User = DB::table('campaigns')
            ->where('user_id', Auth::user()->id)
            ->where('campaign_visibility', '1')
            ->pluck('campaign_status_id')->toArray();

        if (count(array_unique($All_Campaign_User)) == 1 && $All_Campaign_User['0'] == 2) {
            DB::table('users')->where('id', Auth::user()->id)->update(['user_visibility'=> 2]);
        }

        if( $status == 3 ){
            $action = "Pending";
        } elseif( $status == 1 ){
            $action = "Running";
        } elseif( $status == 4 ){
            $action = "Pause";
        } else {
            $action = "Stopped";
        }

        //this update for call centers campaigns
        if (config('app.env', 'local') != "local") {
            if (in_array($campaign_name->campaign_Type, array(4, 5, 6, 7))) {
                //For send an email to account manager or sales & call center when stop or running the campaigns
                $admin_email2 = array();
                $admin_ac = User::find(Auth::user()->acc_manger_id);
                if (!empty($admin_ac)) {
                    $admin_email2[] = $admin_ac->email;
                }

                $admin_email1 = "john@allieddigitalmedia.net";
                $admin_email2[] = "lana@allieddigitalmedia.net";
                $admin_name = "Team";
                $subject_email = "$action Campaign";
                $data_msg = array(
                    'name' => $admin_name,
                    'campaign' => $campaign_name->campaign_name . " #$campaign_id",
                    "action" => $action,
                    "user_type" => "Buyer",
                    'url' => 'Admin/Campaign/' . Auth::user()->id
                );

                Mail::send(['text' => 'Mail.campaign_status_change'], $data_msg, function ($message) use ($admin_email1, $admin_name, $subject_email, $admin_email2) {
                    $message->to($admin_email1, $admin_name)->cc($admin_email2)->subject($subject_email);
                    $message->from(config('mail.from.address', ''), config('mail.from.name', ''));
                });
            }

            //Send Slack Notifications
            if( config('app.name', '') == "Zone1Remodeling" ){
                $client = new Client('https://hooks.slack.com/services/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');
            } else {
                $client = new Client('https://hooks.slack.com/services/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');
            }
            $client->send("Buyer " . Auth::user()->user_business_name . " has changed " . $campaign_name->campaign_name . " #$campaign_id campaign status to $action on " . config("app.name", "") . ".");
        }

        //Access LOG
        AccessLog::create([
            'user_id' => Auth::user()->id,
            'user_name' => Auth::user()->username,
            'section_id' => $campaign_id,
            'section_name' => $campaign_name->campaign_name,
            'user_role' => Auth::user()->role_id,
            'section'   => 'Campaign',
            'action'    => $action,
            'ip_address' => request()->ip(),
            'location' => json_encode(\Location::get(request()->ip())),
            'request_method' => json_encode($request->all())
        ]);

        return response()->json($change, 200);
    }
}
