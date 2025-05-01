<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SellerTotalAmount extends Model
{
    use HasFactory;

    public $table = "seller_total_amounts";

    protected $fillable = [
        'total_amounts_id','user_id','total_amounts_value','created_at','updated_at'
    ];
}
