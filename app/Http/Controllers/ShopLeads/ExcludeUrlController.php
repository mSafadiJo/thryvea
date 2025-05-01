<?php

namespace App\Http\Controllers\ShopLeads;

use App\AccessLog;
use App\Campaign;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class ExcludeUrlController extends Controller
{
    public function __construct(Request $request)
    {
        $request->permission_page = '11-0';
        if( $request->is("ExcludeUrl") ){
            $request->permission_page = '11-9';
        } else if( $request->is("ExcludeUrlEdit") ){
            $request->permission_page = '11-2';
        }

        $this->middleware(['auth', 'AdminMiddleware', 'PermissionUsers']);
    }

    public function index()
    {
        $campaign_Data = Campaign::join('users', 'campaigns.user_id', '=', 'users.id')
            ->where('users.user_visibility', 1)
            ->where('campaigns.campaign_visibility', 1)
            ->whereNotNull('campaigns.exclude_url')
            ->get();

        $ListOfBuyers = User::whereIn('role_id', [3, 4, 6])->where('user_visibility', 1)->orderBy('created_at', 'DESC')->get();

        return view('Admin.ShopLead.ExcludeUrl.index', compact("ListOfBuyers", "campaign_Data"));
    }

    public function saveUrl(Request $request){
        $this->validate($request, [
            'BuyerId' => ['required', 'string', 'max:255'],
            'CampaignsShopLead' => ['required', 'string', 'max:255'],
            'UrlList' => ['required', 'string'],
        ]);

        $message = "";
        $Url_List_array = explode(',', preg_replace('/\s+/', '', strtolower($request->UrlList)));

        $campaign_url = Campaign::where('campaign_id', $request->CampaignsShopLead)->first(['exclude_url', 'campaign_name']);
        if(!empty($campaign_url->exclude_url)){
            $message = "This campaign (" . $request->CampaignsShopLead . ") has been excluded already.";
        } else {
            Campaign::where('campaign_id', $request->CampaignsShopLead)
                ->update([
                    'exclude_url' => json_encode($Url_List_array),
                ]);

            //Access LOG
            AccessLog::create([
                'user_id' => Auth::user()->id,
                'user_name' => Auth::user()->username,
                'section_id' => $request->CampaignsShopLead,
                'section_name' => $campaign_url->campaign_name,
                'user_role' => Auth::user()->role_id,
                'section'   => 'ShopLeads',
                'action'    => 'Create (Exclude Url)',
                'ip_address' => request()->ip(),
                'location' => json_encode(\Location::get(request()->ip())),
                'request_method' => json_encode($request->all())
            ]);
        }

        return redirect()->back()->with('message', $message);
    }

    public function EditUrl($id)
    {
        $campaign_Data = Campaign::join('users', 'campaigns.user_id', '=', 'users.id')
            ->where('campaigns.campaign_id', $id)
            ->first();

        return view('Admin.ShopLead.ExcludeUrl.edit', compact( 'campaign_Data'));
    }

    public function UpdateUrl(Request $request)
    {
        $this->validate($request, [
            'BuyerId' => ['required', 'string', 'max:255'],
            'CampaignsShopLead' => ['required', 'string', 'max:255'],
            'UrlList' => ['required', 'string'],
        ]);

        $Url_List_array = explode(',', preg_replace('/\s+/', '', strtolower($request->UrlList)) );

        Campaign::where('campaign_id', $request->CampaignsShopLead)
            ->update([
                'exclude_url' => json_encode($Url_List_array),
            ]);

        //To get campaign name
        $campaign_Data = Campaign::where('campaign_id', $request->CampaignsShopLead)->first('campaign_name');

        //Access LOG
        AccessLog::create([
            'user_id' => Auth::user()->id,
            'user_name' => Auth::user()->username,
            'section_id' => $request->CampaignsShopLead,
            'section_name' => $campaign_Data->campaign_name,
            'user_role' => Auth::user()->role_id,
            'section'   => 'ShopLeads',
            'action'    => 'Updated (Exclude Url)',
            'ip_address' => request()->ip(),
            'location' => json_encode(\Location::get(request()->ip())),
            'request_method' => json_encode($request->all())
        ]);

        return Redirect::route('ExcludeUrl');
    }

    public function DeleteUrl(Request $request, $id)
    {
        //To get campaign name
        $campaign_Data = Campaign::where('campaign_id', $id)->first('campaign_name');

        Campaign::where('campaign_id', $id)
            ->update([
                'exclude_url' => null,
            ]);

        //Access LOG
        AccessLog::create([
            'user_id' => Auth::user()->id,
            'user_name' => Auth::user()->username,
            'section_id' => $id,
            'section_name' => $campaign_Data->campaign_name,
            'user_role' => Auth::user()->role_id,
            'section'   => 'ShopLeads',
            'action'    => 'Deleted (Exclude Url)',
            'ip_address' => request()->ip(),
            'location' => json_encode(\Location::get(request()->ip())),
            'request_method' => json_encode($request->all())
        ]);

        return Redirect::back();
    }
}
