<?php

namespace App\Http\Controllers;

use App\AccessLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SuperAdminController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'AdminMiddleware']);
    }

    public function ListServices(){
        $services = DB::table('service__campaigns')->get()->All();

        return view('SuperAdmin.Services.ServicesList')->with('services', $services);
    }

    public function Edit_Service($service_campaign_id){
        $service = DB::table('service__campaigns')->where('service_campaign_id', $service_campaign_id)->first();

        return view('SuperAdmin.Services.EditService')->with('service', $service);
    }

    public function updateServicedUserAdmin(Request $request){
        $this->validate($request, [
            'id' => 'required',
            'name' => 'required|string',
            'description' => 'required|string'
        ]);

        $updateServices = DB::table('service__campaigns')
            ->where('service_campaign_id', $request['id'])
            ->update([
                'service_campaign_name' => $request['name'],
                'service_campaign_description' => $request['description'] ]);

        AccessLog::create([
            'user_id' => Auth::user()->id,
            'user_name' => Auth::user()->username,
            'section_id' => $request['id'],
            'section_name' => $request['name'],
            'user_role' => Auth::user()->role_id,
            'section'   => 'Services',
            'action'    => 'Updated',
            'ip_address' => request()->ip(),
            'location' => json_encode(\Location::get(request()->ip())),
            'request_method' => json_encode($request->all())
        ]);

        return redirect()->back();
    }

    public function updateStatusServicedUserAdmin(Request $request){

        $updateServices = DB::table('service__campaigns')
            ->where('service_campaign_id', $request['id'])
            ->update([
                'service_is_active' => $request['status']
            ]);

        AccessLog::create([
            'user_id' => Auth::user()->id,
            'user_name' => Auth::user()->username,
            'section_id' => $request['id'],
            'section_name' => $request['name'],
            'user_role' => Auth::user()->role_id,
            'section'   => 'Services',
            'action'    => 'Updated',
            'ip_address' => request()->ip(),
            'location' => json_encode(\Location::get(request()->ip())),
            'request_method' => json_encode($request->all())
        ]);

        return 1;
    }

    public function AddForm(){
        return view('SuperAdmin.Services.ServiceAddForm');
    }

    public function Store(Request $request){
        $this->validate($request, [
            'name' => 'required|string',
            'description' => 'required|string'
        ]);
        //Save Services
        DB::table('service__campaigns')->insert([
            'service_campaign_name'           => $request['name'],
            'service_campaign_description'    => $request['description'],
            'created_at'    => date("Y-m-d H:i:s"),
            'updated_at'    => date("Y-m-d H:i:s"),
        ]);

        $id = DB::getPdo()->lastInsertId();
        AccessLog::create([
            'user_id' => Auth::user()->id,
            'user_name' => Auth::user()->username,
            'section_id' => $id,
            'section_name' => $request['name'],
            'user_role' => Auth::user()->role_id,
            'section'   => 'Services',
            'action'    => 'Created',
            'ip_address' => request()->ip(),
            'location' => json_encode(\Location::get(request()->ip())),
            'request_method' => json_encode($request->all())
        ]);

        return redirect()->route('SuberAdminServices');
    }

    public function DeleteService($id){
        DB::table('service__campaigns')->where('service_campaign_id', $id)->delete();

        return redirect()->back();
    }
}
