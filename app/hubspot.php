<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class hubspot extends Model
{
    public $table = "hubspot";


    public function fetchWithWhere($key,$value,$firsrKey='',$getType)
    {
        if($getType=='first') {
            if (!empty($firsrKey)) {
                return DB::table('hubspot')
                    ->where($key, $value)->first($firsrKey);
            } else {
                return DB::table('hubspot')
                    ->where($key, $value)->first();
            }
        }


    }

    protected $fillable = [
        'Api_Key', 'key_type', 'campaign_id'
    ];
}
