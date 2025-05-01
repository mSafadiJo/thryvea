<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class LeadConduit extends Model
{

    public $table = "lead_conduits";


    public function fetchWithWhere($key,$value,$firsrKey='',$getType)
    {
        if($getType=='first') {
            if (!empty($firsrKey)) {
                return DB::table('lead_conduits')
                    ->where($key, $value)->first($firsrKey);
            } else {
                return DB::table('lead_conduits')
                    ->where($key, $value)->first();
            }
        }


    }

    protected $fillable = [
        'post_url', 'campaign_id'
    ];
}
