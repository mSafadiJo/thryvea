<?php

namespace App\Services;

use App\AcculynxCrm;
use App\CallTools;
use App\CampaignsLeadsUsers;
use App\Five9;
use App\Http\Controllers\AdminPayments\NMIPaymentController;
use App\Http\Controllers\AdminPayments\PaymentAuthController;
use App\Http\Controllers\StripePaymentController;
use App\hubspot;
use App\Improveit360Crm;
use App\Jangle;
use App\LeadConduit;
use App\leadPerfectionCrm;
use App\LeadPortal;
use App\Leads_Pedia;
use App\leads_pedia_track;
use App\LeadsCustomer;
use App\Marketsharpm;
use App\Models\Builder_Prime_CRM;
use App\Models\CampaignsLeadsUsersAff;
use App\Models\HatchCrm;
use App\Models\job_nimbus;
use App\Models\SalesforceCrm;
use App\Models\SellerTotalAmount;
use App\Models\SetShapeCrm;
use App\Models\Sunbase;
use App\Models\ZapierCrm;
use App\Payment;
use App\Pipe_Drive;
use App\Services\Allied\PingCRMAllied;
use App\Services\Allied\PostCRMAllied;
use App\Services\Zone\PingCRMZone;
use App\Services\Zone\PostCRMZone;
use App\TestLeadsCustomer;
use App\TotalAmount;
use App\ZohoCrm;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Slack;
use Twilio\Rest\Client;
use App\Http\Controllers\BandWidth\BandWidthController;

class ApiMain {

    public function filterCampaign_exclusive_sheared_new_way($listOFCampain_sharedDB, $data_msg, $count, $type, $leadsCampaignsCapsExclusive = [], $leadsCampaignsCapsShared = []){
        //Data Filter exclusive And Sheared
        $listOFCampainDB_array_shared = array();
        $budget_bid_sharedDB = 0;
        $counter_Shared = 0;
        $LostReportStep3_1 = array();
        $LostReportStep3_2 = array();
        $LostReportStep3_3 = array();
        $LostReportStep3_5 = array();

        $leadsCampaignsDailiesExclusive = (isset($leadsCampaignsCapsExclusive['leadsCampaignsDailiesExclusive']) ? $leadsCampaignsCapsExclusive['leadsCampaignsDailiesExclusive'] : []);
        $leadsCampaignsWeeklyExclusive = (isset($leadsCampaignsCapsExclusive['leadsCampaignsWeeklyExclusive']) ? $leadsCampaignsCapsExclusive['leadsCampaignsWeeklyExclusive'] : []);
        $leadsCampaignsMonthlyExclusive = (isset($leadsCampaignsCapsExclusive['leadsCampaignsMonthlyExclusive']) ? $leadsCampaignsCapsExclusive['leadsCampaignsMonthlyExclusive'] : []);

        $leadsCampaignsDailiesShared = (isset($leadsCampaignsCapsShared['leadsCampaignsDailiesShared']) ? $leadsCampaignsCapsShared['leadsCampaignsDailiesShared'] : []);
        $leadsCampaignsWeeklyShared = (isset($leadsCampaignsCapsShared['leadsCampaignsWeeklyShared']) ? $leadsCampaignsCapsShared['leadsCampaignsWeeklyShared'] : []);
        $leadsCampaignsMonthlyShared = (isset($leadsCampaignsCapsShared['leadsCampaignsMonthlyShared']) ? $leadsCampaignsCapsShared['leadsCampaignsMonthlyShared'] : []);

        if( !empty( $listOFCampain_sharedDB ) ){
            foreach( $listOFCampain_sharedDB as $campaign ) {
                if ($counter_Shared == $count) {
                    break;
                }

                $campaign_id_curent = $campaign->campaign_id;
                $typeOFBidLead = '';

                if( $type == 1 ){
                    $period_campaign_count_lead_id =  $campaign->period_campaign_count_lead_id_exclusive;
                    $numberOfLeadCampaign = filter_var($campaign->campaign_count_lead_exclusive, FILTER_SANITIZE_NUMBER_INT);

                    $budget = filter_var($campaign->campaign_budget_exclusive, FILTER_SANITIZE_NUMBER_INT);
                    $budget_campaign_count_lead_id = $campaign->period_campaign_budget_id_exclusive;

                    $budget_bid = 0;
                    if( !empty($campaign->campaign_budget_bid_exclusive) || $campaign->campaign_budget_bid_exclusive != 0 ){
                        $budget_bid = filter_var(($campaign->campaign_budget_bid_exclusive - $campaign->virtual_price), FILTER_SANITIZE_NUMBER_INT);
                    }

                    $typeOFBidLead = 'Exclusive';
                    if(!array_key_exists($campaign_id_curent, $leadsCampaignsDailiesExclusive)) {
                        $leadsCampaignsDailiesSumbid = 0;
                        $leadsCampaignsDailiesTotallead = 0;
                    } else {
                        $leadsCampaignsDailiesSumbid = $leadsCampaignsDailiesExclusive[$campaign_id_curent]['sumbid'];
                        $leadsCampaignsDailiesTotallead = $leadsCampaignsDailiesExclusive[$campaign_id_curent]['totallead'];
                    }

                    if(!array_key_exists($campaign_id_curent, $leadsCampaignsWeeklyExclusive)) {
                        $leadsCampaignsWeeklySumbid = 0;
                        $leadsCampaignsWeeklyTotallead = 0;
                    } else {
                        $leadsCampaignsWeeklySumbid = $leadsCampaignsWeeklyExclusive[$campaign_id_curent]['sumbid'];
                        $leadsCampaignsWeeklyTotallead = $leadsCampaignsWeeklyExclusive[$campaign_id_curent]['totallead'];
                    }

                    if(!array_key_exists($campaign_id_curent, $leadsCampaignsMonthlyExclusive)) {
                        $leadsCampaignsMonthlySumbid = 0 ;
                        $leadsCampaignsMonthlyTotallead = 0 ;
                    } else {
                        $leadsCampaignsMonthlySumbid = $leadsCampaignsMonthlyExclusive[$campaign_id_curent]['sumbid'];
                        $leadsCampaignsMonthlyTotallead = $leadsCampaignsMonthlyExclusive[$campaign_id_curent]['totallead'];
                    }
                } else if( $type == 2 ) {
                    $period_campaign_count_lead_id = $campaign->period_campaign_count_lead_id;
                    $numberOfLeadCampaign = filter_var($campaign->campaign_count_lead, FILTER_SANITIZE_NUMBER_INT);

                    $budget = filter_var($campaign->campaign_budget, FILTER_SANITIZE_NUMBER_INT);
                    $budget_campaign_count_lead_id = $campaign->period_campaign_budget_id;

                    $budget_bid = 0;
                    if (!empty($campaign->campaign_budget_bid_shared) || $campaign->campaign_budget_bid_shared != 0) {
                        $budget_bid = filter_var(($campaign->campaign_budget_bid_shared - $campaign->virtual_price), FILTER_SANITIZE_NUMBER_INT);
                    }

                    $typeOFBidLead = 'Shared';

                    if(!array_key_exists($campaign_id_curent, $leadsCampaignsDailiesShared)) {
                        $leadsCampaignsDailiesSumbid = 0;
                        $leadsCampaignsDailiesTotallead = 0;
                    } else {
                        $leadsCampaignsDailiesSumbid = $leadsCampaignsDailiesShared[$campaign_id_curent]['sumbid'];
                        $leadsCampaignsDailiesTotallead = $leadsCampaignsDailiesShared[$campaign_id_curent]['totallead'];
                    }

                    if(!array_key_exists($campaign_id_curent, $leadsCampaignsWeeklyShared)) {
                        $leadsCampaignsWeeklySumbid = 0;
                        $leadsCampaignsWeeklyTotallead = 0;
                    } else {
                        $leadsCampaignsWeeklySumbid = $leadsCampaignsWeeklyShared[$campaign_id_curent]['sumbid'];
                        $leadsCampaignsWeeklyTotallead = $leadsCampaignsWeeklyShared[$campaign_id_curent]['totallead'];
                    }

                    if(!array_key_exists($campaign_id_curent, $leadsCampaignsMonthlyShared)) {
                        $leadsCampaignsMonthlySumbid = 0;
                        $leadsCampaignsMonthlyTotallead = 0;
                    } else {
                        $leadsCampaignsMonthlySumbid = $leadsCampaignsMonthlyShared[$campaign_id_curent]['sumbid'];
                        $leadsCampaignsMonthlyTotallead = $leadsCampaignsMonthlyShared[$campaign_id_curent]['totallead'];
                    }
                }

                //campaign we will search on
                $LostReportStep3_5[] = $campaign_id_curent ;

                $payment_type_method_status = $campaign->payment_type_method_status;
                $payment_type_method_limit = filter_var($campaign->payment_type_method_limit, FILTER_SANITIZE_NUMBER_INT);
                $payment_type_method_id = $campaign->payment_type_method_id;
                $totalAmmountUser_value = ( !empty($campaign->total_amounts_value) ? $campaign->total_amounts_value : 0 );

                //Check for multi Service if the campaign accept the same lead you bought for another service
                if( !empty($data_msg['is_multi_service']) ){
                    if ( $campaign->multi_service_accept == 0 && $data_msg['is_multi_service'] == 1 ) {
                        $is_get_before = CampaignsLeadsUsers::join('leads_customers', 'leads_customers.lead_id', '=', 'campaigns_leads_users.lead_id')
                            ->where('leads_customers.universal_leadid', $data_msg['LeadId'])
                            ->where('leads_customers.lead_phone_number', $data_msg['LeadPhone'])
                            ->where('leads_customers.lead_type_service_id', '<>', $data_msg['service_id'])
                            ->where('leads_customers.is_multi_service', 1)
                            ->where('leads_customers.created_at', '>=', date('Y-m-d') . ' 00:00:00')
                            ->where('leads_customers.created_at', '<=', date('Y-m-d') . ' 23:59:59')
                            ->where('campaigns_leads_users.user_id', $campaign->user_id)
                            ->first();

                        if (!empty($is_get_before)) {
                            $LostReportStep3_1[] = $campaign_id_curent; //non multi Service out
                            continue;
                        }
                    }
                }

                //Check if the seller allowed to bought this campaign this case when we are bay leaves from sellers
//                if( $seller_id != 0 ){
//                    if( !empty($campaign->exclude_include_type)){
//                        $data_seller_id = array();
//                        if(!empty($campaign->exclude_include_campaigns)){
//                            $data_seller_id = json_decode($campaign->exclude_include_campaigns,true);
//                        }
//                        if( $campaign->exclude_include_type == 'Exclude' ){
//                            if(in_array($seller_id, $data_seller_id)){
//                                $LostReportStep3_3[] = $campaign_id_curent; //cap out
//                                continue;
//                            }
//                        } else {
//                            if(!in_array($seller_id, $data_seller_id)){
//                                $LostReportStep3_3[] = $campaign_id_curent; //cap out
//                                continue;
//                            }
//                        }
//                    }
//                }
                //======================================================================================================

                //To check time delivery
                $completeStatus = 1;
                if ($campaign->campaign_Type == 1) {
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
                }

                if( $completeStatus == 0 ){
                    $LostReportStep3_2[]=$campaign_id_curent; //time delivery
                    continue;
                }

                //==================================================================================================

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

                if( $data_is_identical == 1 ) {
                    /////////////////////////////////////////////////////////////////////////////////////////////////////
                    //To Check Source Percentage for the lead =====================================================
                    //Get Percentage For this Campaign & Source
                    if (!empty($campaign->percentage_value)) {
                        $percentage_value_array = json_decode($campaign->percentage_value,true);

                        if(array_key_exists($data_msg['lead_source_id'] , $percentage_value_array)) {
                            $percentage_value =  $percentage_value_array[$data_msg['lead_source_id']] / 100 ;
                        } else {
                            $percentage_value = 1;
                        }

                        $numberOfLeadWithSourse = DB::table('campaigns_leads_users')
                            ->join('leads_customers', 'leads_customers.lead_id', '=', 'campaigns_leads_users.lead_id')
                            ->where('campaigns_leads_users.campaign_id', $campaign_id_curent)
                            ->where('campaigns_leads_users.campaigns_leads_users_type_bid', $typeOFBidLead)
                            ->where('leads_customers.lead_source', $data_msg['lead_source_id']);

                        if ($period_campaign_count_lead_id == 1) {
                            $numberOfLeadWithSourse->where('campaigns_leads_users.date', date("Y-m-d"));
                        } else if ($period_campaign_count_lead_id == 2) {
                            $numberOfLeadWithSourse->whereBetween('campaigns_leads_users.date', [date('Y-m-d', strtotime(Carbon::now()->startOfWeek())), date('Y-m-d', strtotime(Carbon::now()->endOfWeek()))]);
                        } else {
                            $numberOfLeadWithSourse->whereBetween('campaigns_leads_users.date', [date('Y-m') . '-1', date('Y-m-t')]);
                        }

                        $numberOfLeadWithSourse = $numberOfLeadWithSourse->count();

                        if ( (ceil($numberOfLeadCampaign * $percentage_value) < ($numberOfLeadWithSourse + 1) && $numberOfLeadWithSourse != 0) || $percentage_value == 0 ) {
                            $data_is_identical = 0;
                        }
                    }
                    /////////////////////////////////////////////////////////////////////////////////////////////////////
                }

                $cap_true = 1;
                if( $data_is_identical == 1 ){
                    if( $period_campaign_count_lead_id == $budget_campaign_count_lead_id ){
                        if( $period_campaign_count_lead_id == 1 ){
                            if( $numberOfLeadCampaign > $leadsCampaignsDailiesTotallead && $leadsCampaignsDailiesSumbid < $budget ){
                                $budget_bid_sharedDB += $budget_bid;
                                $listOFCampainDB_array_shared[] = $campaign;
                                $counter_Shared++;
                            } else {
                                $cap_true = 0;
                            }
                        }

                        else  if( $period_campaign_count_lead_id == 2 ){
                            if( $numberOfLeadCampaign > $leadsCampaignsWeeklyTotallead && $leadsCampaignsWeeklySumbid < $budget ){
                                $budget_bid_sharedDB += $budget_bid;
                                $listOFCampainDB_array_shared[] = $campaign;
                                $counter_Shared++;
                            } else {
                                $cap_true = 0;
                            }
                        }
                        else {
                            if( $numberOfLeadCampaign > $leadsCampaignsMonthlyTotallead && $leadsCampaignsMonthlySumbid < $budget ){
                                $budget_bid_sharedDB += $budget_bid;
                                $listOFCampainDB_array_shared[] = $campaign;
                                $counter_Shared++;
                            } else {
                                $cap_true = 0;
                            }
                        }
                    } else {
                        if( $period_campaign_count_lead_id == 1 ){
                            if( $budget_campaign_count_lead_id == 2 ){
                                if( $numberOfLeadCampaign > $leadsCampaignsDailiesTotallead && $leadsCampaignsWeeklySumbid < $budget ){
                                    $budget_bid_sharedDB += $budget_bid;
                                    $listOFCampainDB_array_shared[] = $campaign;
                                    $counter_Shared++;
                                } else {
                                    $cap_true = 0;
                                }
                            } else {
                                if( $numberOfLeadCampaign > $leadsCampaignsDailiesTotallead && $leadsCampaignsMonthlySumbid < $budget ){
                                    $budget_bid_sharedDB += $budget_bid;
                                    $listOFCampainDB_array_shared[] = $campaign;
                                    $counter_Shared++;
                                } else {
                                    $cap_true = 0;
                                }
                            }
                        } else  if( $period_campaign_count_lead_id == 2 ){
                            if( $budget_campaign_count_lead_id == 1 ){
                                if( $numberOfLeadCampaign > $leadsCampaignsWeeklyTotallead && $leadsCampaignsDailiesSumbid < $budget ){
                                    $budget_bid_sharedDB += $budget_bid;
                                    $listOFCampainDB_array_shared[] = $campaign;
                                    $counter_Shared++;
                                } else {
                                    $cap_true = 0;
                                }
                            } else {
                                if( $numberOfLeadCampaign > $leadsCampaignsWeeklyTotallead && $leadsCampaignsMonthlySumbid < $budget ){
                                    $budget_bid_sharedDB += $budget_bid;
                                    $listOFCampainDB_array_shared[] = $campaign;
                                    $counter_Shared++;
                                } else {
                                    $cap_true = 0;
                                }
                            }
                        } else {
                            if( $budget_campaign_count_lead_id == 1 ){
                                if( $numberOfLeadCampaign > $leadsCampaignsMonthlyTotallead && $leadsCampaignsDailiesSumbid < $budget ){
                                    $budget_bid_sharedDB += $budget_bid;
                                    $listOFCampainDB_array_shared[] = $campaign;
                                    $counter_Shared++;
                                } else {
                                    $cap_true = 0;
                                }
                            } else {
                                if( $numberOfLeadCampaign > $leadsCampaignsMonthlyTotallead && $leadsCampaignsWeeklySumbid < $budget ){
                                    $budget_bid_sharedDB += $budget_bid;
                                    $listOFCampainDB_array_shared[] = $campaign;
                                    $counter_Shared++;
                                } else {
                                    $cap_true = 0;
                                }
                            }
                        }
                    }
                } else {
                    $cap_true = 0;
                }

                if( $cap_true == 0 ){
                    $LostReportStep3_3[]=$campaign_id_curent; //cap out
                }
            }
        }

        $response_arr = array(
            'campaigns' => $listOFCampainDB_array_shared,
            'budget_bid' => $budget_bid_sharedDB,
            'LostReportStep3_1' => $LostReportStep3_1,
            'LostReportStep3_2' => $LostReportStep3_2,
            'LostReportStep3_3' => $LostReportStep3_3,
            'LostReportStep3_5' =>$LostReportStep3_5
        );

        return $response_arr;
    }

