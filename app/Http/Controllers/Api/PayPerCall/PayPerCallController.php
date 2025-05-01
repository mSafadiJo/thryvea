<?php
namespace App\Http\Controllers\Api\PayPerCall;

use App\Http\Controllers\Controller;
use App\LeadsCustomer;
use App\Models\ConnectLeadTime;
use App\Services\Allied\PingCRMAllied;
use App\Services\ApiMain;
use App\Services\APIValidations;
use App\Services\ServiceQueries;
use App\Services\Zone\PingCRMZone;
use App\TotalAmount;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Campaign;
use Illuminate\Support\Facades\DB;

class PayPerCallController extends Controller
{
    public function __construct()
    {
        ini_set('max_execution_time', '0');
        ini_set('memory_limit', '-1');
    }

    public function PayPerCallApi(Request $request)
    {
        $this->validate($request, [
            'city_id' => ['required', 'string', 'max:255'],
            'state_id' => ['required', 'string', 'max:255'],
            'zipcode_id' => ['required', 'string', 'max:255'],
            'service_id' => ['required'],
            'lead_website' => ['required']
        ]);

        $fName = strtolower($request['fname']);
        $lName = strtolower($request['lname']);
        if ($fName == "test" || $lName == "test" || $fName == "testing" || $lName == "testing") {
            return array();
        }

        $api_validations = new APIValidations();
        $service_queries = new ServiceQueries();

        //Return Lead Regarding Area =========================================================================
        $address['zipcode_id'] = $request['zipcode_id'];
        $address['zip_code_list'] = $request['zipcode_name'];
        $address['county_id'] = $request['county_id'];
        $address['county_name'] = $request['county_name'];
        $address['city_id'] = $request['city_id'];
        $address['city_name'] = $request['city_name'];
        $address['state_id'] = $request['state_id'];
        $address['state_name'] = $request['state_name'];
        $address['state_code'] = $request['state_code'];

        //Update Questions =======================================================================================
        if ($request['ownership'] == 2) {
            $request['ownership'] = 0;
        }
        //Kitchen
        if ($request['removing_adding_walls'] == 2) {
            $request['removing_adding_walls'] = 0;
        }
        if ($request['service_id'] == 4) {
            $request['projectnature'] = $request['nature_flooring_project'];
        } else if ($request['service_id'] == 6) {
            $request['projectnature'] = $request['nature_of_roofing'];
        } else if ($request['service_id'] == 7) {
            if ($request['nature_of_siding'] == 1 || $request['nature_of_siding'] == 2) {
                $request['projectnature'] = 1;
            } else if ($request['nature_of_siding'] == 3) {
                $request['projectnature'] = 2;
            } else if ($request['nature_of_siding'] == 4) {
                $request['projectnature'] = 3;
            }
        }
        //Update Questions =======================================================================================

        //start window questions ==========================================================================
        $questions = $api_validations->check_questions_ids_array($request);
        $LeaddataIDs = $questions['LeaddataIDs'];
        //end window questions ==========================================================================

        //==================================================================================================
        //List of PayPerClick Campaigns
        $campaigns_pay_per_click = $service_queries->service_queries_per_click_new_way($request->lead_website, $address);
        if (empty($campaigns_pay_per_click)) {
            return array();
        }
        $campaigns_pay_per_click_list = $campaigns_pay_per_click->pluck('campaign_id')->toArray();

        //List of Schedule Appointment Campaigns
        $campaigns_pay_per_appointment = $service_queries->service_queries_per_appointment_new_way($request->service_id, $LeaddataIDs, $address, $request->lead_website);
        if (empty($campaigns_pay_per_appointment)) {
            return array();
        }
        $campaigns_pay_per_appointment_list = $campaigns_pay_per_appointment->pluck('campaign_id')->toArray();

        //List of PayPerCall Campaigns
        $campaigns_pay_per_call = $service_queries->service_queries_per_call_new_way($request->service_id, $LeaddataIDs, $address, $request->lead_website);
        if (empty($campaigns_pay_per_call)) {
            return array();
        }
        $campaigns_pay_per_call_list = $campaigns_pay_per_call->pluck('campaign_id')->toArray();

        //to marge pay_per_call & pay_per_click & pay_per_appointment campaign
        $tempCollection = collect([$campaigns_pay_per_click, $campaigns_pay_per_appointment, $campaigns_pay_per_call]);
        $lastCampaignArray = $tempCollection->flatten(1);

        //to marge pay_per_call & pay_per_click & pay_per_appointment campaign_id
        $campaign_list = array_merge($campaigns_pay_per_click_list, $campaigns_pay_per_appointment_list, $campaigns_pay_per_call_list);
        //==================================================================================================

        //to check Lead Duplicated ===================================================================
        $Check_User = DB::table('leads_customers')
            ->where('lead_phone_number', $request['phone_number'])
            ->where('universal_leadid', $request['universal_leadid'])
            ->pluck('lead_id')->toArray();
        //to check Lead Duplicated ===================================================================

        //For Check Time Delivery and Campaign Cap =========================================================
        $listOFCampaignDB_array_shared = array();
        $data_pay_per_call = array();
        $data_pay_per_click = array();
        $data_pay_per_appointment = array();

        //Filtration For cap Shared
        $leadsCampaignsDailiesShared = DB::table('campaigns_leads_users')
            ->select('campaigns_leads_users_type_bid', 'campaign_id',
                DB::raw('COUNT(campaigns_leads_users_id) as totallead'),
                DB::raw('SUM(campaigns_leads_users_bid) as sumbid'))
            ->where('date', date("Y-m-d"))
            ->where('campaigns_leads_users_type_bid', 'Shared')
            ->whereIn('campaign_id', $campaign_list)
            ->groupBy('campaign_id')
            ->get()->keyBy('campaign_id');

        $leadsCampaignsWeeklyShared = DB::table('campaigns_leads_users')
            ->select('campaigns_leads_users_type_bid', 'campaign_id',
                DB::raw('COUNT(campaigns_leads_users_id) as totallead'),
                DB::raw('SUM(campaigns_leads_users_bid) as sumbid'))
            ->whereBetween('date', [date('Y-m-d', strtotime(Carbon::now()->startOfWeek())), date('Y-m-d', strtotime(Carbon::now()->endOfWeek()))])
            ->where('campaigns_leads_users_type_bid', 'Shared')
            ->whereIn('campaign_id', $campaign_list)
            ->groupBy('campaign_id')
            ->get()->keyBy('campaign_id');

        $leadsCampaignsMonthlyShared = DB::table('campaigns_leads_users')
            ->select('campaigns_leads_users_type_bid', 'campaign_id',
                DB::raw('COUNT(campaigns_leads_users_id) as totallead'),
                DB::raw('SUM(campaigns_leads_users_bid) as sumbid'))
            ->whereBetween('date', [date('Y-m') . '-1', date('Y-m-t')])
            ->where('campaigns_leads_users_type_bid', 'Shared')
            ->whereIn('campaign_id', $campaign_list)
            ->groupBy('campaign_id')
            ->get()->keyBy('campaign_id');

        $leadsCampaignsCapsShared['leadsCampaignsDailiesShared'] = json_decode($leadsCampaignsDailiesShared, true);
        $leadsCampaignsCapsShared['leadsCampaignsWeeklyShared'] = json_decode($leadsCampaignsWeeklyShared, true);
        $leadsCampaignsCapsShared['leadsCampaignsMonthlyShared'] = json_decode($leadsCampaignsMonthlyShared, true);

        $leadsCampaignsDailiesShared = (isset($leadsCampaignsCapsShared['leadsCampaignsDailiesShared']) ? $leadsCampaignsCapsShared['leadsCampaignsDailiesShared'] : []);
        $leadsCampaignsWeeklyShared = (isset($leadsCampaignsCapsShared['leadsCampaignsWeeklyShared']) ? $leadsCampaignsCapsShared['leadsCampaignsWeeklyShared'] : []);
        $leadsCampaignsMonthlyShared = (isset($leadsCampaignsCapsShared['leadsCampaignsMonthlyShared']) ? $leadsCampaignsCapsShared['leadsCampaignsMonthlyShared'] : []);

        foreach ($lastCampaignArray as $campaign) {
            $completeStatus = 1;

            $campaign_id_curent = $campaign->campaign_id;
            $period_campaign_count_lead_id = $campaign->period_campaign_count_lead_id;
            $numberOfLeadCampaign = filter_var($campaign->campaign_count_lead, FILTER_SANITIZE_NUMBER_INT);
            $budget = filter_var($campaign->campaign_budget, FILTER_SANITIZE_NUMBER_INT);
            $budget_campaign_count_lead_id = $campaign->period_campaign_budget_id;
            $payment_type_method_status = $campaign->payment_type_method_status;
            $payment_type_method_limit = filter_var($campaign->payment_type_method_limit, FILTER_SANITIZE_NUMBER_INT);
            $payment_type_method_id = $campaign->payment_type_method_id;
            $totalAmmountUser_value = (!empty($campaign->total_amounts_value) ? $campaign->total_amounts_value : 0);

            if (!array_key_exists($campaign_id_curent, $leadsCampaignsDailiesShared)) {
                $leadsCampaignsDailiesSumbid = 0;
                $leadsCampaignsDailiesTotallead = 0;
            }
            else {
                $leadsCampaignsDailiesSumbid = $leadsCampaignsDailiesShared[$campaign_id_curent]['sumbid'];
                $leadsCampaignsDailiesTotallead = $leadsCampaignsDailiesShared[$campaign_id_curent]['totallead'];
            }

            if (!array_key_exists($campaign_id_curent, $leadsCampaignsWeeklyShared)) {
                $leadsCampaignsWeeklySumbid = 0;
                $leadsCampaignsWeeklyTotallead = 0;
            }
            else {
                $leadsCampaignsWeeklySumbid = $leadsCampaignsWeeklyShared[$campaign_id_curent]['sumbid'];
                $leadsCampaignsWeeklyTotallead = $leadsCampaignsWeeklyShared[$campaign_id_curent]['totallead'];
            }

            if (!array_key_exists($campaign_id_curent, $leadsCampaignsMonthlyShared)) {
                $leadsCampaignsMonthlySumbid = 0;
                $leadsCampaignsMonthlyTotallead = 0;
            }
            else {
                $leadsCampaignsMonthlySumbid = $leadsCampaignsMonthlyShared[$campaign_id_curent]['sumbid'];
                $leadsCampaignsMonthlyTotallead = $leadsCampaignsMonthlyShared[$campaign_id_curent]['totallead'];
            }

            if(in_array($campaign->campaign_Type, [2, 3])) {
                $budget_bid = 1;
            }
            else {
                $budget_bid = $campaign->campaign_budget_bid_shared;
            }

            if ($campaign->campaign_Type == 2 || $campaign->campaign_Type == 5) {
                if ($campaign->campaign_time_delivery_status != 1) {
                    date_default_timezone_set('UTC');
                    $timezone = $campaign->campaign_time_delivery_timezone;
                    if ($timezone == 5) {
                        date_default_timezone_set('America/New_York');
                    } else if ($timezone == 6) {
                        date_default_timezone_set('America/Chicago');
                    } else if ($timezone == 7) {
                        date_default_timezone_set('America/denver');
                    } else {
                        date_default_timezone_set('America/Los_Angeles');
                    }
                    $todayDay = date('D', strtotime(date('Y-m-d H:i:s')));
                    $todayHour = date('H:i:s', strtotime(date('Y-m-d H:i:s')));

                    if ($todayDay == 'Sun') {
                        if ($campaign->status_sun != 1) {
                            if (date('H:i:s', strtotime($campaign->start_sun)) != date('H:i:s', strtotime($campaign->end_sun))) {
                                if ($todayHour < date('H:i:s', strtotime($campaign->start_sun)) || $todayHour > date('H:i:s', strtotime($campaign->end_sun))) {
                                    $completeStatus = 0;
                                }
                            }
                        } else {
                            $completeStatus = 0;
                        }
                    } else if ($todayDay == 'Mon') {
                        if ($campaign->status_mon != 1) {
                            if (date('H:i:s', strtotime($campaign->start_mon)) != date('H:i:s', strtotime($campaign->end_mon))) {
                                if ($todayHour < date('H:i:s', strtotime($campaign->start_mon)) || $todayHour > date('H:i:s', strtotime($campaign->end_mon))) {
                                    $completeStatus = 0;
                                }
                            }
                        } else {
                            $completeStatus = 0;
                        }
                    } else if ($todayDay == 'Tue') {
                        if ($campaign->status_tus != 1) {
                            if (date('H:i:s', strtotime($campaign->start_tus)) != date('H:i:s', strtotime($campaign->end_tus))) {
                                if ($todayHour < date('H:i:s', strtotime($campaign->start_tus)) || $todayHour > date('H:i:s', strtotime($campaign->end_tus))) {
                                    $completeStatus = 0;
                                }
                            }
                        } else {
                            $completeStatus = 0;
                        }
                    } else if ($todayDay == 'Wed') {
                        if ($campaign->status_wen != 1) {
                            if (date('H:i:s', strtotime($campaign->start_wen)) != date('H:i:s', strtotime($campaign->end_wen))) {
                                if ($todayHour < date('H:i:s', strtotime($campaign->start_wen)) || $todayHour > date('H:i:s', strtotime($campaign->end_wen))) {
                                    $completeStatus = 0;
                                }
                            }
                        } else {
                            $completeStatus = 0;
                        }
                    } else if ($todayDay == 'Thu') {
                        if ($campaign->status_thr != 1) {
                            if (date('H:i:s', strtotime($campaign->start_thr)) != date('H:i:s', strtotime($campaign->end_thr))) {
                                if ($todayHour < date('H:i:s', strtotime($campaign->start_thr)) || $todayHour > date('H:i:s', strtotime($campaign->end_thr))) {
                                    $completeStatus = 0;
                                }
                            }
                        } else {
                            $completeStatus = 0;
                        }
                    } else if ($todayDay == 'Fri') {
                        if ($campaign->status_fri != 1) {
                            if (date('H:i:s', strtotime($campaign->start_fri)) != date('H:i:s', strtotime($campaign->end_fri))) {
                                if ($todayHour < date('H:i:s', strtotime($campaign->start_fri)) || $todayHour > date('H:i:s', strtotime($campaign->end_fri))) {
                                    $completeStatus = 0;
                                }
                            }
                        } else {
                            $completeStatus = 0;
                        }
                    } else if ($todayDay == 'Sat') {
                        if ($campaign->status_sat != 1) {
                            if (date('H:i:s', strtotime($campaign->start_sat)) != date('H:i:s', strtotime($campaign->end_sat))) {
                                if ($todayHour < date('H:i:s', strtotime($campaign->start_sat)) || $todayHour > date('H:i:s', strtotime($campaign->end_sat))) {
                                    $completeStatus = 0;
                                }
                            }
                        } else {
                            $completeStatus = 0;
                        }
                    }
                    date_default_timezone_set("America/Los_Angeles");
                }
            }

            if ($completeStatus == 1) {
                $data_is_identical = 0;
                if ($payment_type_method_status == 1 && in_array($payment_type_method_id, ['3', '4', '5', '6', '7', '8'])) {
                    if (abs($totalAmmountUser_value - $budget_bid) <= $payment_type_method_limit) {
                        $data_is_identical = 1;
                    }
                }
                else {
                    if ($totalAmmountUser_value >= $budget_bid && $totalAmmountUser_value > 0 && $budget_bid > 0) {
                        $data_is_identical = 1;
                    }
                }

                if ($data_is_identical == 1) {
                    if ($period_campaign_count_lead_id == $budget_campaign_count_lead_id) {
                        if ($period_campaign_count_lead_id == 1) {
                            if ($numberOfLeadCampaign > $leadsCampaignsDailiesTotallead && $leadsCampaignsDailiesSumbid < $budget) {
                                $listOFCampaignDB_array_shared[] = $campaign;
                            }
                        } else if ($period_campaign_count_lead_id == 2) {
                            if ($numberOfLeadCampaign > $leadsCampaignsWeeklyTotallead && $leadsCampaignsWeeklySumbid < $budget) {
                                $listOFCampaignDB_array_shared[] = $campaign;
                            }
                        } else {
                            if ($numberOfLeadCampaign > $leadsCampaignsMonthlyTotallead && $leadsCampaignsMonthlySumbid < $budget) {
                                $listOFCampaignDB_array_shared[] = $campaign;
                            }
                        }
                    } else {
                        if ($period_campaign_count_lead_id == 1) {
                            if ($budget_campaign_count_lead_id == 2) {
                                if ($numberOfLeadCampaign > $leadsCampaignsDailiesTotallead && $leadsCampaignsWeeklySumbid < $budget) {
                                    $listOFCampaignDB_array_shared[] = $campaign;
                                }
                            } else {
                                if ($numberOfLeadCampaign > $leadsCampaignsDailiesTotallead && $leadsCampaignsMonthlySumbid < $budget) {
                                    $listOFCampaignDB_array_shared[] = $campaign;
                                }
                            }
                        } else if ($period_campaign_count_lead_id == 2) {
                            if ($budget_campaign_count_lead_id == 1) {
                                if ($numberOfLeadCampaign > $leadsCampaignsWeeklyTotallead && $leadsCampaignsDailiesSumbid < $budget) {
                                    $listOFCampaignDB_array_shared[] = $campaign;
                                }
                            } else {
                                if ($numberOfLeadCampaign > $leadsCampaignsWeeklyTotallead && $leadsCampaignsMonthlySumbid < $budget) {
                                    $listOFCampaignDB_array_shared[] = $campaign;
                                }
                            }
                        } else {
                            if ($budget_campaign_count_lead_id == 1) {
                                if ($numberOfLeadCampaign > $leadsCampaignsMonthlyTotallead && $leadsCampaignsDailiesSumbid < $budget) {
                                    $listOFCampaignDB_array_shared[] = $campaign;
                                }
                            } else {
                                if ($numberOfLeadCampaign > $leadsCampaignsMonthlyTotallead && $leadsCampaignsWeeklySumbid < $budget) {
                                    $listOFCampaignDB_array_shared[] = $campaign;
                                }
                            }
                        }
                    }
                }
            }
        }

        if (!empty($listOFCampaignDB_array_shared)) {
            foreach ($listOFCampaignDB_array_shared as $DB_array_shared) {
                if ($DB_array_shared->campaign_Type == 3) {
                    $data_pay_per_click[] = array(
                        'campaign_id' => $DB_array_shared->campaign_id,
                        'click_url' => $DB_array_shared->click_url,
                        'click_text' => $DB_array_shared->click_text,
                    );
                } else if ($DB_array_shared->campaign_Type == 5) {
                    $data_pay_per_appointment[] = array(
                        'campaign_id' => $DB_array_shared->campaign_id,
                        'click_text' => $DB_array_shared->click_text,
                    );
                } else {
                    if (empty($Check_User)) {
                        $data_pay_per_call = array(
                            'campaign_id' => $DB_array_shared->campaign_id,
                            'phone' => $DB_array_shared->phone1
                        );
                    } else {
                        //to Check if lead paid to this campaign before on this day ==============
                        $curentDate = date('Y-m-d');
                        $Check_Campaign = DB::table('campaigns_leads_users')
                            ->where('campaign_id', $DB_array_shared->campaign_id)
                            ->whereIn('lead_id', $Check_User)
                            ->where('date', $curentDate)
                            ->orderBy('date', 'DESC')
                            ->first('campaign_id');

                        if (empty($Check_Campaign)) {
                            $data_pay_per_call = array(
                                'campaign_id' => $DB_array_shared->campaign_id,
                                'phone' => $DB_array_shared->phone1
                            );
                        }
                        //to Check if lead paid to this campaign before on this day ==============
                    }
                }
            }
        }

        $array_final = array(
            'pay_per_click' => $data_pay_per_click ,
            'pay_per_Appointment' => $data_pay_per_appointment,
            'pay_per_call' => $data_pay_per_call
        );
        ///// end check //////
        return $array_final;
    }

