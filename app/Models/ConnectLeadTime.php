<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ConnectLeadTime extends Model
{
    public $table = "connect_lead_time";

    protected $fillable = [
        'id','lead_phone','duration_time','created_at','updated_at'
    ];
}
