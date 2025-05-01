<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;


class CallTools extends Model
{

    public $table = "calltools";


    public function fetchWithWhere($key,$value,$firsrKey='',$getType)
    {
        if($getType=='first') {
            if (!empty($firsrKey)) {
                return DB::table('calltools')
                    ->where($key, $value)->first($firsrKey);
            } else {
                return DB::table('calltools')
                    ->where($key, $value)->first();
            }
        }
    }

    protected $fillable = [
        'api_key', 'file_id', 'campaign_id'
    ];
}
