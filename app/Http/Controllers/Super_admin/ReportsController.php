<?php

namespace App\Http\Controllers\Super_admin;

use App\Http\Controllers\Controller;
use App\BuyersClaim;
use App\CampaignsLeadsUsers;
use App\LeadsCustomer;
use App\Ticket;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Response;

class ReportsController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'AdminMiddleware']);
    }

    //Sellers Lead Volume =========================================================================
    public function seller_lead_volume(){
        return view('Reports.seller_volume');
    }

    public function seller_lead_volume_data(Request $request)
    {

        $service_id = array();
        if( !empty($request->service_id) ){
            $service_id = explode(',', $request->service_id);
        }

        $state_id = array();
        if( !empty($request->state_id) ){
            $state_id = explode(',', $request->state_id);
        }

        $county_id = array();
        if( !empty($request->county_id) ){
            $county_id = explode(',', $request->county_id);
        }

        $city_id = array();
        if( !empty($request->city_id) ){
            $city_id = explode(',', $request->city_id);
        }

        $zipcode_id = array();
        if( !empty($request->zipcode_id) ){
            $zipcode_id = explode(',', $request->zipcode_id);
        }

        $seller_id = array();
        if( !empty($request->seller_id) ){
            $seller_id = explode(',', $request->seller_id);
        }

        $from_date = $request->from_date;
        $to_date = $request->to_date;

        $lead_volumes = LeadsCustomer::join('states','state_id', '=', 'leads_customers.lead_state_id')
            ->join('campaigns', 'campaigns.vendor_id', '=', 'leads_customers.vendor_id')
            ->join('users', 'users.id', '=', 'campaigns.user_id')
            ->where('leads_customers.is_duplicate_lead',  0 )
            ->where('leads_customers.lead_fname', '!=', "test")
            ->where('leads_customers.lead_lname', '!=',"test")
            ->where('leads_customers.lead_fname','!=', "testing")
            ->where('leads_customers.lead_lname','!=',"testing")
            ->where('leads_customers.lead_fname', '!=',"Test")
            ->where('leads_customers.lead_lname','!=',"Test")
            ->where('leads_customers.is_test', 0)
            ->where('leads_customers.status', 0)
            ->where('leads_customers.created_at', '>=', $from_date)
            ->where('leads_customers.created_at', '<=', $to_date);

        if (!empty($service_id)) {
            $lead_volumes->whereIn('leads_customers.lead_type_service_id', $service_id);
        }

        if( !empty($seller_id) ){
            $lead_volumes->whereIn('campaigns.user_id', $seller_id);
        }

        if (!empty($state_id)) {
            $lead_volumes->whereIn('leads_customers.lead_state_id', $state_id);
        }

        if (!empty($county_id)) {
            $lead_volumes->whereIn('leads_customers.lead_county_id', $county_id);
        }

        if (!empty($city_id)) {
            $lead_volumes->whereIn('leads_customers.lead_city_id', $city_id);
        }

        if (!empty($zipcode_id)) {
            $lead_volumes->whereIn('leads_customers.lead_zipcode_id', $zipcode_id);
        }

        $lead_volume_data = $lead_volumes->groupBy('states.state_name', 'campaigns.user_id')->get([
            'users.user_business_name',
            DB::raw("COUNT(leads_customers.lead_id) AS totallead"),
            DB::raw("states.state_name AS states"),
        ]);

        $count= 0 ;
        $data_Returned = '';

        $data_Returned .= '<table id="datatable-buttons" class="table table-striped table-bordered" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th>Seller</th>
                                    <th>State</th>
                                    <th>Lead#</th>
                                </tr>
                            </thead>
                            <tbody>';
        if( !empty($lead_volume_data) ){
            foreach ( $lead_volume_data as $item ){
                if( !empty($item->sumbid) ){
                    $datasumbid = $item->sumbid;
                } else {
                    $datasumbid = 0;
                }
                $data_Returned .=  "<tr>";
                $data_Returned .=  "<td>". $item->user_business_name  . "</td>";
                $data_Returned .=  "<td>". $item->states  . "</td>";
                $data_Returned .=  "<td>". $item->totallead. "</td>";
                $data_Returned .=  "</tr>";
                $count += $item->totallead ;
            }
        }

        $data_Returned .= "</tbody>
                            <tfoot>
                                <tr>
                                    <th>Total</th>
                                    <th></th>
                                    <td>$count</td>
                                </tr>
                            </tfoot>
                        </table>";


        return $data_Returned;
    }

}
