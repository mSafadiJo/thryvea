<?php

namespace App\Services;

use App\Campaign;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ServiceQueries {

    public function service_queries_new_way($service, $LeaddataIDs, $type, $is_ping,  $address, $lead_source = "All Source", $is_forms = 0, $sub_id='', $OriginalURL=''){
        $projectnatureArrayData = array();
        if( empty($LeaddataIDs['project_nature']) ){
            $projectnatureArrayData[] = 1;
            $projectnatureArrayData[] = 2;
            $projectnatureArrayData[] = 3;
        }
        else if( $LeaddataIDs['project_nature'] != 3 ){
            $projectnatureArrayData[] = 1;
            $projectnatureArrayData[] = 2;
        }
        else {
            $projectnatureArrayData[] = 3;
        }

        $ownershipArrayData = array();
        if( !isset($LeaddataIDs['homeOwn']) || $LeaddataIDs['homeOwn'] == '3' ){
            $ownershipArrayData[] = 0;
            $ownershipArrayData[] = 1;
        }
        else {
            $ownershipArrayData[] = $LeaddataIDs['homeOwn'];
        }

        $property_typeArrayData = array();
        if( empty($LeaddataIDs['property_type']) ){
            if( !empty($LeaddataIDs['property_type_roofing']) ){
                if( $LeaddataIDs['property_type_roofing'] == 1 ){
                    $property_typeArrayData[] = 1;
                    $property_typeArrayData[] = 2;
                } else {
                    $property_typeArrayData[] = 3;
                }
            } else {
                $property_typeArrayData[] = 1;
                $property_typeArrayData[] = 2;
                $property_typeArrayData[] = 3;
            }
        }
        else {
            $property_typeArrayData[] = $LeaddataIDs['property_type'];
        }

        $sub_id = preg_replace('/\s+/', '', strtolower($sub_id));
        if(str_contains($OriginalURL, "https")) {
            $host = parse_url($OriginalURL, PHP_URL_HOST);
        } else {
            $host = parse_url('//'.$OriginalURL.'/', PHP_URL_HOST);
        }
        $domain = strtolower(str_replace($host, 'www.',''));

        $campaigns = Campaign::join('users', 'users.id', '=', 'campaigns.user_id')
            ->leftJoin('total_amounts', 'users.id', '=', 'total_amounts.user_id')
            ->join('campaign_time_delivery', 'campaign_time_delivery.campaign_id', '=', 'campaigns.campaign_id')
            ->leftjoin('exclude_sellers_campaigns', 'exclude_sellers_campaigns.campaign_id', '=', 'campaigns.campaign_id')
            ->join('service__campaigns', 'service__campaigns.service_campaign_id', '=', 'campaigns.service_campaign_id')
            ->join('campaign_target_area', 'campaign_target_area.campaign_id', '=', 'campaigns.campaign_id');

        $matchingCampaigns = new AllServicesQuestions();

        $campaigns = $matchingCampaigns->campaignsMatchingWithQuestions($campaigns, $service, $projectnatureArrayData, $ownershipArrayData, $property_typeArrayData, $LeaddataIDs);

        $zipcode_id = $address['zipcode_id'];
        $city_id = $address['city_id'];
        $county_id = $address['county_id'];
        $state_id = $address['state_id'];
        $campaigns = $campaigns->where(function($query) use($zipcode_id, $city_id, $county_id, $state_id){
            $query->whereJsonContains('campaign_target_area.zipcode_id', (int)$zipcode_id);
            $query->OrwhereJsonContains('campaign_target_area.city_id', "$city_id");
            $query->OrwhereJsonContains('campaign_target_area.county_id', "$county_id");
            $query->OrwhereJsonContains('campaign_target_area.state_id', "$state_id");
        })
            ->whereJsonDoesntContain('campaign_target_area.city_ex_id', "$city_id")
            ->whereJsonDoesntContain('campaign_target_area.county_ex_id', "$county_id")
            ->whereJsonDoesntContain('campaign_target_area.zipcode_ex_id', (int)$zipcode_id)
            ->where(function ($query) use($sub_id){
                $query->whereNull('campaigns.exclude_sources');
                $query->OrWhereJsonDoesntContain('campaigns.exclude_sources', "$sub_id");
            })
            ->where(function ($query) use($domain){
                $query->whereNull('campaigns.exclude_url');
                $query->OrWhereJsonDoesntContain('campaigns.exclude_url', "$domain");
            })
            ->where('campaigns.campaign_visibility', 1)
            ->where('campaigns.campaign_status_id', 1)
            ->where('users.user_visibility', 1)
            ->whereIn('users.role_id', ['3', '4', '6'])
            ->where('campaigns.service_campaign_id', $service)
            ->where('service__campaigns.service_is_active', 1)
            ->where('campaigns.is_seller', 0)
            ->where('campaigns.is_ping_account', $is_ping);

        //For Marketing Filtration =============================================
        $all_source = "All Source";
        $campaigns->where(function($query) use($lead_source, $all_source){
            $query->where('campaigns.lead_source', "");
            $query->OrWhere('campaigns.lead_source',"[]");
            $query->OrwhereJsonContains('campaigns.lead_source', "$all_source");
            $query->OrwhereJsonContains('campaigns.lead_source', "$lead_source");
        });
        //======================================================================

        if( $is_forms == 1 ){
            $campaigns->whereIn('campaigns.campaign_Type', array(1, 7));
        } elseif( $is_forms == 2 ){
            $campaigns->where('campaigns.campaign_Type', 8);
        } else {
            $campaigns->where('campaigns.campaign_Type', 1);
        }

        //Check if Lead Seconds service and if the campaign contains Seconds service
        if( !empty($LeaddataIDs['is_sec_service']) ){
            if( $LeaddataIDs['is_sec_service'] == 1 ){
                $campaigns->where('campaigns.sec_service_accept', 1);
            }
        }

        if( $type == 1 ){
            $campaigns->whereJsonContains('campaigns.custom_paid_campaign_id', "1");
            $campaigns->where("campaigns.campaign_budget_bid_exclusive", '<>', 0);
            $campaigns->orderBy("campaigns.campaign_budget_bid_exclusive", 'DESC');
        }
        else if( $type == 2 ){
            $campaigns->whereJsonContains('campaigns.custom_paid_campaign_id', "2");
            if($is_forms != 2){
                $campaigns->where("campaigns.campaign_budget_bid_shared", '<>', 0);
            }
            $campaigns->orderBy("campaigns.campaign_budget_bid_shared", 'DESC');
        }

        //For check if Brands Leads =======================================
        $is_brand_lead = ( !empty($LeaddataIDs['brand_buyer_id']) ? 1 : 0 );
        $campaigns->where('campaigns.is_branded_camp', $is_brand_lead);
        if( $is_brand_lead == 1 && !empty($LeaddataIDs['brand_buyer_id']) ){
            $campaigns->where('campaigns.user_id', $LeaddataIDs['brand_buyer_id']);
        }
        //=================================================================

        $seller_id = "";
        if(!empty($LeaddataIDs['seller_id'])){
            $seller_id = $LeaddataIDs['seller_id'];
            $campaigns->where(function ($query) use($seller_id){
                $query->whereNull('campaigns.exclude_include_type');
                $query->OrWhere(function($query) use($seller_id){
                    $query->where('campaigns.exclude_include_type', "Exclude");
                    $query->whereJsonDoesntContain('campaigns.exclude_include_campaigns', "$seller_id");
                });
                $query->OrWhere(function($query) use($seller_id){
                    $query->where('campaigns.exclude_include_type', "Include");
                    $query->whereJsonContains('campaigns.exclude_include_campaigns', "$seller_id");
                });
            });
        }

        if( !empty($LeaddataIDs['if_seller_api']) ){
            if( $LeaddataIDs['if_seller_api'] == 1 ){
                if( !empty($seller_id) ){
                    $campaigns->where("campaigns.user_id", '<>', $seller_id);
                }
//                if( !empty($LeaddataIDs['hash_legs_sold']['phones']) ){
//                    if( is_array($LeaddataIDs['hash_legs_sold']['phones']) ){
//                        $campaigns->whereNotIn("users.hash_phone_number", $LeaddataIDs['hash_legs_sold']['phones'])
//                            ->whereNotIn("users.hash_mobile_number", $LeaddataIDs['hash_legs_sold']['phones']);
//                    }
//                }
            }
        }

        $campaigns = $campaigns->get([
            'campaigns.*', 'service__campaigns.service_campaign_name', 'users.id', 'total_amounts.total_amounts_value',
            'users.username', 'users.email', 'users.user_phone_number', 'users.user_mobile_number',
            'users.payment_type_method_id', 'users.payment_type_method_limit', 'users.payment_type_method_status',
            'campaign_time_delivery.*', "users.created_at AS buyer_created_at", "users.role_id",
            'users.user_auto_pay_status', 'users.user_auto_pay_amount'
            //'exclude_sellers_campaigns.types AS exclude_sellers_campaigns_type',
            //DB::raw("GROUP_CONCAT(exclude_sellers_campaigns.seller_id) AS exclude_sellers_campaigns_seller_id"),
        ])->unique(['campaign_id']);

        return $campaigns;
    }

    public function check_if_match_campaign_callTools($LeaddataIDs, $service, $address, $campaign_id){
        //Add With Campaign
        $projectnatureArrayData = array();
        if( empty($LeaddataIDs['project_nature']) ){
            $projectnatureArrayData[] = 1;
            $projectnatureArrayData[] = 2;
            $projectnatureArrayData[] = 3;
        }
        else if( $LeaddataIDs['project_nature'] != 3 ){
            $projectnatureArrayData[] = 1;
            $projectnatureArrayData[] = 2;
        }
        else {
            $projectnatureArrayData[] = 3;
        }

        $ownershipArrayData = array();
        if( !isset($LeaddataIDs['homeOwn']) || $LeaddataIDs['homeOwn'] == '3' ){
            $ownershipArrayData[] = 0;
            $ownershipArrayData[] = 1;
        }
        else {
            $ownershipArrayData[] = $LeaddataIDs['homeOwn'];
        }

        $property_typeArrayData = array();
        if( empty($LeaddataIDs['property_type']) ){
            if( !empty($LeaddataIDs['property_type_roofing']) ){
                if( $LeaddataIDs['property_type_roofing'] == 1 ){
                    $property_typeArrayData[] = 1;
                    $property_typeArrayData[] = 2;
                } else {
                    $property_typeArrayData[] = 3;
                }
            } else {
                $property_typeArrayData[] = 1;
                $property_typeArrayData[] = 2;
                $property_typeArrayData[] = 3;
            }
        } else {
            $property_typeArrayData[] = $LeaddataIDs['property_type'];
        }

        $campaigns = DB::table('campaigns')
            ->join('users', 'users.id', '=', 'campaigns.user_id')
            ->leftJoin('total_amounts', 'users.id', '=', 'total_amounts.user_id')
            ->join('campaign_time_delivery', 'campaign_time_delivery.campaign_id', '=', 'campaigns.campaign_id')
            ->join('service__campaigns', 'service__campaigns.service_campaign_id', '=', 'campaigns.service_campaign_id')
            ->join('campaign_target_area', 'campaign_target_area.campaign_id', '=', 'campaigns.campaign_id');

        $matchingCampaigns = new AllServicesQuestions();

        $campaigns = $matchingCampaigns->campaignsMatchingWithQuestions($campaigns, $service, $projectnatureArrayData, $ownershipArrayData, $property_typeArrayData, $LeaddataIDs);

        $zipcode_id = $address['zipcode_id'];
        $city_id = $address['city_id'];
        $county_id = $address['county_id'];
        $state_id = $address['state_id'];
        $campaigns = $campaigns->where('campaigns.campaign_id', $campaign_id)
            ->where(function($query) use($zipcode_id, $city_id, $county_id, $state_id){
                $query->whereJsonContains('campaign_target_area.zipcode_id', (int)$zipcode_id);
                $query->OrwhereJsonContains('campaign_target_area.city_id', "$city_id");
                $query->OrwhereJsonContains('campaign_target_area.county_id', "$county_id");
                $query->OrwhereJsonContains('campaign_target_area.state_id', "$state_id");
            })
            ->whereJsonDoesntContain('campaign_target_area.city_ex_id', "$city_id")
            ->whereJsonDoesntContain('campaign_target_area.county_ex_id', "$county_id")
            ->whereJsonDoesntContain('campaign_target_area.zipcode_ex_id', (int)$zipcode_id)
            ->where('campaigns.campaign_visibility', 1)
            ->where('campaigns.campaign_status_id', 1)
            ->where('campaigns.service_campaign_id', $service)
            ->where('users.user_visibility', 1)
            ->whereIn('users.role_id', ['3', '4', '6'])
            ->where('service__campaigns.service_is_active', 1)
            ->where('campaigns.is_seller', 0)
            ->first([
                'campaigns.*', 'service__campaigns.service_campaign_name', 'users.id', 'total_amounts.total_amounts_value',
                'users.username', 'users.email', 'users.user_phone_number', 'users.user_mobile_number',
                'users.payment_type_method_id', 'users.payment_type_method_limit', 'users.payment_type_method_status',
                'campaign_time_delivery.*', "users.created_at AS buyer_created_at", "users.role_id",
                'users.user_auto_pay_status', 'users.user_auto_pay_amount'
            ]);

        return $campaigns;
    }

    public function service_queries($service, $LeaddataIDs, $lastCampainInArea, $type, $is_ping, $lead_source = "All Source", $is_forms = 0){
        $projectnatureArrayData = array();
        if( empty($LeaddataIDs['project_nature']) ){
            $projectnatureArrayData[] = 1;
            $projectnatureArrayData[] = 2;
            $projectnatureArrayData[] = 3;
        } else if( $LeaddataIDs['project_nature'] != 3 ){
            $projectnatureArrayData[] = 1;
            $projectnatureArrayData[] = 2;
        } else {
            $projectnatureArrayData[] = 3;
        }

        $ownershipArrayData = array();
        if( !isset($LeaddataIDs['homeOwn']) || $LeaddataIDs['homeOwn'] == '3' ){
            $ownershipArrayData[] = 0;
            $ownershipArrayData[] = 1;
        } else {
            $ownershipArrayData[] = $LeaddataIDs['homeOwn'];
        }

        $property_typeArrayData = array();
        if( empty($LeaddataIDs['property_type']) ){
            if( !empty($LeaddataIDs['property_type_roofing']) ){
                if( $LeaddataIDs['property_type_roofing'] == 1 ){
                    $property_typeArrayData[] = 1;
                    $property_typeArrayData[] = 2;
                } else {
                    $property_typeArrayData[] = 3;
                }
            } else {
                $property_typeArrayData[] = 1;
                $property_typeArrayData[] = 2;
                $property_typeArrayData[] = 3;
            }
        } else {
            $property_typeArrayData[] = $LeaddataIDs['property_type'];
        }

        $campaigns = Campaign::join('users', 'users.id', '=', 'campaigns.user_id')
            ->leftJoin('total_amounts', 'users.id', '=', 'total_amounts.user_id')
            ->join('campaign_time_delivery', 'campaign_time_delivery.campaign_id', '=', 'campaigns.campaign_id')
            ->join('service__campaigns', 'service__campaigns.service_campaign_id', '=', 'campaigns.service_campaign_id')
            ->join('home_owned_campaign', function($join) use($ownershipArrayData) {
                $join->on('home_owned_campaign.campaign_id', '=', 'campaigns.campaign_id')
                    ->whereIn('home_owned_campaign.campaign_home_owned', $ownershipArrayData);
            });

        if( $service != 23 ) {
            $campaigns->Join('property_type_many_campaign', function($join) use($property_typeArrayData) {
                $join->on('property_type_many_campaign.campaign_id', '=', 'campaigns.campaign_id')
                    ->whereIn('property_type_many_campaign.property_type_campaign_id', $property_typeArrayData);
            });
            $campaigns->join('installing_campaign', function($join) use($projectnatureArrayData) {
                $join->on('installing_campaign.campaign_id', '=', 'campaigns.campaign_id')
                    ->whereIn('installing_campaign.installing_type_campaign_id', $projectnatureArrayData);
            });
        }

        $matchingCampaigns = new AllServicesQuestions();

        $campaigns = $matchingCampaigns->campaignsMatchingWithQuestions($campaigns, $service, $projectnatureArrayData, $ownershipArrayData, $property_typeArrayData, $LeaddataIDs);

        $campaigns->whereIn('campaigns.campaign_id', $lastCampainInArea)
            ->where('campaigns.campaign_visibility', 1)
            ->where('campaigns.campaign_status_id', 1)
            ->where('users.user_visibility', 1)
            ->where('campaigns.service_campaign_id', $service)
            ->where('service__campaigns.service_is_active', 1)
            ->where('campaigns.is_seller', 0)
            ->where('campaigns.is_ping_account', $is_ping);

        //For Marketing Filtration =============================================
        $campaigns->where(function($query) use($lead_source){
            $query->where('campaigns.lead_source', "");
            $query->OrWhere('campaigns.lead_source',"[]");
            $query->OrwhereJsonContains('campaigns.lead_source', "All Source");
            $query->OrwhereJsonContains('campaigns.lead_source', $lead_source);
        });
        //======================================================================

        if( $is_forms == 1 ){
            $campaigns->whereIn('campaigns.campaign_Type', array(1, 7));
        }  elseif( $is_forms == 2 ){
            $campaigns->where('campaigns.campaign_Type', 8);
        } else {
            $campaigns->where('campaigns.campaign_Type', 1);
        }

        //Check if Lead Seconds service and if the campaign contains Seconds service
        if( !empty($LeaddataIDs['is_sec_service']) ){
            if( $LeaddataIDs['is_sec_service'] == 1 ){
                $campaigns->where('campaigns.sec_service_accept', 1);
            }
        }

        if( $type == 1 ){
            $campaigns->join('custom_bid_campaign', function ($join) {
                $join->on('custom_bid_campaign.campaign_id', '=', 'campaigns.campaign_id')
                    ->whereIn('custom_bid_campaign.custom_paid_campaign_id', array(1));
            });
            $campaigns->where("campaigns.campaign_budget_bid_exclusive", '<>', 0);
            $campaigns->orderBy("campaigns.campaign_budget_bid_exclusive", 'DESC');
        } else if( $type == 2 ){
            $campaigns->join('custom_bid_campaign', function ($join) {
                $join->on('custom_bid_campaign.campaign_id', '=', 'campaigns.campaign_id')
                    ->whereIn('custom_bid_campaign.custom_paid_campaign_id', array(2));
            });
            if($is_forms != 2){
                $campaigns->where("campaigns.campaign_budget_bid_shared", '<>', 0);
            }
            $campaigns->orderBy("campaigns.campaign_budget_bid_shared", 'DESC');
        }

        if( $service == 23 ) {
            if( $painting_service == 3 ) {
                $campaigns->groupBy('campaigns.campaign_id')
                    ->havingRaw('COUNT(campaigns.campaign_id) = ' . (count(json_decode($LeaddataIDs['painted_feature'], true) ) * count($ownershipArrayData)));
            } else  if( $painting_service == 4 ){
                $campaigns->groupBy('campaigns.campaign_id')
                    ->havingRaw('COUNT(campaigns.campaign_id) = ' . (count(json_decode($LeaddataIDs['existing_roof'], true) ) * count($ownershipArrayData)));
            } else  if( $painting_service == 5 ){
                $campaigns->groupBy('campaigns.campaign_id')
                    ->havingRaw('COUNT(campaigns.campaign_id) = ' . (count(json_decode($LeaddataIDs['surfaces_kinds'], true) ) * count($ownershipArrayData)));
            }
        }

        if( !empty($LeaddataIDs['if_seller_api']) ){
            if( $LeaddataIDs['if_seller_api'] == 1 ){
                if( !empty($LeaddataIDs['seller_id']) ){
                    $campaigns->where("campaigns.user_id", '<>', $LeaddataIDs['seller_id']);
                }
                if( !empty($LeaddataIDs['hash_legs_sold']) ){
                    if( is_array($LeaddataIDs['hash_legs_sold']) ){
                        $campaigns->whereNotIn("users.hash_phone_number", $LeaddataIDs['hash_legs_sold'])
                            ->whereNotIn("users.hash_mobile_number", $LeaddataIDs['hash_legs_sold']);
                    }
                }
            }
        }

        //For check if Brands Leads =======================================
        $is_brand_lead = ( !empty($LeaddataIDs['brand_buyer_id']) ? 1 : 0 );
        $campaigns->where('campaigns.is_branded_camp', $is_brand_lead);
        if( $is_brand_lead == 1 && !empty($LeaddataIDs['brand_buyer_id']) ){
            $campaigns->where('campaigns.user_id', $LeaddataIDs['brand_buyer_id']);
        }
        //=================================================================

        $campaigns = $campaigns->get([
            'campaigns.*', 'service__campaigns.service_campaign_name', 'users.id', 'total_amounts.total_amounts_value',
            'users.username', 'users.email', 'users.user_phone_number', 'users.user_mobile_number',
            'users.payment_type_method_id', 'users.payment_type_method_limit', 'users.payment_type_method_status',
            'campaign_time_delivery.*', "users.created_at AS buyer_created_at", "users.role_id"
        ])->unique(['campaign_id']);

        return $campaigns;
    }

    public function service_queries_per_call_new_way($service, $LeaddataIDs, $address,$lead_website){
        $projectnatureArrayData = array();
        if( empty($LeaddataIDs['project_nature']) ){
            $projectnatureArrayData[] = 1;
            $projectnatureArrayData[] = 2;
            $projectnatureArrayData[] = 3;
        }
        else if( $LeaddataIDs['project_nature'] != 3 ){
            $projectnatureArrayData[] = 1;
            $projectnatureArrayData[] = 2;
        }
        else {
            $projectnatureArrayData[] = 3;
        }

        $ownershipArrayData = array();
        if( !isset($LeaddataIDs['homeOwn']) || $LeaddataIDs['homeOwn'] == '3' ){
            $ownershipArrayData[] = 0;
            $ownershipArrayData[] = 1;
        } else {
            $ownershipArrayData[] = $LeaddataIDs['homeOwn'];
        }

        $property_typeArrayData = array();
        if( empty($LeaddataIDs['property_type']) ){
            if( !empty($LeaddataIDs['property_type_roofing']) ){
                if( $LeaddataIDs['property_type_roofing'] == 1 ){
                    $property_typeArrayData[] = 1;
                    $property_typeArrayData[] = 2;
                } else {
                    $property_typeArrayData[] = 3;
                }
            } else {
                $property_typeArrayData[] = 1;
                $property_typeArrayData[] = 2;
                $property_typeArrayData[] = 3;
            }
        } else {
            $property_typeArrayData[] = $LeaddataIDs['property_type'];
        }

        $campaign_website = str_replace('www.', '', $lead_website);

        $campaigns = Campaign::join('users', 'users.id', '=', 'campaigns.user_id')
            ->leftJoin('total_amounts', 'users.id', '=', 'total_amounts.user_id')
            ->join('campaign_time_delivery', 'campaign_time_delivery.campaign_id', '=', 'campaigns.campaign_id')
            ->join('service__campaigns', 'service__campaigns.service_campaign_id', '=', 'campaigns.service_campaign_id')
            ->join('campaign_target_area', 'campaign_target_area.campaign_id', '=', 'campaigns.campaign_id');

        $matchingCampaigns = new AllServicesQuestions();

        $campaigns = $matchingCampaigns->campaignsMatchingWithQuestions($campaigns, $service, $projectnatureArrayData, $ownershipArrayData, $property_typeArrayData, $LeaddataIDs);

        $zipcode_id = $address['zipcode_id'];
        $city_id = $address['city_id'];
        $county_id = $address['county_id'];
        $state_id = $address['state_id'];
        $campaigns = $campaigns->where(function($query) use($zipcode_id, $city_id, $county_id, $state_id){
            $query->whereJsonContains('campaign_target_area.zipcode_id', (int)$zipcode_id);
            $query->OrwhereJsonContains('campaign_target_area.city_id', "$city_id");
            $query->OrwhereJsonContains('campaign_target_area.county_id', "$county_id");
            $query->OrwhereJsonContains('campaign_target_area.state_id', "$state_id");
        })
            ->whereJsonDoesntContain('campaign_target_area.city_ex_id', "$city_id")
            ->whereJsonDoesntContain('campaign_target_area.county_ex_id', "$county_id")
            ->whereJsonDoesntContain('campaign_target_area.zipcode_ex_id', (int)$zipcode_id)
            ->where('campaigns.campaign_visibility', 1)
            ->where('campaigns.campaign_status_id', 1)
            ->where('users.user_visibility', 1)
            ->whereIn('users.role_id', ['3', '4', '6'])
            ->where('campaigns.service_campaign_id', $service)
            ->where('service__campaigns.service_is_active', 1)
            ->where('campaigns.is_seller', 0)
            ->where('campaigns.campaign_Type', 2)
            ->whereJsonContains('campaigns.website', "$campaign_website")
            ->where("campaigns.campaign_budget_bid_shared", '<>', 0);

        //======================================================================

        $campaigns = $campaigns->orderBy('campaigns.campaign_budget_bid_shared', 'DESC')->get([
            'campaigns.*', 'service__campaigns.service_campaign_name', 'users.id', 'total_amounts.total_amounts_value',
            'users.username', 'users.email', 'users.user_phone_number', 'users.user_mobile_number',
            'users.payment_type_method_id', 'users.payment_type_method_limit', 'users.payment_type_method_status',
            'campaign_time_delivery.*', "users.created_at AS buyer_created_at", "users.role_id"
        ])->unique(['campaign_id']);

        return $campaigns;
    }

    public function service_queries_per_click_new_way($lead_website, $address){
        $campaign_website = str_replace('www.', '', $lead_website);

        $campaigns = Campaign::join('users', 'users.id', '=', 'campaigns.user_id')
            ->leftJoin('total_amounts', 'users.id', '=', 'total_amounts.user_id')
            ->join('service__campaigns', 'service__campaigns.service_campaign_id', '=', 'campaigns.service_campaign_id')
            ->join('campaign_target_area', 'campaign_target_area.campaign_id', '=', 'campaigns.campaign_id');

        $zipcode_id = $address['zipcode_id'];
        $city_id = $address['city_id'];
        $county_id = $address['county_id'];
        $state_id = $address['state_id'];
        $campaigns = $campaigns->where(function($query) use($zipcode_id, $city_id, $county_id, $state_id){
            $query->whereJsonContains('campaign_target_area.zipcode_id', (int)$zipcode_id);
            $query->OrwhereJsonContains('campaign_target_area.city_id', "$city_id");
            $query->OrwhereJsonContains('campaign_target_area.county_id', "$county_id");
            $query->OrwhereJsonContains('campaign_target_area.state_id', "$state_id");
        })
            ->whereJsonDoesntContain('campaign_target_area.city_ex_id', "$city_id")
            ->whereJsonDoesntContain('campaign_target_area.county_ex_id', "$county_id")
            ->whereJsonDoesntContain('campaign_target_area.zipcode_ex_id', (int)$zipcode_id)
            ->where('campaigns.campaign_visibility', 1)
            ->where('campaigns.campaign_status_id', 1)
            ->where('users.user_visibility', 1)
            ->whereIn('users.role_id', ['3', '4', '6'])
            ->where('service__campaigns.service_is_active', 1)
            ->where('campaigns.is_seller', 0)
            ->where('campaigns.campaign_Type', 3)
            ->whereJsonContains('campaigns.website', "$campaign_website");

        $campaigns = $campaigns->orderBy('campaigns.campaign_budget_bid_shared', 'DESC')->get([
            'campaigns.*', 'service__campaigns.service_campaign_name', 'users.id', 'total_amounts.total_amounts_value',
            'users.username', 'users.email', 'users.user_phone_number', 'users.user_mobile_number',
            'users.payment_type_method_id', 'users.payment_type_method_limit', 'users.payment_type_method_status',
            "users.created_at AS buyer_created_at", "users.role_id"
        ])->unique(['campaign_id']);

        return $campaigns;
    }

    public function service_queries_per_appointment_new_way($service, $LeaddataIDs, $address, $lead_website){
        $projectnatureArrayData = array();
        if( empty($LeaddataIDs['project_nature']) ){
            $projectnatureArrayData[] = 1;
            $projectnatureArrayData[] = 2;
            $projectnatureArrayData[] = 3;
        }
        else if( $LeaddataIDs['project_nature'] != 3 ){
            $projectnatureArrayData[] = 1;
            $projectnatureArrayData[] = 2;
        }
        else {
            $projectnatureArrayData[] = 3;
        }

        $ownershipArrayData = array();
        if( !isset($LeaddataIDs['homeOwn']) || $LeaddataIDs['homeOwn'] == '3' ){
            $ownershipArrayData[] = 0;
            $ownershipArrayData[] = 1;
        }
        else {
            $ownershipArrayData[] = $LeaddataIDs['homeOwn'];
        }

        $property_typeArrayData = array();
        if( empty($LeaddataIDs['property_type']) ){
            if( !empty($LeaddataIDs['property_type_roofing']) ){
                if( $LeaddataIDs['property_type_roofing'] == 1 ){
                    $property_typeArrayData[] = 1;
                    $property_typeArrayData[] = 2;
                } else {
                    $property_typeArrayData[] = 3;
                }
            } else {
                $property_typeArrayData[] = 1;
                $property_typeArrayData[] = 2;
                $property_typeArrayData[] = 3;
            }
        }
        else {
            $property_typeArrayData[] = $LeaddataIDs['property_type'];
        }

        $campaign_website = str_replace('www.', '', $lead_website);

        $campaigns = Campaign::join('users', 'users.id', '=', 'campaigns.user_id')
            ->leftJoin('total_amounts', 'users.id', '=', 'total_amounts.user_id')
            ->join('campaign_time_delivery', 'campaign_time_delivery.campaign_id', '=', 'campaigns.campaign_id')
            ->join('service__campaigns', 'service__campaigns.service_campaign_id', '=', 'campaigns.service_campaign_id')
            ->join('campaign_target_area', 'campaign_target_area.campaign_id', '=', 'campaigns.campaign_id');

        $matchingCampaigns = new AllServicesQuestions();

        $campaigns = $matchingCampaigns->campaignsMatchingWithQuestions($campaigns, $service, $projectnatureArrayData, $ownershipArrayData, $property_typeArrayData, $LeaddataIDs);

        $zipcode_id = $address['zipcode_id'];
        $city_id = $address['city_id'];
        $county_id = $address['county_id'];
        $state_id = $address['state_id'];
        $campaigns = $campaigns->where(function($query) use($zipcode_id, $city_id, $county_id, $state_id){
            $query->whereJsonContains('campaign_target_area.zipcode_id', (int)$zipcode_id);
            $query->OrwhereJsonContains('campaign_target_area.city_id', "$city_id");
            $query->OrwhereJsonContains('campaign_target_area.county_id', "$county_id");
            $query->OrwhereJsonContains('campaign_target_area.state_id', "$state_id");
        })
            ->whereJsonDoesntContain('campaign_target_area.city_ex_id', "$city_id")
            ->whereJsonDoesntContain('campaign_target_area.county_ex_id', "$county_id")
            ->whereJsonDoesntContain('campaign_target_area.zipcode_ex_id', (int)$zipcode_id)
            ->where('campaigns.campaign_visibility', 1)
            ->where('campaigns.campaign_status_id', 1)
            ->where('users.user_visibility', 1)
            ->whereIn('users.role_id', ['3', '4', '6'])
            ->where('campaigns.service_campaign_id', $service)
            ->where('service__campaigns.service_is_active', 1)
            ->where('campaigns.is_seller', 0)
            ->where('campaigns.campaign_Type', 5)
            ->whereJsonContains('campaigns.website', "$campaign_website")
            ->where("campaigns.campaign_budget_bid_shared", '<>', 0);

        $campaigns = $campaigns->get([
            'campaigns.*', 'service__campaigns.service_campaign_name', 'users.id', 'total_amounts.total_amounts_value',
            'users.username', 'users.email', 'users.user_phone_number', 'users.user_mobile_number',
            'users.payment_type_method_id', 'users.payment_type_method_limit', 'users.payment_type_method_status',
            'campaign_time_delivery.*', "users.created_at AS buyer_created_at", "users.role_id"
        ])->unique(['campaign_id']);

        return $campaigns;
    }
}
