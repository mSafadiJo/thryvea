<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SourcePercentage extends Model
{
    use HasFactory;
    public $table = "source_percentage";

    protected $fillable = [
        'id','user_id','campaign_id','source_id','percentage_value','created_at','updated_at'
    ];
}
