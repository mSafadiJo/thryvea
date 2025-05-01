<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Builder_Prime_CRM extends Model
{
    use HasFactory;

    public $table = "builder_prime";

    public function fetchWithWhere($key,$value,$firsrKey='',$getType)
    {
        if($getType=='first') {
            if (!empty($firsrKey)) {
                return DB::table('builder_prime')
                    ->where($key, $value)->first($firsrKey);
            } else {
                return DB::table('builder_prime')
                    ->where($key, $value)->first();
            }
        }
    }
    protected $fillable = [
        'post_url', 'secret_key', 'campaign_id'
    ];
}