    public function PayPerCallApiPay(Request $request)
    {
        $CampaignDB = $request['campaignId'];
        $universal_leadid = $request['universal_leadid'];
        $campaign_type = $request['campaign_type'];
        $serviceId = $request['serviceId'];

        if($campaign_type == 3) {
            $curentDate = date('Y-m-d');
            $Check_User = DB::table('leads_customers')
                ->where('universal_leadid', $universal_leadid)
                ->where('lead_type_service_id', $serviceId)
                ->pluck('lead_id')->toArray();

            $Check_Campaign = DB::table('campaigns_leads_users')
                ->where('campaign_id', $CampaignDB)
                ->whereIn('lead_id', $Check_User)
                ->where('date', $curentDate)
                ->orderBy('date', 'DESC')
                ->first('campaign_id');

            if(!empty($Check_Campaign)){
                return "false";
            }
        }

        //For select duration_time =========================================================================
        $duration_time = 0;
//        if(!empty($leadPhone)) {
//            $ConnectLeadPhone = ConnectLeadTime::where('lead_phone', "1".$leadPhone)->orderBy('created_at', 'DESC')->first(['duration_time']);
//            if(empty($ConnectLeadPhone)) {
//                sleep(3);
//                $ConnectLeadPhone = ConnectLeadTime::where('lead_phone', "1".$leadPhone)->orderBy('created_at', 'DESC')->first(['duration_time']);
//            }
//            $duration_time = $ConnectLeadPhone->duration_time ;
//        } else {
//            return false;
//        }
        //For select duration_time =========================================================================

        $campaign = Campaign::join('users', 'users.id', '=', 'campaigns.user_id')
            ->leftJoin('total_amounts', 'users.id', '=', 'total_amounts.user_id')
            ->where('campaigns.campaign_id', $CampaignDB)
            ->first([
                'campaigns.*', 'total_amounts.total_amounts_value','users.*'
            ]);

        $leadCustomer_info = LeadsCustomer::where('universal_leadid', $universal_leadid)
            ->where('lead_type_service_id', $serviceId)
            ->orderBy('created_at', 'DESC')
            ->first(["lead_id"]);
        if (empty($leadCustomer_info)) {
            return "false1";
        }
        $leadCustomer_id = $leadCustomer_info->lead_id;

        //Bayment And Send Msg/email/crm
        $ApiMain = new ApiMain();
        $curentDate = date('Y-m-d');
        if (!empty($campaign)) {
            $user_id = $campaign->user_id;
            $buyersEmail = $campaign->email;
            $buyersusername = $campaign->username;

            if($campaign->campaign_Type == 2){
                $budget_bid = 0 ;
            } else {
                $budget_bid = $campaign->campaign_budget_bid_shared;
            }

//            if($duration_time > 30 && $duration_time <= 60) {
//                $budget_bid = 30 ;
//            } else if($duration_time > 60) {
//                $budget_bid = 50 ;
//            }

            $payment_type_method_status = $campaign->payment_type_method_status;
            $payment_type_method_id = $campaign->payment_type_method_id;
            $payment_type_method_limit = filter_var($campaign->payment_type_method_limit, FILTER_SANITIZE_NUMBER_INT);

            //=========================Payment Here==========================
            $totalAmmountUser_value = ( !empty($campaign->total_amounts_value) ? $campaign->total_amounts_value : 0 );
            if (($totalAmmountUser_value >= $budget_bid && $totalAmmountUser_value > 0)
                || ($payment_type_method_status == 1 && in_array($payment_type_method_id, ['3', '4', '5', '6', '7', '8']))) {
                //Payment user
                $ApiMain->payment_lead($user_id, $budget_bid);
                //=========================Insert Data===========================
                $dataleads = array(
                    'user_id' => $user_id,
                    'campaign_id' => $CampaignDB,
                    'lead_id' => $leadCustomer_id,
                    'curent_date' => $curentDate,
                    'type_bid' => "Shared",
                    'bid_budget' => $budget_bid,
                    'transactionId' => 1,
                    'is_recorded' => 0
                );

                $ApiMain->AddLeadsCampaignUser($dataleads);
            } else {
                return "false2";
            }

            // To check if User amount is low or less than $50
            $totalAmmountUser_new_list = TotalAmount::where('user_id', $user_id)->first(['total_amounts_value']);
            $totalAmmountUser_new = (!empty($totalAmmountUser_new_list) ? $totalAmmountUser_new_list->total_amounts_value : 0);

            $status = 0;
            if ($campaign->campaign_budget_bid_exclusive >= $campaign->campaign_budget_bid_shared) {
                if ($campaign->campaign_budget_bid_exclusive > $totalAmmountUser_new) {
                    $status = 1;
                }
            } else {
                if ($campaign->campaign_budget_bid_shared > $totalAmmountUser_new) {
                    $status = 1;
                }
            }

            if (($payment_type_method_status == 1 && in_array($payment_type_method_id, ['3', '4', '5', '6', '7', '8']))) {
                if ($totalAmmountUser_new <= 0) {
                    if ($payment_type_method_limit - abs($totalAmmountUser_new) <= 150) {
                        $ApiMain->send_email_buyers_threshold($buyersEmail, $buyersusername, 2);
                    }
                }
            } else {
                if ($campaign['user_auto_pay_status'] == 1 && $campaign['user_auto_pay_amount'] > 0) {
                    if ($totalAmmountUser_new <= 50) {
                        $ApiMain->autopaycampaign($campaign['user_id'], $campaign['user_auto_pay_amount']);
                    }
                } else {
                    if ($status == 1) {
                        $ApiMain->send_email_buyers_threshold($buyersEmail, $buyersusername, 1);
                    }
                }
            }
        } else {
            return "false3";
        }
        //===============================================================

        $data_rsponse = array('success' => "true");
        return $data_rsponse;
        //==================================================================================================================
    }

