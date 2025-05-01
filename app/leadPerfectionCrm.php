<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class leadPerfectionCrm extends Model
{
    public $table = "lead_perfection_crms";


    public function fetchWithWhere($key,$value,$firsrKey='',$getType)
    {
        if($getType=='first') {
            if (!empty($firsrKey)) {
                return DB::table('lead_perfection_crms')
                    ->where($key, $value)->first($firsrKey);
            } else {
                return DB::table('lead_perfection_crms')
                    ->where($key, $value)->first();
            }
        }


    }

    protected $fillable = [
        'campaign_id', 'lead_perfection_url', 'lead_perfection_srs_id','lead_perfection_pro_id', 'lead_perfection_pro_desc', 'lead_perfection_sender'
    ];
}
