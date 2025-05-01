<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Services\ServiceQueries;

class Service_Campaign extends Model
{
    public function campaigns(){
        return $this->hasMany('App\Campaign');
    }
    public function leadsCustomer(){
        return $this->hasMany('App\LeadsCustomer');
    }


    public static function serviceCampaigns($lead, $campaignsType = 'all', $source = 'All Source', $onlyPings = false, $shared = false) {
        // Will get campaigns that matched the lead service with ability to get specific type of campaigns by $campaignsType
        $leadData = $lead->dataAsUnifiedJson();
        $service = $lead->service;
        $sharedFlag = $shared ? 2 : 1;
        $addressMapping = $lead->addressMapping();

        // $campaignType set based on $is_forms flag inside ServiceQueries#service_queries_new_way
        switch ($campaignsType) {
            case 'lead-campaigns':
                $campaignsType = 0;
                break;
            case 'all-except-rev-share-and-aged-campaigns':
                $campaignsType = 1;
                break;
            case 'rev-share-campaigns':
                $campaignsType = 2;
                break;
            case 'aged-campaigns':
                $campaignsType = 3;
                break;
            default:
                throw new InvalidArgumentException('`campaignsType` value unknown');
                break;
        }

        $service_queries = new ServiceQueries();

        return $service_queries->service_queries_new_way(
            $service->service_campaign_id,
            $leadData,
            $sharedFlag,
            (int) $onlyPings,
            $addressMapping,
            $source,
            $campaignsType,
        );
    }

    ///// new edit from safadi ///////
    public function CountAllService(){
        $services = DB::table('service__campaigns')->get();
        $count = count($services);
        return $count ;
    }
    protected $hidden =['pivot'];

    public function featchAllService(){
        $services = DB::table('service__campaigns')->get()->All();
        return $services ;
    }
    public function theme(){
        return $this -> belongsToMany('App\Models\Themes','themes_services','service_id','theme_id','service_campaign_id','id');
    }
}