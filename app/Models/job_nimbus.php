<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class job_nimbus extends Model
{
    use HasFactory;

    protected $table = 'job_nimbus';

    public function fetchWithWhere($key,$value,$firsrKey='',$getType)
    {
        if($getType=='first') {
            if (!empty($firsrKey)) {
                return DB::table('job_nimbus')
                    ->where($key, $value)->first($firsrKey);
            } else {
                return DB::table('job_nimbus')
                    ->where($key, $value)->first();
            }
        }
    }

    protected $fillable = [
        'api_key', 'campaign_id'
    ];
}