    public function PayPerScheduleLeadApiPay(Request $request){
        $campaign_id_curent = $request['campaignId'];
        $universal_leadid = $request['universal_leadid'];
        $campaign_type = $request['campaign_type'];
        $appointment_date = $request['appointment_date'];
        $serviceId = $request['serviceId'];

        $campaign = DB::table('campaigns')
            ->join('users', 'users.id', '=', 'campaigns.user_id')
            ->join('service__campaigns', 'service__campaigns.service_campaign_id', '=', 'campaigns.service_campaign_id')
            ->join('campaign_time_delivery', 'campaign_time_delivery.campaign_id', '=', 'campaigns.campaign_id')
            ->leftJoin('total_amounts', 'users.id', '=', 'total_amounts.user_id')
            ->where('campaigns.campaign_id', $campaign_id_curent)
            ->first([
                'campaigns.*', 'service__campaigns.service_campaign_name',
                'users.username', 'users.email', 'users.user_phone_number', 'users.user_mobile_number',
                'users.payment_type_method_id', 'users.payment_type_method_limit', 'users.payment_type_method_status',
                'campaign_time_delivery.*', 'total_amounts.total_amounts_value',
                'users.user_auto_pay_status', 'users.user_auto_pay_amount'
            ]);

        $leadcustomer_info = LeadsCustomer::join('states', 'states.state_id', '=', 'leads_customers.lead_state_id')
            ->leftjoin('counties', 'counties.county_id', '=', 'leads_customers.lead_county_id')
            ->join('cities', 'cities.city_id', '=', 'leads_customers.lead_city_id')
            ->join('zip_codes_lists', 'zip_codes_lists.zip_code_list_id', '=', 'leads_customers.lead_zipcode_id')
            ->where('leads_customers.universal_leadid', $universal_leadid)
            ->where('leads_customers.lead_type_service_id', $serviceId)
            ->orderBy('leads_customers.created_at', 'DESC')
            ->first([
                'states.state_name', 'states.state_code', 'counties.county_name', 'cities.city_name', 'zip_codes_lists.zip_code_list',
                'leads_customers.*'
            ]);

        if (empty($leadcustomer_info)) {
            return "false1";
        }
        $id = $leadcustomer_info->lead_id;

        $api_validations = new APIValidations();
        $questions = $api_validations->check_questions_ids_push_array($leadcustomer_info);
        $dataMassageForBuyers = $questions['dataMassageForBuyers'];
        $Leaddatadetails = $questions['Leaddatadetails'];

        $user_id = $campaign->user_id;
        $buyersEmail = $campaign->email; //from user
        $buyersusername = $campaign->username; //from user
        $service_campaign_name = $campaign->service_campaign_name; //from servece
        $service_id = $campaign->service_campaign_id;

        $lead_source_api = "ADMS20";
        $lead_source = "SEO";
        $lead_source_id = 1;
        $marketing_platform = DB::table('marketing_platforms')->select('id','lead_source', 'name')
            ->where('name', $leadcustomer_info->lead_source_text)->first();
        if( !empty($marketing_platform) ){
            $lead_source_api = $marketing_platform->lead_source;
            $lead_source = $marketing_platform->name;
            $lead_source_id = $marketing_platform->id;
        }

        $city_arr = explode('=>', $leadcustomer_info->city_name);
        $county_arr = explode('=>', $leadcustomer_info->county_name);
        $data_msg = array(
            'leadCustomer_id' => $id,
            'name' => $buyersusername,
            'leadName' => $leadcustomer_info->lead_fname . ' ' . $leadcustomer_info->lead_lname,
            'LeadEmail' => $leadcustomer_info->lead_email,
            'LeadPhone' => $leadcustomer_info->lead_phone_number,
            'Address' =>  'Address: ' . $leadcustomer_info->lead_address . ', City: ' . $city_arr[0] . ', State: ' . $leadcustomer_info->state_name . ', Zipcode: ' . $leadcustomer_info->zip_code_list,
            'LeadService' => $service_campaign_name,
            'service_id' => $service_id,
            'data' => $dataMassageForBuyers,
            'street' => $leadcustomer_info->lead_address,
            'City' => trim($city_arr[0]),
            'State' =>  $leadcustomer_info->state_name,
            'state_code' =>  $leadcustomer_info->state_code,
            'Zipcode' =>$leadcustomer_info->zip_code_list,
            'county' => trim($county_arr[0]),
            'first_name' => $leadcustomer_info->lead_fname,
            'last_name' => $leadcustomer_info->lead_lname,
            'trusted_form' => $leadcustomer_info->trusted_form,
            'appointment_date' => $appointment_date,
            'appointment_type' => '',
            "is_lead_review" => 0,
            'UserAgent' => $leadcustomer_info->lead_aboutUserBrowser,
            'OriginalURL' => $leadcustomer_info->lead_serverDomain,
            'OriginalURL2' => "https://www.".$leadcustomer_info->lead_serverDomain,
            'SessionLength' => $leadcustomer_info->lead_timeInBrowseData,
            'IPAddress' => $leadcustomer_info->lead_ipaddress,
            'LeadId' => $leadcustomer_info->universal_leadid,
            'browser_name' => $leadcustomer_info->lead_browser_name,
            'tcpa_compliant' => $leadcustomer_info->tcpa_compliant,
            'TCPAText' => $leadcustomer_info->tcpa_consent_text,
            'is_multi_service' => $leadcustomer_info->is_multi_service,
            'is_sec_service' => $leadcustomer_info->is_sec_service,
            'lead_source' => $lead_source_api,
            'lead_source_name' => $lead_source,
            'lead_source_id' => $lead_source_id,
            'traffic_source' => $leadcustomer_info->traffic_source,
            'google_ts' => $leadcustomer_info->google_ts,
            'dataMassageForBuyers' => $dataMassageForBuyers,
            'Leaddatadetails' => $Leaddatadetails,
        );

        $main_api_file = new ApiMain();

        $listOFCampainDB_type = 'Shared';
        $budget_bid = filter_var($campaign->campaign_budget_bid_shared, FILTER_SANITIZE_NUMBER_INT);
        $payment_type_method_status = $campaign->payment_type_method_status; //from user
        $payment_type_method_id = $campaign->payment_type_method_id; //from user
        $payment_type_method_limit = filter_var($campaign->payment_type_method_limit, FILTER_SANITIZE_NUMBER_INT); //from user

        //=========================Payment Here==========================
        $totalAmmountUser_value = ( !empty($campaign->total_amounts_value) ? $campaign->total_amounts_value : 0 );

        if( ( $totalAmmountUser_value >= $budget_bid && $totalAmmountUser_value > 0 && $budget_bid > 0 )
            || ( $payment_type_method_status == 1 && in_array($payment_type_method_id, ['3','4','5','6','7','8'])
                && abs($totalAmmountUser_value - $budget_bid) <=  $payment_type_method_limit ) ){

            //Time Delivery Validation
            $completeStatus = 1;
            if ($campaign->campaign_time_delivery_status != 1) {
                date_default_timezone_set('UTC');
                $timezone = $campaign->campaign_time_delivery_timezone;
                if( $timezone == 5 ){
                    date_default_timezone_set('America/New_York');
                } else if( $timezone == 6 ){
                    date_default_timezone_set('America/Chicago');
                } else if( $timezone == 7 ){
                    date_default_timezone_set('America/denver');
                } else {
                    date_default_timezone_set('America/Los_Angeles');
                }
                $todayDay = date('D', strtotime(date('Y-m-d H:i:s')));
                $todayHour = date('H:i:s', strtotime(date('Y-m-d H:i:s')));

                if ($todayDay == 'Sun') {
                    if ($campaign->status_sun != 1) {
                        if(date('H:i:s', strtotime($campaign->start_sun)) != date('H:i:s', strtotime($campaign->end_sun))) {
                            if ($todayHour < date('H:i:s', strtotime($campaign->start_sun)) || $todayHour > date('H:i:s', strtotime($campaign->end_sun))) {
                                $completeStatus = 0;
                            }
                        }
                    } else {
                        $completeStatus = 0;
                    }
                }
                else if ($todayDay == 'Mon') {
                    if ($campaign->status_mon != 1) {
                        if(date('H:i:s', strtotime($campaign->start_mon)) != date('H:i:s', strtotime($campaign->end_mon))){
                            if ($todayHour < date('H:i:s', strtotime($campaign->start_mon)) || $todayHour > date('H:i:s', strtotime($campaign->end_mon))) {
                                $completeStatus = 0;
                            }
                        }
                    } else {
                        $completeStatus = 0;
                    }
                }
                else if ($todayDay == 'Tue') {
                    if ($campaign->status_tus != 1) {
                        if(date('H:i:s', strtotime($campaign->start_tus)) != date('H:i:s', strtotime($campaign->end_tus))) {
                            if ($todayHour < date('H:i:s', strtotime($campaign->start_tus)) || $todayHour > date('H:i:s', strtotime($campaign->end_tus))) {
                                $completeStatus = 0;
                            }
                        }
                    } else {
                        $completeStatus = 0;
                    }
                }
                else if ($todayDay == 'Wed') {
                    if ($campaign->status_wen != 1) {
                        if(date('H:i:s', strtotime($campaign->start_wen)) != date('H:i:s', strtotime($campaign->end_wen))) {
                            if ($todayHour < date('H:i:s', strtotime($campaign->start_wen)) || $todayHour > date('H:i:s', strtotime($campaign->end_wen))) {
                                $completeStatus = 0;
                            }
                        }
                    } else {
                        $completeStatus = 0;
                    }
                }
                else if ($todayDay == 'Thu') {
                    if ($campaign->status_thr != 1) {
                        if(date('H:i:s', strtotime($campaign->start_thr)) != date('H:i:s', strtotime($campaign->end_thr))) {
                            if ($todayHour < date('H:i:s', strtotime($campaign->start_thr)) || $todayHour > date('H:i:s', strtotime($campaign->end_thr))) {
                                $completeStatus = 0;
                            }
                        }
                    } else {
                        $completeStatus = 0;
                    }
                }
                else if ($todayDay == 'Fri') {
                    if ($campaign->status_fri != 1) {
                        if(date('H:i:s', strtotime($campaign->start_fri)) != date('H:i:s', strtotime($campaign->end_fri))) {
                            if ($todayHour < date('H:i:s', strtotime($campaign->start_fri)) || $todayHour > date('H:i:s', strtotime($campaign->end_fri))) {
                                $completeStatus = 0;
                            }
                        }
                    } else {
                        $completeStatus = 0;
                    }
                }
                else if ($todayDay == 'Sat') {
                    if ($campaign->status_sat != 1) {
                        if(date('H:i:s', strtotime($campaign->start_sat)) != date('H:i:s', strtotime($campaign->end_sat))) {
                            if ($todayHour < date('H:i:s', strtotime($campaign->start_sat)) || $todayHour > date('H:i:s', strtotime($campaign->end_sat))) {
                                $completeStatus = 0;
                            }
                        }
                    } else {
                        $completeStatus = 0;
                    }
                }
                date_default_timezone_set("America/Los_Angeles");
            }

            if( $completeStatus == 0 ){
                return "false2";
            }

            //Check Campaign Budget
            $period_campaign_count_lead_id = $campaign->period_campaign_count_lead_id;
            $numberOfLeadCampaign = filter_var($campaign->campaign_count_lead, FILTER_SANITIZE_NUMBER_INT);

            $budget = filter_var($campaign->campaign_budget, FILTER_SANITIZE_NUMBER_INT);
            $budget_campaign_count_lead_id = $campaign->period_campaign_budget_id;

            $completeStatus_final = 0;
            //==================================================================================================
            $leadsCampaignsDailies = DB::table('campaigns_leads_users')
                ->where('campaign_id', $campaign_id_curent)
                ->where('date', date("Y-m-d"))
                ->where('campaigns_leads_users_type_bid', $listOFCampainDB_type)
                ->get([
                    DB::raw("SUM(campaigns_leads_users.campaigns_leads_users_bid) AS sumbid"),
                    DB::raw("COUNT(campaigns_leads_users.campaigns_leads_users_id) AS totallead")
                ]);

            $leadsCampaignsWeekly = DB::table('campaigns_leads_users')
                ->where('campaign_id', $campaign_id_curent)
                ->whereBetween('date', [date('Y-m-d', strtotime(Carbon::now()->startOfWeek())),date('Y-m-d', strtotime(Carbon::now()->endOfWeek()))])
                ->where('campaigns_leads_users_type_bid', $listOFCampainDB_type)
                ->get([
                    DB::raw("SUM(campaigns_leads_users.campaigns_leads_users_bid) AS sumbid"),
                    DB::raw("COUNT(campaigns_leads_users.campaigns_leads_users_id) AS totallead")
                ]);

            $leadsCampaignsMonthly = DB::table('campaigns_leads_users')
                ->where('campaign_id', $campaign_id_curent)
                ->whereBetween('date', [date('Y-m'). '-1',date('Y-m-t')])
                ->where('campaigns_leads_users_type_bid', $listOFCampainDB_type)
                ->get([
                    DB::raw("SUM(campaigns_leads_users.campaigns_leads_users_bid) AS sumbid"),
                    DB::raw("COUNT(campaigns_leads_users.campaigns_leads_users_id) AS totallead")
                ]);

            $data_is_identical = 0;
            if( $payment_type_method_status == 1 && in_array($payment_type_method_id, ['3', '4', '5', '6', '7', '8']) ){
                if (abs($totalAmmountUser_value - $budget_bid) <= $payment_type_method_limit) {
                    $data_is_identical = 1;
                }
            } else {
                if( $totalAmmountUser_value >= $budget_bid && $totalAmmountUser_value > 0 && $budget_bid > 0 ){
                    $data_is_identical = 1;
                }
            }

            if( $data_is_identical == 1 ){
                if( $period_campaign_count_lead_id == $budget_campaign_count_lead_id ){
                    if( $period_campaign_count_lead_id == 1 ){
                        if( $numberOfLeadCampaign > $leadsCampaignsDailies[0]->totallead && $leadsCampaignsDailies[0]->sumbid < $budget ){
                            $completeStatus_final = 1;
                        }
                    } else  if( $period_campaign_count_lead_id == 2 ){
                        if( $numberOfLeadCampaign > $leadsCampaignsWeekly[0]->totallead && $leadsCampaignsWeekly[0]->sumbid < $budget ){
                            $completeStatus_final = 1;
                        }
                    } else {
                        if( $numberOfLeadCampaign > $leadsCampaignsMonthly[0]->totallead && $leadsCampaignsMonthly[0]->sumbid < $budget ){
                            $completeStatus_final = 1;
                        }
                    }
                } else {
                    if( $period_campaign_count_lead_id == 1 ){
                        if( $budget_campaign_count_lead_id == 2 ){
                            if( $numberOfLeadCampaign > $leadsCampaignsDailies[0]->totallead && $leadsCampaignsWeekly[0]->sumbid < $budget ){
                                $completeStatus_final = 1;
                            }
                        } else {
                            if( $numberOfLeadCampaign > $leadsCampaignsDailies[0]->totallead && $leadsCampaignsMonthly[0]->sumbid < $budget ){
                                $completeStatus_final = 1;
                            }
                        }
                    } else  if( $period_campaign_count_lead_id == 2 ){
                        if( $budget_campaign_count_lead_id == 1 ){
                            if( $numberOfLeadCampaign > $leadsCampaignsWeekly[0]->totallead && $leadsCampaignsDailies[0]->sumbid < $budget ){
                                $completeStatus_final = 1;
                            }
                        } else {
                            if( $numberOfLeadCampaign > $leadsCampaignsWeekly[0]->totallead && $leadsCampaignsMonthly[0]->sumbid < $budget ){
                                $completeStatus_final = 1;
                            }
                        }
                    } else {
                        if( $budget_campaign_count_lead_id == 1 ){
                            if( $numberOfLeadCampaign > $leadsCampaignsMonthly[0]->totallead && $leadsCampaignsDailies[0]->sumbid < $budget ){
                                $completeStatus_final = 1;
                            }
                        } else {
                            if( $numberOfLeadCampaign > $leadsCampaignsMonthly[0]->totallead && $leadsCampaignsWeekly[0]->sumbid < $budget ){
                                $completeStatus_final = 1;
                            }
                        }
                    }
                }
            }

            if( $completeStatus_final == 0 ){
                return "false3";
            }

            //To send CRM data
            $status_of_send_lead = $main_api_file->delivery_methods($data_msg, $campaign, $listOFCampainDB_type, 1);
            $is_ping_account = $campaign->is_ping_account;
            if( $status_of_send_lead != 0 && !empty($status_of_send_lead) ){
                $main_api_file->payment_lead($user_id, $budget_bid);
                $curentDate = date('Y-m-d');
                //=========================Insert Data===========================
                $dataleads = array(
                    'user_id' =>   $user_id,
                    'campaign_id' => $campaign_id_curent,
                    'lead_id' => $id,
                    'curent_date' => $curentDate,
                    'type_bid' => $listOFCampainDB_type,
                    'bid_budget' => $budget_bid,
                    'transactionId' => $status_of_send_lead,
                    'is_recorded' => 0,
                );
                $leadsCustomerCampaign_id = $main_api_file->AddLeadsCampaignUser($dataleads);
            } else {
                return "false4";
            }
            //==============================================================

            // To check if User amount is low or less than $50
            $totalAmmountUser_new_list = TotalAmount::where('user_id', $user_id)->first(['total_amounts_value']);
            $totalAmmountUser_new = (!empty($totalAmmountUser_new_list) ? $totalAmmountUser_new_list->total_amounts_value : 0);

            $status = 0;
            if( $is_ping_account != 1 ){
                if ($campaign->campaign_budget_bid_exclusive <= $campaign->campaign_budget_bid_shared) {
                    if ($campaign->campaign_budget_bid_exclusive > $totalAmmountUser_new) {
                        $status = 1;
                    }
                } else {
                    if ($campaign->campaign_budget_bid_shared > $totalAmmountUser_new) {
                        $status = 1;
                    }
                }
            } else {
                if ($totalAmmountUser_new <= 150) {
                    $status = 1;
                }
            }

            if (($payment_type_method_status == 1 && in_array($payment_type_method_id, ['3', '4', '5', '6', '7', '8']))) {
                if ($totalAmmountUser_new <= 0) {
                    if ($payment_type_method_limit - abs($totalAmmountUser_new) <= 150) {
                        $main_api_file->send_email_buyers_threshold($buyersEmail, $buyersusername, 2);
                    }
                }
            } else {
                if ($campaign->user_auto_pay_status == 1 && $campaign->user_auto_pay_amount > 0) {
                    if ($totalAmmountUser_new <= 50) {
                        $main_api_file->autopaycampaign($campaign->user_id, $campaign->user_auto_pay_amount);
                    }
                } else {
                    if ($status == 1) {
                        $main_api_file->send_email_buyers_threshold($buyersEmail, $buyersusername, 1);
                    }
                }
            }
        } else {
            return "false5";
        }
        //===============================================================
        DB::table('leads_customers')->where('lead_id', $id)->update([
            'appointment_date'  => date('Y/m/d H:i:s', strtotime($appointment_date))
        ]);

        $data_rsponse = array('success' => "true");
        return $data_rsponse;
    }

