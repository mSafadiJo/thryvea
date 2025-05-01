<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Leads_Pedia extends Model
{

    public $table = "leads_pedia";

    public function fetchWithWhere($key,$value,$firsrKey='',$getType)
    {
        if($getType=='first') {
            if (!empty($firsrKey)) {
                return DB::table('leads_pedia')
                    ->where($key, $value)->first($firsrKey);
            } else {
                return DB::table('leads_pedia')
                    ->where($key, $value)->first();
            }
        }

    }

    protected $fillable = [
        'leads_pedia_url', 'campine_key', 'campaign_id','IP_Campaign_ID'
    ];


}
