<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class LeadPortal extends Model
{
    public $table = "lead_portal";

    public function fetchWithWhere($key,$value,$firsrKey='',$getType)
    {
        if($getType=='first') {
            if (!empty($firsrKey)) {
                return DB::table('lead_portal')
                    ->where($key, $value)->first($firsrKey);
            } else {
                return DB::table('lead_portal')
                    ->where($key, $value)->first();
            }
        }


    }

    protected $fillable = [
        'key', 'SRC', 'api_url' ,'campaign_id', 'type'
    ];
}
