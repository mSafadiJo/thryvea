<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Record extends Model
{
    use HasFactory;

    public $table = "record_bandWidth";


    protected $fillable = [
        'eventType',
        'eventTime',
        'accountId',

        'applicationId',
        'to',
        'from',

        'direction',
        'callId',
        'recordingId',

        'channels',
        'startTime',
        'endTime',

        'duration',
        'fileFormat',
        'callUrl',

        'mediaUrl',
        'status',
        'created_at',
    ];
}
