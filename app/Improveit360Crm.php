<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Improveit360Crm extends Model
{
    public $table = "improveit360_crms";


    public function fetchWithWhere($key,$value,$firsrKey='',$getType)
    {
        if($getType=='first') {
            if (!empty($firsrKey)) {
                return DB::table('improveit360_crms')
                    ->where($key, $value)->first($firsrKey);
            } else {
                return DB::table('improveit360_crms')
                    ->where($key, $value)->first();
            }
        }


    }

    protected $fillable = [
        'campaign_id', 'improveit360_url', 'improveit360_source', 'market_segment', 'source_type', 'project'
    ];
}
