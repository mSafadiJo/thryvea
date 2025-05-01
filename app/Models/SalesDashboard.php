<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalesDashboard extends Model
{
    use HasFactory;

    public $table = "sales_dashboards";


    protected $fillable = [
        'user_id',
        'transfer_number'
    ];
}
