<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class leads_pedia_track extends Model
{
    public $table = "leads_pedia_track";


    public function fetchWithWhere($key,$value,$firsrKey='',$getType)
    {
        if ($getType == 'first') {
            if (!empty($firsrKey)) {
                return DB::table('leads_pedia_track')
                    ->where($key, $value)->first($firsrKey);
            } else {
                return DB::table('leads_pedia_track')
                    ->where($key, $value)->first();
            }
        }
    }
    protected $fillable = [
        'lp_campaign_id', 'lp_campaign_key', 'ping_url' , 'post_url' , 'campaign_id'
    ];
}
