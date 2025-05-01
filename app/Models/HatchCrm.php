<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class HatchCrm extends Model
{
    use HasFactory;

    public $table = "hatch_crms";

    public function fetchWithWhere($key,$value,$firsrKey='',$getType)
    {
        if($getType=='first') {
            if (!empty($firsrKey)) {
                return DB::table('hatch_crms')
                    ->where($key, $value)->first($firsrKey);
            } else {
                return DB::table('hatch_crms')
                    ->where($key, $value)->first();
            }
        }
    }

    protected $fillable = [
        'dep_id', 'org_token', 'campaign_id', 'URL_sub'
    ];
}
