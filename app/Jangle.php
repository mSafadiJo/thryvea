<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Jangle extends Model
{
    public $table = "jangle";


    public function fetchWithWhere($key,$value,$firsrKey='',$getType)
    {
        if($getType=='first') {
            if (!empty($firsrKey)) {
                return DB::table('jangle')
                    ->where($key, $value)->first($firsrKey);
            } else {
                return DB::table('jangle')
                    ->where($key, $value)->first();
            }
        }


    }

    protected $fillable = [
        'Authorization', 'PingUrl', 'PostUrl','campaign_id'
    ];
}

