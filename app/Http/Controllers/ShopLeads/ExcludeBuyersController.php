<?php

namespace App\Http\Controllers\ShopLeads;

use App\AccessLog;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class ExcludeBuyersController extends Controller
{
    public function __construct(Request $request)
    {
        $request->permission_page = '11-0';
        if( $request->is("ExcludeBuyers") ){
            $request->permission_page = '11-7';
        } else if( $request->is("ExcludeBuyersEdit") ){
            $request->permission_page = '11-2';
        }

        $this->middleware(['auth', 'AdminMiddleware', 'PermissionUsers']);
    }

    public function index(){
        $ListOfBuyers = User::whereIn('role_id', [3, 4, 6])->where('user_visibility', 1)->orderBy('created_at', 'DESC')->get();
        $getAllData = DB::table('exclude_buyers')
            ->join('users AS buyersA','exclude_buyers.user_idA', '=', 'buyersA.id')
            ->join('users AS buyersB','exclude_buyers.user_idB', '=', 'buyersB.id')
            ->get([
                'exclude_buyers.*', 'buyersA.user_business_name AS user_business_nameA', 'buyersB.user_business_name AS user_business_nameB',
            ]);

        return view('Admin.ShopLead.ExcludeBuyers.index', compact('ListOfBuyers', 'getAllData'));
    }

    public function saveShopLead(Request $request){

        $this->validate($request, [
            'buyer1' => ['required'],
            'buyer2' => ['required']
        ]);

        if( $request->buyer1 == $request->buyer2 ) {
            \Session::put('error', "Buyer A Cannot Be Equal to Buyer B!");
            return back();
        }

        //Check Of Duplicated
        $buyer_arr = array($request->buyer1, $request->buyer2);
        $is_set = DB::table('exclude_buyers')
            ->whereIn('user_idA', $buyer_arr)
            ->whereIn('user_idB', $buyer_arr)
            ->first();

        if( !empty($is_set) ){
            \Session::put('error', "Buyer A and B Have Already Been Excluded!");
            return back();
        }

        //add exclude_buyers
        DB::table('exclude_buyers')->insert([
            'user_idA' => $request->buyer1,
            'user_idB'   => $request->buyer2,
            'created_at'   => date("Y-m-d H:i:s"),
            'updated_at'   => date("Y-m-d H:i:s")
        ]);
        $section_id = DB::getPdo()->lastInsertId();

        //Access LOG
        AccessLog::create([
            'user_id' => Auth::user()->id,
            'user_name' => Auth::user()->username,
            'section_id' => $section_id,
            'section_name' => "Exclude Buyers",
            'user_role' => Auth::user()->role_id,
            'section'   => 'ShopLeads',
            'action'    => 'Create (Exclude Buyers)',
            'ip_address' => request()->ip(),
            'location' => json_encode(\Location::get(request()->ip())),
            'request_method' => json_encode($request->all())
        ]);

        \Session::put('success', "Buyer A And B Have Been Excluded Successfully!");
        return Redirect::back();
    }

    public function DeleteShopLead(Request $request, $id){
        //Delete exclude_buyers
        DB::table('exclude_buyers')->where('id', $id)->Delete();

        //Access LOG
        AccessLog::create([
            'user_id' => Auth::user()->id,
            'user_name' => Auth::user()->username,
            'section_id' => $id,
            'section_name' => "Exclude Buyers",
            'user_role' => Auth::user()->role_id,
            'section'   => 'ShopLeads',
            'action'    => 'Deleted (Exclude Buyers)',
            'ip_address' => request()->ip(),
            'location' => json_encode(\Location::get(request()->ip())),
            'request_method' => json_encode($request->all())
        ]);

        \Session::put('success', "Buyer A And B Have Been Deleted Successfully!");
        return Redirect::back();
    }
}
