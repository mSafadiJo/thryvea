<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class SetShapeCrm extends Model
{
    use HasFactory;

    public $table = "set_shape_crms";

    public function fetchWithWhere($key,$value,$firsrKey='',$getType)
    {
        if($getType=='first') {
            if (!empty($firsrKey)) {
                return DB::table('set_shape_crms')
                    ->where($key, $value)->first($firsrKey);
            } else {
                return DB::table('set_shape_crms')
                    ->where($key, $value)->first();
            }
        }
    }

    protected $fillable = [
        'post_url', 'campaign_id'
    ];
}
