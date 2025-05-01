<?php

namespace App\Http\Controllers\Reports;

use App\LeadTrafficSources;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\State;
use Illuminate\Support\Facades\DB;

class FilterReportController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'AdminMiddleware']);
    }

    public function admins(Request $request){
        $admin = DB::table('users')
            ->whereNotIn('role_id',  [1, 2])
            ->where('user_visibility', 1)
            ->get(['id', 'username']);

        return response()->json($admin, 200);
    }

    public function buyers(Request $request){
        $buyers = DB::table('users')
            ->whereIn('role_id',  [3, 4, 6])
            ->where('user_visibility', 1)
            ->get(['id', 'user_business_name']);

        return response()->json($buyers, 200);
    }

    public function sellers(Request $request){
        $sellers = DB::table('users')
            ->whereIn('role_id',  [4,5])
            ->where('user_visibility', 1)
            ->get(['id', 'user_business_name']);

        return response()->json($sellers, 200);
    }

    public function services(Request $request){
        $services = DB::table('service__campaigns')
            ->get([ 'service_campaign_id', 'service_campaign_name' ]);

        return response()->json($services, 200);
    }

    public function states(Request $request){
        $states = State::All();

        return response()->json($states, 200);
    }

    public function counties(Request $request){
        $counties = DB::table('counties')->get(['county_id', 'county_name']);

        return response()->json($counties, 200);
    }

    public function cities(Request $request){
        $cities = DB::table('cities')->get(['city_id', 'city_name']);

        return response()->json($cities, 200);
    }

    public function zipcodes(Request $request){
        $zip_codes_lists = DB::table('zip_codes_lists')->get(['zip_code_list_id', 'zip_code_list']);

        return response()->json($zip_codes_lists, 200);
    }

    public function traffic_source(Request $request){
        $traffic_source = LeadTrafficSources::get(['id', 'name']);

        return response()->json($traffic_source, 200);
    }

    public function environments(Request $request){
        $environments = array(
            array(
                'id' => 1,
                'name' => "All"
            ),
            array(
                'id' => 2,
                'name' => "Sold"
            ),
            array(
                'id' => 3,
                'name' => "UnSold"
            ),
            array(
                'id' => 4,
                'name' => "Deleted"
            ),
            array(
                'id' => 5,
                'name' => "Test Lead"
            ),
            array(
                'id' => 6,
                'name' => "Duplicated"
            ),
            array(
                'id' => 7,
                'name' => "Blocked"
            ),
        );

        return response()->json($environments, 200);
    }
}
