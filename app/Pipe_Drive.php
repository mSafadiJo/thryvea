<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Pipe_Drive extends Model
{
    public $table = "pipe_drive";


    public function fetchWithWhere($key,$value,$firsrKey='',$getType)
    {
        if($getType=='first') {
            if (!empty($firsrKey)) {
                return DB::table('pipe_drive')
                    ->where($key, $value)->first($firsrKey);
            } else {
                return DB::table('pipe_drive')
                    ->where($key, $value)->first();
            }
        }


    }

    protected $fillable = [
        'api_token', 'persons_domain', 'campaign_id', 'persons', 'deals_leads'
    ];
}
