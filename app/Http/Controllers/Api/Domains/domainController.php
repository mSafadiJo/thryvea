<?php

namespace App\Http\Controllers\Api\Domains;

use App\Http\Controllers\Pixels\pixelsController;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class domainController extends Controller
{
    public function  index(Request $request)
    {

        try {
            $domain = DB::table('domains')
            ->where('status', 1)
            ->where('domain_name', $request->domain_name)
            ->select([
                'id', 'domain_name', 'Service_type', 'theme_id', 'session_recording', 'exit_popup',
                'session_recoding_option', 'traffic_source_selected', 'lead_review', 'join_network',
                'progress_bar', 'second_service', 'logo', 'background', 'icon', 'meta_description'
            ])
            ->get();
//            $domain = DB::table('domains')
//                ->join('pixels', 'domains.id', '=', 'pixels.domain_id')
//                ->where('status', 1)
//                ->where('domain_name', $request->domain_name)
//                ->select([
//                    'domains.id', 'domains.domain_name', 'domains.Service_type', 'domains.theme_id', 'domains.session_recording', 'domains.exit_popup',
//                    'domains.session_recoding_option', 'domains.traffic_source_selected', 'domains.lead_review', 'domains.join_network',
//                    'domains.progress_bar', 'domains.second_service', 'domains.logo', 'domains.background', 'domains.icon', 'domains.meta_description',
//                    'pixels.pixels_name'
//                ])
//                ->get();
            //return $domain;
            $facebook_pixel = DB::table('facebook_pixel')
                ->where('domain_id',$domain[0]->id)
                ->select(['meta_tag' , 'pixel'])
                ->get();
            $google_pixel = DB::table('google_pixel')
                ->where('domain_id',$domain[0]->id)
                ->where('ts_name' , '==' , '')
                ->select(['tag_manager_id'])
                ->get();
            $google_pixel_TS = DB::table('google_pixel')
                ->where('domain_id',$domain[0]->id)
                ->where('ts_name' , '!=' , null)
                ->select(['tag_manager_id' , 'ts_name'])
                ->get();
            //return $pixels;
            $services = collect();
            $services_obj = collect(DB::table('domain_services')
                ->where('domain_id', $domain[0]->id)
                ->select(['service_id'])
                ->get());

            $services_obj->map(function ($value, $key) use ($services) {
                $services->add($value->service_id);
            });

            $content = DB::table('services_content')
                ->where('domain_id', $domain[0]->id)
                ->select(['main_header', 'main_body', 'second_header', 'second_body'])
                ->get();
            return response()->json(['domain' => $domain, 'services' => $services, 'content' => $content, 'facebook_pixel'=>$facebook_pixel , 'google_pixel' => $google_pixel , 'google_pixel_TS' => $google_pixel_TS]);
        }catch (\Exception $ex){

            return $ex;
            return response()->json(["the required domain doesn't exists" => "please try on another domain name!"]);
        }
    }
}
