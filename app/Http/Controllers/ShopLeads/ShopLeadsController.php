<?php

namespace App\Http\Controllers\ShopLeads;

use App\AccessLog;
use App\Campaign;
use App\Http\Controllers\Controller;
use App\MarketingPlatform;
use App\Models\SourcePercentage;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use function GuzzleHttp\Promise\all;

class ShopLeadsController extends Controller
{
    public function __construct(Request $request)
    {
        $request->permission_page = '11-0';
        if( $request->is("ShopLeads") ){
            $request->permission_page = '11-5';
        } else if( $request->is("ShopLeadsEdit") ){
            $request->permission_page = '11-2';
        }

        $this->middleware(['auth', 'AdminMiddleware', 'PermissionUsers']);
    }

    public function index()
    {
        $ListOfBuyers = User::whereIn('role_id', [3, 4, 6])->where('user_visibility', 1)->orderBy('created_at', 'DESC')->get();

        $platforms = new MarketingPlatform();
        $platforms = $platforms->get();

        $campaign_Data = Campaign::join('users','campaigns.user_id', '=', 'users.id')
            ->where('user_visibility', 1)
            ->whereNotNull('percentage_value')
            ->get();


        return view('Admin.ShopLead.index')
            ->with('ListOfBuyers', $ListOfBuyers)
            ->with('platforms',$platforms)
            ->with('campaign_Data',$campaign_Data);
    }

    public function getAllCampaignsByBuyer(Request $request)
    {
        $campaigns = DB::table('campaigns')
            ->where('campaign_visibility', 1)
            ->where('campaign_status_id', 1)
            ->where('user_id', $request['BuyerId'])
            ->where('is_seller', 0)
            ->whereNull('percentage_value')
            ->orderBy('campaigns.created_at', 'DESC')
            ->get();


        return $campaigns ;

    }

    public function getAllSourceByCampaign(Request $request)
    {
        $campaignsId = $request['CampaignId'];
        $campaigns = DB::table('campaigns')->where('campaigns.campaign_id',$campaignsId)->first();
        //to convert lead_source from string to array
        $array = str_replace('[','',$campaigns->lead_source);
        $array = str_replace(']','',$array);
        $array = str_replace('"','',$array);
        $lead_sourceArray = explode(',', $array);

        if (in_array("All Source", $lead_sourceArray) || empty($campaigns->lead_source) || $campaigns->lead_source == "[]" ) {
            $platforms = DB::table('marketing_platforms')->get();
        } else {
            $platforms = DB::table('marketing_platforms')->whereIn('name',$lead_sourceArray)->get();
        }

        return $platforms ;

    }

    public function saveShopLead(Request $request)
    {
        $percentage_value = array();

        for($i=0;$i<count($request->percentage);$i++)
        {
            if($request->percentage[$i] == $request->has($request->percentage[$i]))
            {
                $value = $request->percentage[$i];
                $LeadSourceID = substr($value, strpos($value, "_") + 1);

                $LeadSource_value = $request->$value;

                if(!empty($LeadSource_value) || $LeadSource_value == "0" ){
                    $percentage_value[$LeadSourceID] = $LeadSource_value;
                }

            }
        }
        DB::table('campaigns')->where('campaign_id', $request->CampaignsShopLead)
            ->update([
                'percentage_value'   => json_encode($percentage_value),
                'created_percentage_value'   => date('Y-m-d H:i:s'),
            ]);

        //To get campaign name
        $campaign_Data = Campaign::where('campaign_id', $request->CampaignsShopLead)->first();

        //Access LOG
        AccessLog::create([
            'user_id' => Auth::user()->id,
            'user_name' => Auth::user()->username,
            'section_id' => $request->CampaignsShopLead,
            'section_name' => $campaign_Data->campaign_name,
            'user_role' => Auth::user()->role_id,
            'section'   => 'ShopLeads',
            'action'    => 'Create (Sources Percentage)',
            'ip_address' => request()->ip(),
            'location' => json_encode(\Location::get(request()->ip())),
            'request_method' => json_encode($request->all())
        ]);

        return Redirect::back();
    }

    public function EditShopLead($id)
    {

        $campaign_Data = Campaign::join('users','campaigns.user_id', '=', 'users.id')
            ->where('campaigns.campaign_id', $id)->first();

        $percentage_value_array = json_decode($campaign_Data->percentage_value,true) ;
        $platforms = DB::table('marketing_platforms')->get();


        return view('Admin.ShopLead.UpdateShopLead')
            ->with('campaign_Data', $campaign_Data)
            ->with('platforms',$platforms)
            ->with('percentage_value_array',$percentage_value_array);

    }

    public function UpdateShopLead(Request $request)
    {

        $percentage_value = array();
        $Length = count($request->all()) - 3;
        for($i=0;$i<$Length;$i++) {
            if (!empty($request->{$i}) || $request->{$i} == "0" ) {
                $value = $request->{$i};
                $LeadSourceID = $i;

                $percentage_value[$LeadSourceID] = $value;
            }
        }


        DB::table('campaigns')->where('campaign_id', $request->CampaignsShopLead)
            ->update([
                'percentage_value'   => json_encode($percentage_value),
                'created_percentage_value'   => date('Y-m-d H:i:s'),
            ]);


        //To get campaign name
        $campaign_Data = Campaign::where('campaign_id', $request->CampaignsShopLead)->first();

        //Access LOG
        AccessLog::create([
            'user_id' => Auth::user()->id,
            'user_name' => Auth::user()->username,
            'section_id' => $request->CampaignsShopLead,
            'section_name' => $campaign_Data->campaign_name,
            'user_role' => Auth::user()->role_id,
            'section'   => 'ShopLeads',
            'action'    => 'Updated (Sources Percentage)',
            'ip_address' => request()->ip(),
            'location' => json_encode(\Location::get(request()->ip())),
            'request_method' => json_encode($request->all())
        ]);

        return redirect()->route('ShopLeads');
    }

    public function DeleteShopLead(Request $request, $id)
    {
        //To get campaign name
        $campaign_Data = Campaign::where('campaign_id', $id)->first();

        DB::table('campaigns')->where('campaign_id', $id)
            ->update([
                'percentage_value'   => null,
            ]);

        //Access LOG
        AccessLog::create([
            'user_id' => Auth::user()->id,
            'user_name' => Auth::user()->username,
            'section_id' => $id,
            'section_name' => $campaign_Data->campaign_name,
            'user_role' => Auth::user()->role_id,
            'section'   => 'ShopLeads',
            'action'    => 'Deleted (Sources Percentage)',
            'ip_address' => request()->ip(),
            'location' => json_encode(\Location::get(request()->ip())),
            'request_method' => json_encode($request->all())
        ]);

        return redirect()->route('ShopLeads');
    }

}
