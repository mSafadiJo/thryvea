<?php

namespace App\Http\Controllers\Seller;

use App\City;
use App\County;
use App\MarketingPlatform;
use App\Services\AllServicesQuestions;
use App\User;
use App\ZipCodesList;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use App\AccessLog;
use App\Campaign;
use App\Service_Campaign;
use App\State;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\SalesOrder;
use App\Services\ApiMain;

class CampaignController extends Controller
{

    public function __construct()
    {
        $this->middleware(['auth', 'AdminMiddleware']);
        ini_set('max_execution_time', '0');
        ini_set('memory_limit', '-1');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index(Request $request)
    {
        $col_name = null;
        $query = null;
        $sort_search = null;
        $sort_type = null;

        $users = DB::table('users')
            ->whereIn('users.role_id', ['5', '4'])
            ->where('users.user_visibility', 1);

        if ($request->type != null){
            $var = explode(",", $request->type);
            $col_name = $var[0];
            $query = $var[1];
            $users = $users->orderBy($col_name, $query);
            $sort_type = $request->type;
        }
        if ($request->search != null){
            $sort_search = $request->search;
            $users = $users->where('users.user_business_name', 'like', '%'.$sort_search.'%');
            $sort_search = $request->search;
        }

//        $users = $users->orderBy('users.created_at', 'DESC')->paginate(15);
        $users = $users->orderBy('users.created_at', 'DESC')->get();

        return view('Seller.Campaigns.index', compact('users', 'col_name', 'query', 'sort_search', 'sort_type'));
    }

    public function list($id)
    {
        $campaigns = DB::table('campaigns')
            ->join('service__campaigns', 'service__campaigns.service_campaign_id', '=', 'campaigns.service_campaign_id')
            ->join('campaign_status', 'campaign_status.campaign_status_id', '=', 'campaigns.campaign_status_id')
            ->join('users', 'users.id', '=', 'campaigns.user_id')
            ->orderBy('campaigns.created_at', 'DESC')
            ->where('campaigns.campaign_visibility', 1)
            ->where('campaigns.user_id', $id)
            ->where('campaigns.is_seller', 1)
            ->orderBy('campaigns.created_at', 'DESC')
            ->get([
                'campaigns.*', 'service__campaigns.service_campaign_name', 'campaign_status.campaign_status_name',
                'users.username', 'users.user_business_name'
            ]);

        $services = DB::table('service__campaigns')->get()->All();
        $sellers = DB::table('users')->whereIn('users.role_id', ['4', '5'])->where('user_visibility', 1)->get()->All();
        $campaign_status = DB::table('campaign_status')->get()->all();

        $data = array(
            'services' => $services,
            'sellers' => $sellers,
            'id' => $id
        );

        return view('Seller.Campaigns.list')
            ->with('data', $data)
            ->with('campaigns', $campaigns)
            ->with('campaign_status', $campaign_status);
    }

    public function filterList(Request $request)
    {
        $seller_id = $request->seller_id;
        $service_id = $request->service_id;

        $campaigns = DB::table('campaigns')
            ->join('service__campaigns', 'service__campaigns.service_campaign_id', '=', 'campaigns.service_campaign_id')
            ->join('campaign_status', 'campaign_status.campaign_status_id', '=', 'campaigns.campaign_status_id')
            ->join('users', 'users.id', '=', 'campaigns.user_id')
            ->where('campaigns.campaign_visibility', 1)
            ->where('campaigns.is_seller', 1);

        if (!empty($seller_id)) {
            $campaigns->whereIn('users.id', $seller_id);
        }

        if (!empty($service_id)) {
            $campaigns->whereIn('campaigns.service_campaign_id', $service_id);
        }

        $campaigns = $campaigns->orderBy('campaigns.created_at', 'DESC')
            ->get([
                'campaigns.*', 'service__campaigns.service_campaign_name', 'campaign_status.campaign_status_name',
                'users.username', 'users.user_business_name'
            ]);

        $campaign_status = DB::table('campaign_status')->get()->all();

        $permission_users = array();
        if( !empty(Auth::user()->permission_users) ){
            $permission_users = json_decode(Auth::user()->permission_users, true);
        }

        $dataJason = '';
        $dataJason .= '<table id="datatable-buttons3" class="table table-striped table-bordered" cellspacing="0" width="100%">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Vendor Id</th>
                                    <th>Campaign Name</th>
                                    <th>Campaign Type</th>
                                    <th>Seller</th>
                                    <th>Service</th>
                                    <th>Profit</th>
                                    <th>Created At</th>
                                    <th>Status</th>
                                    <th>Active</th>';
        if( empty($permission_users) || in_array('12-1', $permission_users) || in_array('12-2', $permission_users) || in_array('12-3', $permission_users) || in_array('12-7', $permission_users) ) {
            $dataJason .= '<th>Action</th>';
        }

        $dataJason .= ' </tr>
                                </thead>
                                <tbody>';

        if (!empty($campaigns)) {
            foreach ($campaigns as $campaign) {
                $dataJason .= "<tr>";
                $dataJason .= "<td>" . $campaign->campaign_id . "</td>";
                $dataJason .= "<td>" . $campaign->vendor_id . "</td>";
                $dataJason .= "<td>" . $campaign->campaign_name . "</td>";
                $dataJason .= "<td>";
                if ($campaign->is_ping_account == 1) {
                    $dataJason .= "PING & POST";
                } else {
                    $dataJason .= "Direct POST";
                }
                $dataJason .= "</td>";
                $dataJason .= "<td>" . $campaign->user_business_name . "</td>";
                $dataJason .= "<td>" . $campaign->service_campaign_name . "</td>";
                $dataJason .= "<td>" . $campaign->campaign_budget_bid_exclusive . "</td>";
                $dataJason .= "<td>" . date('Y/m/d', strtotime($campaign->created_at)) . "</td>";
                if (empty($permission_users) || in_array('12-2', $permission_users)) {
                    $dataJason .= "<td>";
                    $dataJason .= '<select name="campaign_status" class="form-control" style="height: unset;width: 80%;" id="admincampaign_status_table_Ajax_changing-' . $campaign->campaign_id . '"
                                                        onchange="return admincampaign_status_table_Ajax_changing(' . $campaign->campaign_id . ');"';

                    if( Auth::user()->role_id != 1 && $campaign->campaign_status_id == 3 ){
                        $dataJason .= ' disabled ';
                    }
                    $dataJason .= '>';

                    if (!empty($campaign_status)) {
                        foreach ($campaign_status as $status) {
                            if( $status->campaign_status_id == 3 ){
                                if( $campaign->campaign_status_id == 3 ){
                                    $dataJason .= '<option value="' . $status->campaign_status_id . '-' . $campaign->campaign_id . '"';
                                    if ($campaign->campaign_status_id == $status->campaign_status_id) {
                                        $dataJason .= 'selected';
                                    }
                                    $dataJason .= '>' . $status->campaign_status_name . '</option>';
                                }
                            } else {
                                $dataJason .= '<option value="' . $status->campaign_status_id . '-' . $campaign->campaign_id . '"';
                                if ($campaign->campaign_status_id == $status->campaign_status_id) {
                                    $dataJason .= 'selected';
                                }
                                $dataJason .= '>' . $status->campaign_status_name . '</option>';
                            }
                        }
                    }

                    $dataJason .= '</select>';
                    $dataJason .= '</td>';
                } else {
                    if ($campaign->campaign_status_id == 1 ){
                        $dataJason .= "<td>Running</td>";
                    } else {
                        $dataJason .= "<td>Stopped</td>";
                    }
                }

                $dataJason .= '<td>';
                if ($campaign->campaign_visibility == 1) {
                    $dataJason .= 'Active';
                } else {
                    $dataJason .= 'Not Active';
                }
                $dataJason .= '</td>';
                if( empty($permission_users) || in_array('12-1', $permission_users) || in_array('12-2', $permission_users) || in_array('12-3', $permission_users) || in_array('12-7', $permission_users) ) {
                    $dataJason .= '<td>';
                    if ($campaign->campaign_visibility == 1) {
                        if (empty($permission_users) || in_array('12-2', $permission_users) || in_array('12-7', $permission_users)) {
                            $dataJason .= '<a href="' . route("Campaigns.edit", $campaign->campaign_id) . '" class="on-default edit-row" data-toggle="tooltip" data-placement="top" title="" data-original-title="Edit" data-trigger="hover" data-animation="false">
                                        <i class="fa fa-pencil"></i>
                                   </a>';
                        }

                        if( empty($permission_users) || in_array('12-1', $permission_users) ) {
                            $dataJason .= '<span style="cursor: pointer;color: #36c736;" class="on-default remove-row" data-toggle="tooltip" data-placement="top" title="" data-original-title="Add Clone" data-trigger="hover" data-animation="false">
                                        <i class="mdi mdi-plus-circle-outline" data-toggle="modal" data-target="#con-close-modal"
                                            onclick="return document.getElementById(\'campaign_id_menu\').value = \'' . $campaign->campaign_id . '\';"></i>
                                   </span>';
                        }

                        if( empty($permission_users) || in_array('12-3', $permission_users) ) {
                            $dataJason .= '<form style="display: inline-block" action="' . route("AdminCampaignDelete") . '" method="post" id="DeleteCampaignForm-' . $campaign->campaign_id . '">';
                            $dataJason .= csrf_field();
                            $dataJason .= '<input type="hidden" class="form-control" value="' . $campaign->campaign_id . '" name="campaign_id">
                                                    </form>';
                            $dataJason .= '<span style="cursor: pointer;" class="on-default remove-row" data-toggle="tooltip" data-placement="top" title="" data-original-title="Delete" data-trigger="hover" data-animation="false"
                                        onclick="return DeleteCampaignForm(\'' . $campaign->campaign_id . '\');">
                                        <i class="fa fa-trash-o"></i>
                                   </span>';
                        }

                    }
                    $dataJason .= "</td>";
                }
                $dataJason .= "</tr>";
            }
        }

        $dataJason .= '</tbody>
                            </table>';

        return $dataJason;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if(Cache::has('AllState')){
            $states = Cache::get("AllState");
        } else {
            $states = State::All();
        }

        $address = array(
            'states' => $states,
        );

        $utility_providers = DB::table('lead_current_utility_provider')->groupBy('lead_current_utility_provider_name')->get()->All();
        $services = DB::table('service__campaigns')->where('service_is_active', 1)->get()->All();
        $users = DB::table('users')->whereIn('role_id', [5 , 4])->where('user_visibility', 1)->orderBy('created_at', 'desc')->get()->All();
        $platforms = MarketingPlatform::All();

        return view('Seller.Campaigns.create')
            ->with('services', $services)
            ->with('users', $users)
            ->with('platforms', $platforms)
            ->with('address', $address)
            ->with('utility_providers', $utility_providers);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'Campaign_name' => ['required', 'string', 'max:255'],
            'service_id' => ['required', 'string', 'max:255'],
            'propertytype' => ['required'],
            'Installings' => ['required'],
            'homeowned' => ['required'],
            'seller_id' => ['required'],
            'typeOFLead_Source' => ['required'],
            'is_ping_account',
            'if_static_cost'
        ]);