    public function saveDurationTime(Request $request)
    {
        // To Save Duration Time with lead phone when call completed
        $ConnectLeadTime = new ConnectLeadTime();
        $ConnectLeadTime->lead_phone = $request['phone'];
        $ConnectLeadTime->duration_time = $request['duration'];
        $ConnectLeadTime->save();
        return 1 ;
    }

    public function conversionLeads($id ,$token, Request $request){
        //Get lead info from token
        $lead_info = DB::table('leads_customers')->where('visitor_leads_id', $token)->first(['lead_id']);
        if(!empty($lead_info)) {
            //Update lead to converted lead
            if(isset($request->price)){
                $price = (!empty($request->price) ? $request->price : 0);
                $lead_update = DB::table('campaigns_leads_users')
                    ->where('user_id', $id)
                    ->where('lead_id', $lead_info->lead_id)
                    ->update([
                        'campaigns_leads_users_note' => "converted",
                        'campaigns_leads_users_bid' => $price
                    ]);

                if(!empty($price)){
                    $main_api_file = new ApiMain();
                    $main_api_file->payment_lead($id, $price);
                }
            } else {
                $lead_update = DB::table('campaigns_leads_users')
                    ->where('user_id', $id)
                    ->where('lead_id', $lead_info->lead_id)
                    ->update(['campaigns_leads_users_note' => "converted"]);
            }

            if($lead_update == true){
                return "Ok";
            }
        }
        return "Invalid Pixel";
    }
}
