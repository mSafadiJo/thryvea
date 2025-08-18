<?php

namespace App\Http\Controllers;

use App\Campaign;
use App\Services\ApiMain;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use phpDocumentor\Reflection\Types\Object_;
use Rap2hpoutre\FastExcel\FastExcel;


class CampaignMainController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    public function deleteAllStateFilter(Request $request){
        $campaign_id = $request->id;

        //State Filter in Campaign
        DB::table('campaign_state_filter')->where('campaign_id', $campaign_id)->delete();

        return response()->json('true', 200);
    }

    public function deleteAllState(Request $request){
        $campaign_id = $request->id;

        //Delete All in state_campaigns table
        DB::table('state_campaigns')->where('campaign_id', $campaign_id)
            ->where('state_campaigns_active', 1)->delete();

        return response()->json('true', 200);
    }

    public function deleteAllCounty(Request $request){
        $campaign_id = $request->id;

        //Delete All in county__campaigns table
        DB::table('county__campaigns')->where('campaign_id', $campaign_id)
            ->where('county_campaigns_active', 1)->delete();

        return response()->json('true', 200);
    }

    public function deleteAllCity(Request $request){
        $campaign_id = $request->id;

        //Delete All in city__campaigns table
        DB::table('city__campaigns')->where('campaign_id', $campaign_id)
            ->where('city_campaigns_active', 1)->delete();

        return response()->json('true', 200);
    }

    public function deleteAllZipcode(Request $request){
        $campaign_id = $request->id;

        //Delete All in zipcode__campaigns table
        DB::table('zipcode__campaigns')->where('campaign_id', $campaign_id)
            ->where('zipcode_campaigns_active', 1)->delete();

        return response()->json('true', 200);
    }

    public function deleteAllZipcode2(Request $request){
        $campaign_id = $request->delete_campaign_id;

        //Delete All in zipcode__campaigns table
        DB::table('zipcode__campaigns')->where('campaign_id', $campaign_id)
            ->where('zipcode_campaigns_active', 1)->delete();
        //Delete All in campaign_zipcode_distance table
        DB::table('campaign_zipcode_distance')->where('campaign_id', $campaign_id)->delete();
        //Update Distance area to 0
        DB::table('campaigns')->where('campaign_id', $campaign_id)->update([
            'campaign_distance_area' => 0
        ]);

        return redirect()->back();
    }

    public function deleteAllCountyExpect(Request $request){
        $campaign_id = $request->id;

        //Delete All in zipcode__campaigns table
        DB::table('county__campaigns')->where('campaign_id', $campaign_id)
            ->where('county_campaigns_active', 0)->delete();

        return response()->json('true', 200);
    }

    public function deleteAllCityExpect(Request $request){
        $campaign_id = $request->id;

        //Delete All in zipcode__campaigns table
        DB::table('city__campaigns')->where('campaign_id', $campaign_id)
            ->where('city_campaigns_active', 0)->delete();

        return response()->json('true', 200);
    }

    public function deleteAllZipcodeExpect(Request $request){
        $campaign_id = $request->id;

        //Delete All in zipcode__campaigns table
        DB::table('zipcode__campaigns')->where('campaign_id', $campaign_id)
            ->where('zipcode_campaigns_active', 0)->delete();

        return response()->json('true', 200);
    }

    public function exportCampZipcodes(Request $request){
        ini_set('max_execution_time', '0');
        ini_set('memory_limit', '-1');

        $campaign_id = $request->id;

        $campaign = DB::table('campaign_target_area')
            ->where('campaign_id', $campaign_id)
            ->first();


        $zipCodes_data = DB::table('zip_codes_lists')
            ->join('states', 'states.state_id', '=', 'zip_codes_lists.state_id')
            ->join('counties', 'counties.county_id', '=', 'zip_codes_lists.county_id')
            ->join('cities', 'cities.city_id', '=', 'zip_codes_lists.city_id')
            ->whereIn('zip_code_list_id', json_decode($campaign->zipcode_id))
            ->groupBy('zip_codes_lists.zip_code_list_id')
            ->get(['zip_codes_lists.zip_code_list', 'states.state_code', 'states.state_name', 'counties.county_name', 'cities.city_name']);

        return (new FastExcel($zipCodes_data))->download('ZipCodes.csv', function ($zipCode) {
            return [
                'Zipcode' => $zipCode->zip_code_list,
                'City' => $zipCode->city_name,
                'County' => $zipCode->county_name,
                'State Code' => $zipCode->state_code,
                'State Name' => $zipCode->state_name
            ];
        });
    }

    public function exportCampExpectZipcodes(Request $request){
        $campaign_id = $request->id;

        $campaign = DB::table('campaign_target_area')
            ->where('campaign_id', $campaign_id)
            ->first();

        $zipCodes_expect = DB::table('zip_codes_lists')
            ->whereIn('zip_code_list_id', json_decode($campaign->zipcode_ex_id))
            ->pluck('zip_code_list')->toArray();

        array_unshift($zipCodes_expect, "EXCLUDED ZIPCODES");

        header("Content-type: application/vnd.ms-excel");
        header('Content-Type: text/csv');
        header('Content-Type: application/force-download');
        header("Content-disposition: attachment; filename=List Of Expect_ZipCode.csv");
        header('Content-Encoding: UTF-8');
        header('Content-type: text/csv; charset=UTF-8');
        header("Pragma: no-cache");
        header("Expires: 0");
        header("Cache-Control: ");

        $i=0;
        foreach( $zipCodes_expect as $key=>$item ){
            $zipcode_CSV[$i] = array($item);
            $i++;
        }

        $fp = fopen('php://output', 'wb');
        foreach ($zipcode_CSV as $line) {
            fputcsv($fp, $line, ',');
        }
        fclose($fp);

    }

    public function exportCampaignTarget(Request $request){
        $campaign_id = $request->id;

        $campaign = DB::table('campaign_target_area')
            ->where('campaign_id', $campaign_id)
            ->first();


        $states = DB::table('states')
            ->whereIn('state_id', json_decode($campaign->state_id))
            ->pluck('states.state_code')->toArray();

        array_unshift($states, "STATES");


        $counties = DB::table('counties')
            ->whereIn('county_id', json_decode($campaign->county_id))
            ->pluck('counties.county_name')->toArray();

        array_unshift($counties, "COUNTIES");


        $cities = DB::table('cities')
            ->whereIn('city_id', json_decode($campaign->city_id))
            ->pluck('city_name')->toArray();

        array_unshift($cities, "CITIES");


        $zipCodes = DB::table('zip_codes_lists')
            ->whereIn('zip_code_list_id', json_decode($campaign->zipcode_id))
            ->pluck('zip_code_list')->toArray();

        array_unshift($zipCodes, "ZIPCODES");


        $counties_expect = DB::table('counties')
            ->whereIn('county_id', json_decode($campaign->county_ex_id))
            ->pluck('counties.county_name')->toArray();

        array_unshift($counties_expect, "EXCLUDED COUNTIES");


        $cities_expect = DB::table('cities')
            ->whereIn('city_id', json_decode($campaign->city_ex_id))
            ->pluck('city_name')->toArray();

        array_unshift($cities_expect, "EXCLUDED CITIES");



        $zipCodes_expect = DB::table('zip_codes_lists')
            ->whereIn('zip_code_list_id', json_decode($campaign->zipcode_ex_id))
            ->pluck('zip_code_list')->toArray();

        array_unshift($zipCodes_expect, "EXCLUDED ZIPCODES");


        $data_arr = array_merge($states, $counties, $cities, $zipCodes, $counties_expect, $cities_expect, $zipCodes_expect);

        header("Content-type: application/vnd.ms-excel");
        header('Content-Type: application/force-download');
        header("Content-disposition: attachment; filename=List Of Area.csv");
        header('Content-Encoding: UTF-8');
        header('Content-type: text/csv; charset=UTF-8');
        header("Pragma: no-cache");
        header("Expires: 0");
        header("Cache-Control: ");

        $i=0;
        foreach( $data_arr as $key=>$item ){
            $zipcode_CSV[$i] = array($item);
            $i++;
        }

        $fp = fopen('php://output', 'wb');
        foreach ($zipcode_CSV as $line) {
            fputcsv($fp, $line, ',');
        }
        fclose($fp);

    }

    public function sendTestLead(Request $request){
        $campaign_id = $request->id;

        $campaign = DB::table('campaigns')
            ->where('campaign_id', $campaign_id)->first();
        $service_id = $campaign->service_campaign_id;

        // get user information
        $user_info = DB::table('users')
            ->where('id', $campaign->user_id)
            ->first();

        // get service information
        $service_info = DB::table('service__campaigns')
            ->where('service_campaign_id', $service_id)
            ->first();

        $dataMassageForBuyers = array(
            'one' => '',
            'two' => '',
            'three' => '',
            'four' => '',
            'five' => '',
            'six' => ''
        );

        $main_api_file = new ApiMain();

        $campaign_id_curent = $campaign->campaign_id;
        $user_id = $campaign->user_id;
        $buyersusername = $user_info->username; //from user

        $service_campaign_name = $service_info->service_campaign_name; //from servece

        //Send Request
        $delivery_methods = DB::table('delivery_method_campaign')
            ->where('campaign_id', $campaign_id_curent)
            ->get(['delivery_Method_id'])->All();


        $data_msg = array(
            'name' => $buyersusername,
            'leadName' => 'Test Test',
            'LeadEmail' => 'tech@thryvea.co',
            'LeadPhone' => '8582840441',
            'Address' =>  'Address: Test',
            'LeadService' => $service_campaign_name,
            'data' => $dataMassageForBuyers,
            'street' => "123 Test",
            'trusted_form' => '',
            'appointment_date' => "",
            'appointment_type' => '',
        );

        if( !empty( $delivery_methods ) ){
            foreach( $delivery_methods as $delivery_method ){
                if( $delivery_method->delivery_Method_id == 1 ){
                    $sms_data_array = array(
                        'user_id' => $user_id,
                        'phone1' => $campaign->phone1,
                        'phone2' => $campaign->phone2,
                        'phone3' => $campaign->phone3,
                    );

                    $main_api_file->send_sms($data_msg, $sms_data_array);
                }

                if( $delivery_method->delivery_Method_id == 2 ){
                    //EMAIL
                    $email_data_array = array(
                        'buyersusername' => $buyersusername,
                        'email1' => $campaign->email1,
                        'email2' => $campaign->email2,
                        'email3' => $campaign->email3,
                    );

                    $main_api_file->send_email($data_msg, $email_data_array);
                }
            }
        }

        return response()->json('true', 200);
    }
}
