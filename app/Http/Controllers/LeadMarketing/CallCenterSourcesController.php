<?php

namespace App\Http\Controllers\LeadMarketing;

use App\AccessLog;
use App\Http\Controllers\Controller;
use App\LeadTrafficSources;
use Illuminate\Http\Request;
use App\Models\CallCenterSources;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CallCenterSourcesController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'AdminMiddleware']);
    }

    public function index(){
        $call_center_source =  DB::table('call_center_sources')->get();
        return view('LeadMarketing.call_center_sources.index', compact('call_center_source'));
    }

    public function create(){
        return view('LeadMarketing.call_center_sources.create');
    }

    public function store(Request $request){
        $this->validate($request, [
            'name' => ['required', 'string', 'max:255'],
        ]);

        $Call_Center_source = new CallCenterSources();
        $Call_Center_source->name = $request->name;
        $Call_Center_source->save();
        $Call_Center_source_id = DB::getPdo()->lastInsertId();

        //Access LOG
        AccessLog::create([
            'user_id' => Auth::user()->id,
            'user_name' => Auth::user()->username,
            'section_id' => $Call_Center_source_id,
            'section_name' => $request->name,
            'user_role' => Auth::user()->role_id,
            'section'   => 'CallCenterSource',
            'action'    => 'Created',
            'ip_address' => request()->ip(),
            'location' => json_encode(\Location::get(request()->ip())),
            'request_method' => json_encode($request->all())
        ]);

        return redirect()->route('CallCenterSources.index');
    }

    public function edit($id){
        $call_center_source =  DB::table('call_center_sources')->where('id',$id)->first();
        return view('LeadMarketing.call_center_sources.edit', compact('call_center_source'));
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => ['required', 'string', 'max:255'],
        ]);

        $Call_Center_source =CallCenterSources::find($id);
        $Call_Center_source->name = $request->name;
        $Call_Center_source->save();

        //Access LOG
        AccessLog::create([
            'user_id' => Auth::user()->id,
            'user_name' => Auth::user()->username,
            'section_id' => $id,
            'section_name' => $request->name,
            'user_role' => Auth::user()->role_id,
            'section'   => 'CallCenterSource',
            'action'    => 'Updated',
            'ip_address' => request()->ip(),
            'location' => json_encode(\Location::get(request()->ip())),
            'request_method' => json_encode($request->all())
        ]);

        return redirect()->route('CallCenterSources.index');
    }

    public function destroy($id){
        $Call_Center_source = CallCenterSources::find($id);
        $Call_Center_source->delete();

        //Access LOG
        AccessLog::create([
            'user_id' => Auth::user()->id,
            'user_name' => Auth::user()->username,
            'section_id' => $Call_Center_source->id,
            'section_name' => $Call_Center_source->name,
            'user_role' => Auth::user()->role_id,
            'section'   => 'CallCenterSource',
            'action'    => 'Delete',
            'ip_address' => request()->ip(),
            'location' => json_encode(\Location::get(request()->ip())),
            'request_method' => ""
        ]);

        return Back();
    }



}
