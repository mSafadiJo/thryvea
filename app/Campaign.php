<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Services\APIMain;
use App\Services\Allied\PingCRMAllied;
use Carbon\Carbon;

class Campaign extends Model
{
    public function service_campaign(){
        return $this->belongsTo('App\Service_Campaign');
    }

    public function states_campaign(){
        return $this->belongsToMany('App\State', 'state_campaigns');
    }

    public function counties_campaign(){
        return $this->belongsToMany('App\County', 'county__campaigns');
    }

    public function cities_campaign(){
        return $this->belongsToMany('App\City', 'city__campaigns');
    }

    public function zipcods_campaign(){
        return $this->hasMany('App\Zipcode_Campaign');
    }

    public function campaign_type() {
        $this->custom_paid_campaign_id;
    }
    
    public static function totalLeadsAndBids($campaignIds, $budgetPeriod, $shared = false) { 
        // Based on budgetPeriod and the campaigns desired to get for total sold leads and total bids
            switch ($budgetPeriod) {
                case 'daily':
                    $date_range = [date("Y-m-d"), date("Y-m-d")];
                    break;
                case 'weekly':
                    $date_range = [
                        date('Y-m-d', strtotime(Carbon::now()->startOfWeek())),
                        date('Y-m-d', strtotime(Carbon::now()->endOfWeek()))
                    ];
                    break;
                case 'monthly':
                    $date_range = [date('Y-m'). '-1', date('Y-m-t')];
                    break;
                default:
                    throw new InvalidArgumentException("`budgetPeriod` value unknown", 1);
                    break;
            }
        $sharedFlag = $shared ? 'Shared' : 'Exclusive';

        // Query for given campaigns ids for selling records inside `campaigns_leads_users_affs`
        return \DB::table('campaigns_leads_users_affs')
            ->select('campaigns_leads_users_type_bid','campaign_id', 'date',
                \DB::raw('COUNT(campaigns_leads_users_id) as totallead'),
                \DB::raw('SUM(campaigns_leads_users_bid) as sumbid' ))
            ->whereBetween('date', $date_range)
            ->where('campaigns_leads_users_type_bid', $sharedFlag)
            ->whereIn('campaign_id', $campaignIds)
            ->where('is_returned', '<>', 1)
            ->groupBy('campaign_id', 'DATE')
            ->get()->keyBy('campaign_id');
    }

    protected $fillable = [
        'campain_name', 'campain_count', 'campain_budget', 'period_campaign_count_lead_id', 'campaign_id',
        'budget_period', 'service_campaign_id', 'campaign_status_id', 'campaign_budget_bid_exclusive',
        'campaign_budget_bid_shared', 'file_calltools_id','crm','email1','email2','email3', 'email4',
        'email5', 'email6', 'phone1','phone2','phone3', 'phone4','phone5','phone6',
        'campaign_calltools_id','subject_email','lead_source', 'is_ping_account',
        'is_seller', 'vendor_id', 'typeOFLead_Source', 'token', 'visitor_id', 'virtual_price', 'multi_service_accept',
        'sec_service_accept', 'if_static_cost', 'special_budget_bid_exclusive', 'special_state', 'is_multi_crms',
        'band_width_accept_record', 'is_branded_camp', 'script_text','website', 'transfer_numbers', 'exclude_url'
    ];
}