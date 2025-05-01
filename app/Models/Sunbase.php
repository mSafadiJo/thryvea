<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Sunbase extends Model
{
    use HasFactory;

    protected $table = 'sunbase_data';

    public function fetchWithWhere($key,$value,$firsrKey='',$getType)
    {
        if($getType=='first') {
            if (!empty($firsrKey)) {
                return DB::table('sunbase_data')
                    ->where($key, $value)->first($firsrKey);
            } else {
                return DB::table('sunbase_data')
                    ->where($key, $value)->first();
            }
        }
    }

    protected $fillable = [
        'url', 'schema_name', 'campaign_id'
    ];
}
