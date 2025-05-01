<?php

namespace App\Http\Controllers\ShopLeads;

use App\AccessLog;
use App\Campaign;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class ExcludeSourcesController extends Controller
{
    public function __construct(Request $request)
    {
        $request->permission_page = '11-0';
        if( $request->is("ExcludeSources") ){
            $request->permission_page = '11-8';
        } else if( $request->is("ExcludeSourcesEdit") ){
            $request->permission_page = '11-2';
        }

        $this->middleware(['auth', 'AdminMiddleware', 'PermissionUsers']);
    }

    public function index()
    {
        $campaign_Data = Campaign::join('users', 'campaigns.user_id', '=', 'users.id')
            ->where('users.user_visibility', 1)
            ->where('campaigns.campaign_visibility', 1)
            ->whereNotNull('campaigns.exclude_sources')
            ->where('campaigns.is_seller', 0)
            ->get();

        $ListOfBuyers = User::whereIn('role_id', [3, 4, 6])->where('user_visibility', 1)->orderBy('created_at', 'DESC')->get();

        return view('Admin.ShopLead.ExcludeSources.index', compact("ListOfBuyers", "campaign_Data"));
    }

    public function saveSources(Request $request){
        $this->validate($request, [
            'BuyerId' => ['required', 'string', 'max:255'],
            'CampaignsShopLead' => ['required', 'string', 'max:255'],
            'SourcesList' => ['required', 'string'],
        ]);

        $message="";
        $Sources_List_array = explode(',', preg_replace('/\s+/', '', strtolower($request->SourcesList)));

        $campaign_sources = Campaign::where('campaign_id', $request->CampaignsShopLead)->first(['exclude_sources', 'campaign_name']);
        if(!empty($campaign_sources->exclude_sources)){
            $message = "This campaign (" . $request->CampaignsShopLead . ") has been excluded already.";
        } else {
            Campaign::where('campaign_id', $request->CampaignsShopLead)
                ->update([
                    'exclude_sources' => json_encode($Sources_List_array),
                ]);

            //Access LOG
            AccessLog::create([
                'user_id' => Auth::user()->id,
                'user_name' => Auth::user()->username,
                'section_id' => $request->CampaignsShopLead,
                'section_name' => $campaign_sources->campaign_name,
                'user_role' => Auth::user()->role_id,
                'section'   => 'ShopLeads',
                'action'    => 'Create (Exclude Sources)',
                'ip_address' => request()->ip(),
                'location' => json_encode(\Location::get(request()->ip())),
                'request_method' => json_encode($request->all())
            ]);
        }

        return redirect()->back()->with('message', $message);
    }

    public function EditSources($id)
    {
        $campaign_Data = Campaign::join('users', 'campaigns.user_id', '=', 'users.id')
            ->where('campaigns.campaign_id', $id)
            ->first();

        return view('Admin.ShopLead.ExcludeSources.edit', compact( 'campaign_Data'));
    }

    public function UpdateSources(Request $request)
    {
        $this->validate($request, [
            'BuyerId' => ['required', 'string', 'max:255'],
            'CampaignsShopLead' => ['required', 'string', 'max:255'],
            'SourcesList' => ['required', 'string'],
        ]);

        $Sources_List_array = explode(',', preg_replace('/\s+/', '', strtolower($request->SourcesList)) );

        Campaign::where('campaign_id', $request->CampaignsShopLead)
            ->update([
                'exclude_sources' => json_encode($Sources_List_array),
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
            'action'    => 'Updated (Exclude Sources)',
            'ip_address' => request()->ip(),
            'location' => json_encode(\Location::get(request()->ip())),
            'request_method' => json_encode($request->all())
        ]);

        return Redirect::route('ExcludeSources');
    }

    public function DeleteSources(Request $request, $id)
    {
        //To get campaign name
        $campaign_Data = Campaign::where('campaign_id', $id)->first('campaign_name');

        Campaign::where('campaign_id', $id)
            ->update([
                'exclude_sources' => null,
            ]);

        //Access LOG
        AccessLog::create([
            'user_id' => Auth::user()->id,
            'user_name' => Auth::user()->username,
            'section_id' => $id,
            'section_name' => $campaign_Data->campaign_name,
            'user_role' => Auth::user()->role_id,
            'section'   => 'ShopLeads',
            'action'    => 'Deleted (Exclude Sources)',
            'ip_address' => request()->ip(),
            'location' => json_encode(\Location::get(request()->ip())),
            'request_method' => json_encode($request->all())
        ]);

        return Redirect::back();
    }
}
