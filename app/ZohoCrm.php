<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ZohoCrm extends Model
{
    public $table = "zoho_crms";


    public function fetchWithWhere($key,$value,$firsrKey='',$getType)
    {
        if($getType=='first') {
            if (!empty($firsrKey)) {
                return DB::table('zoho_crms')
                    ->where($key, $value)->first($firsrKey);
            } else {
                return DB::table('zoho_crms')
                    ->where($key, $value)->first();
            }
        }


    }

    protected $fillable = [
        'refresh_token', 'client_id', 'client_secret', 'redirect_url', 'campaign_id', 'Lead_Source'
    ];
}
