<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class SalesforceCrm extends Model
{
    use HasFactory;

    public $table = "salesforce_crms";


    public function fetchWithWhere($key,$value,$firsrKey='',$getType)
    {
        if($getType=='first') {
            if (!empty($firsrKey)) {
                return DB::table('salesforce_crms')
                    ->where($key, $value)->first($firsrKey);
            } else {
                return DB::table('salesforce_crms')
                    ->where($key, $value)->first();
            }
        }


    }

    protected $fillable = [
        'url','lead_source','retURL','oid','campaign_id'
    ];
}
