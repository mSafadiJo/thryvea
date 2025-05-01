<?php

namespace App\Http\Controllers\Recording;

use App\SessionRecording;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class SessionRecordingController extends Controller
{
    public function viewSessionRecordingTable(Request $request)
    {
        $col_name = null;
        $query = null;
        $sort_search = null;
        $sort_type = null;

        $recording = new SessionRecording();

        if ($request->type != null){
            $var = explode(",", $request->type);
            $col_name = $var[0];
            $query = $var[1];
            $recording = $recording->orderBy($col_name, $query);
            $sort_type = $request->type;
        }
        if ($request->search != null){
            $sort_search = $request->search;
            $recording = $recording->where(function ($query) use($sort_search) {
                $query->where('visitor_id', 'like', '%'.$sort_search.'%');
                $query->OrWhere('domain_name', 'like', '%'.$sort_search.'%');
                $query->OrWhere('ts_name', 'like', '%'.$sort_search.'%');
                $query->OrWhere('created_at', 'like', '%'.$sort_search.'%');
            });
            $sort_search = $request->search;
        }

        $Getrecording = $recording->orderBy('created_at', 'DESC')
            ->groupBy('jornaya_id')->paginate(10);

        return view('SuperAdmin/Recording.SessionRecording', compact('Getrecording', 'col_name', 'query', 'sort_search', 'sort_type'));

    }

    public function viewSessionRecordingVideo($id)
    {
        $Eventreq = DB::connection('mysql2')->table('recording')->select("event")->where('visitor_id', $id)->get()->all();

        return view('SuperAdmin/Recording.SessionRecordingVideo')->with('Eventreq', $Eventreq);

    }

}