    public function filterCampaign_exclusive_sheared($listOFCampain_sharedDB, $data_msg, $count, $type, $seller_id = 0){
        //Data Filter exclusive And Sheared
        $listOFCampainDB_array_shared = array();
        $budget_bid_sharedDB = 0;
        $counter_Shared = 0;
        $LostReportStep3_1 = array();
        $LostReportStep3_2 = array();
        $LostReportStep3_3 = array();
        $LostReportStep3_5 = array();

        if( !empty( $listOFCampain_sharedDB ) ){
            foreach( $listOFCampain_sharedDB as $campaign ) {
                if ($counter_Shared == $count) {
                    break;
                }

                $typeOFBidLead = '';
                if( $type == 1 ){
                    $period_campaign_count_lead_id =  $campaign->period_campaign_count_lead_id_exclusive;
                    $numberOfLeadCampaign = filter_var($campaign->campaign_count_lead_exclusive, FILTER_SANITIZE_NUMBER_INT);

                    $budget = filter_var($campaign->campaign_budget_exclusive, FILTER_SANITIZE_NUMBER_INT);
                    $budget_campaign_count_lead_id = $campaign->period_campaign_budget_id_exclusive;

                    $budget_bid = 0;
                    if( !empty($campaign->campaign_budget_bid_exclusive) || $campaign->campaign_budget_bid_exclusive != 0 ){
                        $budget_bid = filter_var(($campaign->campaign_budget_bid_exclusive - $campaign->virtual_price), FILTER_SANITIZE_NUMBER_INT);
                    }

                    $typeOFBidLead = 'Exclusive';
                }
                else if( $type == 2 ) {
                    $period_campaign_count_lead_id = $campaign->period_campaign_count_lead_id;
                    $numberOfLeadCampaign = filter_var($campaign->campaign_count_lead, FILTER_SANITIZE_NUMBER_INT);

                    $budget = filter_var($campaign->campaign_budget, FILTER_SANITIZE_NUMBER_INT);
                    $budget_campaign_count_lead_id = $campaign->period_campaign_budget_id;

                    $budget_bid = 0;
                    if (!empty($campaign->campaign_budget_bid_shared) || $campaign->campaign_budget_bid_shared != 0) {
                        $budget_bid = filter_var(($campaign->campaign_budget_bid_shared - $campaign->virtual_price), FILTER_SANITIZE_NUMBER_INT);
                    }

                    $typeOFBidLead = 'Shared';
                }

                $campaign_id_curent = $campaign->campaign_id;

                //campaign we will search on
                $LostReportStep3_5[] = $campaign_id_curent ;

                $payment_type_method_status = $campaign->payment_type_method_status;
                $payment_type_method_limit = filter_var($campaign->payment_type_method_limit, FILTER_SANITIZE_NUMBER_INT);
                $payment_type_method_id = $campaign->payment_type_method_id;
                $totalAmmountUser_value = ( !empty($campaign->total_amounts_value) ? $campaign->total_amounts_value : 0 );

                if( $budget_bid == 0 ){
                    $LostReportStep3_3[]=$campaign_id_curent; //cap out
                    continue;
                }

                //Check for multi Service if the campaign accept the same lead you bought for another service
                if( !empty($data_msg['is_multi_service']) ){
                    if ( $campaign->multi_service_accept == 0 && $data_msg['is_multi_service'] == 1 ) {
                        $is_get_before = CampaignsLeadsUsers::join('leads_customers', 'leads_customers.lead_id', '=', 'campaigns_leads_users.lead_id')
                            ->where('leads_customers.universal_leadid', $data_msg['LeadId'])
                            ->where('leads_customers.lead_phone_number', $data_msg['LeadPhone'])
                            ->where('leads_customers.lead_type_service_id', '<>', $data_msg['service_id'])
                            ->where('leads_customers.is_multi_service', 1)
                            ->where('leads_customers.created_at', '>=', date('Y-m-d') . ' 00:00:00')
                            ->where('leads_customers.created_at', '<=', date('Y-m-d') . ' 23:59:59')
                            ->where('campaigns_leads_users.user_id', $campaign->user_id)
                            ->first();

                        if (!empty($is_get_before)) {
                            $LostReportStep3_1[] = $campaign_id_curent; //non multi Service out
                            continue;
                        }
                    }
                }

                //Check if the seller allowed to bought this campaign this case when we are bay leaves from sellers
                if( $seller_id != 0 ){
                    $exclude_sellers_campaigns_exist = DB::table('exclude_sellers_campaigns')
                        ->where('campaign_id', $campaign->campaign_id)
                        ->first();

                    $exclude_sellers_campaigns = DB::table('exclude_sellers_campaigns')
                        ->where('campaign_id', $campaign->campaign_id)
                        ->where('seller_id', $seller_id)
                        ->first();

                    if(!empty($exclude_sellers_campaigns_exist)){
                        if( $exclude_sellers_campaigns_exist->types == 'Exclude' ){
                            if(!empty($exclude_sellers_campaigns)) {
                                $LostReportStep3_3[]=$campaign_id_curent; //cap out
                                continue;
                            }
                        } else {
                            if(empty($exclude_sellers_campaigns)) {
                                $LostReportStep3_3[]=$campaign_id_curent; //cap out
                                continue;
                            }
                        }
                    }
                }
                //======================================================================================================

                //To check time delivery
                $completeStatus = 1;
                if ($campaign->campaign_Type == 1) {
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
                }

                if( $completeStatus == 0 ){
                    $LostReportStep3_2[]=$campaign_id_curent; //time delivery
                    continue;
                }

                //==================================================================================================
                $leadsCampaignsDailies = DB::table('campaigns_leads_users')
                    ->where('campaign_id', $campaign_id_curent)
                    ->where('date', date("Y-m-d"))
                    ->where('campaigns_leads_users_type_bid', $typeOFBidLead)
                    ->get([
                        DB::raw("SUM(campaigns_leads_users.campaigns_leads_users_bid) AS sumbid"),
                        DB::raw("COUNT(campaigns_leads_users.campaigns_leads_users_id) AS totallead")
                    ]);

                $leadsCampaignsWeekly = DB::table('campaigns_leads_users')
                    ->where('campaign_id', $campaign_id_curent)
                    ->whereBetween('date', [date('Y-m-d', strtotime(Carbon::now()->startOfWeek())),date('Y-m-d', strtotime(Carbon::now()->endOfWeek()))])
                    ->where('campaigns_leads_users_type_bid', $typeOFBidLead)
                    ->get([
                        DB::raw("SUM(campaigns_leads_users.campaigns_leads_users_bid) AS sumbid"),
                        DB::raw("COUNT(campaigns_leads_users.campaigns_leads_users_id) AS totallead")
                    ]);

                $leadsCampaignsMonthly = DB::table('campaigns_leads_users')
                    ->where('campaign_id', $campaign_id_curent)
                    ->whereBetween('date', [date('Y-m'). '-1',date('Y-m-t')])
                    ->where('campaigns_leads_users_type_bid', $typeOFBidLead)
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

                $cap_true = 1;
                if( $data_is_identical == 1 ) {
                    /////////////////////////////////////////////////////////////////////////////////////////////////////
                    //To Check Source Percentage for the lead =====================================================
                    //Get Percentage For this Campaign & Source
                    $percentage_data = DB::table('source_percentage')->where('campaign_id', $campaign_id_curent)->first();

                    if (!empty($percentage_data)) {
                        $percentage_value = DB::table('source_percentage')->where('campaign_id', $campaign_id_curent)
                            ->where('source_id', $data_msg['lead_source_id'])->first(['percentage_value']);

                        $percentage_value = ( !empty($percentage_value) ? $percentage_value->percentage_value / 100 : 1 );

                        $numberOfLeadWithSourse = DB::table('campaigns_leads_users')
                            ->join('leads_customers', 'leads_customers.lead_id', '=', 'campaigns_leads_users.lead_id')
                            ->where('campaigns_leads_users.campaign_id', $campaign_id_curent)
                            ->where('campaigns_leads_users.campaigns_leads_users_type_bid', $typeOFBidLead)
                            ->where('leads_customers.lead_source', $data_msg['lead_source_id']);

                        if ($period_campaign_count_lead_id == 1) {
                            $numberOfLeadWithSourse->where('campaigns_leads_users.date', date("Y-m-d"));
                        } else if ($period_campaign_count_lead_id == 2) {
                            $numberOfLeadWithSourse->whereBetween('campaigns_leads_users.date', [date('Y-m-d', strtotime(Carbon::now()->startOfWeek())), date('Y-m-d', strtotime(Carbon::now()->endOfWeek()))]);
                        } else {
                            $numberOfLeadWithSourse->whereBetween('campaigns_leads_users.date', [date('Y-m') . '-1', date('Y-m-t')]);
                        }

                        $numberOfLeadWithSourse = $numberOfLeadWithSourse->count();

                        if ( (ceil($numberOfLeadCampaign * $percentage_value) < ($numberOfLeadWithSourse + 1) && $numberOfLeadWithSourse != 0) || $percentage_value == 0 ) {
                            $data_is_identical = 0;
                        }
                    }
                    /////////////////////////////////////////////////////////////////////////////////////////////////////
                } else {
                    $cap_true = 0;
                }

                if( $data_is_identical == 1 ){
                    if( $period_campaign_count_lead_id == $budget_campaign_count_lead_id ){
                        if( $period_campaign_count_lead_id == 1 ){
                            if( $numberOfLeadCampaign > $leadsCampaignsDailies[0]->totallead && $leadsCampaignsDailies[0]->sumbid < $budget ){
                                $budget_bid_sharedDB += $budget_bid;
                                $listOFCampainDB_array_shared[] = $campaign;
                                $counter_Shared++;
                            } else {
                                $cap_true = 0;
                            }
                        } else  if( $period_campaign_count_lead_id == 2 ){
                            if( $numberOfLeadCampaign > $leadsCampaignsWeekly[0]->totallead && $leadsCampaignsWeekly[0]->sumbid < $budget ){
                                $budget_bid_sharedDB += $budget_bid;
                                $listOFCampainDB_array_shared[] = $campaign;
                                $counter_Shared++;
                            } else {
                                $cap_true = 0;
                            }
                        } else {
                            if( $numberOfLeadCampaign > $leadsCampaignsMonthly[0]->totallead && $leadsCampaignsMonthly[0]->sumbid < $budget ){
                                $budget_bid_sharedDB += $budget_bid;
                                $listOFCampainDB_array_shared[] = $campaign;
                                $counter_Shared++;
                            } else {
                                $cap_true = 0;
                            }
                        }
                    } else {
                        if( $period_campaign_count_lead_id == 1 ){
                            if( $budget_campaign_count_lead_id == 2 ){
                                if( $numberOfLeadCampaign > $leadsCampaignsDailies[0]->totallead && $leadsCampaignsWeekly[0]->sumbid < $budget ){
                                    $budget_bid_sharedDB += $budget_bid;
                                    $listOFCampainDB_array_shared[] = $campaign;
                                    $counter_Shared++;
                                } else {
                                    $cap_true = 0;
                                }
                            } else {
                                if( $numberOfLeadCampaign > $leadsCampaignsDailies[0]->totallead && $leadsCampaignsMonthly[0]->sumbid < $budget ){
                                    $budget_bid_sharedDB += $budget_bid;
                                    $listOFCampainDB_array_shared[] = $campaign;
                                    $counter_Shared++;
                                } else {
                                    $cap_true = 0;
                                }
                            }
                        } else  if( $period_campaign_count_lead_id == 2 ){
                            if( $budget_campaign_count_lead_id == 1 ){
                                if( $numberOfLeadCampaign > $leadsCampaignsWeekly[0]->totallead && $leadsCampaignsDailies[0]->sumbid < $budget ){
                                    $budget_bid_sharedDB += $budget_bid;
                                    $listOFCampainDB_array_shared[] = $campaign;
                                    $counter_Shared++;
                                } else {
                                    $cap_true = 0;
                                }
                            } else {
                                if( $numberOfLeadCampaign > $leadsCampaignsWeekly[0]->totallead && $leadsCampaignsMonthly[0]->sumbid < $budget ){
                                    $budget_bid_sharedDB += $budget_bid;
                                    $listOFCampainDB_array_shared[] = $campaign;
                                    $counter_Shared++;
                                } else {
                                    $cap_true = 0;
                                }
                            }
                        } else {
                            if( $budget_campaign_count_lead_id == 1 ){
                                if( $numberOfLeadCampaign > $leadsCampaignsMonthly[0]->totallead && $leadsCampaignsDailies[0]->sumbid < $budget ){
                                    $budget_bid_sharedDB += $budget_bid;
                                    $listOFCampainDB_array_shared[] = $campaign;
                                    $counter_Shared++;
                                } else {
                                    $cap_true = 0;
                                }
                            } else {
                                if( $numberOfLeadCampaign > $leadsCampaignsMonthly[0]->totallead && $leadsCampaignsWeekly[0]->sumbid < $budget ){
                                    $budget_bid_sharedDB += $budget_bid;
                                    $listOFCampainDB_array_shared[] = $campaign;
                                    $counter_Shared++;
                                } else {
                                    $cap_true = 0;
                                }
                            }
                        }
                    }
                } else {
                    $cap_true = 0;
                }

                if( $cap_true == 0 ){
                    $LostReportStep3_3[]=$campaign_id_curent; //cap out
                }
            }
        }

        $response_arr = array(
            'campaigns' => $listOFCampainDB_array_shared,
            'budget_bid' => $budget_bid_sharedDB,
            'LostReportStep3_1' => $LostReportStep3_1,
            'LostReportStep3_2' => $LostReportStep3_2,
            'LostReportStep3_3' => $LostReportStep3_3,
            'LostReportStep3_5' =>$LostReportStep3_5
        );

        return $response_arr;
    }

