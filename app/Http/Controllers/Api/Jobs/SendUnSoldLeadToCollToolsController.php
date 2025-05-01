<?php

namespace App\Http\Controllers\Api\Jobs;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class SendUnSoldLeadToCollToolsController extends Controller
{

    public function __construct(){
        ini_set('max_execution_time', '0');
        ini_set('memory_limit', '-1');
    }

    public function index(){
        return 1;
        //Return buyers last transaction
        $last_trx_arr = DB::table('campaigns_leads_users')
            ->join('users', 'users.id', '=', 'campaigns_leads_users.user_id')
            ->where("campaigns_leads_users.is_returned", 0)
            ->where('users.user_visibility', 1)
            ->orderBy("campaigns_leads_users.date", "desc")
            ->selectRaw('campaigns_leads_users.user_id, MAX(campaigns_leads_users.date) as last_date')
            ->groupBy('campaigns_leads_users.user_id')
            ->get();

        if(!empty($last_trx_arr)){
            foreach ($last_trx_arr as $val){
                $last_trx = $val->last_date;
                $user_id = $val->user_id;

                $entry_date = Carbon::parse($last_trx);
                $diff_month  = Carbon::now()->diffInMonths($entry_date);
                if($diff_month > 3){
                    DB::table('users')->where('id', $user_id)->update(['user_visibility' => 2]);
                }
            }
        }
        return 1;
        ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        ///-------------------------------------------------------------------------------------------------------------///
        ///-------------------------------------------------------------------------------------------------------------///
        ///-------------------------------------------------------------------------------------------------------------///
        /// ------------------------------------------------------------------------------------------------------------///
        ///-------------------------------------------------------------------------------------------------------------///
        ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        //Select Date Before number of month
        $date_deleted1 = Carbon::now()->subMonth(1);
        $date_deleted2 = Carbon::now()->subMonth(2);
        $date_deleted3 = Carbon::now()->subMonth(3);

        //Remove all lead review without first/last name before 2 month
        DB::table('lead_reviews')
            ->where('created_at', "<=", $date_deleted2)
            ->whereNull('lead_fname')
            ->whereNull('lead_lname')
            ->delete();

        //Remove all test leads from lead customers before 1 month
        DB::table('leads_customers')
            ->leftJoin('campaigns_leads_users' , 'campaigns_leads_users.lead_id' , '=' , 'leads_customers.lead_id')
            ->whereNull('campaigns_leads_users.lead_id')
            ->where('leads_customers.created_at', "<=", $date_deleted1)
            ->where('leads_customers.lead_id', "!=", 1)
            ->where(function ($query) {
                $query->where('leads_customers.lead_fname', "test");
                $query->OrWhere('leads_customers.lead_lname', "test");
                $query->OrWhere('leads_customers.lead_fname', "testing");
                $query->OrWhere('leads_customers.lead_lname', "testing");
                $query->OrWhere('leads_customers.lead_fname', "Test");
                $query->OrWhere('leads_customers.lead_lname', "Test");
                $query->OrWhere('leads_customers.is_test', 1);
            })
            ->delete();

        //Remove all test lead responses
        DB::table('crm_responses')->where('campaigns_leads_users_id', 1)->delete();
        //==========================================================================================================

        //Add record job on database
        DB::table('jobs')->insert([
            'queue' => 'Send Unsold Lead To CallTools',
            'payload' => 'Send Unsold Lead To CallTools',
            'attempts' => '1',
            'reserved_at' => date('Y-m-d H:i:s'),
            'available_at' => date('Y-m-d H:i:s'),
            'created_at' => date('Y-m-d H:i:s')
        ]);
    }
}
