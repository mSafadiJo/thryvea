<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pixels extends Model
{
    public $table = "pixels";


    protected $fillable = [
        'id','pixels_name','type','domain_id','ts_name','status','created_at','updated_at'
    ];
}
