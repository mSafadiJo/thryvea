<?php

namespace App\Http\Controllers;

use App\BuyersClaim;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class ClaimController extends Controller
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

        $claims = BuyersClaim::Join('users', 'users.id', '=', 'buyers_claims.buyer_id');

//        if ($request->type != null){
//            $var = explode(",", $request->type);
//            $col_name = $var[0];
//            $query = $var[1];
//            $claims = $claims->orderBy($col_name, $query);
//            $sort_type = $request->type;
//        }
//        if ($request->search != null){
//            $sort_search = $request->search;
//            $claims = $claims->where(function ($query) use($sort_search) {
//                $query->where('users.username', 'like', '%'.$sort_search.'%');
//                $query->OrWhere('users.user_business_name', 'like', '%'.$sort_search.'%');
//                $query->OrWhere('buyers_claims.admin_name', 'like', '%'.$sort_search.'%');
//            });
//            $sort_search = $request->search;
//        }

//        $claims = $claims->orderBy('buyers_claims.created_at', 'DESC')
//            ->select([
//                'buyers_claims.*', 'users.username', 'users.user_business_name'
//            ])->paginate(15);


        $claims = $claims->orderBy('buyers_claims.created_at', 'DESC')
            ->get([
                'buyers_claims.*', 'users.username', 'users.user_business_name'
            ]);

//        return view('Admin.AccounOwnership.Claim.index', compact('claims', 'col_name', 'query', 'sort_search', 'sort_type'));
        return view('Admin.AccounOwnership.Claim.index', compact('claims'));
    }

    public function adminAddClaim(Request $request){
        $this->validate($request, [
            'user_id'   => 'required',
            'type'      => 'required',
            'claim'     => 'required'
        ]);

        $buyer_id = $request->user_id;

        $buyername = User::find($request->user_id);

        $is_already_claim = BuyersClaim::where('buyer_id', $buyer_id)
            ->where('admin_id', Auth::user()->id)
            ->where('claim_type', $request->claim)
            ->first();

        if( !empty($is_already_claim) ){
            return redirect()->back();
        }

        $claim = new BuyersClaim();

        $claim->buyer_id = $buyer_id;
        $claim->admin_id = Auth::user()->id;
        $claim->admin_name = Auth::user()->username;
        $claim->claim_type = $request->claim;
        $claim->type = $request->type;
        $claim->is_claim = 0;

        $claim->save();

        $data = array(
            'name' => 'Mike Ismair',
            'buyername' => $buyername->username,
            'admin_name' => Auth::user()->username,
            'is_claim' => '1',
            'typeofpayment' => ''
        );
        Mail::send(['text'=>'Mail.account_ownership'], $data, function($message) {
            $message->to('mike@allieddigitalmedia.com', 'Mike Ismair')->subject('Account Claim Request');
            $message->from(config('mail.from.address', ''),config('mail.from.name', ''));
        });

        return redirect()->back();
    }

    public function edit_status_Claim(Request $request){
        $status = $request->status;
        $claim_id = $request->claim_id;

        $claimDetails = BuyersClaim::where('buyers_claims_id', $claim_id)->first();

        if( !empty($claimDetails) ){
            if( $claimDetails->claim_type == "Sdr" ){
                $type = "sdr_id";
            } else if( $claimDetails->claim_type == "Sales" ){
                $type = "sales_id";
            } else {
                $type = "acc_manger_id";
            }

            $clamer_id = 0;
            $user_id = $claimDetails->buyer_id;

            if( $status != 2 ){
                BuyersClaim::where('buyers_claims_id', $claim_id)
                    ->update([
                        'is_claim' => $status,
                        'confirmed_by_id' => Auth::user()->id,
                        'confirmed_by_name' => Auth::user()->username,
                        'confirmed_at' => date('Y-m-d H:i:s')
                    ]);

                if( $status == 1 ) {
                    $clamer_id = $claimDetails->admin_id;
                }
            } else {
                BuyersClaim::where('buyers_claims_id', $claim_id)->Delete();
            }

            User::where('id', $user_id)->update([
                $type => $clamer_id
            ]);

            return response()->json('0', 200);
        }

        return response()->json('1', 500);
    }
}
