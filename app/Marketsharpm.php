<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Marketsharpm extends Model
{
    public $table = "marketsharpm";


    public function fetchWithWhere($key,$value,$firsrKey='',$getType)
    {
        if($getType=='first') {
            if (!empty($firsrKey)) {
                return DB::table('marketsharpm')
                    ->where($key, $value)->first($firsrKey);
            } else {
                return DB::table('marketsharpm')
                    ->where($key, $value)->first();
            }
        }


    }

    protected $fillable = [
        'MSM_source', 'MSM_coy', 'MSM_formId' , 'campaign_id'
    ];
}
