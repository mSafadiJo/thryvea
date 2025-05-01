<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class AcculynxCrm extends Model
{
    public $table = "acculynx_crms";


    public function fetchWithWhere($key,$value,$firsrKey='',$getType)
    {
        if($getType=='first') {
            if (!empty($firsrKey)) {
                return DB::table('acculynx_crms')
                    ->where($key, $value)->first($firsrKey);
            } else {
                return DB::table('acculynx_crms')
                    ->where($key, $value)->first();
            }
        }


    }

    protected $fillable = [
        'api_key', 'campaign_id'
    ];
}
