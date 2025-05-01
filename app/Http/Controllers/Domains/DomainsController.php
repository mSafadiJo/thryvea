<?php

namespace App\Http\Controllers\Domains;

use App\AccessLog;
use App\Models\Domains;
use App\Models\Pixels;
use App\Models\Themes;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Service_Campaign;
use phpDocumentor\Reflection\Types\Null_;
use PhpParser\Node\Expr\Array_;
use function foo\func;

class DomainsController extends Controller
{
    public function ListDomains(){
        $services = collect(DB::table('domain_services')
            ->join('service__campaigns', 'domain_services.service_id', '=', 'service__campaigns.service_campaign_id')
            ->select(['service__campaigns.service_campaign_name', 'domain_services.id', 'domain_services.domain_id'])
            ->get())
            ->groupBy('domain_id');
        $service_campaigns = DB::table('service__campaigns')
            ->select(['service_campaign_id', 'service_campaign_name'])
            ->get();

        $domains =  DB::table('domains')
            ->join('themes', 'domains.theme_id', '=', 'themes.id')
            ->select(['domains.*', 'themes.theme_img', 'themes.theme_name'])
            ->get();
//return compact(['domains', 'service_campaigns', 'services']);
        return view('SuperAdmin.Domains.ListDomain', compact(['domains', 'service_campaigns', 'services']));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|void
     */

    public function Edit_Domain(Request $request){
        if(DB::table('domains')->where('id', $request->id)->exists()){
            $allService = new Service_Campaign();
            $services = $allService->featchAllService();
            $choosed_services_before = collect(DB::table('domain_services')
                ->where('domain_id', $request->id)
                ->select(['service_id'])
                ->get())->values();
            $contents = collect(DB::table('services_content')
                ->where('domain_id', $request->id)
                ->get());
            if (count($contents) > 0){
                $contents = count($contents) > 0 ? ($contents[0]) : [];
            }
            $choosed_services = (array)collect($choosed_services_before)->keyBy('service_id')->keys();
            $domain = DB::table('domains')
                ->where('id', $request->id)
                ->get();
            $pixel_facebook = DB::table('pixels')
                ->where('domain_id', $request->id)
                ->where('type', 'Facebook')
                ->first();
            $pixel_google = DB::table('pixels')
                ->where('domain_id', $request->id)
                ->where('type', 'Google')
                ->get();

            $traffic = DB::table('lead_traffic_sources')
                ->get();
            return view('SuperAdmin.Domains.edit', compact(['domain', 'services', 'pixel_facebook', 'pixel_google', 'traffic', 'choosed_services_before', 'contents']));
        }else{
            return abort(404);
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */

    public function Edit_Domain_save(Request $request){
        $services_choosed = collect();
        if (($request->service_type == 2 || $request->service_type == 3)) {
            collect($request->service_id_multi)->filter(function ($value, $key) use ($services_choosed) {
                if ($value != NULL) {
                    $services_choosed->add($value);
                }
            });
        }else{
            $services_choosed->add($request->service_id_single);
        }
        $request->validate([
           'theme_id' => 'required',
        ]);
        if ($request->hasFile('logo')) {
            $Image = $request->file('logo');
            $extension = $Image->getClientOriginalExtension();
            $logo = '/images/domains/images/logo/' . $Image->getFilename() . '.' . $extension;
            $request->logo->move(public_path('/images/domains/images/logo'), $logo);
        }
        if ($request->hasFile('background')) {
            $Image = $request->file('background');
            $extension = $Image->getClientOriginalExtension();
            $background = '/images/domains/images/background/' . $Image->getFilename() . '.' . $extension;
            $request->background->move(public_path('/images/domains/images/background'), $background);
        }
        if ($request->hasFile('icon')) {
            $Image = $request->file('icon');
            $extension = $Image->getClientOriginalExtension();
            $icon = '/images/domains/images/icon/' . $Image->getFilename() . '.' . $extension;
            $request->icon->move(public_path('/images/domains/images/icon'), $icon);
        }
        $session_recoding_option = "";
        $traffic_source_selected = null;

        if ($request->all == "all traffic source"){
            $session_recoding_option = $request->all;
        }elseif ($request->all == "according traffic source"){
            $session_recoding_option = $request->all;
        }
        if ($request->session_recording == "on" && $request->all == "according traffic source"){
            $traffic_source_selected = json_encode(($request->TS));
        }else{
            $traffic_source_selected = NULL;
        }
        $images = DB::table('domains')->select(['logo', 'icon', 'background'])->where('id', $request->domain_id)->get();
        if ($request->exit_popup == "on") {
            if ($request->service_type == 1 || $request->service_type == 3) {
                $exit_popup = 1;
            } elseif ($request->service_type == 2) {
                $exit_popup = 0;
            }
        }else{
            $exit_popup = 0;
        }

        if ($request->second_service_checkbox == "on") {
            if ($request->service_type == 2 || $request->service_type == 3) {
                $second_service = null;
            } elseif ($request->service_type == 1) {
                $second_service = $request->Second_service;
            }
        }else{
            $second_service = null;
        }

        DB::table('domains')
            ->where('id', $request->domain_id)
            ->update([
                'domain_name' => $request->name,
                'Service_type' => $request->service_type,
                'theme_id' => $request->theme_id,
                'session_recording' => isset($request->session_recording) ? 1: 0,
                'join_network' => isset($request->join_network) ? 1: 0,
                'lead_review' => isset($request->lead_review) ? 1: 0,
                'exit_popup' => $exit_popup,
                'session_recoding_option' => $session_recoding_option,
                'traffic_source_selected' => isset($request->session_recording)?$traffic_source_selected: NULL,
                'second_service' => $second_service != null ? json_encode($second_service): null,
                'background' => isset($background) ? $background : $images[0]->background,
                'logo' => isset($logo) ? $logo : $images[0]->logo,
                'icon' => isset($icon) ? $icon : $images[0]->icon,
                'meta_description' => isset($request->meta) ? $request->meta : NULL
            ]);

        if ( !isset($request->facebook_check) || !isset($request->FPixels) ){
            DB::table('pixels')
                ->where('type', 'Facebook')
                ->where('domain_id', $request->domain_id)
                ->delete();
        }

        if (isset($request->facebook_check) && isset($request->FPixels)) {
            DB::table('pixels')
                ->where('type', 'Facebook')
                ->where('domain_id', $request->domain_id)
                ->insert([
                    'pixels_name' => $request->FPixels,
                    'type' => 'Facebook',
                    'domain_id' => $request->domain_id,
                    'ts_name' => NULL
                ]);
        }

        if ($request->google_check == "on") {
            if (isset($request->GTM)) {
                DB::table('pixels')
                    ->where('domain_id', $request->domain_id)
                    ->where('type', 'Google')
                    ->whereNull('ts_name')
                    ->updateOrInsert([
                        'pixels_name' => $request->GTM,
                        'type' => 'Google',
                        'domain_id' => $request->domain_id
                    ]);
            }
        }
        DB::transaction(function() use($request, $services_choosed) {
            if (isset($request->GPixelsId)) {
                DB::table('pixels')
                ->where('domain_id', $request->domain_id)
                ->where('type', 'Google')
                ->where('ts_name', '!=', '')
                ->delete();
                foreach ($request->GPixelsId as $key => $item) {
                    DB::table('pixels')
                        ->where('domain_id', $request->domain_id)
                        ->where('type', 'Google')
                        ->where('ts_name', '!=', '')
                        ->insert([
                            'pixels_name' => $request->GPixelsId[$key],
                            'ts_name' => $request->TsName[$key],
                            'type' => 'Google',
                            'domain_id' => $request->domain_id
                        ]);
                }
            }
            DB::table('domain_services')
                ->where('domain_id', $request->domain_id)
                ->delete();
            foreach ($services_choosed as $service) {
                DB::table('domain_services')->insert([
                    'domain_id' => $request->domain_id,
                    'service_id' => $service,
                ]);
            }
            DB::transaction(function () use ($request){
                DB::table('services_content')
                    ->where('domain_id', $request->domain_id)
                    ->delete();
               DB::table('services_content')
                   ->where('domain_id', $request->domain_id)
                   ->insert([
                       'domain_id' => $request->domain_id,
                       'main_header' => $request->main_header,
                       'main_body' => $request->main_body,
                       'second_header' => $request->second_header,
                       'second_body' => $request->second_body
               ]);
            });
        });
        if (!isset($request->google_check)){
            DB::table('pixels')
                ->where('type', '=', 'Google')
                ->where('domain_id', $request->domain_id)
                ->delete();
        }

        AccessLog::create([
            'user_id' => Auth::user()->id,
            'user_name' => Auth::user()->username,
            'section_id' => $request->domain_id,
            'section_name' => $request->name,
            'user_role' => Auth::user()->role_id,
            'section'   => 'DomainTemplates',
            'action'    => 'Updated',
            'ip_address' => request()->ip(),
            'location' => json_encode(\Location::get(request()->ip())),
            'request_method' => json_encode($request->all())
        ]);

        return redirect()->route('AllDomains');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */

    public function trafficSourceAjax(Request $request){
        $traffic = DB::table('pixels')
            ->where('type', 'Google')
            ->where('domain_id', $request->domain_id)
            ->where('ts_name', '!=', '')
            ->get();
        $all_traffic = DB::table('lead_traffic_sources')
            ->get();
        return response()->json(['traffic' => $traffic, 'allTraffic' => $all_traffic]);
    }

    public function AddForm(){
        ///// to fetch all service from service model///////
        $service_campaigns = DB::table('service__campaigns')
            ->select(['service_campaign_id', 'service_campaign_name'])
            ->get();
        $traffic_sources = DB::table('lead_traffic_sources')
            ->select(['id', 'name'])
            ->get();
        return view('SuperAdmin.Domains.AddDomain', compact(['service_campaigns', 'traffic_sources']));
    }

    /**
     * @param Request $request
     * @return \Exception|\Illuminate\Http\RedirectResponse|Request|void
     * what data save and where saved
     * 1. domains, pixels, services_content (database model).
     */
    public function Store(Request $request){
        $services_choosed = collect();
        if (isset($request->service_id_multi)) {
            collect($request->service_id_multi)->filter(function ($value, $key) use ($services_choosed) {
                if ($value != NULL) {
                    $services_choosed->add($value);
                }
            });
        }elseif (isset($request->service_id_single)){
            $services_choosed->add($request->service_id_single);
        }
        $request->validate([
            'service_type' => 'required',
            'theme_id' => 'required',
            'domain_name' => 'required|unique:domains|string'
        ]);
        try {
            if(empty($request['status']))
            {
                $status = 0;
            }else
            {
                $status = $request['status'];
            }

            //Save Domain
            $Save_Domain = new Domains();
            $Save_Domain->domain_name = $request['domain_name'];
            $Save_Domain->Service_type = $request['service_type'];
            $Save_Domain->theme_id = $request['theme_id'];
            $Save_Domain->status = $status;
            $Save_Domain->session_recording = isset($request->session_recording) ? 1: 0;
            $Save_Domain->join_network = isset($request->join_network) ? 1: 0;
            $Save_Domain->exit_popup = isset($request->exit_popup) ? 1: 0;
            if ($request->all == "all traffic source"){
                $Save_Domain->session_recoding_option = $request->all;
            }elseif ($request->all == "according traffic source"){
                $Save_Domain->session_recoding_option = $request->all;
            }
            if ($request->session_recording == "on" && $request->all == "according traffic source"){
                $Save_Domain->traffic_source_selected = json_encode(($request->TS));
            }else{
                $Save_Domain->traffic_source_selected = NULL;
            }
            $Save_Domain->status = $status;
            $Save_Domain->lead_review = isset($request->lead_review) ? 1: 0;
            $Save_Domain->second_service = isset($request->second_service_checkbox) ? json_encode($request->Second_service) : NULL;
            $Save_Domain->created_at = date("Y-m-d H:i:s") ;
            $Save_Domain->updated_at = date("Y-m-d H:i:s") ;
            if ($request->hasFile('logo')) {
                $Image = $request->file('logo');
                $extension = $Image->getClientOriginalExtension();
                $logo = '/images/domains/images/logo/' . $Image->getFilename() . '.' . $extension;
                $request->logo->move(public_path('/images/domains/images/logo'), $logo);
                $Save_Domain->logo = $logo;
            }
            if ($request->hasFile('background')) {
                $Image = $request->file('background');
                $extension = $Image->getClientOriginalExtension();
                $background = '/images/domains/images/background/' . $Image->getFilename() . '.' . $extension;
                $request->background->move(public_path('/images/domains/images/background'), $background);
                $Save_Domain->background = $background;
            }
            if ($request->hasFile('icon')) {
                $Image = $request->file('icon');
                $extension = $Image->getClientOriginalExtension();
                $icon = '/images/domains/images/icon/' . $Image->getFilename() . '.' . $extension;
                $request->icon->move(public_path('/images/domains/images/icon'), $icon);
                $Save_Domain->icon = $icon;
            }
            $Save_Domain->meta_description = isset($request->meta) ? $request->meta : NULL;
            $Save_Domain->save();

            $LastDomainId = $Save_Domain->id;
            //store content on services_content table.
            DB::table('services_content')
                ->insert([
                    'domain_id' => $LastDomainId,
                    'main_header' => empty($request->main_header)? '': $request->main_header,
                    'main_body' => empty($request->main_body)? '': $request->main_body,
                    'second_header' => empty($request->second_header)? '': $request->second_header,
                    'second_body' => empty($request->second_body)? '': $request->second_body
                ]);
            foreach ($services_choosed as $service) {
                DB::table('domain_services')->insert([
                    'domain_id' => $LastDomainId,
                    'service_id' => $service,
                ]);
            }
            $id = DB::getPdo()->lastInsertId();
            AccessLog::create([
                'user_id'           => Auth::user()->id,
                'user_name'         => Auth::user()->username,
                'section_id'        => $id,
                'section_name'      => $request['domain_name'],
                'user_role'         => Auth::user()->role_id,
                'section'           => 'DomainTemplates',
                'action'            => 'Created',
                'ip_address'        => request()->ip(),
                'location'          => json_encode(\Location::get(request()->ip())),
                'request_method'    => json_encode($request->all())
            ]);

            if(!empty($request['FPixels'])) {
                $Save_Pixels = new Pixels();
                $Save_Pixels->pixels_name = $request['FPixels'];
                $Save_Pixels->type = "Facebook";
                $Save_Pixels->domain_id = $LastDomainId;
                $Save_Pixels->created_at = date("Y-m-d H:i:s");
                $Save_Pixels->updated_at = date("Y-m-d H:i:s");
                $Save_Pixels->save();
            }if(!empty($request['GTM'])){
                $Save_Pixels = new Pixels();
                $Save_Pixels->pixels_name = $request['GTM'];
                $Save_Pixels->type = "Google";
                $Save_Pixels->domain_id = $LastDomainId;
                $Save_Pixels->created_at = date("Y-m-d H:i:s");
                $Save_Pixels->updated_at = date("Y-m-d H:i:s");
                $Save_Pixels->save();
            }
            if($request['GPixelsId'] != ""){
                if(count($request['GPixelsId']) >= 1)
                {
                    for ($x = 0; $x < count($request['GPixelsId']); $x++) {

                        $Save_Pixels = new Pixels();
                        $Save_Pixels->pixels_name = $request['GPixelsId'][$x];
                        $Save_Pixels->type = "Google";
                        $Save_Pixels->ts_name = $request['TsName'][$x];
                        $Save_Pixels->domain_id = $LastDomainId;
                        $Save_Pixels->created_at = date("Y-m-d H:i:s");
                        $Save_Pixels->updated_at = date("Y-m-d H:i:s");
                        $Save_Pixels->save();
                    }
                }
            }
        }catch (\Exception $ex){
            return $ex;
            return abort(500);
        }

        return redirect()->route('AllDomains');
    }

    public function DeleteDomain($id){
        $domain_name = DB::table('domains')
            ->where('id', $id)
            ->first(['domain_name']);

        DB::table('domains')->where('id', $id)->delete();

        // to save any action in AccessLog table
        AccessLog::create([
            'user_id' => Auth::user()->id,
            'user_name' => Auth::user()->username,
            'section_id' => $id,
            'section_name' => $domain_name->domain_name,
            'user_role' => Auth::user()->role_id,
            'section'   => 'DomainTemplates',
            'action'    => 'Deleted',
            'ip_address' => request()->ip(),
            'location' => json_encode(\Location::get(request()->ip())),
            'request_method' => ""
        ]);
    }

    public function getAllTrafficSorce(){
        $GetAllTrafficSorce = DB::table('lead_traffic_sources')->select('id','name')->get();
        return $GetAllTrafficSorce;
    }
    function changeStatus(Request $request){
        $status = $request->status;
        $deletedomain =  DB::table('domains')
            ->where('id', $request->id)
            ->update(['status' => $status]);

        // to save any action in AccessLog table
        AccessLog::create([
            'user_id' => Auth::user()->id,
            'user_name' => Auth::user()->username,
            'section_id' => $request->id,
            'section_name' => $request->name,
            'user_role' => Auth::user()->role_id,
            'section'   => 'DomainTemplates',
            'action'    => 'Updated',
            'ip_address' => request()->ip(),
            'location' => json_encode(\Location::get(request()->ip())),
            'request_method' => json_encode($request->all())
        ]);

        return $deletedomain;
    }
}
