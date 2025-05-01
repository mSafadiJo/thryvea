<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TestLeadsCustomer extends Model
{
    protected $fillable = [
        'lead_id', 'listOFCampain_exclusiveDB', 'listOFCampain_sharedDB','listOFCampain_pingDB',
        'listOFCampainDB_array_exclusive','listOFCampainDB_array_shared' ,'listOFCampainDB_array_ping',
        'campaigns_sh','campaigns_ex' ,'campaigns_sh_col', 'campaigns_ex_col','listOFCampainDB'
    ];
}