        $crm = '0';
        $budget_bid_shared = 0;
        $budget_bid_exclusive = (!empty($request['budget_bid_exclusive']) ? $request['budget_bid_exclusive'] : 0);

        $specialSource = (!empty($request['special_source']) ?  explode(",", strtolower($request['special_source'])) : '');

        $campaign = new Campaign();

        $campaign->campaign_name = $request['Campaign_name'];
        $campaign->campaign_count_lead_exclusive = $request['numberOfCustomerCampaign_exclusive'];
        $campaign->campaign_budget_exclusive = 0;
        $campaign->period_campaign_count_lead_id_exclusive = $request['numberOfCustomerCampaign_period_exclusive'];
        $campaign->period_campaign_budget_id_exclusive = 1;
        $campaign->campaign_count_lead = $request['numberOfCustomerCampaign_exclusive'];
        $campaign->campaign_budget = 0;
        $campaign->period_campaign_count_lead_id = $request['numberOfCustomerCampaign_period_exclusive'];
        $campaign->period_campaign_budget_id = 1;
        $campaign->service_campaign_id = $request['service_id'];
        $campaign->campaign_status_id = (Auth::user()->role_id == 1 ? 1 : 3);

        $campaign->user_id = $request['seller_id'];
        $campaign->campaign_distance_area = $request['distance_area'];
        $campaign->campaign_budget_bid_exclusive = $budget_bid_exclusive;
        $campaign->campaign_budget_bid_shared = $budget_bid_shared;
        $campaign->campaign_Type = 1;
        $campaign->crm = $crm;
        $campaign->is_ping_account = (empty($request['is_ping_account']) ? 0 : $request['is_ping_account']);
        $campaign->is_utility_solar_filter = (empty($request['is_utility_providers']) ? 0 : $request['is_utility_providers']);
        $campaign->typeOFLead_Source = $request['typeOFLead_Source'];
        $campaign->is_seller = 1;
        $campaign->vendor_id = $request['typeOFLead_Source'];
        $campaign->if_static_cost = ($request['if_static_cost'] == 1 ?  $request['if_static_cost'] : 0);
        $campaign->special_budget_bid_exclusive = (!empty($request['special_budget_bid_exclusive']) ?  $request['special_budget_bid_exclusive'] : 0);
        $campaign->special_state = (!empty($request['special_state']) ? json_encode($request['special_state']) : '[]');
        $campaign->special_source = (!empty($specialSource) ? json_encode($specialSource) : '[]');
        $campaign->special_source_price = (!empty($request['special_source_price']) ?  $request['special_source_price'] : 0);
        $campaign->vendor_id = time();
        $campaign->crm = "[]";
        $campaign->is_multi_crms = 0;

