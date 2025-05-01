<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LostLeadReport extends Model
{
    public $table = "report_lost_lead";

    protected $fillable = [
        'id',
        'Lead_id',
        'step1',
        'step2',
        'step3_1',
        'step3_2',
        'step3_3',
        'step3_4',
        'step3_5',
        'step3_6',
        'step3_7',
        'step4_1',
        'step4_2',
        'created_at',
        'updated_at',
    ];

}
