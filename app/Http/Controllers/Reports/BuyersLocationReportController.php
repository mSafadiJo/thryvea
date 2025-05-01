<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Rap2hpoutre\FastExcel\FastExcel;

class BuyersLocationReportController extends Controller
{
    public function __construct()
    {
        ini_set('max_execution_time', '0');
        ini_set('memory_limit', '-1');
        $this->middleware(['auth', 'AdminMiddleware']);
    }

    public function index(){
        $services = DB::table('service__campaigns')->get();
        $campaignTypes = DB::table('campaign_types')->get();

        return view('Reports.BuyersLocationReport', compact('services', 'campaignTypes'));
    }

    public function getData(Request $request){
        $service_id = $request->service_id;
        $campaignTypes = $request->campaignTypes;
        $user_type = $request->user_type;
        $campaignStatus = $request->campaignStatus;
        $depending_on = $request->depending_on;

        //Get Campaigns
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
        if (!empty($campaignTypes)) {
            $campaigns->where('campaigns.campaign_Type', $campaignTypes);
        }
        if (!empty($user_type)) {
            $campaigns->whereIn('users.role_id', $user_type);
        }
        if (!empty($campaignStatus)) {
            $status = [1,4];
            if($campaignStatus == "Pause") {
                $campaigns->where('campaigns.campaign_status_id', 4);
            } elseif($campaignStatus == "Running") {
                $campaigns->where('campaigns.campaign_status_id', 1);
            } else {
                $campaigns->whereIn('campaigns.campaign_status_id', $status);
            }
        }

        $campaigns = $campaigns->get();

        //Return Zipcode ID's from Campaigns
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

            $ListOFZipCodeActive = $ListOFZipCodeActive->pluck('zip_code_list_id')->toArray();
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
                $ListOFZipCodeNotActive = $ListOFZipCodeNotActive->pluck('zip_code_list_id')->toArray();
                $ZipCodeNotActiveArray = array_unique($ListOFZipCodeNotActive);
            }

            $EndZipCode = array_diff($ZipCodeActive, $ZipCodeNotActiveArray);
            $EndZipCodeList = array_merge($EndZipCodeList, $EndZipCode);
        }

        $EndZipCodeList = array_unique($EndZipCodeList);

        if($depending_on == "State"){
            //Return Zipcodes number inside only single state
            $zipcode_number = DB::table('states')
                ->leftJoin('zip_codes_lists', 'zip_codes_lists.state_id', '=', 'states.state_id')
                ->groupBy('states.state_id')
                ->selectRaw('COUNT(zip_codes_lists.zip_code_list_id) AS totalZipCode, states.state_code')
                ->pluck('totalZipCode', "state_code")->toarray();

            //Return Zipcodes number inside only single state for our Campaigns
            $campaign_zipcode_number = DB::table('states')
                ->leftJoin('zip_codes_lists', function($join) use($EndZipCodeList) {
                    $join->on('zip_codes_lists.state_id', '=', 'states.state_id')
                        ->whereIn('zip_codes_lists.zip_code_list_id', $EndZipCodeList);
                })
                ->groupBy('states.state_id')
                ->selectRaw('COUNT(zip_codes_lists.zip_code_list_id) AS totalZipCode, states.state_code')
                ->pluck('totalZipCode', "state_code")->toarray();
        } else {
            //Return Zipcodes number inside only single county
            $zipcode_number = DB::table('counties')
                ->leftJoin('zip_codes_lists', 'zip_codes_lists.county_id', '=', 'counties.county_id')
                ->groupBy('counties.county_id')
                ->selectRaw('COUNT(zip_codes_lists.zip_code_list_id) AS totalZipCode, counties.county_name')
                ->pluck('totalZipCode', "county_name")->toarray();

            //Return Zipcodes number inside only single county for our Campaigns
            $campaign_zipcode_number = DB::table('counties')
                ->leftJoin('zip_codes_lists', function($join) use($EndZipCodeList) {
                    $join->on('zip_codes_lists.county_id', '=', 'counties.county_id')
                        ->whereIn('zip_codes_lists.zip_code_list_id', $EndZipCodeList);
                })
                ->groupBy('counties.county_id')
                ->selectRaw('COUNT(zip_codes_lists.zip_code_list_id) AS totalZipCode, counties.county_name')
                ->pluck('totalZipCode', "county_name")->toarray();
        }

        $final_data = collect();
        foreach ($campaign_zipcode_number as $key => $item) {
            $data = [
                "type" => $key,
                "count" => $item,
                "percentage" => "%".(($item/$zipcode_number[$key])*100)
            ];

            $final_data->push((object) $data);
        }

        return (new FastExcel($final_data))->download('BuyersLocation.csv', function ($data) use($depending_on){
            return [
                $depending_on => $data->type,
                'Count' => $data->count,
                'Percentage' => $data->percentage
            ];
        });
    }
}
