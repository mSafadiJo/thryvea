<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Dotenv\Validator;
use Illuminate\Http\Request;
use App\Models\Record;

class AddRecordController extends Controller
{
    public function addRecord(Request $request)
    {

        $this->validate($request, [
            'eventType',
            'eventTime' ,
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
        ]);

        $Save_Record = new Record();

        $Save_Record->eventType = $request['eventType'];
        $Save_Record->eventTime = $request['eventTime'];
        $Save_Record->accountId = $request['accountId'];
        $Save_Record->applicationId = $request['applicationId'];;
        $Save_Record->to = $request['to'];
        $Save_Record->from = $request['from'];
        $Save_Record->direction = $request['direction'];

        $Save_Record->callId = $request['callId'];
        $Save_Record->recordingId = $request['recordingId'];
        $Save_Record->channels = $request['channels'];
        $Save_Record->startTime = $request['startTime'];
        $Save_Record->endTime = $request['endTime'];
        $Save_Record->duration = $request['duration'];
        $Save_Record->fileFormat = $request['fileFormat'];

        $Save_Record->callUrl = $request['callUrl'];
        $Save_Record->mediaUrl = $request['mediaUrl'];
        $Save_Record->status = $request['status'];

        $Save_Record->save();

        return "true";
    }
}
