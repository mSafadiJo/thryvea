<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Five9 extends Model
{
    public $table = "five9";


    public function fetchWithWhere($key,$value,$firsrKey='',$getType)
    {
        if($getType=='first') {
            if (!empty($firsrKey)) {
                return DB::table('five9')
                    ->where($key, $value)->first($firsrKey);
            } else {
                return DB::table('five9')
                    ->where($key, $value)->first();
            }
        }


    }

    protected $fillable = [
        'five9_domian', 'five9_list', 'campaign_id'
    ];
}
