<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;


class BuilderTrend extends Model
{
    public $table = "builder_trend";


    public function fetchWithWhere($key,$value,$firsrKey='',$getType)
    {
        if($getType=='first') {
            if (!empty($firsrKey)) {
                return DB::table('builder_trend')
                    ->where($key, $value)->first($firsrKey);
            } else {
                return DB::table('builder_trend')
                    ->where($key, $value)->first();
            }
        }


    }

    protected $fillable = [
        'builder_id', 'campaign_id'
    ];

}