        $campaign->save();
        $campaign_id = DB::getPdo()->lastInsertId();

        $campaignsQuestionsDB = DB::table('campaigns_questions');

        $allServicesQuestion = new AllServicesQuestions();

        $allServicesQuestion->insertFromCampaigns($campaignsQuestionsDB, $request, $campaign_id);

        //** import Zipcods from excel ***/
        $array_zipcode_distance         = array();
        $zip_codes_array_saved          = array();
        $zipcode_id_array               = array();
        $array_zipcode_distance_final   = array();
        //** import Zipcods from excel ***/
        if ($request->hasFile('listOfzipcode')) {
            $dataexcel = Excel::toArray(new SalesOrder, $request->file('listOfzipcode'));
            $dataexcel = array_unique($dataexcel[0], SORT_REGULAR);

            foreach ($dataexcel as $item) {
                // To fetch zipcode
//                if (Cache::has("AllZipCode")) {
//                    $zipcode_data_cache = Cache::get("AllZipCode")->where('zip_code_list', $item[0]);
//                    foreach ($zipcode_data_cache as $data) {
//                        $zipcode_id = $data->zip_code_list_id;
//                    }
//                } else {
                    $zipcode_id = DB::table('zip_codes_lists')->where('zip_code_list', $item[0])->first(['zip_code_list_id']);
                   // $zipcode_id = $zipcode_id->zip_code_list_id;
             //   }
                if (!empty($zipcode_id)) {
                    //** to save all zip code from excel to array */
                    $zipcode_id_array[] = $zipcode_id->zip_code_list_id;
                }
            }
        }
        else if (!empty($request['zipcode'])) {
            if (count($request['zipcode']) == 1 && !empty($request['distance_area']) && $request['distance_area'] != 0) {
                $zipcode_id_array [] = $request['zipcode']['0'];
                $distancekm = $request['distance_area'];
                // To fetch zipcode
                if(!empty($zipcode_data_cache)){
                    $zipcode_data_cache = $zipcode_data_cache->where('zip_code_list_id', $request['zipcode']['0']);
                    foreach($zipcode_data_cache as $item){
                        $zipcode = $item->zip_code_list;
                    }
                } else {
                    $zipcode_data = DB::table('zip_codes_lists')
                        ->where('zip_code_list_id', $request['zipcode']['0'])
                        ->first(['zip_code_list']);
                    $zipcode = $zipcode_data->zip_code_list;
                }

                if (!empty($zipcode)) {
                    $main_api_file = new ApiMain();
                    $decoded_result = $main_api_file->zipcodes_distance_filter($zipcode, $distancekm);

                    if (!empty($decoded_result['zip_codes'])) {
                        foreach ($decoded_result['zip_codes'] as $val) {
                            $array_zipcode_distance [] = $val['zip_code'];
                        }
                    }
                }
                //get all zipcode_id from zip code dis
                if (!empty($array_zipcode_distance)) {

                    // To fetch zip_codes_array
                    if(Cache::has('AllZipCode')){
                        $array_zipcode_distance_final = Cache::get("AllZipCode")->whereIn('zip_code_list', $array_zipcode_distance)->pluck('zip_code_list_id')->toArray();
                        $array_zipcode_distance_final = array_unique($array_zipcode_distance_final);
                    } else {
                        $array_zipcode_distance_final = DB::table('zip_codes_lists')->whereIn('zip_code_list', $array_zipcode_distance)->distinct('zip_code_list')->pluck('zip_code_list_id')->toArray();
                    }
                }
            } else {
                $array_zipcode_distance_final = array();
            }
        }

