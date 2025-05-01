<?php

namespace App\Http\Controllers\Api\Pro;

use App\JoinAsaPro;
use App\LeadTrafficSources;
use App\MarketingPlatform;
use App\Services\ApiMain;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class GeneralAPIController extends Controller
{
    public function join_as_a_pro(Request $request){
        $lead_source = "SEO";
        if( !empty($request['google_ts']) ){
            $marketing_ts = LeadTrafficSources::where('name', strtolower($request['google_ts']))->first();
            if( !empty($marketing_ts) ){
                $marketing_platform = MarketingPlatform::where('id', $marketing_ts->marketing_platform_id)->first();
                if( !empty($marketing_platform) ){
                    $lead_source = $marketing_platform->name;
                }
            }
        }

        $user = new JoinAsaPro();

        $user->full_name = $request->full_name;
        $user->business_name = $request->business_name;
        $user->phone_number = $request->phone_number;
        $user->email = $request->email;
        $user->note = $request->note;
        $user->google_ts = $request->google_ts;
        $user->google_c = $request->google_c;
        $user->google_k = $request->google_k;
        $user->google_g = $request->google_g;
        $user->source = $lead_source;
        $user->ip_address = $request->ip_address;
        $user->visitor_id = $request->visitor_id;
        $user->token = $request->token;
        $user->services = $request->services;
        $user->resource = $request->resource;

        if( !empty($request->zip_code) ){
            $city_name = DB::table('zip_codes_lists')
                ->join('cities', 'cities.city_id', '=', 'zip_codes_lists.city_id')
                ->where('zip_code_list', $request->zip_code)
                ->first(['cities.city_name', 'zip_codes_lists.zip_code_list_id']);

            if( !empty($city_name) ){
                $user->zip_code = $city_name->zip_code_list_id;
                $user->city = $city_name->city_name;
            }
        }

        $user->save();

        //Server to server Conversion
        if( !empty($request['token']) ){
            if( strtolower($request['google_ts']) == 'p99' ){
                $token_data_conv = $request['token'];
                $id = "1GB3F8GA096GAFA46C6A";
                $url_conv = "https://www.conversionpx.com/?id=$id&value=0&token=$token_data_conv";
                $main_api_file = new ApiMain();
                $main_api_file->server_to_server_conv($url_conv);
            }
        }

        $response_code = array(
            'response_code' => 'true',
            'message' => 'Accepted'
        );

        return json_encode($response_code);die();
    }
}
