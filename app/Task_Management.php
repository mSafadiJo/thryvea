<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Task_Management extends Model
{
    public $table = "task_management";


    public function fetchWithWhere($key,$value,$firsrKey='',$getType)
    {
        if($getType=='first') {
            if (!empty($firsrKey)) {
                return DB::table('task_management')
                    ->where($key, $value)->first($firsrKey);
            } else {
                return DB::table('task_management')
                    ->where($key, $value)->first();
            }
        }


    }

    protected $fillable = [
        'task_name', 'description', 'status','priority','assign_from','signed_to'
    ];
}
