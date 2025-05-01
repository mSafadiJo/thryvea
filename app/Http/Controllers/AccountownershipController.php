<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AccountownershipController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    public function index(Request $request){
//        $col_name = null;
//        $query = null;
//        $sort_search = null;
//        $sort_type = null;

        $payment_types = DB::table('user_payment_type_admin')
            ->Join('users', 'users.id', '=', 'user_payment_type_admin.buyer_id')
            ->Join('payment_type_method', 'payment_type_method.payment_type_method_id', '=', 'user_payment_type_admin.payment_type_method_id');

//        if ($request->type != null){
//            $var = explode(",", $request->type);
//            $col_name = $var[0];
//            $query = $var[1];
//            $payment_types = $payment_types->orderBy($col_name, $query);
//            $sort_type = $request->type;
//        }
//        if ($request->search != null){
//            $sort_search = $request->search;
//            $payment_types = $payment_types->where(function ($query) use($sort_search) {
//                $query->where('users.username', 'like', '%'.$sort_search.'%');
//                $query->OrWhere('users.user_business_name', 'like', '%'.$sort_search.'%');
//                $query->OrWhere('user_payment_type_admin.admin_name', 'like', '%'.$sort_search.'%');
//            });
//            $sort_search = $request->search;
//        }

//        $payment_types = $payment_types->orderBy('user_payment_type_admin.created_at', 'DESC')
//            ->select([
//                'user_payment_type_admin.*', 'users.username', 'payment_type_method.payment_type_method_type', 'users.user_business_name'
//            ])->paginate(15);

        $payment_types = $payment_types->orderBy('user_payment_type_admin.created_at', 'DESC')
            ->get([
                'user_payment_type_admin.*', 'users.username', 'payment_type_method.payment_type_method_type', 'users.user_business_name'
            ]);

//        return view('Admin.AccounOwnership.payment_type.index', compact('payment_types', 'col_name', 'query', 'sort_search', 'sort_type'));

        return view('Admin.AccounOwnership.payment_type.index', compact('payment_types'));
    }

    public function changePayment_typeStatus(Request $request){
        $status = $request->status;
        $payment_type_id = $request->payment_type_id;
        $payment_limit = $request->payment_limit;

        $buyer = DB::table('user_payment_type_admin')
            ->where('user_payment_type_admin_id', $payment_type_id)
            ->first(['buyer_id', 'payment_type_method_id']);

        $user_id = $buyer->buyer_id;
        $payment_type_method_id = $buyer->payment_type_method_id;

        if( $status != 2 ){
            DB::table('user_payment_type_admin')
                ->where('user_payment_type_admin_id', $payment_type_id)
                ->update([
                    'confirmed_by_id' => Auth::user()->id,
                    'confirmed_by_name' => Auth::user()->username,
                    'payment_type_method_limit' => $payment_limit,
                    'payment_type_method_status' => $status,
                    'confirmed_at' => date('Y-m-d H:i:s')
                ]);

            if( $status == 1 ){
                User::where('id', $user_id)
                    ->update([
                        'payment_type_method_id' => $payment_type_method_id,
                        'payment_type_method_limit' => $payment_limit,
                        'payment_type_method_status' => 1
                    ]);
            } else {
                User::where('id', $user_id)
                    ->update([
                        'payment_type_method_id' => NULL,
                        'payment_type_method_limit' => 0,
                        'payment_type_method_status' => 0
                    ]);
            }
        } else {
            DB::table('user_payment_type_admin')
                ->where('user_payment_type_admin_id', $payment_type_id)
                ->Delete();

            User::where('id', $user_id)
                ->update([
                    'payment_type_method_id' => NULL,
                    'payment_type_method_limit' => 0,
                    'payment_type_method_status' => 0
                ]);
        }

        return response()->json('0', 200);
    }
}