        //Zip_Cods not in Campaign
        //import Zipcods from excel
        //import Zipcods from excel EX
        $dataexcel_expect = array();
        $array_zipcodeEx_id = array();
        if( $request->hasFile('listOfzipcode_expect') ){
            $dataexcel_expect = Excel::toArray(new SalesOrder, $request->file('listOfzipcode_expect'));
            $dataexcel = array_unique($dataexcel_expect[0], SORT_REGULAR);

            foreach( $dataexcel as $item ){
                // To fetch zipcode
//                if(Cache::has('AllZipCode')){
//                    $zipcode_id_arrayEX = Cache::get("AllZipCode")->where('zip_code_list', $item[0]);
//                    foreach($zipcode_id_arrayEX as $item){
//                        $zipcode_idEX = $item->zip_code_list_id;
//                    }
//                } else {
                    $zipcode_idEX = DB::table('zip_codes_lists')->where('zip_code_list', $item[0])->first(['zip_code_list_id']);
               //     $zipcode_idEX = $zipcode_idEX->zip_code_list_id;
              //  }
                if( !empty($zipcode_idEX) ) {
                    $array_zipcodeEx_id[]= $zipcode_idEX->zip_code_list_id;
                }
            }
        }

        //marge zipcode id from input and distance
        $final_zip_code = array_merge($array_zipcode_distance_final , $zipcode_id_array);
        //get all state_id from zipcode_id
        if(Cache::has('AllZipCode')){
            $state_id_array =Cache::get('AllZipCode')->whereIn('zip_code_list_id', $final_zip_code)->pluck('state_id')->toArray();
            $state_id_array = array_unique($state_id_array);
        } else {
            $state_id_array =  ZipCodesList::join('states', 'states.state_id', '=', 'zip_codes_lists.state_id')
                ->whereIn('zip_codes_lists.zip_code_list_id', $final_zip_code)->distinct('states.state_id')->pluck('states.state_id')->toArray();
        }

        //get all state_id from city_id
        if (!empty($request['city'])) {
            if (Cache::has('AllCities')) {
                $city_id_array = Cache::get("AllCities")->whereIn('city_id', $request['city'])->pluck('state_id')->toArray();
                $city_id_array = array_unique($city_id_array);
            } else {
                $city_id_array = City::join('states', 'states.state_id', '=', 'cities.state_id')
                    ->whereIn('cities.city_id', $request['city'])->distinct('states.state_id')->pluck('states.state_id')->toArray();
            }
        } else {
            $city_id_array = array();
        }

        //get all state_id from county_id
        if(!empty($request['county'])) {
            if (Cache::has('AllCounty')) {
                $county_id_array = Cache::get("AllCounty")->whereIn('county_id', $request['county'])->pluck('state_id')->toArray();
                $county_id_array = array_unique($county_id_array);
            } else {
                $county_id_array = County::join('states', 'states.state_id', '=', 'counties.state_id')
                    ->whereIn('counties.county_id', $request['county'])->distinct('states.state_id')->pluck('states.state_id')->toArray();
            }
        } else {
            $county_id_array = array();
        }

        if(empty($request['state'])){
            $state_array = array();
        } else {
            $state_array=$request['state'];
        }

        //State Filter Campaign
        $state_id_array_final = array_merge($state_array, $state_id_array);
        $state_id_array_final = array_merge($state_id_array_final, $city_id_array);
        $state_id_array_final = array_merge($state_id_array_final, $county_id_array);
        $state_id_array_final = array_unique($state_id_array_final);

        if(Cache::has('AllState')){
            $states = Cache::get("AllState");
        } else {
            $states = State::All();
        }

        $stateCode = array();
        $stateName = array();
        foreach ($states as $state1) {
            if(in_array($state1->state_id, $state_id_array_final)) {
                $stateCode []= $state1->state_code;
                $stateName []= $state1->state_name;
            }
        }

        DB::table('campaign_target_area')->insert([
            'campaign_id'            => $campaign_id,
            'stateFilter_id'         => json_encode(array_values(array_unique(!empty($state_id_array_final) ? $state_id_array_final : array()))),
            'state_id'               => json_encode(!empty($request['state']) ? $request['state'] : array()),
            'county_id'              => json_encode(!empty($request['county']) ? $request['county'] : array()),
            'city_id'                => json_encode(!empty($request['city']) ? $request['city'] : array()),
            'zipcode_id'             => json_encode(array_values(array_unique(!empty($final_zip_code) ? $final_zip_code : array()))),
            'county_ex_id'           => json_encode(!empty($request['county_expect']) ? $request['county_expect'] : array()),
            'city_ex_id'             => json_encode(!empty($request['city_expect']) ? $request['city_expect'] : array()),
            'zipcode_ex_id'          => json_encode(array_values(array_unique(!empty($array_zipcodeEx_id) ? $array_zipcodeEx_id : array()))),
            'stateFilter_name'       => json_encode(array_values(array_unique(!empty($stateName) ? $stateName : array()))),
            'stateFilter_code'       => json_encode(array_values(array_unique(!empty($stateCode) ? $stateCode : array()))),
        ]);

        //Access LOG
        AccessLog::create([
            'user_id' => Auth::user()->id,
            'user_name' => Auth::user()->username,
            'section_id' => $campaign_id,
            'section_name' => $request['Campaign_name'],
            'user_role' => Auth::user()->role_id,
            'section' => 'SellerCampaign',
            'action' => 'Created',
            'ip_address' => request()->ip(),
            'location' => json_encode(\Location::get(request()->ip())),
            'request_method' => json_encode($request->all())
        ]);

