<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TotalAmount extends Model
{
    public function user(){
        return $this->belongsTo('App\User');
    }

    protected $fillable = [
        'user_id', 'total_amounts_value',
    ];
}
