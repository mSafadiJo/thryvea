<?php

namespace App\Http\Controllers\Api\LeadsBridgeExports;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Rap2hpoutre\FastExcel\FastExcel;

class LeadsBridgeExportController extends Controller
{
    public function downloadCSVLeadsBridge(Request $request){

        $headerAuth = $request->header('Authorization');

        if ($headerAuth == "SG8r1tAJEM4XsxAfsdFqbaWOwnw6Il3PxoarHpzJm"){
            try {
                $lead_data = DB::table('leads_customers')
                    ->whereBetween('leads_customers.created_at', [date('Y-m-d')." 00:00:00", date('Y-m-d')." 23:59:59"])
                    ->where('leads_customers.is_duplicate_lead', '=', '0')
                    ->where('leads_customers.is_test', '=', '0')
                    ->where('leads_customers.created_at', '<>', '1')
                    ->join('states', 'states.state_id', '=', 'leads_customers.lead_state_id')
                    ->join('cities', 'cities.city_id', '=', 'leads_customers.lead_city_id')
                    ->join('zip_codes_lists', 'zip_codes_lists.zip_code_list_id', '=', 'leads_customers.lead_zipcode_id')
                    ->get(['leads_customers.lead_fname', 'leads_customers.lead_lname', 'leads_customers.lead_email', 'leads_customers.lead_phone_number', 'cities.city_name', 'states.state_code', 'zip_codes_lists.zip_code_list'])
                    ->all();

                return (new FastExcel($lead_data))->download('leads.csv', function ($lead){
                    return [
                        'first_name' => $lead->lead_fname,
                        'last_name' => $lead->lead_lname,
                        'email' => $lead->lead_email,
                        'phone' =>  " " . $lead->lead_phone_number . " " ,
                        'city' => $lead->city_name,
                        'state' => $lead->state_code,
                        'zip_code' => " " . $lead->zip_code_list . " ",
                        'country_code' => "USA"
                    ];
                });
            } catch (\Exception $e){
                $errorResponse = array(
                    "Error" => "Something went wrong, please try again later!"
                );
                $errorResponse = json_encode($errorResponse);
                return $errorResponse;
            }
        } else{
            $errorResponse = array(
                "Error" => "Request unauthorized"
            );
            $errorResponse = json_encode($errorResponse);
            return $errorResponse;
        }

    }
}
