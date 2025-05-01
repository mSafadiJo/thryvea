<?php

namespace App\Http\Controllers\ShopLeads;

use App\AccessLog;
use App\Campaign;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class ExcludeSellersController extends Controller
{
    public function __construct(Request $request)
    {
        $request->permission_page = '11-0';
        if( $request->is("ExcludeSellers") ){
            $request->permission_page = '11-6';
        } else if( $request->is("ExcludeSellersEdit") ){
            $request->permission_page = '11-2';
        }

        $this->middleware(['auth', 'AdminMiddleware', 'PermissionUsers']);
    }

    public function index()
    {
        $campaign_Data = Campaign::join('users','campaigns.user_id', '=', 'users.id')
            ->where('user_visibility', 1)
            ->whereNotNull('exclude_include_campaigns')
            ->get();

        $ListOfBuyers = User::whereIn('role_id', [3, 4, 6])->where('user_visibility', 1)->orderBy('created_at', 'DESC')->get();
        $ListOfSellers = User::whereIn('users.role_id', ['4', '5'])->where('user_visibility', 1)->get();

        return view('Admin.ShopLead.ExcludeSellers.index')
            ->with('ListOfBuyers', $ListOfBuyers)
            ->with('ListOfSellers', $ListOfSellers)
            ->with('campaign_Data',$campaign_Data);
    }

    public function getAllCampaignsByBuyer(Request $request)
    {
        $campaigns = DB::table('campaigns')
            ->where('campaign_visibility', 1)
            ->where('campaign_status_id', 1)
            ->where('user_id', $request['BuyerId']);

        if($request['type'] != 4){
            $campaigns->where('is_seller', 0);
        } else {
            $campaigns->where('is_seller', 1);
        }

        if($request['type'] == 2){
            $campaigns->whereNull('exclude_include_campaigns');
        }

        $campaigns = $campaigns->orderBy('campaigns.created_at', 'DESC')->get();

        return $campaigns ;
    }

    public function saveShopLead(Request $request){
        $this->validate($request, [
            'BuyerID' => ['required'],
            'CampaignsShopLead' => ['required'],
            'seller_id'    => ['required'],
            'SellerTypes'    => ['required']
        ]);

        DB::table('campaigns')->where('campaign_id', $request->CampaignsShopLead)
            ->update([
                'exclude_include_type'        => $request->SellerTypes,
                'exclude_include_campaigns'   => json_encode($request->seller_id),
                'created_exclude_include'   => date('Y-m-d H:i:s'),
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
            'action'    => 'Create (Exclude Sellers)',
            'ip_address' => request()->ip(),
            'location' => json_encode(\Location::get(request()->ip())),
            'request_method' => json_encode($request->all())
        ]);

        return Redirect::back();
    }

    public function EditShopLead($id)
    {

        $campaign_Data = Campaign::join('users','campaigns.user_id', '=', 'users.id')
            ->where('user_visibility', 1)
            ->where('campaign_id', $id)
            ->first();

        $ListOfSellers = User::whereIn('users.role_id', ['4', '5'])
            ->where('user_visibility', 1)
            ->orderBy('created_at', 'DESC')->get();


        return view('Admin.ShopLead.ExcludeSellers.edit', compact('ListOfSellers', 'campaign_Data'));
    }

    public function UpdateShopLead(Request $request)
    {
        $this->validate($request, [
            'BuyerID' => ['required'],
            'CampaignsShopLead' => ['required'],
            'seller_id'    => ['required'],
            'SellerTypes'    => ['required']
        ]);

        DB::table('campaigns')->where('campaign_id', $request->CampaignsShopLead)
            ->update([
                'exclude_include_type'        => $request->SellerTypes,
                'exclude_include_campaigns'   => json_encode($request->seller_id),
                'created_exclude_include'   => date('Y-m-d H:i:s'),
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
            'action'    => 'Updated (Exclude Sellers)',
            'ip_address' => request()->ip(),
            'location' => json_encode(\Location::get(request()->ip())),
            'request_method' => json_encode($request->all())
        ]);

        return Redirect::route('ExcludeAndIncludeSellers');
    }

    public function DeleteShopLead(Request $request, $id)
    {
        //To get campaign name
        $campaign_Data = Campaign::where('campaign_id', $id)->first();

        DB::table('campaigns')->where('campaign_id', $id)
            ->update([
                'exclude_include_type'        => null,
                'exclude_include_campaigns'   => null,
            ]);

        //Access LOG
        AccessLog::create([
            'user_id' => Auth::user()->id,
            'user_name' => Auth::user()->username,
            'section_id' => $id,
            'section_name' => $campaign_Data->campaign_name,
            'user_role' => Auth::user()->role_id,
            'section'   => 'ShopLeads',
            'action'    => 'Deleted (Exclude Sellers)',
            'ip_address' => request()->ip(),
            'location' => json_encode(\Location::get(request()->ip())),
            'request_method' => json_encode($request->all())
        ]);

        return Redirect::back();
    }
}
