<?php

namespace App\Http\Controllers\NewFilter;

use App\CampaignType;
use App\Services\ApiMain;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Rap2hpoutre\FastExcel\FastExcel;

class FilterCampaignController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'AdminMiddleware']);
    }

    public function index(Request $request){
        $services = DB::table('service__campaigns')->get();
        $types = CampaignType::All();

        $zipcodes_reports_Filter = "";
        $service_id = "";
        $campaign_type = "";
        $campaigns = array();
        if(isset($request->submit_btn)){
            $zipcodes_reports_Filter = $request->zipcodes_reports_Filter;
            $service_id = $request->service_id;
            $campaign_type = $request->campaign_type;

            $campaigns = DB::table('campaigns')
                ->join('campaign_types', 'campaign_types.campaign_types_id', '=', 'campaigns.campaign_Type')
                ->join('service__campaigns', 'service__campaigns.service_campaign_id', '=', 'campaigns.service_campaign_id')
                ->join('campaign_status', 'campaign_status.campaign_status_id', '=', 'campaigns.campaign_status_id')
                ->join('campaign_time_delivery', 'campaign_time_delivery.campaign_id', '=', 'campaigns.campaign_id')
                ->join('users', 'users.id', '=', 'campaigns.user_id')
                ->join('campaign_target_area', 'campaign_target_area.campaign_id', '=', 'campaigns.campaign_id')
                ->leftJoin('users AS acc_manager_users', 'acc_manager_users.id', '=', 'users.acc_manger_id')
                ->where('users.user_visibility', 1)
                ->where('campaigns.campaign_visibility', 1)
                ->where('campaigns.is_seller', 0)
                ->where('campaigns.campaign_status_id', 1);

            if (!empty($zipcodes_reports_Filter)) {
                $city_State_County = DB::table('zip_codes_lists')
                    ->where('zip_code_list_id', $zipcodes_reports_Filter)->first();

                if(!empty($city_State_County)){
                    $zipcode_id = $zipcodes_reports_Filter;
                    $city_id = $city_State_County->city_id;
                    $county_id = $city_State_County->county_id;
                    $state_id = $city_State_County->state_id;

                    $campaigns->where(function($query) use($zipcode_id, $city_id, $county_id, $state_id){
                        $query->whereJsonContains('campaign_target_area.zipcode_id', (int)$zipcode_id);
                        $query->OrwhereJsonContains('campaign_target_area.city_id', "$city_id");
                        $query->OrwhereJsonContains('campaign_target_area.county_id', "$county_id");
                        $query->OrwhereJsonContains('campaign_target_area.state_id', "$state_id");
                    })
                        ->whereJsonDoesntContain('campaign_target_area.city_ex_id', "$city_id")
                        ->whereJsonDoesntContain('campaign_target_area.county_ex_id', "$county_id")
                        ->whereJsonDoesntContain('campaign_target_area.zipcode_ex_id', (int)$zipcode_id);
                }
            }

            if (!empty($service_id)) {
                $campaigns->where('campaigns.service_campaign_id', $service_id);
            }

            if (!empty($campaign_type)) {
                if( $campaign_type == "CallCenter" ){
                    $campaigns->whereIn('campaigns.campaign_Type', array(4,5,6,7));
                } else {
                    $campaigns->where('campaigns.campaign_Type', $campaign_type);
                }
            }

            $campaigns = $campaigns->orderBy('campaigns.campaign_budget_bid_shared', 'DESC')
                ->orderBy('campaigns.campaign_budget_bid_exclusive', 'DESC')
                ->orderBy('campaigns.created_at', 'DESC')
                ->get([
                    'service__campaigns.service_campaign_name','campaign_time_delivery.*','campaigns.*', "campaign_target_area.stateFilter_code AS state_code",
                    'users.username', 'users.user_business_name', 'campaign_types.campaign_types_name','acc_manager_users.username AS acc_manager_username',
                    DB::raw("(SELECT date FROM campaigns_leads_users
                     WHERE campaigns_leads_users.campaign_id = campaigns.campaign_id
                     ORDER BY campaigns_leads_users.campaigns_leads_users_id
                     DESC LIMIT 1) as Last_Pay")
                ]);
        }

        return view('Admin.FilterCampaign.ListOfCampaignFilter', compact('services', 'types', 'service_id', 'zipcodes_reports_Filter', 'campaign_type', 'campaigns' ));
    }

    public function listZipCodeByServiceShow(){
        $services = DB::table('service__campaigns')->get()->All();
        $Platforms = DB::table('marketing_platforms')->get()->All();
        $campaignTypes = DB::table('campaign_types')->get()->All();

        return view('Admin.FilterCampaign.ListOfZipCodeByServiceFilter')
            ->with('services', $services)
            ->with('Platforms', $Platforms)
            ->with('campaignTypes',$campaignTypes);
    }
    public function ExportlistZipCodeByService(Request $request){
        ini_set('max_execution_time', '0');
        ini_set('memory_limit', '-1');
        $service_id = $request->service_id;
        $platform_id = $request->platform_id;
        $campaignTypes = $request->campaignTypes;
        $user_type = $request->user_type;
        $campaignStatus = $request->campaignStatus;

        $staus = [1,4];
        $campaigns = DB::table('campaigns')
            ->join('users', 'users.id', '=', 'campaigns.user_id')
            ->join('campaign_target_area', 'campaign_target_area.campaign_id', '=', 'campaigns.campaign_id')
            ->where('campaigns.campaign_visibility', 1)
            ->where('users.user_visibility', 1)
            ->where('campaigns.is_seller', 0)
            ->where(function ($query) {
                $query->WhereJsonLength('campaign_target_area.state_id', '>', 0);
                $query->OrWhereJsonLength('campaign_target_area.county_id', '>', 0);
                $query->OrWhereJsonLength('campaign_target_area.city_id', '>', 0);
                $query->OrWhereJsonLength('campaign_target_area.zipcode_id', '>', 0);
            });

        if (!empty($service_id)) {
            $campaigns->where('service_campaign_id', $service_id);
        }

        if (!empty($platform_id)) {
            //For Marketing Filtration
            $campaigns->where(function($query) use($platform_id){
                $query->where('campaigns.lead_source', "");
                $query->OrWhere('campaigns.lead_source',"[]");
                $query->OrwhereJsonContains('campaigns.lead_source', 'All Source');
                $query->OrwhereJsonContains('campaigns.lead_source', $platform_id);
            });
        }

        if (!empty($campaignTypes)) {
            $campaigns->where('campaigns.campaign_Type', $campaignTypes);
        }

        if (!empty($user_type)) {
            $campaigns->whereIn('users.role_id', $user_type);
        }

        if (!empty($campaignStatus)) {
            if($campaignStatus == "Pause") {
                $campaigns->where('campaigns.campaign_status_id', 4);
            } elseif($campaignStatus == "Running") {
                $campaigns->where('campaigns.campaign_status_id', 1);
            } else {
                $campaigns->whereIn('campaigns.campaign_status_id', $staus);
            }
        }

        $campaigns = $campaigns->get();

        $EndZipCodeList = array();
        foreach($campaigns as $campaign){
            $ListOFZipCodeActive = DB::table('zip_codes_lists');
            //active zip code section
            $zipcode = json_decode($campaign->zipcode_id,true);
            $city = json_decode($campaign->city_id,true);
            $state = json_decode($campaign->state_id,true);
            $county = json_decode($campaign->county_id,true);

            if (!empty($state)) {
                $ListOFZipCodeActive->orWhereIn('state_id', $state);
            }
            if (!empty($city)) {
                $ListOFZipCodeActive->orWhereIn('city_id', $city);
            }
            if (!empty($county)) {
                $ListOFZipCodeActive->orWhereIn('county_id', $county);
            }
            if (!empty($zipcode)) {
                $ListOFZipCodeActive->orWhereIn('zip_code_list_id', $zipcode);
            }
            $ListOFZipCodeActive = $ListOFZipCodeActive->pluck('zip_code_list')->toArray();

            $ZipCodeActive = array_unique($ListOFZipCodeActive);

            //not active zip code section
            $ListOFZipCodeNotActive = DB::table('zip_codes_lists');
            $zipcode_ex = json_decode($campaign->zipcode_ex_id,true);
            $county_ex = json_decode($campaign->county_ex_id,true);
            $city_ex = json_decode($campaign->city_ex_id,true);

            if (!empty($city_ex)) {
                $ListOFZipCodeNotActive->orWhereIn('city_id', $city_ex);
            }
            if (!empty($county_ex)) {
                $ListOFZipCodeNotActive->orWhereIn('county_id', $county_ex);
            }
            if (!empty($zipcode_ex)) {
                $ListOFZipCodeNotActive->orWhereIn('zip_code_list_id', $zipcode_ex);
            }

            if (empty($city_ex) && empty($county_ex) && empty($zipcode_ex)) {
                $ZipCodeNotActiveArray = array();
            } else {
                $ListOFZipCodeNotActive = $ListOFZipCodeNotActive->pluck('zip_code_list')->toArray();
                $ZipCodeNotActiveArray = array_unique($ListOFZipCodeNotActive);
            }

            $EndZipCode = array_diff($ZipCodeActive, $ZipCodeNotActiveArray);

            $EndZipCodeList = array_merge($EndZipCodeList, $EndZipCode);
        }

        $EndZipCodeList = array_unique($EndZipCodeList);

        $AllZipCode = DB::table('zip_codes_lists')
            ->join('states', 'states.state_id', '=', 'zip_codes_lists.state_id')
            ->join('counties', 'counties.county_id', '=', 'zip_codes_lists.county_id')
            ->join('cities', 'cities.city_id', '=', 'zip_codes_lists.city_id')
            ->whereIn('zip_code_list', $EndZipCodeList)
            ->groupBy('zip_codes_lists.zip_code_list_id')
            ->get([
                'zip_codes_lists.zip_code_list_id', 'zip_codes_lists.zip_code_list',
                'zip_codes_lists.state_id', 'states.state_name', 'states.state_code',
                'zip_codes_lists.county_id', 'counties.county_name',
                'zip_codes_lists.city_id', 'cities.city_name'
            ]);

        return (new FastExcel($AllZipCode))->download('ZipCodes.csv', function ($data) {
            return [
                'ZipCode' => $data->zip_code_list,
                'City' => $data->city_name,
                'County' => $data->county_name,
                'State Name' => $data->state_name,
                'State Code' => $data->state_code,
            ];
        });
    }

    public function listLostLeadReportShow(){
        $campaign = DB::table('campaigns')
            ->join('users', 'users.id', '=', 'campaigns.user_id')
            ->where('campaigns.is_seller', 0)
            ->where('campaigns.campaign_visibility', 1)
            ->where('campaigns.campaign_status_id', 1)
            ->where('users.user_visibility', 1)
            ->get([
                'campaigns.*', 'users.user_business_name'
            ]);

        return view('Admin.FilterCampaign.LostLeadReport')
            ->with('campaign', $campaign);
    }
    public function filterLostLeadReport(Request $request){
        $campaign = $request->campaign;
        $start_date = date('Y-m-d', strtotime($request->start_date)) . " 00:00:00";
        $end_date = date('Y-m-d', strtotime($request->end_date)) . " 23:59:59";

        //Return Service name
        $campaign_Service = DB::table('campaigns')->where('campaign_id' ,$campaign)->first('service_campaign_id');
        //Purchase leads
        $listOfPurchaseLeads = DB::table('campaigns_leads_users')->where('campaign_id' ,$campaign)->pluck('lead_id')->toArray();

        $LostLeadReport = DB::table('report_lost_lead')
            ->join('leads_customers', 'leads_customers.lead_id', '=', 'report_lost_lead.Lead_id')
            ->where('leads_customers.lead_type_service_id',  $campaign_Service->service_campaign_id)
            ->whereNotIn('report_lost_lead.Lead_id', $listOfPurchaseLeads)
            ->whereBetween('report_lost_lead.created_at', [$start_date, $end_date])
            ->orderBy('report_lost_lead.created_at', 'DESC')
            ->get([
                'report_lost_lead.*','leads_customers.*',
            ]);

        $permission_users = array();
        if( !empty(Auth::user()->permission_users) ){
            $permission_users = json_decode(Auth::user()->permission_users, true);
        }

        $dataJason = '';
        $dataJason .= '<table class="table table-striped table-bordered" cellspacing="0" width="100%"';
        if( empty($permission_users) || in_array('3-4', $permission_users) ){
            $dataJason .= 'id="datatable-buttons"';
        } else {
            $dataJason .= 'id="datatable"';
        }

        $dataJason .= '><thead>
                                <tr>
                                    <th>lead Id</th>
                                    <th>Lead Name</th>
                                    <th>Source</th>
                                    <th>Reason</th>
                                    <th>Date</th>';
        if( empty($permission_users) || in_array('8-10', $permission_users) ) {
            $dataJason .= '<th>Action</th>';
        }
        $dataJason .= ' </tr>
                                </thead>
                                <tbody>';

        if( !empty($LostLeadReport) ){
            foreach ( $LostLeadReport as $LostLead ){
                if(!in_array($campaign, json_decode($LostLead->step1, true) ) ) {
                    $Reason = "Out of target area";
                } else if(in_array($campaign, json_decode($LostLead->step2, true) ) ) {
                    $Reason = "Criteria Not Match, Traffic excluded, Bid Zero";
                } else if(in_array($campaign, json_decode($LostLead->step3_1, true) ) ) {
                    $Reason = "Not accepting Multi Services";
                } else if(in_array($campaign, json_decode($LostLead->step3_2, true) ) ) {
                    $Reason = "Time Delivery";
                } else if(in_array($campaign, json_decode($LostLead->step3_3, true) ) ) {
                    $Reason = "Reached Cap";
                } else if(in_array($campaign, json_decode($LostLead->step3_4, true) ) ) {
                    $Crm_ping = DB::table('crm_responses')->where('crm_responses.campaign_id',  $campaign)
                        ->where('crm_responses.lead_id',  $LostLead->lead_id)->first();

                    $Reason = "Ping request was rejected or the bid was less than $1.";
                    if( !empty($Crm_ping) ){
                        //Change response to text only
                        $result_response_data = "";
                        $array_char_empty = ['"', "'", '{', '}', '[', ']', '#', '$', '@', '/', '(', ')', '\\'];
                        $array_char_space = [",", '|', '_'];
                        if( !empty($Crm_ping->response) ){
                            $result_response = json_decode($Crm_ping->response, true);
                            if( is_array($result_response) ) {
                                //If Json Response
                                $result_response_data = trim(str_replace($array_char_empty, '', $Crm_ping->response));
                                $result_response_data = trim(str_replace(":", ': ', $result_response_data));
                                $result_response_data = trim(str_replace($array_char_space, ' ', $result_response_data));
                                $result_response_data = strip_tags($result_response_data);
                            } else {
                                try {
                                    libxml_use_internal_errors(true);
                                    $result2 = simplexml_load_string($Crm_ping->response);
                                    $result3 = json_encode($result2);
                                    $result4 = json_decode($result3  , TRUE);

                                    if( !empty($result4) ){
                                        if( is_array($result4) ) {
                                            //If XML Response
                                            $result_response_data = trim(str_replace($array_char_empty, '', $result3));
                                            $result_response_data = trim(str_replace(":", ': ', $result_response_data));
                                            $result_response_data = trim(str_replace($array_char_space, ' ', $result_response_data));
                                            $result_response_data = strip_tags($result_response_data);
                                        }
                                    }
                                } catch (Exception $e){

                                }
                            }
                        }

                        if( $result_response_data == "" ){
                            //If Empty OR Text Response
                            $result_response_data = trim(str_replace($array_char_empty, '', $Crm_ping->response));
                            $result_response_data = trim(str_replace(":", ': ', $result_response_data));
                            $result_response_data = trim(str_replace($array_char_space, ' ', $result_response_data));
                            $result_response_data = strip_tags($result_response_data);
                        }

                        $Reason .= " Response: " . $result_response_data;
                    }
                } else if(in_array($campaign, json_decode($LostLead->step3_5, true) ) ) {
                    $Reason = "Out Bid" ;
                } else if(in_array($campaign, json_decode($LostLead->step4_1, true) ) ) {
                    $Reason = "Out of budget";
                } else if(in_array($campaign, json_decode($LostLead->step4_2, true) ) ) {
                    $Crm_post = DB::table('crm_responses')->where('crm_responses.campaign_id',  $campaign)
                        ->where('crm_responses.campaigns_leads_users_id',  $LostLead->lead_id)->first();

                    $Reason = "Post Rejected.";
                    if( !empty($Crm_post) ){
                        //Change response to text only
                        $result_response_data = "";
                        $array_char_empty = ['"', "'", '{', '}', '[', ']', '#', '$', '@', '/', '(', ')', '\\'];
                        $array_char_space = [",", '|', '_'];
                        if( !empty($Crm_post->response) ){
                            $result_response = json_decode($Crm_post->response, true);
                            if( is_array($result_response) ) {
                                //If Json Response
                                $result_response_data = trim(str_replace($array_char_empty, '', $Crm_post->response));
                                $result_response_data = trim(str_replace(":", ': ', $result_response_data));
                                $result_response_data = trim(str_replace($array_char_space, ' ', $result_response_data));
                                $result_response_data = strip_tags($result_response_data);
                            } else {
                                try {
                                    libxml_use_internal_errors(true);
                                    $result2 = simplexml_load_string($Crm_post->response);
                                    $result3 = json_encode($result2);
                                    $result4 = json_decode($result3  , TRUE);

                                    if( !empty($result4) ){
                                        if( is_array($result4) ) {
                                            //If XML Response
                                            $result_response_data = trim(str_replace($array_char_empty, '', $result3));
                                            $result_response_data = trim(str_replace(":", ': ', $result_response_data));
                                            $result_response_data = trim(str_replace($array_char_space, ' ', $result_response_data));
                                            $result_response_data = strip_tags($result_response_data);
                                        }
                                    }
                                } catch (Exception $e){

                                }
                            }
                        }

                        if( $result_response_data == "" ){
                            //If Empty OR Text Response
                            $result_response_data = trim(str_replace($array_char_empty, '', $Crm_post->response));
                            $result_response_data = trim(str_replace(":", ': ', $result_response_data));
                            $result_response_data = trim(str_replace($array_char_space, ' ', $result_response_data));
                            $result_response_data = strip_tags($result_response_data);
                        }

                        $Reason .= " Response: " . $result_response_data;
                    }
                } else {
                    if(in_array($campaign, json_decode($LostLead->step3_6, true) ) ){
                        $Reason = "Out Bid";
                    } else {
                        $Reason = "Other";
                    }
                }

                $dataJason .= "<tr>";
                $dataJason .= "<td>" . $LostLead->lead_id . "</td>";
                $dataJason .= "<td>" . $LostLead->lead_fname . " " . $LostLead->lead_lname . "</td>";
                $dataJason .= "<td>" . $LostLead->lead_source_text . "</td>";
                $dataJason .= "<td>" .$Reason . "</td>";
                $dataJason .= "<td>" . date('Y/m/d', strtotime($LostLead->created_at)) . "</td>";
                if( empty($permission_users) || in_array('8-10', $permission_users) ) {
                    $dataJason .= '<td>';
                    if( empty($permission_users) || in_array('8-10', $permission_users) ) {
                        $dataJason .= '<a href="/Admin/lead/' . $LostLead->lead_id . '/details/Lost" class="on-default edit-row" data-toggle="tooltip" data-placement="top" title="" data-original-title="Details" data-trigger="hover" data-animation="false">
                                     <i class="mdi mdi-file-document-box font-18 vertical-middle m-r-10"></i>
                               </a>';
                    }
                    $dataJason .= '</td>';
                }
                $dataJason .= '</tr>';
            }
        }

        $dataJason .= '</tbody>
                            </table>';

        return $dataJason;

    }

}
