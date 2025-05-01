<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ZapierCrm extends Model
{
    use HasFactory;

    public $table = "zapier_crms";

    public function fetchWithWhere($key,$value,$firsrKey='',$getType)
    {
        if($getType=='first') {
            if (!empty($firsrKey)) {
                return DB::table('zapier_crms')
                    ->where($key, $value)->first($firsrKey);
            } else {
                return DB::table('zapier_crms')
                    ->where($key, $value)->first();
            }
        }
    }

    protected $fillable = [
        'post_url', 'campaign_id'
    ];
}
