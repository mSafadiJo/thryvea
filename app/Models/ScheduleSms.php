<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ScheduleSms extends Model
{
    use HasFactory;

    public $table = "schedule_sms";


    protected $fillable = [
        'user_id', 'content', 'phone_list', 'schedule_date', 'send_date', 'is_sent'
    ];
}
