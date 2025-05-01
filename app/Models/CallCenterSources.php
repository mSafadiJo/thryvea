<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CallCenterSources extends Model
{
    use HasFactory;
    public $table = "call_center_sources";

    protected $fillable = [
        'name'
    ];
}
