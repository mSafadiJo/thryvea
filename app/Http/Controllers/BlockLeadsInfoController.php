<?php

namespace App\Http\Controllers;

use App\AccessLog;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BlockLeadsInfoController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'AdminMiddleware']);
    }

    public function index(){
        return view('Admin.Block_info.index');
    }

    public function logic_data(Request $request){
        $type = $request->type;
        $section = $request->section;
        $input_value = $request->input_value;
        $response = 2;

        $table_name = "block_".$section."_lists";
        if( $type == "search" ){
            $if_is_set = DB::table($table_name)->where('value', $input_value)->first();
            if( empty($if_is_set) ){
                $response = 1;
            }
        } else if( $type == "block" ){
            DB::table($table_name)->insert([
                'value' => $input_value,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ]);
            $id = DB::getPdo()->lastInsertId();
            if( $id >= 1 ){
                $response = 1;
            }
        } else if( $type == "unblock" ){
            DB::table($table_name)->where('value', $input_value)->delete();
            $response = 1;
        }

        if( $type != "search" ) {
            AccessLog::create([
                'user_id' => Auth::user()->id,
                'user_name' => Auth::user()->username,
                'section_id' => 1,
                'section_name' => $section,
                'user_role' => Auth::user()->role_id,
                'section' => 'BlockLead',
                'action' => $type,
                'ip_address' => request()->ip(),
                'location' => json_encode(\Location::get(request()->ip())),
                'request_method' => json_encode($request->all())
            ]);
        }

        return $response;
    }
}