    public function filterCampaign_ping_post_new_way($listOFCampain_sharedDB, $data_msg, $type, $is_pingandpost = 0, $leadsCampaignsCapsExclusive = [], $leadsCampaignsCapsShared = [])
    {
        $LostReportStep3_1 = array();
        $LostReportStep3_2 = array();
        $LostReportStep3_3 = array();
        $LostReportStep3_4 = array();
        $LostReportStep3_5 = array();

        //Data Filter exclusive And Sheared
        $listOFCampainDB_array_shared = array();
        $ping_post_arr = array();

        $leadsCampaignsDailiesExclusive = (isset($leadsCampaignsCapsExclusive['leadsCampaignsDailiesExclusive']) ? $leadsCampaignsCapsExclusive['leadsCampaignsDailiesExclusive'] : []);
        $leadsCampaignsWeeklyExclusive = (isset($leadsCampaignsCapsExclusive['leadsCampaignsWeeklyExclusive']) ? $leadsCampaignsCapsExclusive['leadsCampaignsWeeklyExclusive'] : []);
        $leadsCampaignsMonthlyExclusive = (isset($leadsCampaignsCapsExclusive['leadsCampaignsMonthlyExclusive']) ? $leadsCampaignsCapsExclusive['leadsCampaignsMonthlyExclusive'] : []);

        $leadsCampaignsDailiesShared = (isset($leadsCampaignsCapsShared['leadsCampaignsDailiesShared']) ? $leadsCampaignsCapsShared['leadsCampaignsDailiesShared'] : []);
        $leadsCampaignsWeeklyShared = (isset($leadsCampaignsCapsShared['leadsCampaignsWeeklyShared']) ? $leadsCampaignsCapsShared['leadsCampaignsWeeklyShared'] : []);
        $leadsCampaignsMonthlyShared = (isset($leadsCampaignsCapsShared['leadsCampaignsMonthlyShared']) ? $leadsCampaignsCapsShared['leadsCampaignsMonthlyShared'] : []);

        if( !empty( $listOFCampain_sharedDB ) ){
            foreach( $listOFCampain_sharedDB as $campaign ) {
                $campaign_id_curent = $campaign->campaign_id;
                $typeOFBidLead = '';
                if( $type == 1 ){
                    $period_campaign_count_lead_id =  $campaign->period_campaign_count_lead_id_exclusive;
                    $numberOfLeadCampaign = filter_var($campaign->campaign_count_lead_exclusive, FILTER_SANITIZE_NUMBER_INT);

                    $budget = filter_var($campaign->campaign_budget_exclusive, FILTER_SANITIZE_NUMBER_INT);
                    $budget_campaign_count_lead_id = $campaign->period_campaign_budget_id_exclusive;

                    $budget_bid = 0;
                    if( !empty($campaign->campaign_budget_bid_exclusive) || $campaign->campaign_budget_bid_exclusive != 0 ){
                        $budget_bid = filter_var(($campaign->campaign_budget_bid_exclusive - $campaign->virtual_price), FILTER_SANITIZE_NUMBER_INT);
                    }

                    $typeOFBidLead = 'Exclusive';

                    if(!array_key_exists($campaign_id_curent, $leadsCampaignsDailiesExclusive)) {
                        $leadsCampaignsDailiesSumbid = 0;
                        $leadsCampaignsDailiesTotallead = 0;
                    } else {
                        $leadsCampaignsDailiesSumbid = $leadsCampaignsDailiesExclusive[$campaign_id_curent]['sumbid'];
                        $leadsCampaignsDailiesTotallead = $leadsCampaignsDailiesExclusive[$campaign_id_curent]['totallead'];
                    }

                    if(!array_key_exists($campaign_id_curent, $leadsCampaignsWeeklyExclusive)) {
                        $leadsCampaignsWeeklySumbid = 0;
                        $leadsCampaignsWeeklyTotallead = 0;
                    } else {
                        $leadsCampaignsWeeklySumbid = $leadsCampaignsWeeklyExclusive[$campaign_id_curent]['sumbid'];
                        $leadsCampaignsWeeklyTotallead = $leadsCampaignsWeeklyExclusive[$campaign_id_curent]['totallead'];
                    }

                    if(!array_key_exists($campaign_id_curent, $leadsCampaignsMonthlyExclusive)) {
                        $leadsCampaignsMonthlySumbid = 0;
                        $leadsCampaignsMonthlyTotallead = 0;
                    } else {
                        $leadsCampaignsMonthlySumbid = $leadsCampaignsMonthlyExclusive[$campaign_id_curent]['sumbid'];
                        $leadsCampaignsMonthlyTotallead = $leadsCampaignsMonthlyExclusive[$campaign_id_curent]['totallead'];
                    }
                }
                else if( $type == 2 ) {
                    $period_campaign_count_lead_id = $campaign->period_campaign_count_lead_id;
                    $numberOfLeadCampaign = filter_var($campaign->campaign_count_lead, FILTER_SANITIZE_NUMBER_INT);

                    $budget = filter_var($campaign->campaign_budget, FILTER_SANITIZE_NUMBER_INT);
                    $budget_campaign_count_lead_id = $campaign->period_campaign_budget_id;

                    $budget_bid = 0;
                    if (!empty($campaign->campaign_budget_bid_shared) || $campaign->campaign_budget_bid_shared != 0) {
                        $budget_bid = filter_var(($campaign->campaign_budget_bid_shared - $campaign->virtual_price), FILTER_SANITIZE_NUMBER_INT);
                    }

                    $typeOFBidLead = 'Shared';

                    if(!array_key_exists($campaign_id_curent, $leadsCampaignsDailiesShared)) {
                        $leadsCampaignsDailiesSumbid = 0;
                        $leadsCampaignsDailiesTotallead = 0;
                    } else {
                        $leadsCampaignsDailiesSumbid = $leadsCampaignsDailiesShared[$campaign_id_curent]['sumbid'];
                        $leadsCampaignsDailiesTotallead = $leadsCampaignsDailiesShared[$campaign_id_curent]['totallead'];
                    }

                    if(!array_key_exists($campaign_id_curent, $leadsCampaignsWeeklyShared)) {
                        $leadsCampaignsWeeklySumbid = 0;
                        $leadsCampaignsWeeklyTotallead = 0;
                    } else {
                        $leadsCampaignsWeeklySumbid = $leadsCampaignsWeeklyShared[$campaign_id_curent]['sumbid'];
                        $leadsCampaignsWeeklyTotallead = $leadsCampaignsWeeklyShared[$campaign_id_curent]['totallead'];
                    }

                    if(!array_key_exists($campaign_id_curent, $leadsCampaignsMonthlyShared)) {
                        $leadsCampaignsMonthlySumbid = 0;
                        $leadsCampaignsMonthlyTotallead = 0;
                    } else {
                        $leadsCampaignsMonthlySumbid = $leadsCampaignsMonthlyShared[$campaign_id_curent]['sumbid'];
                        $leadsCampaignsMonthlyTotallead = $leadsCampaignsMonthlyShared[$campaign_id_curent]['totallead'];
                    }
                }

                //campaign we will search on
                $LostReportStep3_5[] = $campaign_id_curent;

                $payment_type_method_status = $campaign->payment_type_method_status;
                $payment_type_method_limit = filter_var($campaign->payment_type_method_limit, FILTER_SANITIZE_NUMBER_INT);
                $payment_type_method_id = $campaign->payment_type_method_id;
                $totalAmmountUser_value = ( !empty($campaign->total_amounts_value) ? $campaign->total_amounts_value : 0 );

                //Check for multi Service if the campaign accept the same lead you bought for another service
                if( !empty($data_msg['is_multi_service']) ){
                    if ( $campaign->multi_service_accept == 0 && $data_msg['is_multi_service'] == 1 ) {
                        $is_get_before = CampaignsLeadsUsers::join('leads_customers', 'leads_customers.lead_id', '=', 'campaigns_leads_users.lead_id')
                            ->where('leads_customers.universal_leadid', $data_msg['LeadId'])
                            ->where('leads_customers.lead_phone_number', $data_msg['LeadPhone'])
                            ->where('leads_customers.lead_type_service_id', '<>', $data_msg['service_id'])
                            ->where('leads_customers.is_multi_service', 1)
                            ->where('leads_customers.created_at', '>=', date('Y-m-d') . ' 00:00:00')
                            ->where('leads_customers.created_at', '<=', date('Y-m-d') . ' 23:59:59')
                            ->where('campaigns_leads_users.user_id', $campaign->user_id)
                            ->first();

                        if (!empty($is_get_before)) {
                            $LostReportStep3_1[] = $campaign_id_curent; //non multi Service out
                            continue;
                        }
                    }
                }

                //To check time delivery
                $completeStatus = 1;
                if ($campaign->campaign_Type == 1) {
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
                }

                if( $completeStatus == 0 ){
                    $LostReportStep3_2[]=$campaign_id_curent; //time delivery
                    continue;
                }

                /////////////////////////////////////////////////////////////////////////////////////////////////////
                $data_percentage_is_true = 1;
                if( !empty($campaign->percentage_value) ){
                    $percentage_value_array = json_decode($campaign->percentage_value,true);

                    if(array_key_exists($data_msg['lead_source_id'] , $percentage_value_array)) {
                        $percentage_value =  $percentage_value_array[$data_msg['lead_source_id']] / 100 ;
                    } else {
                        $percentage_value = 1;
                    }

                    $numberOfLeadWithSourse = DB::table('campaigns_leads_users')
                        ->join('leads_customers', 'leads_customers.lead_id', '=', 'campaigns_leads_users.lead_id')
                        ->where('campaigns_leads_users.campaign_id', $campaign_id_curent)
                        ->where('campaigns_leads_users.campaigns_leads_users_type_bid', $typeOFBidLead)
                        ->where('leads_customers.lead_source', $data_msg['lead_source_id']);

                    if ($period_campaign_count_lead_id == 1) {
                        $numberOfLeadWithSourse->where('campaigns_leads_users.date', date("Y-m-d"));
                    } else if ($period_campaign_count_lead_id == 2) {
                        $numberOfLeadWithSourse->whereBetween('campaigns_leads_users.date', [date('Y-m-d', strtotime(Carbon::now()->startOfWeek())), date('Y-m-d', strtotime(Carbon::now()->endOfWeek()))]);
                    } else {
                        $numberOfLeadWithSourse->whereBetween('campaigns_leads_users.date', [date('Y-m') . '-1', date('Y-m-t')]);
                    }

                    $numberOfLeadWithSourse = $numberOfLeadWithSourse->count();

                    if ( (ceil($numberOfLeadCampaign * $percentage_value) < ($numberOfLeadWithSourse + 1) && $numberOfLeadWithSourse != 0) || $percentage_value == 0 ) {
                        $data_percentage_is_true = 0;
                    }
                }
                /////////////////////////////////////////////////////////////////////////////////////////////////////

                $completeStatus2 = 0;
                if( $data_percentage_is_true == 1 ){
                    $numberOfCamp = count($listOFCampain_sharedDB);
                    if( config('app.name', '') == "Zone1Remodeling" ){
                        $ping_and_post_class = new PingCRMZone();
                    } else {
                        $ping_and_post_class = new PingCRMAllied();
                    }

                    $ping_approved = $ping_and_post_class->pingandpost($campaign, $data_msg, $numberOfCamp, $type, $is_pingandpost);

                    $ping_approved_arr = json_decode($ping_approved, true);

                    if ($ping_approved_arr['Result'] == 1) {
                        $completeStatus3 = 1;
                        if ( $ping_approved_arr['Payout'] < 2 || is_numeric($ping_approved_arr['Payout']) != 1 ) {
                            $completeStatus3 = 0;
                        }

                        if ($completeStatus3 == 1) {
                            $completeStatus2 = 1;
                            $budget_bid = $ping_approved_arr['Payout'];
                            $ping_post_arr['campaign-'.$campaign_id_curent] = $ping_approved_arr;

                            $campaign->campaign_budget_bid_exclusive = $ping_approved_arr['Payout'];
                            $campaign->campaign_budget_bid_shared = $ping_approved_arr['Payout'];
                        } else {
                            $LostReportStep3_4[]=$campaign_id_curent; //ping reject
                        }
                    } else{
                        $LostReportStep3_4[]=$campaign_id_curent; //ping reject
                    }
                } else {
                    $LostReportStep3_3[]=$campaign_id_curent; //cap out
                }

                //To check budget of campaign
                if( $completeStatus2 == 1 ){
                    $completeStatus_final = 0;
                    //==================================================================================================
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
                                if( $numberOfLeadCampaign > $leadsCampaignsDailiesTotallead && $leadsCampaignsDailiesSumbid < $budget ){
                                    $completeStatus_final = 1;
                                }
                            } else  if( $period_campaign_count_lead_id == 2 ){
                                if( $numberOfLeadCampaign > $leadsCampaignsWeeklyTotallead && $leadsCampaignsWeeklySumbid < $budget ){
                                    $completeStatus_final = 1;
                                }
                            } else {
                                if( $numberOfLeadCampaign > $leadsCampaignsMonthlyTotallead && $leadsCampaignsMonthlySumbid < $budget ){
                                    $completeStatus_final = 1;
                                }
                            }
                        } else {
                            if( $period_campaign_count_lead_id == 1 ){
                                if( $budget_campaign_count_lead_id == 2 ){
                                    if( $numberOfLeadCampaign > $leadsCampaignsDailiesTotallead && $leadsCampaignsWeeklySumbid < $budget ){
                                        $completeStatus_final = 1;
                                    }
                                } else {
                                    if( $numberOfLeadCampaign > $leadsCampaignsDailiesTotallead && $leadsCampaignsMonthlySumbid < $budget ){
                                        $completeStatus_final = 1;
                                    }
                                }
                            } else  if( $period_campaign_count_lead_id == 2 ){
                                if( $budget_campaign_count_lead_id == 1 ){
                                    if( $numberOfLeadCampaign > $leadsCampaignsWeeklyTotallead && $leadsCampaignsDailiesSumbid < $budget ){
                                        $completeStatus_final = 1;
                                    }
                                } else {
                                    if( $numberOfLeadCampaign > $leadsCampaignsWeeklyTotallead && $leadsCampaignsMonthlySumbid < $budget ){
                                        $completeStatus_final = 1;
                                    }
                                }
                            } else {
                                if( $budget_campaign_count_lead_id == 1 ){
                                    if( $numberOfLeadCampaign > $leadsCampaignsMonthlyTotallead && $leadsCampaignsDailiesSumbid < $budget ){
                                        $completeStatus_final = 1;
                                    }
                                } else {
                                    if( $numberOfLeadCampaign > $leadsCampaignsMonthlyTotallead && $leadsCampaignsWeeklySumbid < $budget ){
                                        $completeStatus_final = 1;
                                    }
                                }
                            }
                        }
                    }

                    if( $completeStatus_final != 1 ){
                        $LostReportStep3_3[] = $campaign_id_curent; //cap out
                    } else {
                        $listOFCampainDB_array_shared[] = $campaign;
                    }
                }
            }
        }

        $response_arr = array(
            'campaigns' => $listOFCampainDB_array_shared,
            'response' => $ping_post_arr,
            'LostReportStep3_1' => $LostReportStep3_1,
            'LostReportStep3_2' => $LostReportStep3_2,
            'LostReportStep3_3' => $LostReportStep3_3,
            'LostReportStep3_4' => $LostReportStep3_4,
            'LostReportStep3_5' => $LostReportStep3_5,

        );

        return $response_arr;
    }

    public function filterCampaign_ping_post_new_way2($listOFCampain_sharedDB, $data_msg, $type, $is_pingandpost = 0, $leadsCampaignsCapsExclusive = [], $leadsCampaignsCapsShared = [])
    {
        $LostReportStep3_1 = array();
        $LostReportStep3_2 = array();
        $LostReportStep3_3 = array();
        $LostReportStep3_4 = array();
        $LostReportStep3_5 = array();

        //Data Filter exclusive And Sheared
        $listOFCampainDB_array_shared = array();
        $ping_post_arr = array();

        $leadsCampaignsDailiesExclusive = (isset($leadsCampaignsCapsExclusive['leadsCampaignsDailiesExclusive']) ? $leadsCampaignsCapsExclusive['leadsCampaignsDailiesExclusive'] : []);
        $leadsCampaignsWeeklyExclusive = (isset($leadsCampaignsCapsExclusive['leadsCampaignsWeeklyExclusive']) ? $leadsCampaignsCapsExclusive['leadsCampaignsWeeklyExclusive'] : []);
        $leadsCampaignsMonthlyExclusive = (isset($leadsCampaignsCapsExclusive['leadsCampaignsMonthlyExclusive']) ? $leadsCampaignsCapsExclusive['leadsCampaignsMonthlyExclusive'] : []);

        $leadsCampaignsDailiesShared = (isset($leadsCampaignsCapsShared['leadsCampaignsDailiesShared']) ? $leadsCampaignsCapsShared['leadsCampaignsDailiesShared'] : []);
        $leadsCampaignsWeeklyShared = (isset($leadsCampaignsCapsShared['leadsCampaignsWeeklyShared']) ? $leadsCampaignsCapsShared['leadsCampaignsWeeklyShared'] : []);
        $leadsCampaignsMonthlyShared = (isset($leadsCampaignsCapsShared['leadsCampaignsMonthlyShared']) ? $leadsCampaignsCapsShared['leadsCampaignsMonthlyShared'] : []);

        if( !empty( $listOFCampain_sharedDB ) ){
            foreach( $listOFCampain_sharedDB as $campaign ) {
                $campaign_id_curent = $campaign->campaign_id;
                $typeOFBidLead = '';
                if( $type == 1 ){
                    $period_campaign_count_lead_id =  $campaign->period_campaign_count_lead_id_exclusive;
                    $numberOfLeadCampaign = filter_var($campaign->campaign_count_lead_exclusive, FILTER_SANITIZE_NUMBER_INT);

                    $budget = filter_var($campaign->campaign_budget_exclusive, FILTER_SANITIZE_NUMBER_INT);
                    $budget_campaign_count_lead_id = $campaign->period_campaign_budget_id_exclusive;

                    $budget_bid = 0;
                    if( !empty($campaign->campaign_budget_bid_exclusive) || $campaign->campaign_budget_bid_exclusive != 0 ){
                        $budget_bid = filter_var(($campaign->campaign_budget_bid_exclusive - $campaign->virtual_price), FILTER_SANITIZE_NUMBER_INT);
                    }

                    $typeOFBidLead = 'Exclusive';

                    if(!array_key_exists($campaign_id_curent, $leadsCampaignsDailiesExclusive)) {
                        $leadsCampaignsDailiesSumbid = 0;
                        $leadsCampaignsDailiesTotallead = 0;
                    } else {
                        $leadsCampaignsDailiesSumbid = $leadsCampaignsDailiesExclusive[$campaign_id_curent]['sumbid'];
                        $leadsCampaignsDailiesTotallead = $leadsCampaignsDailiesExclusive[$campaign_id_curent]['totallead'];
                    }

                    if(!array_key_exists($campaign_id_curent, $leadsCampaignsWeeklyExclusive)) {
                        $leadsCampaignsWeeklySumbid = 0;
                        $leadsCampaignsWeeklyTotallead = 0;
                    } else {
                        $leadsCampaignsWeeklySumbid = $leadsCampaignsWeeklyExclusive[$campaign_id_curent]['sumbid'];
                        $leadsCampaignsWeeklyTotallead = $leadsCampaignsWeeklyExclusive[$campaign_id_curent]['totallead'];
                    }

                    if(!array_key_exists($campaign_id_curent, $leadsCampaignsMonthlyExclusive)) {
                        $leadsCampaignsMonthlySumbid = 0;
                        $leadsCampaignsMonthlyTotallead = 0;
                    } else {
                        $leadsCampaignsMonthlySumbid = $leadsCampaignsMonthlyExclusive[$campaign_id_curent]['sumbid'];
                        $leadsCampaignsMonthlyTotallead = $leadsCampaignsMonthlyExclusive[$campaign_id_curent]['totallead'];
                    }
                }
                else if( $type == 2 ) {
                    $period_campaign_count_lead_id = $campaign->period_campaign_count_lead_id;
                    $numberOfLeadCampaign = filter_var($campaign->campaign_count_lead, FILTER_SANITIZE_NUMBER_INT);

                    $budget = filter_var($campaign->campaign_budget, FILTER_SANITIZE_NUMBER_INT);
                    $budget_campaign_count_lead_id = $campaign->period_campaign_budget_id;

                    $budget_bid = 0;
                    if (!empty($campaign->campaign_budget_bid_shared) || $campaign->campaign_budget_bid_shared != 0) {
                        $budget_bid = filter_var(($campaign->campaign_budget_bid_shared - $campaign->virtual_price), FILTER_SANITIZE_NUMBER_INT);
                    }

                    $typeOFBidLead = 'Shared';

                    if(!array_key_exists($campaign_id_curent, $leadsCampaignsDailiesShared)) {
                        $leadsCampaignsDailiesSumbid = 0;
                        $leadsCampaignsDailiesTotallead = 0;
                    } else {
                        $leadsCampaignsDailiesSumbid = $leadsCampaignsDailiesShared[$campaign_id_curent]['sumbid'];
                        $leadsCampaignsDailiesTotallead = $leadsCampaignsDailiesShared[$campaign_id_curent]['totallead'];
                    }

                    if(!array_key_exists($campaign_id_curent, $leadsCampaignsWeeklyShared)) {
                        $leadsCampaignsWeeklySumbid = 0;
                        $leadsCampaignsWeeklyTotallead = 0;
                    } else {
                        $leadsCampaignsWeeklySumbid = $leadsCampaignsWeeklyShared[$campaign_id_curent]['sumbid'];
                        $leadsCampaignsWeeklyTotallead = $leadsCampaignsWeeklyShared[$campaign_id_curent]['totallead'];
                    }

                    if(!array_key_exists($campaign_id_curent, $leadsCampaignsMonthlyShared)) {
                        $leadsCampaignsMonthlySumbid = 0;
                        $leadsCampaignsMonthlyTotallead = 0;
                    } else {
                        $leadsCampaignsMonthlySumbid = $leadsCampaignsMonthlyShared[$campaign_id_curent]['sumbid'];
                        $leadsCampaignsMonthlyTotallead = $leadsCampaignsMonthlyShared[$campaign_id_curent]['totallead'];
                    }
                }

                //campaign we will search on
                $LostReportStep3_5[] = $campaign_id_curent;

                $payment_type_method_status = $campaign->payment_type_method_status;
                $payment_type_method_limit = filter_var($campaign->payment_type_method_limit, FILTER_SANITIZE_NUMBER_INT);
                $payment_type_method_id = $campaign->payment_type_method_id;
                $totalAmmountUser_value = ( !empty($campaign->total_amounts_value) ? $campaign->total_amounts_value : 0 );

                //Check for multi Service if the campaign accept the same lead you bought for another service
                if( !empty($data_msg['is_multi_service']) ){
                    if ( $campaign->multi_service_accept == 0 && $data_msg['is_multi_service'] == 1 ) {
                        $is_get_before = CampaignsLeadsUsers::join('leads_customers', 'leads_customers.lead_id', '=', 'campaigns_leads_users.lead_id')
                            ->where('leads_customers.universal_leadid', $data_msg['LeadId'])
                            ->where('leads_customers.lead_phone_number', $data_msg['LeadPhone'])
                            ->where('leads_customers.lead_type_service_id', '<>', $data_msg['service_id'])
                            ->where('leads_customers.is_multi_service', 1)
                            ->where('leads_customers.created_at', '>=', date('Y-m-d') . ' 00:00:00')
                            ->where('leads_customers.created_at', '<=', date('Y-m-d') . ' 23:59:59')
                            ->where('campaigns_leads_users.user_id', $campaign->user_id)
                            ->first();

                        if (!empty($is_get_before)) {
                            $LostReportStep3_1[] = $campaign_id_curent; //non multi Service out
                            continue;
                        }
                    }
                }

                //To check time delivery
                $completeStatus = 1;
                if ($campaign->campaign_Type == 1) {
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
                }

                if( $completeStatus == 0 ){
                    $LostReportStep3_2[]=$campaign_id_curent; //time delivery
                    continue;
                }

                /////////////////////////////////////////////////////////////////////////////////////////////////////
                $data_percentage_is_true = 1;
                if( !empty($campaign->percentage_value) ){
                    $percentage_value_array = json_decode($campaign->percentage_value,true);

                    if(array_key_exists($data_msg['lead_source_id'] , $percentage_value_array)) {
                        $percentage_value =  $percentage_value_array[$data_msg['lead_source_id']] / 100 ;
                    } else {
                        $percentage_value = 1;
                    }

                    $numberOfLeadWithSourse = DB::table('campaigns_leads_users')
                        ->join('leads_customers', 'leads_customers.lead_id', '=', 'campaigns_leads_users.lead_id')
                        ->where('campaigns_leads_users.campaign_id', $campaign_id_curent)
                        ->where('campaigns_leads_users.campaigns_leads_users_type_bid', $typeOFBidLead)
                        ->where('leads_customers.lead_source', $data_msg['lead_source_id']);

                    if ($period_campaign_count_lead_id == 1) {
                        $numberOfLeadWithSourse->where('campaigns_leads_users.date', date("Y-m-d"));
                    } else if ($period_campaign_count_lead_id == 2) {
                        $numberOfLeadWithSourse->whereBetween('campaigns_leads_users.date', [date('Y-m-d', strtotime(Carbon::now()->startOfWeek())), date('Y-m-d', strtotime(Carbon::now()->endOfWeek()))]);
                    } else {
                        $numberOfLeadWithSourse->whereBetween('campaigns_leads_users.date', [date('Y-m') . '-1', date('Y-m-t')]);
                    }

                    $numberOfLeadWithSourse = $numberOfLeadWithSourse->count();

                    if ( (ceil($numberOfLeadCampaign * $percentage_value) < ($numberOfLeadWithSourse + 1) && $numberOfLeadWithSourse != 0) || $percentage_value == 0 ) {
                        $data_percentage_is_true = 0;
                    }
                }
                /////////////////////////////////////////////////////////////////////////////////////////////////////

                //To check budget of campaign
                $completeStatus_final = 0;
                if( $data_percentage_is_true == 1 ){
                    if( $period_campaign_count_lead_id == 1 ){
                        if( $numberOfLeadCampaign > $leadsCampaignsDailiesTotallead ){
                            $completeStatus_final = 1;
                        }
                    } else  if( $period_campaign_count_lead_id == 2 ){
                        if( $numberOfLeadCampaign > $leadsCampaignsWeeklyTotallead ){
                            $completeStatus_final = 1;
                        }
                    } else {
                        if( $numberOfLeadCampaign > $leadsCampaignsMonthlyTotallead ){
                            $completeStatus_final = 1;
                        }
                    }
                } else {
                    $LostReportStep3_3[]=$campaign_id_curent; //cap out
                    continue;
                }

                if( $completeStatus_final == 1 ){
                    $numberOfCamp = count($listOFCampain_sharedDB);
                    if( config('app.name', '') == "Zone1Remodeling" ){
                        $ping_and_post_class = new PingCRMZone();
                    } else {
                        $ping_and_post_class = new PingCRMAllied();
                    }

                    $is_multi_api = 1;
                    $ping_approved_arr = $ping_and_post_class->pingandpost($campaign, $data_msg, $numberOfCamp, $type, $is_pingandpost, $is_multi_api);
                    if(empty($ping_approved_arr)){
                        $LostReportStep3_3[] = $campaign_id_curent; //cap out
                        continue;
                    }

                    $ping_approved_arr['campaign_details'] = $campaign;
                    $ping_post_arr[] = $ping_approved_arr;
                    $listOFCampainDB_array_shared[] = $campaign;
                } else {
                    $LostReportStep3_3[] = $campaign_id_curent;//cap out
                    continue;
                }
            }
        }

        $response_arr = array(
            'campaigns' => $listOFCampainDB_array_shared,
            'response' => $ping_post_arr,
            'LostReportStep3_1' => $LostReportStep3_1,
            'LostReportStep3_2' => $LostReportStep3_2,
            'LostReportStep3_3' => $LostReportStep3_3,
            'LostReportStep3_4' => $LostReportStep3_4,
            'LostReportStep3_5' => $LostReportStep3_5,

        );

        return $response_arr;
    }

    public function filterCampaign_ping_post($listOFCampain_sharedDB, $data_msg, $type, $is_pingandpost = 0, $seller_id = 0)
    {
        $LostReportStep3_1 = array();
        $LostReportStep3_2 = array();
        $LostReportStep3_3 = array();
        $LostReportStep3_4 = array();
        $LostReportStep3_5 = array();

        //Data Filter exclusive And Sheared
        $listOFCampainDB_array_shared = array();
        $ping_post_arr = array();

        if( !empty( $listOFCampain_sharedDB ) ){
            foreach( $listOFCampain_sharedDB as $campaign ) {
                $typeOFBidLead = '';
                if( $type == 1 ){
                    $period_campaign_count_lead_id =  $campaign->period_campaign_count_lead_id_exclusive;
                    $numberOfLeadCampaign = filter_var($campaign->campaign_count_lead_exclusive, FILTER_SANITIZE_NUMBER_INT);

                    $budget = filter_var($campaign->campaign_budget_exclusive, FILTER_SANITIZE_NUMBER_INT);
                    $budget_campaign_count_lead_id = $campaign->period_campaign_budget_id_exclusive;

                    $budget_bid = 0;
                    if( !empty($campaign->campaign_budget_bid_exclusive) || $campaign->campaign_budget_bid_exclusive != 0 ){
                        $budget_bid = filter_var(($campaign->campaign_budget_bid_exclusive - $campaign->virtual_price), FILTER_SANITIZE_NUMBER_INT);
                    }

                    $typeOFBidLead = 'Exclusive';
                } else if( $type == 2 ) {
                    $period_campaign_count_lead_id = $campaign->period_campaign_count_lead_id;
                    $numberOfLeadCampaign = filter_var($campaign->campaign_count_lead, FILTER_SANITIZE_NUMBER_INT);

                    $budget = filter_var($campaign->campaign_budget, FILTER_SANITIZE_NUMBER_INT);
                    $budget_campaign_count_lead_id = $campaign->period_campaign_budget_id;

                    $budget_bid = 0;
                    if (!empty($campaign->campaign_budget_bid_shared) || $campaign->campaign_budget_bid_shared != 0) {
                        $budget_bid = filter_var(($campaign->campaign_budget_bid_shared - $campaign->virtual_price), FILTER_SANITIZE_NUMBER_INT);
                    }

                    $typeOFBidLead = 'Shared';
                }

                $campaign_id_curent = $campaign->campaign_id;
                //campaign we will search on
                $LostReportStep3_5[] = $campaign_id_curent;

                $payment_type_method_status = $campaign->payment_type_method_status;
                $payment_type_method_limit = filter_var($campaign->payment_type_method_limit, FILTER_SANITIZE_NUMBER_INT);
                $payment_type_method_id = $campaign->payment_type_method_id;
                $totalAmmountUser_value = ( !empty($campaign->total_amounts_value) ? $campaign->total_amounts_value : 0 );

                if( $budget_bid == 0 ){
                    $LostReportStep3_3[]=$campaign_id_curent; //cap out
                    continue;
                }

                //Check for multi Service if the campaign accept the same lead you bought for another service
                if( !empty($data_msg['is_multi_service']) ){
                    if ( $campaign->multi_service_accept == 0 && $data_msg['is_multi_service'] == 1 ) {
                        $is_get_before = CampaignsLeadsUsers::join('leads_customers', 'leads_customers.lead_id', '=', 'campaigns_leads_users.lead_id')
                            ->where('leads_customers.universal_leadid', $data_msg['LeadId'])
                            ->where('leads_customers.lead_phone_number', $data_msg['LeadPhone'])
                            ->where('leads_customers.lead_type_service_id', '<>', $data_msg['service_id'])
                            ->where('leads_customers.is_multi_service', 1)
                            ->where('leads_customers.created_at', '>=', date('Y-m-d') . ' 00:00:00')
                            ->where('leads_customers.created_at', '<=', date('Y-m-d') . ' 23:59:59')
                            ->where('campaigns_leads_users.user_id', $campaign->user_id)
                            ->first();

                        if (!empty($is_get_before)) {
                            $LostReportStep3_1[] = $campaign_id_curent; //non multi Service out
                            continue;
                        }
                    }
                }

                //Check if the seller allowed to bought this campaign this case when we are bay leaves from sellers
                if( $seller_id != 0 ){
                    $exclude_sellers_campaigns_exist = DB::table('exclude_sellers_campaigns')
                        ->where('campaign_id', $campaign->campaign_id)
                        ->first();

                    $exclude_sellers_campaigns = DB::table('exclude_sellers_campaigns')
                        ->where('campaign_id', $campaign->campaign_id)
                        ->where('seller_id', $seller_id)
                        ->first();

                    if(!empty($exclude_sellers_campaigns_exist->types)){
                        if( $exclude_sellers_campaigns_exist->types == 'Exclude' ){
                            if(!empty($exclude_sellers_campaigns)) {
                                $LostReportStep3_3[]=$campaign_id_curent; //cap out
                                continue;
                            }
                        } else {
                            if(empty($exclude_sellers_campaigns)) {
                                $LostReportStep3_3[]=$campaign_id_curent; //cap out
                                continue;
                            }
                        }
                    }
                }
                //======================================================================================================

                //To check time delivery
                $completeStatus = 1;
                if ($campaign->campaign_Type == 1) {
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
                }

                if( $completeStatus == 0 ){
                    $LostReportStep3_2[]=$campaign_id_curent; //time delivery
                    continue;
                }

                /////////////////////////////////////////////////////////////////////////////////////////////////////
                $leadsCampaignsDailies = DB::table('campaigns_leads_users')
                    ->where('campaign_id', $campaign_id_curent)
                    ->where('date', date("Y-m-d"))
                    ->where('campaigns_leads_users_type_bid', $typeOFBidLead)
                    ->get([
                        DB::raw("SUM(campaigns_leads_users.campaigns_leads_users_bid) AS sumbid"),
                        DB::raw("COUNT(campaigns_leads_users.campaigns_leads_users_id) AS totallead")
                    ]);

                $leadsCampaignsWeekly = DB::table('campaigns_leads_users')
                    ->where('campaign_id', $campaign_id_curent)
                    ->whereBetween('date', [date('Y-m-d', strtotime(Carbon::now()->startOfWeek())),date('Y-m-d', strtotime(Carbon::now()->endOfWeek()))])
                    ->where('campaigns_leads_users_type_bid', $typeOFBidLead)
                    ->get([
                        DB::raw("SUM(campaigns_leads_users.campaigns_leads_users_bid) AS sumbid"),
                        DB::raw("COUNT(campaigns_leads_users.campaigns_leads_users_id) AS totallead")
                    ]);

                $leadsCampaignsMonthly = DB::table('campaigns_leads_users')
                    ->where('campaign_id', $campaign_id_curent)
                    ->whereBetween('date', [date('Y-m'). '-1',date('Y-m-t')])
                    ->where('campaigns_leads_users_type_bid', $typeOFBidLead)
                    ->get([
                        DB::raw("SUM(campaigns_leads_users.campaigns_leads_users_bid) AS sumbid"),
                        DB::raw("COUNT(campaigns_leads_users.campaigns_leads_users_id) AS totallead")
                    ]);

                /////////////////////////////////////////////////////////////////////////////////////////////////////
                //To Check Source Percentage for the lead =====================================================
                //Get Percentage For this Campaign & Source
                $percentage_data = DB::table('source_percentage')->where('campaign_id', $campaign_id_curent)->first();

                $data_percentage_is_true = 1;
                if( !empty($percentage_data) ){
                    $percentage_value = DB::table('source_percentage')->where('campaign_id', $campaign_id_curent)
                        ->where('source_id', $data_msg['lead_source_id'])->first(['percentage_value']);

                    $percentage_value = ( !empty($percentage_value) ? $percentage_value->percentage_value / 100 : 1 );

                    $numberOfLeadWithSourse = DB::table('campaigns_leads_users')
                        ->join('leads_customers', 'leads_customers.lead_id', '=', 'campaigns_leads_users.lead_id')
                        ->where('campaigns_leads_users.campaign_id', $campaign_id_curent)
                        ->where('campaigns_leads_users.campaigns_leads_users_type_bid', $typeOFBidLead)
                        ->where('leads_customers.lead_source', $data_msg['lead_source_id']);

                    if ($period_campaign_count_lead_id == 1) {
                        $numberOfLeadWithSourse->where('campaigns_leads_users.date', date("Y-m-d"));
                    } else if ($period_campaign_count_lead_id == 2) {
                        $numberOfLeadWithSourse->whereBetween('campaigns_leads_users.date', [date('Y-m-d', strtotime(Carbon::now()->startOfWeek())), date('Y-m-d', strtotime(Carbon::now()->endOfWeek()))]);
                    } else {
                        $numberOfLeadWithSourse->whereBetween('campaigns_leads_users.date', [date('Y-m') . '-1', date('Y-m-t')]);
                    }

                    $numberOfLeadWithSourse = $numberOfLeadWithSourse->count();

                    if ( (ceil($numberOfLeadCampaign * $percentage_value) < ($numberOfLeadWithSourse + 1) && $numberOfLeadWithSourse != 0) || $percentage_value == 0 ) {
                        $data_percentage_is_true = 0;
                    }
                }
                /////////////////////////////////////////////////////////////////////////////////////////////////////
                $completeStatus2 = 0;
                if( $data_percentage_is_true == 1 ){
                    $numberOfCamp = count($listOFCampain_sharedDB);
                    if( config('app.name', '') == "Zone1Remodeling" ){
                        $ping_and_post_class = new PingCRMZone();
                    } else {
                        $ping_and_post_class = new PingCRMAllied();
                    }

                    $ping_approved = $ping_and_post_class->pingandpost($campaign, $data_msg, $numberOfCamp, $type, $is_pingandpost);

                    $ping_approved_arr = json_decode($ping_approved, true);

                    if ($ping_approved_arr['Result'] == 1) {
                        $completeStatus3 = 1;
                        if ( $ping_approved_arr['Payout'] < 2 || is_numeric($ping_approved_arr['Payout']) != 1 ) {
                            $completeStatus3 = 0;
                        }

                        if ($completeStatus3 == 1) {
                            $completeStatus2 = 1;
                            $budget_bid = $ping_approved_arr['Payout'];
                            $ping_post_arr['campaign-'.$campaign_id_curent] = $ping_approved_arr;

                            $campaign->campaign_budget_bid_exclusive = $ping_approved_arr['Payout'];
                            $campaign->campaign_budget_bid_shared = $ping_approved_arr['Payout'];
                        } else {
                            $LostReportStep3_4[]=$campaign_id_curent; //ping reject
                        }
                    } else{
                        $LostReportStep3_4[]=$campaign_id_curent; //ping reject
                    }
                } else {
                    $LostReportStep3_3[]=$campaign_id_curent; //cap out
                }

                //To check budget of campaign
                if( $completeStatus2 == 1 ){
                    $completeStatus_final = 0;
                    //==================================================================================================
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

                    if( $completeStatus_final != 1 ){
                        $LostReportStep3_3[]=$campaign_id_curent; //cap out
                    } else {
                        $listOFCampainDB_array_shared[] = $campaign;
                    }
                }
            }
        }

        $response_arr = array(
            'campaigns' => $listOFCampainDB_array_shared,
            'response' => $ping_post_arr,
            'LostReportStep3_1' => $LostReportStep3_1,
            'LostReportStep3_2' => $LostReportStep3_2,
            'LostReportStep3_3' => $LostReportStep3_3,
            'LostReportStep3_4' => $LostReportStep3_4,
            'LostReportStep3_5' => $LostReportStep3_5,

        );

        return $response_arr;
    }

    public function campaign_ids_from_area($zipcode_arr_id, $zipcode_arr_name, $city_arr_id, $county_id, $state_arr_id){
        $ListOFCampaigns_zipcode = DB::table('zipcode__campaigns')
            ->whereIn('zipcode_campaigns', $zipcode_arr_id)
            ->where('zipcode_campaigns_active', 1)
            ->pluck('campaign_id')->toArray();

        $ListOFCampaigns_zipcode_dictance = DB::table('campaign_zipcode_distance')
            ->whereIn('zipcode_campaigns', $zipcode_arr_name)
            ->pluck('campaign_id')->toArray();

        $ListOFCampaigns_city = DB::table('city__campaigns')
            ->whereIn('city_id', $city_arr_id)
            ->where('city_campaigns_active', 1)
            ->pluck('campaign_id')->toArray();

        $ListOFCampaigns_county = DB::table('county__campaigns')
            ->where('county_id', $county_id)
            ->where('county_campaigns_active', 1)
            ->pluck('campaign_id')->toArray();

        $ListOFCampaigns_state = DB::table('state_campaigns')
            ->whereIn('state_id', $state_arr_id)
            ->where('state_campaigns_active', 1)
            ->pluck('campaign_id')->toArray();

        $ListOFCampaigns = array_merge($ListOFCampaigns_zipcode, $ListOFCampaigns_city, $ListOFCampaigns_county, $ListOFCampaigns_state, $ListOFCampaigns_zipcode_dictance);

        $ListOFCampaigns = array_unique($ListOFCampaigns);

        //expect Zipcode
        $expectCampaignIdForZipCodeExpect_array = DB::table('zipcode__campaigns')
            ->whereIn('campaign_id', $ListOFCampaigns)
            ->where('zipcode_campaigns_active', 0)
            ->whereIn('zipcode_campaigns', $zipcode_arr_id)
            ->pluck('campaign_id')->toArray();

        $lastCampaignInAreaZipCode = array();
        foreach( $ListOFCampaigns as $item ){
            if( !in_array($item, $expectCampaignIdForZipCodeExpect_array) ){
                $lastCampaignInAreaZipCode[] = $item;
            }
        }

        //expect Cities
        $expectCampaignIdForCityExpect_array = DB::table('city__campaigns')
            ->whereIn('campaign_id', $lastCampaignInAreaZipCode)
            ->where('city_campaigns_active', 0)
            ->whereIn('city_id', $city_arr_id)
            ->pluck('campaign_id')->toArray();

        $lastCampaignInAreaCity = array();
        foreach( $lastCampaignInAreaZipCode as $item ){
            if( !in_array($item, $expectCampaignIdForCityExpect_array) ){
                $lastCampaignInAreaCity[] = $item;
            }
        }

        //expect Counties
        $expectCampaignIdForCountyExpect_array = DB::table('county__campaigns')
            ->whereIn('campaign_id', $lastCampaignInAreaCity)
            ->where('county_campaigns_active', 0)
            ->where('county_id', $county_id)
            ->pluck('campaign_id')->toArray();

        $lastCampaignInArea = array();
        foreach( $lastCampaignInAreaCity as $item ){
            if( !in_array($item, $expectCampaignIdForCountyExpect_array) ){
                $lastCampaignInArea[] = $item;
            }
        }

        return $lastCampaignInArea;
    }

    public function payment_lead($user_id, $budget_bid){
        $totalAmmountUser = TotalAmount::where('user_id', $user_id)
            ->first(['total_amounts_value']);

        $totalAmmountUser_value = 0;
        if( !empty( $totalAmmountUser ) ){
            $totalAmmountUser_value = $totalAmmountUser['total_amounts_value'];
        }

        $total = $totalAmmountUser_value - $budget_bid;
        if( !empty( $totalAmmountUser ) ){
            TotalAmount::where('user_id', $user_id)->update([ 'total_amounts_value' => $total ]);
        } else {
            $addtotalAmmount = new TotalAmount();

            $addtotalAmmount->user_id = $user_id;
            $addtotalAmmount->total_amounts_value = $total;

            $addtotalAmmount->save();
        }
    }

    public function seller_pay($user_id, $Total){
        $totalAmmount = SellerTotalAmount::where('user_id', $user_id)->first('total_amounts_value');
        if (empty($totalAmmount)) {
            $addtotalAmmount = new SellerTotalAmount();

            $addtotalAmmount->user_id = $user_id;
            $addtotalAmmount->total_amounts_value = $Total;

            $addtotalAmmount->save();
        } else {
            $total = $Total + $totalAmmount['total_amounts_value'];
            SellerTotalAmount::where('user_id', $user_id)->update(['total_amounts_value' => $total]);
        }
    }

    public function AddLeadsCampaignUser($dataleads){
        $lead_id = $dataleads['lead_id'];
        $leadsCustomerCampaign = new CampaignsLeadsUsers();

        $leadsCustomerCampaign->user_id = $dataleads['user_id'];
        $leadsCustomerCampaign->campaign_id = $dataleads['campaign_id'];
        $leadsCustomerCampaign->lead_id = $lead_id;
        $leadsCustomerCampaign->date = $dataleads['curent_date'];
        $leadsCustomerCampaign->lead_id_token_md = md5($lead_id);
        $leadsCustomerCampaign->campaigns_leads_users_type_bid = $dataleads['type_bid'];
        $leadsCustomerCampaign->campaigns_leads_users_bid = $dataleads['bid_budget'];
        $leadsCustomerCampaign->transactionId = $dataleads['transactionId'];
        $leadsCustomerCampaign->created_at = date('Y-m-d H:i:s');
        $leadsCustomerCampaign->is_recorded = $dataleads['is_recorded'];
        $leadsCustomerCampaign->campaigns_leads_users_note = "";
        //For Call Center Data =============================================================
        if( !empty($dataleads['callCenter']) ){
            $leadsCustomerCampaign->call_center = 1;
        }
        if( !empty($dataleads['agent_name']) ){
            $leadsCustomerCampaign->agent_name = $dataleads['agent_name'];
        }
        //For Call Center Data =============================================================

        $leadsCustomerCampaign->save();

        // $leadsCustomerCampaign_id = DB::getPdo()->lastInsertId();
        $leadsCustomerCampaign_id = $leadsCustomerCampaign->campaigns_leads_users_id;
        //========================================================================
        $leadsCustomerCampaign_aff = new CampaignsLeadsUsersAff();
        $leadsCustomerCampaign_aff->user_id = $dataleads['user_id'];
        $leadsCustomerCampaign_aff->campaign_id = $dataleads['campaign_id'];
        $leadsCustomerCampaign_aff->lead_id = $lead_id;
        $leadsCustomerCampaign_aff->date = $dataleads['curent_date'];
        $leadsCustomerCampaign_aff->lead_id_token_md = md5($lead_id);
        $leadsCustomerCampaign_aff->campaigns_leads_users_type_bid = $dataleads['type_bid'];
        $leadsCustomerCampaign_aff->campaigns_leads_users_bid = $dataleads['bid_budget'];
        $leadsCustomerCampaign_aff->transactionId = $dataleads['transactionId'];
        $leadsCustomerCampaign_aff->created_at = date('Y-m-d H:i:s');
        $leadsCustomerCampaign_aff->is_recorded = $dataleads['is_recorded'];
        $leadsCustomerCampaign_aff->campaigns_leads_users_note = "";
        $leadsCustomerCampaign_aff->vendor_id_aff = (!empty($dataleads['vendor_id']) ? $dataleads['vendor_id'] : "");
        $leadsCustomerCampaign_aff->save();
        //========================================================================

        return $leadsCustomerCampaign_id;
    }

    public function send_email_buyers_threshold($email, $buyer_name, $type){
        $data = array(
            'name' => $buyer_name,
            'type' => $type
        );

        if( $type != 2 ){
            Mail::send(['text'=>'Mail.etopup'], $data, function($message) use($email, $buyer_name) {
                $message->to($email, $buyer_name)->subject('eTopUp');
                $message->from(config('mail.from.address', ''),config('mail.from.name', ''));
            });
        }

        //Slack::send("User $email Budget is low!");
    }

    public function autopaycampaign($user_id, $amount){
        $user_payment = Payment::where('user_id', $user_id)
            ->where('payment_primary', 1)
            ->first();

        if( !empty($user_payment) ){
            $merchant_account = config('services.AUTO_PAYMENT_METHODS', '');
            switch ($merchant_account){
                case "Stripe":
                    $stripe_secret = config('services.stripe.secret', '');
                    $stripe_merchant = new StripePaymentController();
                    return $stripe_merchant->auto_pay($merchant_account, $user_id, $amount, $user_payment, $stripe_secret);
                    break;
                case "Auth1":
                    $MERCHANT_LOGIN_ID = "7wY89Qmx";
                    $MERCHANT_TRANSACTION_KEY = "7eBnmN33m2T7W94G";
                    $auth_merchant = new PaymentAuthController();
                    return $auth_merchant->auto_pay($merchant_account, $user_id, $amount, $user_payment, $MERCHANT_LOGIN_ID, $MERCHANT_TRANSACTION_KEY);
                    break;
                case "Auth2":
                    $MERCHANT_LOGIN_ID = "2LmJH4r4f";
                    $MERCHANT_TRANSACTION_KEY = "684598e7KDqwwKDD";
                    $auth_merchant = new PaymentAuthController();
                    return $auth_merchant->auto_pay($merchant_account, $user_id, $amount, $user_payment, $MERCHANT_LOGIN_ID, $MERCHANT_TRANSACTION_KEY);
                    break;
                case "Auth3":
                    $MERCHANT_LOGIN_ID = "47h7eRZyAZ26";
                    $MERCHANT_TRANSACTION_KEY = "5z6D6eHa79p6X7CW";
                    $auth_merchant = new PaymentAuthController();
                    return $auth_merchant->auto_pay($merchant_account, $user_id, $amount, $user_payment, $MERCHANT_LOGIN_ID, $MERCHANT_TRANSACTION_KEY);
                    break;
                case "Auth4":
                    $MERCHANT_LOGIN_ID = "89LvP4kPM";
                    $MERCHANT_TRANSACTION_KEY = "44K4V372tHUk2j6A";
                    $auth_merchant = new PaymentAuthController();
                    return $auth_merchant->auto_pay($merchant_account, $user_id, $amount, $user_payment, $MERCHANT_LOGIN_ID, $MERCHANT_TRANSACTION_KEY);
                    break;
                case "NMI":
                    $security_key = "p8Mfe6mN6HMDcFW5tmGmc7saQn36G3nm";
                    $nmi_merchant = new NMIPaymentController();
                    return $nmi_merchant->auto_pay($merchant_account, $user_id, $amount, $user_payment, $security_key);
                    break;
                case "NMI2":
                    $security_key = "523rMS4zpDnJVNaC5G56UR5swzmzG78k";
                    $nmi_merchant = new NMIPaymentController();
                    return $nmi_merchant->auto_pay($merchant_account, $user_id, $amount, $user_payment, $security_key);
                    break;
                case "NMI3":
                    $security_key = "KM55mz4DQ8MKQStNKu22uUTz394359y8";
                    $nmi_merchant = new NMIPaymentController();
                    return $nmi_merchant->auto_pay($merchant_account, $user_id, $amount, $user_payment, $security_key);
                    break;
            }
        }
        return false;
    }

    public function send_sms_twilio($data_msg, $sms_data_array)
    {
        $buyersusername = $data_msg['name'];
        //SMS
        $datasmsMassage = "Dear $buyersusername,\n";
        $datasmsMassage .= "You have a new lead,\n";
        $datasmsMassage .= "Client's Name: " . $data_msg['leadName'] . ",\n";
        $datasmsMassage .= "Client's Email: " . $data_msg['LeadEmail'] . ",\n";
        $datasmsMassage .= "Client's Phone Number: " . $data_msg['LeadPhone'] . ",\n";
        $datasmsMassage .= $data_msg['Address'] . "\n";
        $datasmsMassage .= "Lead Service: " . $data_msg['LeadService'] . ", \n";
        $datasmsMassage .= "Lead ID: " . $data_msg['leadCustomer_id'] . ", \n";

        if( !empty($data_msg['data']) ){
            foreach($data_msg['data'] as $val){
                if( !empty($val) ) {
                    $datasmsMassage .= "$val, \n";
                }
            }
        }

        if( !empty($data_msg['appointment_type']) ){
            $datasmsMassage .= "Appointment Type: " . $data_msg['appointment_type'] . ",\n";
        }
        if( !empty($data_msg['appointment_date']) ){
            $datasmsMassage .= "Appointment Date: " . $data_msg['appointment_date'] . ",\n";
        }

        $datasmsMassage .= "For more information and to view your received leads please login using the link below:\n";
        $datasmsMassage .= "https://" . $_SERVER['SERVER_NAME'] . ",\n";
        $datasmsMassage .= config('app.name').",\n";
        $datasmsMassage .= "Kind Regards";

        $account_sid = config('services.TWILIO.TWILIO_SID', '');
        $auth_token = config('services.TWILIO.TWILIO_AUTH_TOKEN', '');
        $twilio_number = config('services.TWILIO.TWILIO_NUMBER', '');

        $client = new Client($account_sid, $auth_token);

        try {
            if (!empty($sms_data_array['phone1'])) {
                $client->messages->create(
                    '+1' . $sms_data_array['phone1'],
                    [
                        'from' => $twilio_number,
                        'body' => $datasmsMassage
                    ]
                );
            }
        } catch (\Twilio\Exceptions\RestException $e) {
            //echo "Couldn't send message to number\n";
        } catch (Exception $e) {

        }

        try {
            if (!empty($sms_data_array['phone2'])) {
                $client->messages->create(
                    '+1' . $sms_data_array['phone2'],
                    [
                        'from' => $twilio_number,
                        'body' => $datasmsMassage
                    ]
                );
            }
        } catch (\Twilio\Exceptions\RestException $e) {
            //echo "Couldn't send message to number\n";
        } catch (Exception $e) {

        }

        try {
            if (!empty($sms_data_array['phone3'])) {
                $client->messages->create(
                    '+1' . $sms_data_array['phone3'],
                    [
                        'from' => $twilio_number,
                        'body' => $datasmsMassage
                    ]
                );
            }
        } catch (\Twilio\Exceptions\RestException $e) {
            //echo "Couldn't send message to number\n";
        } catch (Exception $e) {

        }

        try {
            if (!empty($sms_data_array['phone4'])) {
                $client->messages->create(
                    '+1' . $sms_data_array['phone4'],
                    [
                        'from' => $twilio_number,
                        'body' => $datasmsMassage
                    ]
                );
            }
        } catch (\Twilio\Exceptions\RestException $e) {
            //echo "Couldn't send message to number\n";
        } catch (Exception $e) {

        }

        try {
            if (!empty($sms_data_array['phone5'])) {
                $client->messages->create(
                    '+1' . $sms_data_array['phone5'],
                    [
                        'from' => $twilio_number,
                        'body' => $datasmsMassage
                    ]
                );
            }
        } catch (\Twilio\Exceptions\RestException $e) {
            //echo "Couldn't send message to number\n";
        } catch (Exception $e) {

        }

        try {
            if (!empty($sms_data_array['phone6'])) {
                $client->messages->create(
                    '+1' . $sms_data_array['phone6'],
                    [
                        'from' => $twilio_number,
                        'body' => $datasmsMassage
                    ]
                );
            }
        } catch (\Twilio\Exceptions\RestException $e) {
            //echo "Couldn't send message to number\n";
        } catch (Exception $e) {

        }
    }

    public function send_sms_bandwidth($data_msg, $sms_data_array)
    {
        $buyersusername = $data_msg['name'];
        //SMS
        $datasmsMassage = "Dear $buyersusername,\n";
        $datasmsMassage .= "You have a new lead,\n";
        $datasmsMassage .= "Client's Name: " . $data_msg['leadName'] . ",\n";
        $datasmsMassage .= "Client's Email: " . $data_msg['LeadEmail'] . ",\n";
        $datasmsMassage .= "Client's Phone Number: " . $data_msg['LeadPhone'] . ",\n";
        $datasmsMassage .= $data_msg['Address'] . "\n";
        $datasmsMassage .= "Lead Service: " . $data_msg['LeadService'] . ", \n";
        $datasmsMassage .= "Lead ID: " . $data_msg['leadCustomer_id'] . ", \n";

        if( !empty($data_msg['data']) ){
            foreach($data_msg['data'] as $val){
                if( !empty($val) ) {
                    $datasmsMassage .= "$val, \n";
                }
            }
        }

        if( !empty($data_msg['appointment_type']) ){
            $datasmsMassage .= "Appointment Type: " . $data_msg['appointment_type'] . ",\n";
        }
        if( !empty($data_msg['appointment_date']) ){
            $datasmsMassage .= "Appointment Date: " . $data_msg['appointment_date'] . ",\n";
        }

        $datasmsMassage .= "For more information and to view your received leads please login using the link below:\n";
        $datasmsMassage .= "https://" . $_SERVER['SERVER_NAME'] . ",\n";
        $datasmsMassage .= config('app.name').",\n";
        $datasmsMassage .= "Kind Regards";

        try {
            $BandWidth = new BandWidthController();
            if (!empty($sms_data_array['phone1'])) {
                $BandWidth->SendMessage(array('+1' . $sms_data_array['phone1']),$datasmsMassage);
            }
            if (!empty($sms_data_array['phone2'])) {
                $BandWidth->SendMessage(array('+1' . $sms_data_array['phone2']),$datasmsMassage);
            }
            if (!empty($sms_data_array['phone3'])) {
                $BandWidth->SendMessage(array('+1' . $sms_data_array['phone3']),$datasmsMassage);
            }
            if (!empty($sms_data_array['phone4'])) {
                $BandWidth->SendMessage(array('+1' . $sms_data_array['phone4']),$datasmsMassage);
            }
            if (!empty($sms_data_array['phone5'])) {
                $BandWidth->SendMessage(array('+1' . $sms_data_array['phone5']),$datasmsMassage);
            }
            if (!empty($sms_data_array['phone6'])) {
                $BandWidth->SendMessage(array('+1' . $sms_data_array['phone6']),$datasmsMassage);
            }
        } catch (Exception $e) {

        }
    }

    public function send_email($data_msg, $email_data_array){

        $buyersusername = $email_data_array['buyersusername'];
        if( !empty($email_data_array['email1'])){
            $buyersEmail = $email_data_array['email1'];
            $buyersEmailCc1 = $email_data_array['email2'];
            $buyersEmailCc2 = $email_data_array['email3'];
            $buyersEmailCc3 = $email_data_array['email4'];
            $buyersEmailCc4 = $email_data_array['email5'];
            $buyersEmailCc5 = $email_data_array['email6'];

            $subject_email = 'have a new Lead';
            if(!empty($email_data_array['subject_email'])) {
                $subject_email = $email_data_array['subject_email'];
            }

            $emails_cc = array();
            if(!empty($email_data_array['email2'])){
                $emails_cc[] = $buyersEmailCc1;
            }
            if(!empty($email_data_array['email3'])){
                $emails_cc[] = $buyersEmailCc2;
            }
            if(!empty($email_data_array['email4'])){
                $emails_cc[] = $buyersEmailCc3;
            }
            if(!empty($email_data_array['email5'])){
                $emails_cc[] = $buyersEmailCc4;
            }
            if(!empty($email_data_array['email6'])){
                $emails_cc[] = $buyersEmailCc5;
            }

            try {
                Mail::send(['text' => 'Mail.leadmail'], $data_msg, function ($message) use ($buyersEmail, $buyersusername, $subject_email, $emails_cc) {
                    $message->to($buyersEmail, $buyersusername)->cc($emails_cc)->subject($subject_email);
                    $message->from(config('mail.from.address', ''), config('mail.from.name', ''));
                });
            } catch (Exception $e) {

            }
        }
    }

    public function send_CallTools_unsold($data_msg, $file_id = '', $campaign_id = ''){
        if( $file_id == '' ){
            if( $data_msg['LeadService'] == "Window" ){
                $file_id = 795345;
            } else if( $data_msg['LeadService'] == "Solar" ){
                $file_id = 795344;
            } else if( $data_msg['LeadService'] == "Home Security" ){
                $file_id = 796497;
            } else if( $data_msg['LeadService'] == "WALK-IN TUBS" ){
                $file_id = 796495;
            }
        }

        if( $file_id != '' ){
            $crm_details = array(
                'callTollsDetails' => array(
                    'api_key' => config('services.CALLTOOLS_API_KEY', ''),
                    'file_id' => $file_id
                ),
                'service_campaign_name' => $data_msg['LeadService'],
                'campaign_id' => $campaign_id,
                'from_job' => 1
            );

            $crm_api_file = new CrmApi();
            $crm_api_file->callTools($data_msg, $crm_details);
        }
    }

    public function zipcodes_distance_filter($zipcode, $distancekm){
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "http://zipcodedistanceapi.redline13.com/rest/".config('zipcodelistapi.ZIPCODESLISTAPI', '')."/radius.json/".$zipcode."/".$distancekm."/mile",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
        ));

        $response = curl_exec($curl);

        curl_close($curl);

        $decoded_result = json_decode($response, true);

        return $decoded_result;
    }

    public function claim_trusted_form($trusted_form){
        //For Claim TratedForm Lead
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $trusted_form,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_HTTPHEADER => array(
                "Content-Type: application/json",
                "Accept: application/json",
                "charset: utf-8",
                "Authorization: Basic " . config('services.Trusted_Form_Auth', '')
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        //End Claim TratedForm Lead
    }

    public function claim_jornaya_id($jornaya_lead_id){
        //For Claim Jornaya LeadId
        $curl = curl_init();

        $JORNAYA_lac = config('services.JORNAYA.JORNAYA_lac', '');
        $JORNAYA_lak = config('services.JORNAYA.JORNAYA_lak', '');
        $JORNAYA_lpc = config('services.JORNAYA.JORNAYA_lpc', '');

        $jornaya_leadid_url = "https://api.leadid.com/SingleQuery?lac=$JORNAYA_lac&id=$jornaya_lead_id&lak=$JORNAYA_lak&lpc=$JORNAYA_lpc";

        curl_setopt_array($curl, array(
            CURLOPT_URL => $jornaya_leadid_url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                "Cookie: rguserid=29911432-0d78-41e2-96d4-cacc4ff36f0b; rguuid=true; rgisanonymous=true"
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        //End Claim Jornaya LeadId
    }

    public function server_to_server_conv($url_conv){
        try {
            if(empty($url_conv)){
                return false;
            }
            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => $url_conv,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "GET",
                CURLOPT_HTTPHEADER => array(
                    "Content-Type: application/json",
                    "Accept: application/json"
                ),
            ));

            $response = curl_exec($curl);

            curl_close($curl);
        } catch (Exception $e) {

        }
    }

    public function overWriteEnvFile($type, $val){
        $path = base_path('.env');
        if (file_exists($path)) {
            $val = '"'.trim($val).'"';
            if(is_numeric(strpos(file_get_contents($path), $type)) && strpos(file_get_contents($path), $type) >= 0){
                file_put_contents($path, str_replace(
                    $type.'="'.env($type).'"', $type.'='.$val, file_get_contents($path)
                ));
            } else {
                file_put_contents($path, file_get_contents($path)."\r\n".$type.'='.$val);
            }
        }
    }

    public function delivery_methods($data_msg, $campaign, $listOFCampainDB_type, $count_of_camp)
    {
        $leadsCustomerCampaign_id = $data_msg['leadCustomer_id'];
        $campaign_id_curent = $campaign->campaign_id;
        $user_id = $campaign->user_id;
        $buyersusername = $campaign->username;
        $service_campaign_name = $campaign->service_campaign_name;
        $is_ping_account = $campaign->is_ping_account;
        $campaign_Type = $campaign->campaign_Type;
        $campaign_name = $campaign->campaign_name;
        $campaign_budget_bid_exclusive = $campaign->campaign_budget_bid_exclusive;
        $campaign_budget_bid_shared = $campaign->campaign_budget_bid_shared;
        $virtual_price = $campaign->virtual_price;

        if( config('app.name', '') == "Zone1Remodeling" ){
            $crm_api_file = new PostCRMZone();
        } else {
            $crm_api_file = new PostCRMAllied();
        }

        $delivery_methods = json_decode($campaign->delivery_Method_id,true);

        $status = 0;
        if (!empty($delivery_methods)) {
            foreach ($delivery_methods as $delivery_method) {
                if ($delivery_method == 1) {
                    $sms_data_array = array(
                        'user_id' => $user_id,
                        'phone1' => $campaign->phone1,
                        'phone2' => $campaign->phone2,
                        'phone3' => $campaign->phone3,
                        'phone4' => $campaign->phone4,
                        'phone5' => $campaign->phone5,
                        'phone6' => $campaign->phone6,
                    );

//                    $this->send_sms_twilio($data_msg, $sms_data_array);
                    $this->send_sms_bandwidth($data_msg, $sms_data_array);
                    $status = 1;
                }

                if ($delivery_method == 2) {
                    //EMAIL
                    $email_data_array = array(
                        'buyersusername' => $buyersusername,
                        'email1' => $campaign->email1,
                        'email2' => $campaign->email2,
                        'email3' => $campaign->email3,
                        'email4' => $campaign->email4,
                        'email5' => $campaign->email5,
                        'email6' => $campaign->email6,
                        'subject_email' => $campaign->subject_email,
                    );

                    $this->send_email($data_msg, $email_data_array);
                    $status = 1;
                }

                if ($delivery_method == 3) {
                    //CRM
                    $crm_details = array(
                        'campaign_id' => $campaign_id_curent,
                        'service_campaign_name' => $service_campaign_name,
                        'service_id' => $campaign->service_campaign_id,
                        'leadsCustomerCampaign_id' => $leadsCustomerCampaign_id,
                        'buyer_id' => $campaign->user_id,
                        'listOFCampainDB_type' => $listOFCampainDB_type,
                        'count_of_camp' => $count_of_camp,
                        'data' => $data_msg['Leaddatadetails'],
                        'is_ping_account' => $is_ping_account,
                        'campaign_Type' => $campaign_Type,
                        'campaign_name' => $campaign_name,
                        "campaign_budget_bid_exclusive" => $campaign_budget_bid_exclusive,
                        "campaign_budget_bid_shared" => $campaign_budget_bid_shared,
                        "virtual_price" => $virtual_price,
                        "is_lead_review" => (!empty($data_msg["is_lead_review"]) ? $data_msg["is_lead_review"] : 0)
                    );

                    $compaign_crm_arr = json_decode($campaign->crm, true);
                    if (in_array(1, $compaign_crm_arr)) {
                        $callTollsDetails = CallTools::where('campaign_id', $campaign_id_curent)->first();

                        $crm_details['callTollsDetails'] = $callTollsDetails;
                        $status = $crm_api_file->callTools($data_msg, $crm_details);
                    }
                    if (in_array(2, $compaign_crm_arr)) {
                        $Five9Details = Five9::where('campaign_id', $campaign_id_curent)->first();

                        $crm_details['Five9Details'] = $Five9Details;
                        $status = $crm_api_file->five9Crm($data_msg, $crm_details);
                    }
                    if (in_array(3, $compaign_crm_arr)) {
                        $Leads_PediaDetails = Leads_Pedia::where('campaign_id', $campaign_id_curent)->first();

                        $crm_details['Leads_PediaDetails'] = $Leads_PediaDetails;
                        $status = $crm_api_file->leadsPedia($data_msg, $crm_details);
                    }
                    if (in_array(4, $compaign_crm_arr)) {
                        $hubspotDetails = hubspot::where('campaign_id', $campaign_id_curent)->first();

                        $crm_details['hubspotDetails'] = $hubspotDetails;
                        $status = $crm_api_file->hubspot($data_msg, $crm_details);
                    }
                    if (in_array(6, $compaign_crm_arr)) {
                        $pipedriveDetails = Pipe_Drive::where('campaign_id', $campaign_id_curent)->first();

                        $crm_details['pipedriveDetails'] = $pipedriveDetails;
                        $status = $crm_api_file->Pipdrive($data_msg, $crm_details);
                    }
                    if (in_array(7, $compaign_crm_arr)) {
                        $JangleDetails = Jangle::where('campaign_id', $campaign_id_curent)->first();

                        $crm_details['JangleDetails'] = $JangleDetails;
                        $status = $crm_api_file->Jangle($data_msg, $crm_details);
                    }
                    if (in_array(8, $compaign_crm_arr)) {
                        $leadPerfectionCrm = leadPerfectionCrm::where('campaign_id', $campaign_id_curent)->first();

                        $crm_details['leadPerfectionCrm'] = $leadPerfectionCrm;
                        $status = $crm_api_file->leadPerfectionCrm($data_msg, $crm_details);
                    }
                    if (in_array(9, $compaign_crm_arr)) {
                        $Improveit360Crm = Improveit360Crm::where('campaign_id', $campaign_id_curent)->first();

                        $crm_details['Improveit360Crm'] = $Improveit360Crm;
                        $status = $crm_api_file->Improveit360Crm($data_msg, $crm_details);
                    }
                    if (in_array(10, $compaign_crm_arr)) {

                        $LeadConduitCrm = LeadConduit::where('campaign_id', $campaign_id_curent)->first();

                        $crm_details['LeadConduitCrm'] = $LeadConduitCrm;
                        $status = $crm_api_file->LeadConduitCrm($data_msg, $crm_details);
                    }
                    if (in_array(11, $compaign_crm_arr)) {
                        $MarketsharpmCrm = Marketsharpm::where('campaign_id', $campaign_id_curent)->first();

                        $crm_details['MarketsharpmCrm'] = $MarketsharpmCrm;
                        $status = $crm_api_file->MarketsharpmCrm($data_msg, $crm_details);
                    }
                    if (in_array(12, $compaign_crm_arr)) {
                        $leadPortalCrm = LeadPortal::where('campaign_id', $campaign_id_curent)->first();

                        $crm_details['leadPortalCrm'] = $leadPortalCrm;
                        $status = $crm_api_file->leadPortalCrm($data_msg, $crm_details);
                    }
                    if (in_array(13, $compaign_crm_arr)) {
                        $leads_pedia_track = leads_pedia_track::where('campaign_id', $campaign_id_curent)->first();

                        $crm_details['leads_pedia_track'] = $leads_pedia_track;
                        $status = $crm_api_file->leads_pedia_track($data_msg, $crm_details);
                    }
                    if (in_array(14, $compaign_crm_arr)) {
                        $AcculynxCrm = AcculynxCrm::where('campaign_id', $campaign_id_curent)->first();

                        $crm_details['AcculynxCrm'] = $AcculynxCrm;
                        $status = $crm_api_file->AcculynxCrm($data_msg, $crm_details);
                    }
                    if (in_array(15, $compaign_crm_arr)) {
                        $ZohoCrm = ZohoCrm::where('campaign_id', $campaign_id_curent)->first();

                        $crm_details['ZohoCrm'] = $ZohoCrm;
                        $status = $crm_api_file->ZohoCrm($data_msg, $crm_details);
                    }
                    if (in_array(16, $compaign_crm_arr)) {
                        $HatchCrm = HatchCrm::where('campaign_id', $campaign_id_curent)->first();

                        $crm_details['HatchCrm'] = $HatchCrm;
                        $status = $crm_api_file->HatchCrm($data_msg, $crm_details);
                    }
                    if (in_array(17, $compaign_crm_arr)) {
                        $salesforceCRM = SalesforceCrm::where('campaign_id', $campaign_id_curent)->first();

                        $crm_details['salesforceCRM'] = $salesforceCRM;
                        $status = $crm_api_file->SalesforceCrm($data_msg, $crm_details);
                    }
                    if (in_array(18, $compaign_crm_arr)) {
                        $Builder_Prime_CRM = Builder_Prime_CRM::where('campaign_id', $campaign_id_curent)->first();

                        $crm_details['Builder_Prime_CRM'] = $Builder_Prime_CRM;
                        $status = $crm_api_file->BuilderPrimeCRM($data_msg, $crm_details);
                    }
                    if (in_array(19, $compaign_crm_arr)) {
                        $zapier_crm = ZapierCrm::where('campaign_id', $campaign_id_curent)->first();

                        $crm_details['ZapierCrm'] = $zapier_crm;
                        $status = $crm_api_file->ZapierCRM($data_msg, $crm_details);
                    }
                    if (in_array(20, $compaign_crm_arr)) {
                        $set_shape_crm = SetShapeCrm::where('campaign_id', $campaign_id_curent)->first();

                        $crm_details['SetShapeCrm'] = $set_shape_crm;
                        $status = $crm_api_file->SetShapeCrm($data_msg, $crm_details);
                    }
                    if (in_array(21, $compaign_crm_arr)) {
                        $SetJobNimbusCrm = job_nimbus::where('campaign_id', $campaign_id_curent)->first();

                        $crm_details['SetJobNimbusCrm'] = $SetJobNimbusCrm;
                        $status = $crm_api_file->jobNimbusCrm($data_msg, $crm_details);
                    }
                    if (in_array(22, $compaign_crm_arr)) {
                        $set_sunbase_crm = Sunbase::where('campaign_id', $campaign_id_curent)->first();

                        $crm_details['set_sunbase_crm'] = $set_sunbase_crm;
                        $status = $crm_api_file->SunBaseDataCrm($data_msg, $crm_details);
                    }
                    if (in_array(0, $compaign_crm_arr) || empty($compaign_crm_arr)) {
                        $status = $crm_api_file->CustomCrm($data_msg, $crm_details, $user_id);
                    }
                }
            }
        }
        return $status;
        //end delivery methods function
    }

    public function post_and_pay($campaigns_sh_sorted, $campaigns_ex_sorted, $data_msg, $ping_post_arr, $TestLeadsCustomer_id, $first_one){
        //==================================================================================================================
        $leadCustomer_id = $data_msg['leadCustomer_id'];
        $LostReportStep4_1 = array();
        $LostReportStep4_2 = array();
        $sold_leadTo_campaign_toSendSms= array();
        $j = 0;
//        //Get Column Bid + Filtration + Sum
//        $campaigns_sh_col = $campaigns_sh_sorted->pluck('campaign_budget_bid_shared')->slice(0, 3)->sum();
//        $campaigns_ex_col = $campaigns_ex_sorted->pluck('campaign_budget_bid_exclusive')->slice(0,1)->sum();

        //Get Column Bid + Filtration + Sum
        $campaigns_sh_col = $campaigns_sh_sorted->pluck('campaign_budget_bid_shared')->slice(0, 4)->sum();
        $campaigns_ex_col = $campaigns_ex_sorted->pluck('campaign_budget_bid_exclusive')->slice(0,1)->sum();

        //Hows Larger Exclusive Or Share
        if( $campaigns_sh_col >= $campaigns_ex_col ){
            $listOFCampainDB_type = 'Shared';
            $listOFCampainDB = $campaigns_sh_sorted;
            $count_of_lead = 5;
        }
        else {
            $listOFCampainDB_type = 'Exclusive';
            $listOFCampainDB = $campaigns_ex_sorted;
            $count_of_lead = 2;
        }

        if( $first_one == 1 ){
            $campaigns_sh_array = array(
                'campaigns_sh_sorted' => $campaigns_sh_sorted,
                'campaigns_sh_col' => $campaigns_sh_col
            );
            $campaigns_ex_array = array(
                'campaigns_ex_sorted' => $campaigns_ex_sorted,
                'campaigns_ex_col' => $campaigns_ex_col
            );
            $listOFCampainDB_array = array(
                'listOFCampainDB' => $listOFCampainDB,
                'ping_post_arr' => $ping_post_arr
            );

            $TestLeadsCustomer = TestLeadsCustomer::find($TestLeadsCustomer_id);

            $TestLeadsCustomer->campaigns_sh = json_encode($campaigns_sh_array);
            $TestLeadsCustomer->campaigns_ex = json_encode($campaigns_ex_array);
            $TestLeadsCustomer->listOFCampainDB = json_encode($listOFCampainDB_array);

            $TestLeadsCustomer->save();
        }

        //Bayment And Send Msg/email/crm
        $curentDate = date('Y-m-d');
        $number_of_lead = 1;
        if( !empty($listOFCampainDB) ) {
            foreach ($listOFCampainDB as $campaign) {
                if ($number_of_lead >= $count_of_lead) {
                    break;
                }

                $campaign_id_curent = $campaign['campaign_id'];
                $user_id = $campaign['user_id'];
                $buyersEmail = $campaign['email'];
                $buyersusername = $campaign['username'];
                $buyerCreatedAt = $campaign['buyer_created_at'];
                $buyer_role_id = $campaign['role_id'];

                if ($listOFCampainDB_type == 'Exclusive') {
                    $budget_bid = $campaign['campaign_budget_bid_exclusive'];
                    $chrck_excluse_buyers = false;
                }
                else {
                    $budget_bid = $campaign['campaign_budget_bid_shared'];
                    $chrck_excluse_buyers = true;
                }

                //Check Exclude Buyers On Shared Only ==================================================================
                if( $chrck_excluse_buyers == true ){
                    $exclude_buyersA = DB::table('exclude_buyers')->where('user_idB', $user_id)->pluck('user_idA')->toArray();
                    $exclude_buyersB = DB::table('exclude_buyers')->where('user_idA', $user_id)->pluck('user_idB')->toArray();

                    $exclude_buyers = array_merge($exclude_buyersA, $exclude_buyersB);
                    $exclude_buyers = array_unique($exclude_buyers);

                    $check_exclude_buyers = CampaignsLeadsUsers::whereIn('user_id', $exclude_buyers)->where('lead_id', $leadCustomer_id)->first();
                    if( !empty($check_exclude_buyers) ){
                        continue;
                    }
                }
                //======================================================================================================

                $ping_post_data = array();
                if (!empty($ping_post_arr['campaign-' . $campaign_id_curent])) {
                    $ping_post_data = $ping_post_arr['campaign-' . $campaign_id_curent];
                }

                $is_ping_account = $campaign['is_ping_account'];
                $virtual_price = $campaign['virtual_price'];
                if ($is_ping_account == 1) {
                    $last_bid = $budget_bid;
                }
                else {
                    $last_bid = $budget_bid - $virtual_price;
                }

                $payment_type_method_status = $campaign['payment_type_method_status'];
                $payment_type_method_id = $campaign['payment_type_method_id'];
                $payment_type_method_limit = filter_var($campaign['payment_type_method_limit'], FILTER_SANITIZE_NUMBER_INT);

                //=========================Payment Here==========================
                $totalAmmountUser_value = ( !empty($campaign->total_amounts_value) ? $campaign->total_amounts_value : 0 );

                $data_msg['name'] = $buyersusername;
                $data_msg['ping_post_data'] = $ping_post_data;

                if (($totalAmmountUser_value >= $last_bid && $totalAmmountUser_value > 0 && $last_bid > 0)
                    || ($payment_type_method_status == 1 && in_array($payment_type_method_id, ['3', '4', '5', '6', '7', '8']))) {

                    //For Recording The Calls ==============================================================================
                    $EnableRecording = config('services.EnableRecording', false);
                    $is_recorded = 0;
                    if( $EnableRecording == true ){
                        $acceptRecord = $campaign->band_width_accept_record;
                        $BandWidth = new BandWidthController();
                        $buyerCreatedAt = \Carbon\Carbon::createFromFormat('Y-m-d H:s:i', $buyerCreatedAt);
                        $current_date = Carbon::now();
                        $diff_in_days = $buyerCreatedAt->diffInDays($current_date);
                        if ($acceptRecord == 1) {
                            if (empty($data_msg['newNumber'])) {
                                $area = substr($data_msg['LeadPhone'], 0, 3);
                                $NewNumber = $BandWidth->makeOrder($area);
                                if (!empty($NewNumber)) {
                                    LeadsCustomer::where('lead_id', $leadCustomer_id)->update([
                                        "band_width_order_id" => "",
                                        "band_width_new_number" => $NewNumber,
                                        "band_width_connect" => "0"
                                    ]);
                                    $data_msg['LeadPhone'] = $NewNumber;
                                    $data_msg['newNumber'] = $NewNumber;
                                    $is_recorded = 1;
                                } else {
                                    $data_msg['LeadPhone'] = $data_msg['oldNumber'];
                                }
                            } else {
                                $data_msg['LeadPhone'] = $data_msg['newNumber'];
                                $is_recorded = 1;
                            }
                        } else if ($acceptRecord == 0) {
                            if ($buyer_role_id == 3) {
                                if ($diff_in_days <= 30) {
                                    if (empty($data_msg['newNumber'])) {
                                        $area = substr($data_msg['LeadPhone'], 0, 3);
                                        $NewNumber = $BandWidth->makeOrder($area);
                                        if (!empty($NewNumber)) {
                                            LeadsCustomer::where('lead_id', $leadCustomer_id)->update([
                                                "band_width_order_id" => "",
                                                "band_width_new_number" => $NewNumber,
                                                "band_width_connect" => "0"
                                            ]);
                                            $data_msg['LeadPhone'] = $NewNumber;
                                            $data_msg['newNumber'] = $NewNumber;
                                            $is_recorded = 1;
                                        } else {
                                            $data_msg['LeadPhone'] = $data_msg['oldNumber'];
                                        }
                                    } else {
                                        $data_msg['LeadPhone'] = $data_msg['newNumber'];
                                        $is_recorded = 1;
                                    }
                                } else {
                                    $data_msg['LeadPhone'] = $data_msg['oldNumber'];
                                }
                            } else {
                                $data_msg['LeadPhone'] = $data_msg['oldNumber'];
                            }
                        } else {
                            $data_msg['LeadPhone'] = $data_msg['oldNumber'];
                        }
                    }
                    //For Recording The Calls ==============================================================================
                    //To send CRM data
                    $status_of_send_lead = $this->delivery_methods($data_msg, $campaign, $listOFCampainDB_type, $listOFCampainDB->count());

                    if ($status_of_send_lead != 0 && !empty($status_of_send_lead)) {
                        //Payment user
                        $this->payment_lead($user_id, $last_bid);
                        //=========================Insert Data===========================
                        $dataleads = array(
                            'user_id' => $user_id,
                            'campaign_id' => $campaign_id_curent,
                            'lead_id' => $leadCustomer_id,
                            'curent_date' => $curentDate,
                            'type_bid' => $listOFCampainDB_type,
                            'bid_budget' => $last_bid,
                            'transactionId' => $status_of_send_lead,
                            'is_recorded' => $is_recorded
                        );
                        $this->AddLeadsCampaignUser($dataleads);

                        $number_of_lead += 1;

                        // to store direct post campgian for sent SMS to lead
                        $buyer_mobile_number = $campaign['user_mobile_number'];
                        $buyer_business_name = $campaign['user_business_name'];

                        if($is_ping_account != 1){
                            $array = array(
                                "campgian_id"=> $campaign_id_curent,
                                "buyer_mobile_number"=> $buyer_mobile_number,
                                "buyer_business_name"=> $buyer_business_name
                            );

                            $sold_leadTo_campaign_toSendSms[$j++] =$array;
                        }
                    }
                    else {
                        $LostReportStep4_2[] = $campaign_id_curent;
                        if ($number_of_lead == 1) {
                            if ($listOFCampainDB_type == "Exclusive") {
                                $campaigns_ex_sorted->shift();
                            } else {
                                $campaigns_sh_sorted->shift();
                            }

                            $data_rsponse = array(
                                'success' => "false",
                                'type' => $listOFCampainDB_type,
                                'campaigns_sh_sorted' => $campaigns_sh_sorted,
                                'campaigns_ex_sorted' => $campaigns_ex_sorted,
                                'first_one' => 0,
                                'LostReportStep4_1' => $LostReportStep4_1,
                                'LostReportStep4_2' => $LostReportStep4_2,
                                "data_msg" => $data_msg
                            );
                            return $data_rsponse;
                        }
                    }
                    //==============================================================

                    // To check if User amount is low or less than $50
                    $totalAmmountUser_new_list = TotalAmount::where('user_id', $user_id)->first(['total_amounts_value']);
                    $totalAmmountUser_new = 0;
                    if (!empty($totalAmmountUser_new_list)) {
                        $totalAmmountUser_new = $totalAmmountUser_new_list->total_amounts_value;
                    }

                    $status = 0;
                    if( $is_ping_account != 1 ){
                        if($campaign->campaign_budget_bid_exclusive != 0 && $campaign->campaign_budget_bid_shared != 0){
                            if ($campaign->campaign_budget_bid_exclusive <= $campaign->campaign_budget_bid_shared) {
                                if ($campaign->campaign_budget_bid_exclusive > $totalAmmountUser_new) {
                                    $status = 1;
                                }
                            } else {
                                if ($campaign->campaign_budget_bid_shared > $totalAmmountUser_new) {
                                    $status = 1;
                                }
                            }
                        } else if($campaign->campaign_budget_bid_exclusive != 0 && $campaign->campaign_budget_bid_shared == 0){
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

                    if(( $payment_type_method_status == 1 && in_array($payment_type_method_id, ['3','4','5','6','7','8']) )){
                        if( $totalAmmountUser_new <= 0 ){
                            if( $payment_type_method_limit - abs($totalAmmountUser_new) <= 150 ){
                                $this->send_email_buyers_threshold($buyersEmail, $buyersusername, 2);
                            }
                        }
                    }
                    else {
                        if(  $campaign->user_auto_pay_status == 1 && $campaign->user_auto_pay_amount > 0 ){
                            if( $totalAmmountUser_new <= 50 ) {
                                $this->autopaycampaign($campaign->user_id, $campaign->user_auto_pay_amount);
                            }
                        } else {
                            if( $status == 1 ){
                                $this->send_email_buyers_threshold($buyersEmail, $buyersusername, 1);
                            }
                        }
                    }
                } else {
                    $LostReportStep4_1[] = $campaign_id_curent;
                    if ($number_of_lead == 0) {
                        if ($listOFCampainDB_type == "Exclusive") {
                            $campaigns_ex_sorted->shift();
                        } else {
                            $campaigns_sh_sorted->shift();
                        }

                        $data_rsponse = array(
                            'success' => "false",
                            'type' => $listOFCampainDB_type,
                            'campaigns_sh_sorted' => $campaigns_sh_sorted,
                            'campaigns_ex_sorted' => $campaigns_ex_sorted,
                            'first_one' => 0,
                            'LostReportStep4_1' => $LostReportStep4_1,
                            'LostReportStep4_2' => $LostReportStep4_2,
                            "data_msg" => $data_msg
                        );

                        return $data_rsponse;
                    }
                }
                //===============================================================
            }
        }

//        $data_rsponse = array(
//            'success' => "true",
//            'LostReportStep4_1' => $LostReportStep4_1,
//            'LostReportStep4_2' => $LostReportStep4_2,
//            "data_msg" => $data_msg
//        );

        if(!empty($sold_leadTo_campaign_toSendSms)){

            //SMS
            $datasmsMassage = "Dear ". $data_msg['leadName'] .",\n";
            $datasmsMassage .= "Please note that you may be contacted by one or more of these companies ,\n";

            foreach($sold_leadTo_campaign_toSendSms as $val){
                $datasmsMassage .= "Company's Name: " . $val['buyer_business_name'] . ",\n";
            }

            try {
                $BandWidth = new BandWidthController();
                if (!empty($data_msg['LeadPhone'])) {
                    $BandWidth->SendMessage(array('+1' . $data_msg['LeadPhone']),$datasmsMassage);
                }
            } catch (Exception $e) {

            }
        }

        $data_rsponse = array(
            'success' => "true",
            'LostReportStep4_1' => $LostReportStep4_1,
            'LostReportStep4_2' => $LostReportStep4_2,
            "price_exclusive" => $campaigns_ex_col,
            "price_shared" => $campaigns_sh_col,
            "data_msg" => $data_msg
        );


        return $data_rsponse;
        //==================================================================================================================
    }

    public function check_post_if_sold_and_send($lead_details_ping, $data_msg, $transaction_id){
        $curentDate = date('Y-m-d');
        $lead_service_id = $data_msg['service_id'];
        $postLeads_id = $data_msg['leadCustomer_id'];
        $if_sold_lead = 0;

        if( !empty($lead_details_ping) ){
            $campaigns_arr = json_decode($lead_details_ping->campaign_id_arr, true);
            if( !empty($campaigns_arr) ){
                foreach ( $campaigns_arr as $item) {
                    $campaign = DB::table('campaigns')
                        ->join('users', 'users.id', '=', 'campaigns.user_id')
                        ->leftJoin('total_amounts', 'users.id', '=', 'total_amounts.user_id')
                        ->join('campaign_time_delivery', 'campaign_time_delivery.campaign_id', '=', 'campaigns.campaign_id')
                        ->join('service__campaigns', 'service__campaigns.service_campaign_id', '=', 'campaigns.service_campaign_id')
                        ->where('campaigns.campaign_id', $item)
                        ->where('campaigns.campaign_visibility', 1)
                        ->where('campaigns.campaign_status_id', 1)
                        ->where('users.user_visibility', 1)
                        ->where('campaigns.service_campaign_id', $lead_service_id)
                        ->where('service__campaigns.service_is_active', 1)
                        ->where('campaigns.is_seller', 0)
                        ->first([
                            'campaigns.*', 'service__campaigns.service_campaign_name', 'users.id', 'total_amounts.total_amounts_value',
                            'users.username', 'users.email', 'users.user_phone_number', 'users.user_mobile_number',
                            'users.payment_type_method_id', 'users.payment_type_method_limit', 'users.payment_type_method_status',
                            'campaign_time_delivery.*', "users.created_at AS buyer_created_at", "users.role_id",
                            'users.user_auto_pay_status', 'users.user_auto_pay_amount'
                        ]);

                    if( !empty( $campaign ) ) {
                        $user_id = $campaign->user_id;
                        $buyersEmail = $campaign->email;
                        $buyersusername = $campaign->username;
                        $service_campaign_name = $campaign->service_campaign_name;
                        $buyerCreatedAt = $campaign->buyer_created_at;
                        $buyer_role_id = $campaign->role_id;

                        $ping_post_arr = json_decode($lead_details_ping->ping_post_data_arr, true);

                        if ($lead_details_ping->lead_bid_type == 'Exclusive') {
                            $budget_bid = filter_var($campaign->campaign_budget_bid_exclusive, FILTER_SANITIZE_NUMBER_INT);
                        } else {
                            $budget_bid = filter_var($campaign->campaign_budget_bid_shared, FILTER_SANITIZE_NUMBER_INT);
                        }

                        $ping_post_data = array();
                        if (!empty($ping_post_arr['campaign-'.$item])) {
                            $ping_post_data = $ping_post_arr['campaign-'.$item];
                        }

                        $is_ping_account = $campaign->is_ping_account;
                        $virtual_price = $campaign->virtual_price;
                        if( $is_ping_account == 1 ){
                            $last_bid = $ping_post_data['Payout'];
                        } else {
                            $last_bid = $budget_bid - $virtual_price;
                        }

                        $payment_type_method_status = $campaign->payment_type_method_status;
                        $payment_type_method_id = $campaign->payment_type_method_id;
                        $payment_type_method_limit = filter_var($campaign->payment_type_method_limit, FILTER_SANITIZE_NUMBER_INT);

                        //=========================Payment Here==========================
                        $totalAmmountUser_value = ( !empty($campaign->total_amounts_value) ? $campaign->total_amounts_value : 0 );

                        if (($totalAmmountUser_value >= $last_bid && $totalAmmountUser_value > 0 && $last_bid > 0)
                            || ($payment_type_method_status == 1 && in_array($payment_type_method_id, ['3', '4', '5', '6', '7', '8']))) {

                            $data_msg['name'] = $buyersusername;
                            $data_msg['LeadService'] = $service_campaign_name;
                            $data_msg['ping_post_data'] = $ping_post_data;

                            //For Recording The Calls ==============================================================================
                            $EnableRecording = config('services.EnableRecording', false);
                            $is_recorded = 0;
                            if( $EnableRecording == true ) {
                                $acceptRecord = $campaign->band_width_accept_record;
                                $BandWidth = new BandWidthController();
                                $buyerCreatedAt = \Carbon\Carbon::createFromFormat('Y-m-d H:s:i', $buyerCreatedAt);
                                $current_date = Carbon::now();
                                $diff_in_days = $buyerCreatedAt->diffInDays($current_date);

                                if ($acceptRecord == 1) {
                                    if (empty($data_msg['newNumber'])) {
                                        $area = substr($data_msg['LeadPhone'], 0, 3);
                                        $NewNumber = $BandWidth->makeOrder($area);
                                        if (!empty($NewNumber)) {
                                            LeadsCustomer::where('lead_id', $postLeads_id)->update([
                                                "band_width_order_id" => "",
                                                "band_width_new_number" => $NewNumber,
                                                "band_width_connect" => ""
                                            ]);
                                            $data_msg['LeadPhone'] = $NewNumber;
                                            $data_msg['newNumber'] = $NewNumber;
                                            $is_recorded = 1;
                                        } else {
                                            $data_msg['LeadPhone'] = $data_msg['oldNumber'];
                                        }
                                    } else {
                                        $data_msg['LeadPhone'] = $data_msg['newNumber'];
                                        $is_recorded = 1;
                                    }
                                } else if ($acceptRecord == 0) {
                                    if ($buyer_role_id == 3) {
                                        if ($diff_in_days <= 30) {
                                            if (empty($data_msg['newNumber'])) {
                                                $area = substr($data_msg['LeadPhone'], 0, 3);
                                                $NewNumber = $BandWidth->makeOrder($area);
                                                if (!empty($NewNumber)) {
                                                    LeadsCustomer::where('lead_id', $postLeads_id)->update([
                                                        "band_width_order_id" => "",
                                                        "band_width_new_number" => $NewNumber,
                                                        "band_width_connect" => ""
                                                    ]);
                                                    $data_msg['LeadPhone'] = $NewNumber;
                                                    $data_msg['newNumber'] = $NewNumber;
                                                    $is_recorded = 1;
                                                } else {
                                                    $data_msg['LeadPhone'] = $data_msg['oldNumber'];
                                                }
                                            } else {
                                                $data_msg['LeadPhone'] = $data_msg['newNumber'];
                                                $is_recorded = 1;
                                            }
                                        } else {
                                            $data_msg['LeadPhone'] = $data_msg['oldNumber'];
                                        }
                                    } else {
                                        $data_msg['LeadPhone'] = $data_msg['oldNumber'];
                                    }
                                } else {
                                    $data_msg['LeadPhone'] = $data_msg['oldNumber'];
                                }
                            }
                            //For Recording The Calls ==============================================================================

                            $status_of_send_lead = $this->delivery_methods($data_msg, $campaign, $lead_details_ping->lead_bid_type, 1);

                            if( $status_of_send_lead != 0 && !empty($status_of_send_lead) ) {
                                //=========================Insert Data===========================
                                //Payment user
                                $this->payment_lead($user_id, $last_bid);
                                $if_sold_lead += $last_bid;
                                $dataleads = array(
                                    'user_id' => $user_id,
                                    'campaign_id' => $item,
                                    'lead_id' => $postLeads_id,
                                    'curent_date' => $curentDate,
                                    'type_bid' => $lead_details_ping->lead_bid_type,
                                    'bid_budget' => $last_bid,
                                    'transactionId' => $status_of_send_lead,
                                    'is_recorded' => $is_recorded,
                                    'vendor_id' => $lead_details_ping->vendor_id
                                );
                                $leadsCustomerCampaign_id = $this->AddLeadsCampaignUser($dataleads);

                                // To check if User amount is low or less than $50
                                $totalAmmountUser_new = TotalAmount::where('user_id', $user_id)
                                    ->first(['total_amounts_value']);
                                $totalAmmountUser_new = $totalAmmountUser_new->total_amounts_value;

                                $status = 0;
                                if( $is_ping_account != 1 ){
                                    if($campaign->campaign_budget_bid_exclusive != 0 && $campaign->campaign_budget_bid_shared != 0){
                                        if ($campaign->campaign_budget_bid_exclusive <= $campaign->campaign_budget_bid_shared) {
                                            if ($campaign->campaign_budget_bid_exclusive > $totalAmmountUser_new) {
                                                $status = 1;
                                            }
                                        } else {
                                            if ($campaign->campaign_budget_bid_shared > $totalAmmountUser_new) {
                                                $status = 1;
                                            }
                                        }
                                    } else if($campaign->campaign_budget_bid_exclusive != 0 && $campaign->campaign_budget_bid_shared == 0){
                                        if ($campaign->campaign_budget_bid_exclusive > $totalAmmountUser_new) {
                                            $status = 1;
                                        }
                                    } else {
                                        if ($campaign->campaign_budget_bid_shared > $totalAmmountUser_new) {
                                            $status = 1;
                                        }
                                    }
                                }
                                else {
                                    if ($totalAmmountUser_new <= 150) {
                                        $status = 1;
                                    }
                                }

                                if(( $payment_type_method_status == 1 && in_array($payment_type_method_id, ['3','4','5','6','7','8']) )){
                                    if( $totalAmmountUser_new <= 0 ){
                                        if( $payment_type_method_limit - abs($totalAmmountUser_new) <= 150 ){
                                            $this->send_email_buyers_threshold($buyersEmail, $buyersusername, 2);
                                        }
                                    }
                                } else {
                                    if( $campaign->user_auto_pay_status == 1 && $campaign->user_auto_pay_amount > 0 ){
                                        if( $totalAmmountUser_new <= 50 ) {
                                            $this->autopaycampaign($campaign->user_id, $campaign->user_auto_pay_amount);
                                        }
                                    } else {
                                        if( $status == 1 ){
                                            $this->send_email_buyers_threshold($buyersEmail, $buyersusername, 1);
                                        }
                                    }
                                }
                            }
                            //===============================================================
                        }
                    }
                }

                LeadsCustomer::where('lead_id', $postLeads_id)->update([
                    "response_data" => 'Lead Accepted'
                ]);

                if( $if_sold_lead >= $lead_details_ping->price ){
                    $this->seller_pay($data_msg['seller_id'], $lead_details_ping->price);

                    $response_code = array(
                        'response_code' => 'true',
                        'message' => 'Lead Accepted',
                        'error' => '',
                        'transaction_id' => $transaction_id,
                        'price' => $lead_details_ping->price
                    );

                    return $response_code;
                }
            }
        }

        LeadsCustomer::where('lead_id', $postLeads_id)->update([
            "response_data" => 'All buyers have rejected this lead'
        ]);

        $response_code = array(
            'response_code' => 'false',
            'message' => 'Reject',
            'error' => 'Duplicated Lead',
            'transaction_id' => '',
            'price' => '0.00'
        );

        return $response_code;
    }

    public function post_and_pay_direct($campaigns_sh_sorted, $campaigns_ex_sorted, $data_msg, $ping_post_arr){
        //Get Column Bid + Filtration + Sum
        $campaigns_sh_col = $campaigns_sh_sorted->pluck('campaign_budget_bid_shared')->slice(0, 3)->sum();
        $campaigns_ex_col = $campaigns_ex_sorted->pluck('campaign_budget_bid_exclusive')->slice(0,1)->sum();

        //How's Larger Exclusive Or Share
        if( $campaigns_sh_col >= $campaigns_ex_col ){
            $listOFCampainDB_type = 'Shared';
            $listOFCampainDB = $campaigns_sh_sorted;
            $count_of_lead = 5;
        }
        else {
            $listOFCampainDB_type = 'Exclusive';
            $listOFCampainDB = $campaigns_ex_sorted;
            $count_of_lead = 2;
        }

        //Bayment And Send Msg/email/crm
        $curentDate = date('Y-m-d');
        $leadCustomer_id = $data_msg['leadCustomer_id'];
        $lead_total_pid = (!empty($data_msg['lead_total_pid']) ? $data_msg['lead_total_pid'] : 0);

        $number_of_lead = 1;
        if( !empty($listOFCampainDB) ) {
            foreach ($listOFCampainDB as $campaign) {
                if ($number_of_lead >= $count_of_lead) {
                    break;
                }

                $campaign_id_curent = $campaign['campaign_id'];
                $user_id = $campaign['user_id'];
                $buyersEmail = $campaign['email'];
                $buyersusername = $campaign['username'];
                $buyerCreatedAt = $campaign['buyer_created_at'];
                $buyer_role_id = $campaign['role_id'];

                if ($listOFCampainDB_type == 'Exclusive') {
                    $budget_bid = $campaign['campaign_budget_bid_exclusive'];
                    $chrck_excluse_buyers = false;
                }
                else {
                    $budget_bid = $campaign['campaign_budget_bid_shared'];
                    $chrck_excluse_buyers = true;
                }

                //Check Exclude Buyers On Shared Only ==================================================================
                if( $chrck_excluse_buyers == true ){
                    $exclude_buyersA = DB::table('exclude_buyers')->where('user_idB', $user_id)->pluck('user_idA')->toArray();
                    $exclude_buyersB = DB::table('exclude_buyers')->where('user_idA', $user_id)->pluck('user_idB')->toArray();

                    $exclude_buyers = array_merge($exclude_buyersA, $exclude_buyersB);
                    $exclude_buyers = array_unique($exclude_buyers);

                    $check_exclude_buyers = CampaignsLeadsUsers::whereIn('user_id', $exclude_buyers)->where('lead_id', $leadCustomer_id)->first();
                    if( !empty($check_exclude_buyers) ){
                        continue;
                    }
                }
                //======================================================================================================

                $ping_post_data = array();
                if (!empty($ping_post_arr['campaign-' . $campaign_id_curent])) {
                    $ping_post_data = $ping_post_arr['campaign-' . $campaign_id_curent];
                }

                $is_ping_account = $campaign['is_ping_account'];
                $virtual_price = $campaign['virtual_price'];
                if ($is_ping_account == 1) {
                    $last_bid = $budget_bid;
                } else {
                    $last_bid = $budget_bid - $virtual_price;
                }

                $payment_type_method_status = $campaign['payment_type_method_status'];
                $payment_type_method_id = $campaign['payment_type_method_id'];
                $payment_type_method_limit = filter_var($campaign['payment_type_method_limit'], FILTER_SANITIZE_NUMBER_INT);

                //=========================Payment Here==========================
                $totalAmmountUser_value = ( !empty($campaign->total_amounts_value) ? $campaign->total_amounts_value : 0 );

                $data_msg['name'] = $buyersusername;
                $data_msg['ping_post_data'] = $ping_post_data;

                if (($totalAmmountUser_value >= $last_bid && $totalAmmountUser_value > 0 && $last_bid > 0)
                    || ($payment_type_method_status == 1 && in_array($payment_type_method_id, ['3', '4', '5', '6', '7', '8']))) {

                    //For Recording The Calls ==============================================================================
                    $EnableRecording = config('services.EnableRecording', false);
                    $is_recorded = 0;
                    if( $EnableRecording == true ){
                        $acceptRecord = $campaign->band_width_accept_record;
                        $BandWidth = new BandWidthController();
                        $buyerCreatedAt = \Carbon\Carbon::createFromFormat('Y-m-d H:s:i', $buyerCreatedAt);
                        $current_date = Carbon::now();
                        $diff_in_days = $buyerCreatedAt->diffInDays($current_date);
                        if ($acceptRecord == 1) {
                            if (empty($data_msg['newNumber'])) {
                                $area = substr($data_msg['LeadPhone'], 0, 3);
                                $NewNumber = $BandWidth->makeOrder($area);
                                if (!empty($NewNumber)) {
                                    LeadsCustomer::where('lead_id', $leadCustomer_id)->update([
                                        "band_width_order_id" => "",
                                        "band_width_new_number" => $NewNumber,
                                        "band_width_connect" => "0"
                                    ]);
                                    $data_msg['LeadPhone'] = $NewNumber;
                                    $data_msg['newNumber'] = $NewNumber;
                                    $is_recorded = 1;
                                } else {
                                    $data_msg['LeadPhone'] = $data_msg['oldNumber'];
                                }
                            } else {
                                $data_msg['LeadPhone'] = $data_msg['newNumber'];
                                $is_recorded = 1;
                            }
                        } else if ($acceptRecord == 0) {
                            if ($buyer_role_id == 3) {
                                if ($diff_in_days <= 30) {
                                    if (empty($data_msg['newNumber'])) {
                                        $area = substr($data_msg['LeadPhone'], 0, 3);
                                        $NewNumber = $BandWidth->makeOrder($area);
                                        if (!empty($NewNumber)) {
                                            LeadsCustomer::where('lead_id', $leadCustomer_id)->update([
                                                "band_width_order_id" => "",
                                                "band_width_new_number" => $NewNumber,
                                                "band_width_connect" => "0"
                                            ]);
                                            $data_msg['LeadPhone'] = $NewNumber;
                                            $data_msg['newNumber'] = $NewNumber;
                                            $is_recorded = 1;
                                        } else {
                                            $data_msg['LeadPhone'] = $data_msg['oldNumber'];
                                        }
                                    } else {
                                        $data_msg['LeadPhone'] = $data_msg['newNumber'];
                                        $is_recorded = 1;
                                    }
                                } else {
                                    $data_msg['LeadPhone'] = $data_msg['oldNumber'];
                                }
                            } else {
                                $data_msg['LeadPhone'] = $data_msg['oldNumber'];
                            }
                        } else {
                            $data_msg['LeadPhone'] = $data_msg['oldNumber'];
                        }
                    }
                    //For Recording The Calls ==============================================================================
                    //To send CRM data
                    $status_of_send_lead = $this->delivery_methods($data_msg, $campaign, $listOFCampainDB_type, $listOFCampainDB->count());

                    if ($status_of_send_lead != 0 && !empty($status_of_send_lead)) {
                        //Payment user
                        $this->payment_lead($user_id, $last_bid);
                        //=========================Insert Data===========================
                        $dataleads = array(
                            'user_id' => $user_id,
                            'campaign_id' => $campaign_id_curent,
                            'lead_id' => $leadCustomer_id,
                            'curent_date' => $curentDate,
                            'type_bid' => $listOFCampainDB_type,
                            'bid_budget' => $last_bid,
                            'transactionId' => $status_of_send_lead,
                            'is_recorded' => $is_recorded
                        );
                        $this->AddLeadsCampaignUser($dataleads);

                        $lead_total_pid += $last_bid;
                        $number_of_lead += 1;
                    }
                    else {
                        if ($number_of_lead == 1) {
                            if ($listOFCampainDB_type == "Exclusive") {
                                $campaigns_ex_sorted->shift();
                            } else {
                                $campaigns_sh_sorted->shift();
                            }

                            $data_rsponse = array(
                                'success' => "false",
                                'type' => $listOFCampainDB_type,
                                'campaigns_sh_sorted' => $campaigns_sh_sorted,
                                'campaigns_ex_sorted' => $campaigns_ex_sorted,
                                "data_msg" => $data_msg,
                                "lead_total_pid" => $lead_total_pid
                            );
                            return $data_rsponse;
                        }
                    }
                    //==============================================================

                    // To check if User amount is low or less than $50
                    $totalAmmountUser_new_list = TotalAmount::where('user_id', $user_id)->first(['total_amounts_value']);
                    $totalAmmountUser_new = 0;
                    if (!empty($totalAmmountUser_new_list)) {
                        $totalAmmountUser_new = $totalAmmountUser_new_list->total_amounts_value;
                    }

                    $status = 0;
                    if( $is_ping_account != 1 ){
                        if($campaign->campaign_budget_bid_exclusive != 0 && $campaign->campaign_budget_bid_shared != 0){
                            if ($campaign->campaign_budget_bid_exclusive <= $campaign->campaign_budget_bid_shared) {
                                if ($campaign->campaign_budget_bid_exclusive > $totalAmmountUser_new) {
                                    $status = 1;
                                }
                            } else {
                                if ($campaign->campaign_budget_bid_shared > $totalAmmountUser_new) {
                                    $status = 1;
                                }
                            }
                        } else if($campaign->campaign_budget_bid_exclusive != 0 && $campaign->campaign_budget_bid_shared == 0){
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

                    if(( $payment_type_method_status == 1 && in_array($payment_type_method_id, ['3','4','5','6','7','8']) )){
                        if( $totalAmmountUser_new <= 0 ){
                            if( $payment_type_method_limit - abs($totalAmmountUser_new) <= 150 ){
                                $this->send_email_buyers_threshold($buyersEmail, $buyersusername, 2);
                            }
                        }
                    }
                    else {
                        if(  $campaign->user_auto_pay_status == 1 && $campaign->user_auto_pay_amount > 0 ){
                            if( $totalAmmountUser_new <= 50 ) {
                                $this->autopaycampaign($campaign->user_id, $campaign->user_auto_pay_amount);
                            }
                        } else {
                            if( $status == 1 ){
                                $this->send_email_buyers_threshold($buyersEmail, $buyersusername, 1);
                            }
                        }
                    }
                } else {
                    if ($number_of_lead == 0) {
                        if ($listOFCampainDB_type == "Exclusive") {
                            $campaigns_ex_sorted->shift();
                        } else {
                            $campaigns_sh_sorted->shift();
                        }

                        $data_rsponse = array(
                            'success' => "false",
                            'type' => $listOFCampainDB_type,
                            'campaigns_sh_sorted' => $campaigns_sh_sorted,
                            'campaigns_ex_sorted' => $campaigns_ex_sorted,
                            "data_msg" => $data_msg,
                            "lead_total_pid" => $lead_total_pid
                        );

                        return $data_rsponse;
                    }
                }
                //===============================================================
            }
        }

        $data_rsponse = array(
            'success' => "true",
            "data_msg" => $data_msg,
            "lead_total_pid" => $lead_total_pid
        );
        return $data_rsponse;
        //==================================================================================================================
    }
}
