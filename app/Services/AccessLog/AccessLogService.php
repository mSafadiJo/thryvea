<?php

namespace App\Services\AccessLog;


use App\AccessLog;
use Illuminate\Support\Facades\Auth;

class AccessLogService
{

    public function createAccessLog(array $data,$section,$action,$section_id="")
    {
        empty($section_id) ? Auth::user()->id : $section_id;
        AccessLog::create([
            'user_id' => Auth::user()->id,
            'user_name' => ucwords($data['firstname'] . ' ' . $data['lastname']),
            'section_id' => $section_id,
            'section_name' => ucwords($data['firstname'] . ' ' . $data['lastname']),
            'user_role' => Auth::user()->role_id,
            'section' => $section,
            'action' => $action,
            'ip_address' => request()->ip(),
            'location' => json_encode(\Location::get(request()->ip())),
            'request_method' => json_encode($data)
        ]);
    }

}