        return redirect()->route('Admin.Seller.Campaigns.list', $request->seller_id);
    }

    public function AddClone(Request $request)
    {
        $this->validate($request, [
            'campaign_id' => ['required', 'string', 'max:255'],
            'campaign_name' => ['required', 'string', 'max:255'],
        ]);

        $campaign_id = $request['campaign_id'];

        $campaign_Data = Campaign::join('campaigns_questions', 'campaigns_questions.campaign_id', '=', 'campaigns.campaign_id')
            -> join('campaign_target_area', 'campaign_target_area.campaign_id', '=', 'campaigns.campaign_id')
            ->where('campaigns.campaign_id', $campaign_id)
            ->first();

        $campaign = new Campaign();

        $campaign->campaign_name = $request['campaign_name'];
        $campaign->campaign_count_lead = $campaign_Data->campaign_count_lead;
        $campaign->campaign_budget = $campaign_Data->campaign_budget;
        $campaign->period_campaign_count_lead_id = $campaign_Data->period_campaign_count_lead_id;
        $campaign->period_campaign_budget_id = $campaign_Data->period_campaign_budget_id;
        $campaign->campaign_count_lead_exclusive = $campaign_Data->campaign_count_lead_exclusive;
        $campaign->campaign_budget_exclusive = $campaign_Data->campaign_budget_exclusive;
        $campaign->period_campaign_count_lead_id_exclusive = $campaign_Data->period_campaign_count_lead_id_exclusive;
        $campaign->period_campaign_budget_id_exclusive = $campaign_Data->period_campaign_budget_id_exclusive;
        $campaign->service_campaign_id = $campaign_Data->service_campaign_id;
        $campaign->campaign_status_id = (Auth::user()->role_id == 1 ? 1 : 3);

        $campaign->user_id = $campaign_Data->user_id;
        $campaign->campaign_distance_area = $campaign_Data->campaign_distance_area;
        $campaign->campaign_distance_area_expect = $campaign_Data->campaign_distance_area_expect;
        $campaign->campaign_budget_bid_shared = $campaign_Data->campaign_budget_bid_shared;
        $campaign->campaign_budget_bid_exclusive = $campaign_Data->campaign_budget_bid_exclusive;
        $campaign->campaign_Type = $campaign_Data->campaign_Type;
        $campaign->crm = 0;
        $campaign->is_seller = 1;
        $campaign->typeOFLead_Source = $campaign_Data->typeOFLead_Source;
        $campaign->is_ping_account = $campaign_Data->is_ping_account;
        $campaign->is_utility_solar_filter = $campaign_Data->is_utility_solar_filter;
        $campaign->if_static_cost = $campaign_Data->if_static_cost;
        $campaign->special_budget_bid_exclusive = $campaign_Data->special_budget_bid_exclusive;
        $campaign->special_state = $campaign_Data->special_state;
        $campaign->special_source = strtolower($campaign_Data->special_source);
        $campaign->special_source_price = $campaign_Data->special_source_price;
        $campaign->vendor_id = time();
        $campaign->crm = "[]";
        $campaign->is_multi_crms = 0;

        $campaign->save();
        $new_campaign_id = DB::getPdo()->lastInsertId();

        $dbQuery = DB::table('campaigns_questions');

        $allServicesQuestions = new AllServicesQuestions();

        $allServicesQuestions->insertFromCloneCampaigns($dbQuery, $campaign_Data, $new_campaign_id);

        DB::table('campaign_target_area')->insert([
            'campaign_id'            => $new_campaign_id,
            'stateFilter_id'         => $campaign_Data->stateFilter_id,
            'state_id'               => $campaign_Data->state_id,
            'county_id'              => $campaign_Data->county_id,
            'city_id'                => $campaign_Data->city_id,
            'zipcode_id'             => $campaign_Data->zipcode_id,
            'county_ex_id'           => $campaign_Data->county_ex_id,
            'city_ex_id'             => $campaign_Data->city_ex_id,
            'zipcode_ex_id'          => $campaign_Data->zipcode_ex_id,
            'stateFilter_name'          => $campaign_Data->stateFilter_name,
            'stateFilter_code'          => $campaign_Data->stateFilter_code,
        ]);

        //Access LOG
        AccessLog::create([
            'user_id' => Auth::user()->id,
            'user_name' => Auth::user()->username,
            'section_id' => $campaign_id,
            'section_name' => $request['campaign_name'],
            'user_role' => Auth::user()->role_id,
            'section'   => 'SellerCampaign',
            'action'    => 'Created',
            'ip_address' => request()->ip(),
            'location' => json_encode(\Location::get(request()->ip())),
            'request_method' => json_encode($request->all())
        ]);

        return redirect()->back();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($campaign_id)
    {
        $campaign = Campaign::join('campaigns_questions', 'campaigns_questions.campaign_id', '=', 'campaigns.campaign_id')
            ->join('campaign_target_area', 'campaign_target_area.campaign_id', '=', 'campaigns.campaign_id')
            ->join('users', 'campaigns.user_id', '=', 'users.id')
            ->where('campaigns.campaign_id', $campaign_id)
            ->first();

        if( $campaign->campaign_visibility == 0 ){
            return redirect()->route('Admin.Seller.Campaigns.list'.$campaign_id);
        }

        // To fetch all State
        if(Cache::has('AllState')){
            $states = Cache::get("AllState");
        } else {
            $states = State::All();
        }

        $services = Service_Campaign::where('service_is_active', 1)->get()->all();
        $utility_providers = DB::table('lead_current_utility_provider')->groupBy('lead_current_utility_provider_name')->get()->All();
        $platforms = MarketingPlatform::All();

        // To fetch all cities EX & IN selected by the user
        if(Cache::has('AllCities')){
            $cities_in_campaign_arr = Cache::get('AllCities')->whereIn('city_id', json_decode($campaign->city_id, true));
            $cities_not_in_campaign_arr = Cache::get('AllCities')->whereIn('city_id', json_decode($campaign->city_ex_id, true));
        } else {
            $cities_in_campaign_arr = DB::table('cities')->whereIn('city_id', json_decode($campaign->city_id, true))->get(['city_name', 'city_id']);
            $cities_not_in_campaign_arr = DB::table('cities')->whereIn('city_id', json_decode($campaign->city_ex_id, true))->get(['city_name', 'city_id']);
        }

        // To fetch all counties
        if(Cache::has('AllCounty')){
            $counties_in_campaign_arr = Cache::get('AllCounty')->whereIn('county_id', json_decode($campaign->county_id, true));
            $counties_not_in_campaign_arr = Cache::get('AllCounty')->whereIn('county_id', json_decode($campaign->county_ex_id, true));
        } else {
            $counties_in_campaign_arr = DB::table('counties')->whereIn('county_id', json_decode($campaign->county_id, true))->get(['county_id', 'county_name']);
            $counties_not_in_campaign_arr = DB::table('counties')->whereIn('county_id', json_decode($campaign->county_ex_id, true))->get(['county_id', 'county_name']);
        }

        // To fetch zip_codes_array_in_campaign
        if(Cache::has('AllZipCode')){
            $zip_codes_array = Cache::get("AllZipCode")->whereIn('zip_code_list_id', json_decode($campaign->zipcode_id, true))->pluck('zip_code_list')->toArray();
            $zipcods_not_in_campaign_arr = Cache::get("AllZipCode")->whereIn('zip_code_list_id', json_decode($campaign->zipcode_ex_id, true))->pluck('zip_code_list')->toArray();
        } else {
            $zip_codes_array = DB::table('zip_codes_lists')
                ->whereIn('zip_code_list_id', json_decode($campaign->zipcode_id, true))
                ->pluck('zip_code_list')->toArray();

            $zipcods_not_in_campaign_arr = DB::table('zip_codes_lists')
                ->whereIn('zip_code_list_id', json_decode($campaign->zipcode_ex_id, true))
                ->pluck('zip_code_list')->toArray();
        }

        $states_in_campaign_arr = json_decode($campaign->state_id, true);
        $states_Filter_campaign_arr = json_decode($campaign->stateFilter_id, true);
        $zipcods_in_campaign_arr = json_decode($campaign->zipcode_id, true);
        $zipcods_counts = count(json_decode($campaign->zipcode_id, true));

        $address = array(
            'states' => $states,
            'states_in_campaign' => $states_in_campaign_arr,
            'states_Filter_campaign' => $states_Filter_campaign_arr,
            'cities_in_campaign' => $cities_in_campaign_arr,
            'cities_not_in_campaign' => $cities_not_in_campaign_arr,
            'counties_in_campaign' => $counties_in_campaign_arr,
            'counties_not_in_campaign' => $counties_not_in_campaign_arr,
            'zipcods_in_campaign' => $zipcods_in_campaign_arr,
            'zipcods_not_in_campaign' => $zipcods_not_in_campaign_arr,
            'zipcods_counts' => $zipcods_counts,
            'zip_codes_array' => $zip_codes_array,
        );

        return view('Seller.Campaigns.edit')
            ->with('campaign', $campaign)
            ->with('address', $address)
            ->with('services', $services)
            ->with('platforms', $platforms)
            ->with('utility_providers', $utility_providers);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $campaign_id)
    {
        $this->validate($request, [
            'Campaign_name' => ['required', 'string', 'max:255'],
            'propertytype' => ['required'],
            'Installings' => ['required'],
            'homeowned' => ['required'],
            'service_id'    => ['required'],
            'typeOFLead_Source' => ['required'],
            'seller_id' => ['required'],
            'is_ping_account',
            'if_static_cost'
        ]);




        $crm = '0';
        $budget_bid_shared = 0;
        $budget_bid_exclusive = (!empty($request['budget_bid_exclusive']) ? $request['budget_bid_exclusive'] : 0);

        if ($request['append_zip_codes'] == 1) {
            //to get all save zipcode
            $campaign_distance_area = $request['distance_area'];
        } else {
            $campaign_distance_area = "";
        }

        $specialSource = (!empty($request['special_source']) ?  explode(",", strtolower($request['special_source'])) : '');


        DB::table('campaigns')->where('campaign_id', $campaign_id)
            ->update([
                'campaign_name'                             => $request['Campaign_name'],
                'campaign_count_lead_exclusive'             => $request['numberOfCustomerCampaign_exclusive'],
                'period_campaign_count_lead_id_exclusive'   => $request['numberOfCustomerCampaign_period_exclusive'],
                'campaign_budget_exclusive'                 => 0,
                'period_campaign_budget_id_exclusive'       => 1,
                'campaign_distance_area'                    => $campaign_distance_area,
                'campaign_budget_bid_shared'                => $budget_bid_shared,
                'campaign_budget_bid_exclusive'             => $budget_bid_exclusive,
                'service_campaign_id'                       => $request['service_id'],
                'crm'                                       => $crm,
                'typeOFLead_Source'                         => $request['typeOFLead_Source'],
                'is_ping_account'                           => (empty($request['is_ping_account']) ? 0 : $request['is_ping_account']),
                'is_utility_solar_filter'                   => (empty($request['is_utility_providers']) ? 0 : $request['is_utility_providers']),
                'if_static_cost'                            => ($request['if_static_cost'] == 1 ?  $request['if_static_cost'] : 0),
                'special_budget_bid_exclusive'              => (!empty($request['special_budget_bid_exclusive']) ?  $request['special_budget_bid_exclusive'] : 0),
                'special_state'                             => (!empty($request['special_state']) ? json_encode($request['special_state']) : '[]'),
                'special_source'                            => (!empty($specialSource) ? json_encode($specialSource) : '[]'),
                'special_source_price'                      => (!empty($request['special_source_price']) ?  $request['special_source_price'] : 0),
            ]);

        $dbQuery = DB::table('campaigns_questions')->where('campaign_id', $campaign_id);

        $allSErvicesQuestions = new AllServicesQuestions();

        $allSErvicesQuestions->updateFromCampaigns($dbQuery, $request);


        //*** zip code filter ***//
        $array_zipcode_distance         = array();
        $zip_codes_array_saved          = array();
        $zipcode_id_array               = array();
        $array_zipcode_distance_final   = array();

        //if append checked get all save zip code
        if (!empty($request['append_zip_codes'])) {
            if ($request['append_zip_codes'] == 1) {
                //to get all save zipcode
                $zip_codes_array_saved = DB::table('campaign_target_area')
                    ->where('campaign_id', $campaign_id)->first(['zipcode_id']);
            }
        }
        if (empty($zip_codes_array_saved->zipcode_id)) {
            $zip_codes_array_saved = array();
        } else {
            $zip_codes_array_saved = json_decode($zip_codes_array_saved->zipcode_id);
        }

        if (Cache::has('AllZipCode')) {
            $zipcode_data_cache = Cache::get("AllZipCode");
        }

        //** import Zipcods from excel ***/
        if ($request->hasFile('listOfzipcode')) {
            $dataexcel = Excel::toArray(new SalesOrder, $request->file('listOfzipcode'));
            $dataexcel = array_unique($dataexcel[0], SORT_REGULAR);

            foreach ($dataexcel as $item) {
                // To fetch zipcode
//                if (Cache::has("AllZipCode")) {
//                    $zipcode_data_cache = Cache::get("AllZipCode")->where('zip_code_list', $item[0]);
//                    foreach ($zipcode_data_cache as $data) {
//                        $zipcode_id = $data->zip_code_list_id;
//                    }
//                } else {
                    $zipcode_id = DB::table('zip_codes_lists')->where('zip_code_list', $item[0])->first(['zip_code_list_id']);
              //      $zipcode_id = $zipcode_id->zip_code_list_id;
              //  }
                if (!empty($zipcode_id)) {
                    //** to save all zip code from excel to array */
                    $zipcode_id_array[] = $zipcode_id->zip_code_list_id;
                }
            }
        }
        else if (!empty($request['zipcode'])) {
            if (count($request['zipcode']) == 1 && !empty($request['distance_area']) && $request['distance_area'] != 0) {
                $zipcode_id_array [] = $request['zipcode']['0'];
                $distancekm = $request['distance_area'];
                // To fetch zipcode
                if(Cache::has("AllZipCode")){
                    $zipcode_data_cache = Cache::get("AllZipCode")->where('zip_code_list_id', $request['zipcode']['0']);
                    foreach($zipcode_data_cache as $item){
                        $zipcode = $item->zip_code_list;
                    }
                } else {
                    $zipcode_data = DB::table('zip_codes_lists')
                        ->where('zip_code_list_id', $request['zipcode']['0'])
                        ->first(['zip_code_list']);
                    $zipcode = $zipcode_data->zip_code_list;
                }

                if (!empty($zipcode)) {
                    $main_api_file = new ApiMain();
                    $decoded_result = $main_api_file->zipcodes_distance_filter($zipcode, $distancekm);

                    if (!empty($decoded_result['zip_codes'])) {
                        foreach ($decoded_result['zip_codes'] as $val) {
                            $array_zipcode_distance [] = $val['zip_code'];
                        }
                    }
                }
                //get all zipcode_id from zip code dis
                if (!empty($array_zipcode_distance)) {

                    // To fetch zip_codes_array
                    if(Cache::has('AllZipCode')){
                        $array_zipcode_distance_final = Cache::get("AllZipCode")->whereIn('zip_code_list', $array_zipcode_distance)->pluck('zip_code_list_id')->toArray();
                        $array_zipcode_distance_final = array_unique($array_zipcode_distance_final);
                    } else {
                        $array_zipcode_distance_final = DB::table('zip_codes_lists')->whereIn('zip_code_list', $array_zipcode_distance)->distinct('zip_code_list')->pluck('zip_code_list_id')->toArray();
                    }
                }
            } else {
                $array_zipcode_distance_final = array();
            }
        }

        ////if append checked get all save zip code Excluded in  table
        if (!empty($request['Append_All_Excluded_Zip_Codes'])) {
            if ($request['Append_All_Excluded_Zip_Codes'] == 1) {
                //to get all save zipcode
                $zip_codes_array_saved_Excluded = DB::table('campaign_target_area')
                    ->where('campaign_id', $campaign_id)->first(['zipcode_ex_id']);
            }
        }
        if (empty($zip_codes_array_saved_Excluded->zipcode_ex_id)) {
            $zip_codes_array_saved_Excluded = array();
        }
        else {
            $zip_codes_array_saved_Excluded = json_decode($zip_codes_array_saved_Excluded->zipcode_ex_id);
        }

        //import Zipcods from excel EX
        $dataexcel_expect = array();
        $array_zipcodeEx_id = array();
        if( $request->hasFile('listOfzipcode_expect') ){
            $dataexcel_expect = Excel::toArray(new SalesOrder, $request->file('listOfzipcode_expect'));
            $dataexcel = array_unique($dataexcel_expect[0], SORT_REGULAR);

            foreach( $dataexcel as $item ){
                // To fetch zipcode
//                if(Cache::has('AllZipCode')){
//                    $zipcode_id_arrayEX = Cache::get("AllZipCode")->where('zip_code_list', $item[0]);
//                    foreach($zipcode_id_arrayEX as $item){
//                        $zipcode_idEX = $item->zip_code_list_id;
//                    }
//                } else {
                    $zipcode_idEX = DB::table('zip_codes_lists')->where('zip_code_list', $item[0])->first(['zip_code_list_id']);
               //     $zipcode_idEX = $zipcode_idEX->zip_code_list_id;
               // }
                if( !empty($zipcode_idEX) ) {
                    $array_zipcodeEx_id[]= $zipcode_idEX->zip_code_list_id;
                }
            }
        }

        //*** end zipcode filter ** //
        $final_zip_code = array_merge($array_zipcode_distance_final , $zipcode_id_array);
        $final_zip_code = array_merge($final_zip_code , $zip_codes_array_saved);
        $final_zip_code_Excluded = array_merge($zip_codes_array_saved_Excluded , $array_zipcodeEx_id);

        //get all state_id from zipcode_id
        if(Cache::has('AllZipCode')){
            $state_id_array = Cache::get("AllZipCode")->whereIn('zip_code_list_id', $final_zip_code)->pluck('state_id')->toArray();
            $state_id_array = array_unique($state_id_array);
        } else {
            $state_id_array =  ZipCodesList::join('states', 'states.state_id', '=', 'zip_codes_lists.state_id')
                ->whereIn('zip_codes_lists.zip_code_list_id', $final_zip_code)->distinct('states.state_id')->pluck('states.state_id')->toArray();
        }

        //get all state_id from city_id
        if (!empty($request['city'])) {
            if (Cache::has('AllCities')) {
                $city_id_array = Cache::get("AllCities")->whereIn('city_id', $request['city'])->pluck('state_id')->toArray();
                $city_id_array = array_unique($city_id_array);
            } else {
                $city_id_array = City::join('states', 'states.state_id', '=', 'cities.state_id')
                    ->whereIn('cities.city_id', $request['city'])->distinct('states.state_id')->pluck('states.state_id')->toArray();
            }
        } else {
            $city_id_array = array();
        }

        //get all state_id from county_id
        if(!empty($request['county'])) {
            if (Cache::has('AllCounty')) {
                $county_id_array = Cache::get("AllCounty")->whereIn('county_id', $request['county'])->pluck('state_id')->toArray();
                $county_id_array = array_unique($county_id_array);
            } else {
                $county_id_array = County::join('states', 'states.state_id', '=', 'counties.state_id')
                    ->whereIn('counties.county_id', $request['county'])->distinct('states.state_id')->pluck('states.state_id')->toArray();
            }
        } else {
            $county_id_array = array();
        }

        if(empty($request['state'])){
            $state_array = array();
        } else {
            $state_array=$request['state'];
        }

        //State Filter Campaign
        $state_id_array_final = array_merge( $state_array, $state_id_array);
        $state_id_array_final = array_merge($state_id_array_final, $city_id_array);
        $state_id_array_final = array_merge($state_id_array_final, $county_id_array);
        $state_id_array_final = array_unique($state_id_array_final);

        // To fetch all State
        if(Cache::has('AllState')){
            $states = Cache::get("AllState");
        } else {
            $states = State::All();
        }

        $stateCode = array();
        $stateName = array();
        foreach ($states as $state1) {
            if(in_array($state1->state_id, $state_id_array_final)) {
                $stateCode []= $state1->state_code;
                $stateName []= $state1->state_name;
            }
        }

        DB::table('campaign_target_area')->where('campaign_id', $campaign_id)
            ->update([
                'stateFilter_id'         => json_encode(array_values(array_unique(!empty($state_id_array_final) ? $state_id_array_final : array()))),
                'state_id'               => json_encode(!empty($request['state']) ? $request['state'] : array()),
                'county_id'              => json_encode(!empty($request['county']) ? $request['county'] : array()),
                'city_id'                => json_encode(!empty($request['city']) ? $request['city'] : array()),
                'zipcode_id'             => json_encode(array_values(array_unique(!empty($final_zip_code) ? $final_zip_code : array()))),
                'county_ex_id'           => json_encode(!empty($request['county_expect']) ? $request['county_expect'] : array()),
                'city_ex_id'             => json_encode(!empty($request['city_expect']) ? $request['city_expect'] : array()),
                'zipcode_ex_id'          => json_encode(array_values(array_unique(!empty($final_zip_code_Excluded) ? $final_zip_code_Excluded : array()))),
                'stateFilter_name'       => json_encode(array_values(array_unique(!empty($stateName) ? $stateName : array()))),
                'stateFilter_code'       => json_encode(array_values(array_unique(!empty($stateCode) ? $stateCode : array()))),
            ]);

        //Access LOG
        AccessLog::create([
            'user_id' => Auth::user()->id,
            'user_name' => Auth::user()->username,
            'section_id' => $campaign_id,
            'section_name' => $request['Campaign_name'],
            'user_role' => Auth::user()->role_id,
            'section'   => 'SellerCampaign',
            'action'    => 'Updated',
            'ip_address' => request()->ip(),
            'location' => json_encode(\Location::get(request()->ip())),
            'request_method' => json_encode($request->all())
        ]);

        return redirect()->back();
        //return redirect()->route('Admin.Seller.Campaigns.list', $request->seller_id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($campaign_id)
    {
        DB::table('campaigns')->where('campaign_id', $campaign_id)->update(['campaign_visibility'=>0]);
        $campain_name = DB::table('campaigns')->where('campaign_id', $campaign_id)->first(['campaign_name']);

        //Access LOG
        AccessLog::create([
            'user_id' => Auth::user()->id,
            'user_name' => Auth::user()->username,
            'section_id' => $campaign_id,
            'section_name' => $campain_name->campaign_name,
            'user_role' => Auth::user()->role_id,
            'section'   => 'SellerCampaign',
            'action'    => 'Deleted',
            'ip_address' => request()->ip(),
            'location' => json_encode(\Location::get(request()->ip())),
            'request_method' => ''
        ]);

        return redirect()->back();
    }
}
