<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SmsSendData extends Model
{
    protected $fillable = ['user_id', 'mobile_number', 'text_msg'];
}
