<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProspectTransactions extends Model
{
    protected $fillable = [
        'type', 'user_id', 'prospect_id'
    ];
}
