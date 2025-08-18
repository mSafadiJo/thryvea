<?php

namespace App\Http\Controllers;

use App\AccessLog;
use App\BuyersClaim;
use App\Exports\Buyers;
use App\Exports\Contractors;
use App\JoinAsaPro;
use App\State;
use App\User;
use App\Zip_code;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Listeners\SendEmailVerificationNotification;
use Maatwebsite\Excel\Facades\Excel;
use Rap2hpoutre\FastExcel\FastExcel;
use Slack;

class AdminBuyersController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'AdminMiddleware']);
    }

    public function index(Request $request)
    {
        $col_name = null;
        $query = null;
        $sort_search = null;
        $sort_type = null;

        $transactions_comments = ['Credit Accumulation', 'Auto Credit Accumulation', 'eCheck', 'PayPal', 'ACH Credit', 'Add Credit'];

        $users = DB::table('users')
            ->leftJoin('payments', function ($join) {
                $join->on('payments.user_id', '=', 'users.id')
                    ->where('payments.payment_primary', '=', '1');
            })
            ->leftJoin('total_amounts', 'total_amounts.user_id', '=', 'users.id')
            ->leftJoin('payment_type_method', 'payment_type_method.payment_type_method_id', '=', 'users.payment_type_method_id')
            ->join('zip_codes', 'zip_codes.zip_code_id', '=', 'users.zip_code_id')
            ->join('zip_codes_lists', 'zip_codes_lists.zip_code_list_id', '=', 'zip_codes.zip_code')
            ->join('cities', 'cities.city_id', '=', 'zip_codes.city_id')
            ->leftJoin('users AS sales_users', 'sales_users.id', '=', 'users.sales_id')
            ->leftJoin('users AS sdr_users', 'sdr_users.id', '=', 'users.sdr_id')
            ->leftJoin('users AS acc_manager_users', 'acc_manager_users.id', '=', 'users.acc_manger_id')
            ->whereIn('users.role_id', ['3', '4', '5', '6','7'])
            ->where('users.user_visibility', 1);

        if ($request->type != null){
            $var = explode(",", $request->type);
            $col_name = $var[0];
            $query = $var[1];
            $users = $users->orderBy($col_name, $query);
            $sort_type = $request->type;
        }
        if ($request->search != null){
            $sort_search = $request->search;
            $users = $users->where(function ($query) use($sort_search) {
                $query->where('users.username', 'like', '%'.$sort_search.'%');
                $query->OrWhere('users.user_business_name', 'like', '%'.$sort_search.'%');
            });
            $sort_search = $request->search;
        }

        $users->orderBy('users.created_at', 'DESC')
            ->select([
                'users.username', 'users.email', 'users.user_business_name', 'users.user_phone_number',
                'users.user_mobile_number', 'users.created_at', 'users.user_visibility', 'users.id', 'users.role_id',
                'payments.payment_visa_number', 'total_amounts.total_amounts_value', 'users.user_type',
                'cities.city_name', 'zip_codes_lists.zip_code_list', 'payment_type_method.payment_type_method_type',
                'sales_users.username AS sales_username', 'sdr_users.username AS sdr_username', 'acc_manager_users.username AS acc_manager_username',
                DB::raw('(CASE WHEN users.role_id = 4 THEN "Aggregator" WHEN users.role_id = 5 THEN "Seller" WHEN users.role_id = 6 THEN "Enterprise" WHEN users.role_id = 7 THEN "RevShare Seller" ELSE "Buyer" END) AS user_type'),
                DB::raw('(CASE WHEN users.user_visibility = 1 THEN "Active" WHEN users.user_visibility = 2 THEN "Not Active" ELSE "Closed" END) AS users_status_visibility'),
            ]);

        $last_trx_arr = DB::table('campaigns_leads_users')
            ->where("is_returned", 0)
            ->orderBy("date", "desc")
            ->selectRaw('user_id, MAX(date) as last_date')
            ->groupBy('user_id')
            ->pluck('last_date', "user_id")->toarray();

        $total_spend_arr = DB::table('campaigns_leads_users')
            ->where("is_returned", 0)
            ->groupBy('user_id')
            ->selectRaw('SUM(campaigns_leads_users_bid) as total_spend, user_id as user_id_key')
            ->pluck('total_spend', "user_id_key")->toarray();

        $list_of_return_amount = DB::table('transactions')
            ->where("accept", 1)
            ->where('transactions_comments', 'like', '%Return Leads Amount%')
            ->whereNotNull("transactionauthid")
            ->groupBy('user_id')
            ->selectRaw('SUM(transactions_value) as total_bid, user_id as user_id_key')
            ->pluck('total_bid', "user_id_key")->toarray();

        $total_bid_arr = DB::table('transactions')
            ->where("transaction_status", 1)
            ->where("accept", 1)
            ->whereIn("transactions_comments", $transactions_comments)
            ->groupBy('user_id')
            ->selectRaw('SUM(transactions_value) as total_bid, user_id as user_id_key')
            ->pluck('total_bid', "user_id_key")->toarray();

//        $users = $users->paginate(15);
        $users = $users->get();

        $reason_lead_returneds = DB::table('reason_lead_returned')->get()->all();
        $payment_types = DB::table('payment_type_method')->get()->all();

        return view('Admin.Buyers.ListOfBuyers', compact('users', 'col_name', 'query', 'sort_search', 'sort_type', 'reason_lead_returneds', 'payment_types', 'last_trx_arr', 'total_spend_arr', 'total_bid_arr', 'list_of_return_amount'));
    }

    public function showFormAddBuyers(){
        $states = State::All();

        return view('Admin.Buyers.AddBuyersForm')->with('states', $states);
    }

    public function AdminsStore(Request $request){
        $this->validate($request, [
            'firstname' => ['required', 'string', 'max:255'],
            'lastname' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'owner' => ['required', 'string', 'max:255'],
            'businessname' => ['required', 'string', 'max:255'],
            'phonenumber' => ['required', 'string', 'max:255'],
            'mobilenumber' => ['required', 'string', 'max:255'],
            'state' => ['required', 'string', 'max:255'],
            'zipcode' => ['required', 'string', 'max:255'],
            'city' => ['required', 'string', 'max:255'],
            'streetname' => ['required', 'string', 'max:255'],
            'user_type' => ['required']
        ]);

        //Save ZipCode
        $zip_code = new Zip_code();
        $zip_code->city_id = $request['city'];
        $zip_code->zip_code = $request['zipcode'];
        $zip_code->street_name = $request['streetname'];
        $zip_code->state_id = $request['state'];
        $zip_code->save();
        $zip_code_id = DB::getPdo()->lastInsertId();

        $user = new User();

        $user->user_first_name = $request['firstname'];
        $user->user_last_name = $request['lastname'];
        $user->username = ucwords($request['firstname'] . " " . $request['lastname']);
        $user->email = $request['email'];
        $user->password = Hash::make($request['password']);
        $user->user_owner = $request['owner'];
        $user->zip_code_id = $zip_code_id;
        $user->user_business_name = $request['businessname'];
        $user->user_phone_number = $request['phonenumber'];
        $user->user_mobile_number = $request['mobilenumber'];
        $user->hash_phone_number = md5($request['phonenumber']);
        $user->hash_mobile_number = md5($request['mobilenumber']);
        $user->num_of_login = 1;
        $user->user_type = $request['user_type'];
        $user->role_id = $request['user_type'];
        $user->profit_percentage = $request['profit_percentage'];

//        if( $request['verify'] !== null ){
        $user->email_verified_at = date('Y-m-d H:i:s');
//        }

        $user->save();
        $user_id = DB::getPdo()->lastInsertId();

//        if( $request['verify'] == null ){
//            $currentuser = User::find($user_id);
//            $currentuser->sendEmailVerificationNotification();
//        }

        AccessLog::create([
            'user_id' => Auth::user()->id,
            'user_name' => Auth::user()->username,
            'section_id' => $user_id,
            'section_name' => ucwords($request['firstname'] . " " . $request['lastname']),
            'user_role' => Auth::user()->role_id,
            'section'   => 'Buyers',
            'action'    => 'Created',
            'ip_address' => request()->ip(),
            'location' => json_encode(\Location::get(request()->ip())),
            'request_method' => json_encode($request->all())
        ]);

        $email = $request['email'];
        //Slack::send("A new user was registered, email:$email :)");

        return redirect('Admin_Buyers');
    }

    public function DeleteBuyers(Request $request){
        $this->validate($request, [
            'user_id' => ['required', 'string', 'max:255']
        ]);
        $user_id = $request->user_id;

        DB::table('users')->where('id', $user_id)
            ->update(['user_visibility'=>2]);

        $username = DB::table('users')->where('id', $user_id)
            ->first(['username']);

        AccessLog::create([
            'user_id' => Auth::user()->id,
            'user_name' => Auth::user()->username,
            'section_id' => $user_id,
            'section_name' => $username->username,
            'user_role' => Auth::user()->role_id,
            'section'   => 'Buyers',
            'action'    => 'Deleted',
            'ip_address' => request()->ip(),
            'location' => json_encode(\Location::get(request()->ip())),
            'request_method' => json_encode($request->all())
        ]);

        return redirect()->back();
    }

    public function addPaymentMethod(Request $request){
        $user_id = $request->user_id;
        $payment_type = $request->payment_type;

        $admin_id = Auth::user()->id;
        $admin_name = Auth::user()->username;

        $buyername = User::find($user_id);
        $payment_types = DB::table('payment_type_method')
            ->where('payment_type_method_id', $payment_type)
            ->first(['payment_type_method_type']);

        $user_exist = DB::table('user_payment_type_admin')
            ->where('buyer_id', $user_id)
            ->first(['buyer_id']);

        if( empty($user_exist->buyer_id) ){
            DB::table('user_payment_type_admin')->insert([
                'payment_type_method_id' => $payment_type,
                'buyer_id'              => $user_id,
                'admin_id'              => $admin_id,
                'admin_name'            => $admin_name,
                'created_at'            => date('Y-m-d H:i:s'),
                'updated_at'            => date('Y-m-d H:i:s')
            ]);
        } else {
            DB::table('user_payment_type_admin')
                ->where('buyer_id', $user_id)
                ->update([
                    'payment_type_method_id' => $payment_type,
                    'buyer_id'              => $user_id,
                    'admin_id'              => $admin_id,
                    'admin_name'            => $admin_name,
                    'created_at'            => date('Y-m-d H:i:s'),
                    'updated_at'            => date('Y-m-d H:i:s')
                ]);
        }

//        $data = array(
//            'name' => 'Mike Ismair',
//            'buyername' => $buyername->username,
//            'admin_name' => $admin_name,
//            'is_claim' => '0',
//            'typeofpayment' => $payment_types->payment_type_method_type
//        );
//        Mail::send(['text'=>'Mail.account_ownership'], $data, function($message) {
//            $message->to('mike@allieddigitalmedia.com', 'Mike Ismair')->subject('Payment Term Edit Request');
//            $message->from(config('mail.from.address', ''),config('mail.from.name', ''));
//        });

        return redirect()->back();
    }

    public function editBuyers($id){
        $states = State::All();
        $user_data = User::find($id);

        $zip_code_id = $user_data->zip_code_id;
        $zip_code_data = Zip_code::where('zip_code_id', $zip_code_id)->first();

        $zip_code = $zip_code_data->zip_code;
        $street = $zip_code_data->street_name;
        $city_id = $zip_code_data->city_id;

        $state_id = DB::table('cities')->where('city_id', $city_id) ->first('state_id');

        $listOfIds = array(
            'state_id'      => $state_id->state_id,
            'city_id'       => $city_id,
            'zip_code'      => $zip_code,
            'street'        => $street
        );

        return view('Admin.Buyers.edit')
            ->with('states', $states)
            ->with('user_data', $user_data)
            ->with('listOfIds', $listOfIds)
            ->with('errormsg', '');
    }

    public function updateUser(Request $request, $id){
        $this->validate($request, [
            'firstname' => 'required|string',
            'lastname' => 'required|string',
            'email' => 'required|email',
            'owner' => 'required|string',
            'businessname' => 'required|string',
            'phonenumber' => 'required',
            'mobilenumber' => 'required',
            'state' => 'required',
            'city'=> 'required',
            'zipcode' => 'required',
            'streetname'=> 'required',
            'zip_code_id' => 'required',
            'user_type' => 'required'
        ]);

        DB::table('zip_codes')
            ->where('zip_code_id', $request['zip_code_id'] )
            ->update([
                'zip_code' => $request['zipcode'],
                'street_name' => $request['streetname'],
                'city_id' => $request['city'],
                'state_id' => $request['state']
            ]);

        $user = User::find($id);

        $user->user_first_name = $request['firstname'];
        $user->user_last_name = $request['lastname'];
        $user->username =ucwords($request['firstname'] . ' ' .$request['lastname']);
        $user->email = $request['email'];
        $user->user_owner = $request['owner'];
        $user->user_business_name = $request['businessname'];
        $user->user_phone_number = $request['phonenumber'];
        $user->user_mobile_number = $request['mobilenumber'];
        $user->hash_phone_number = md5($request['phonenumber']);
        $user->hash_mobile_number = md5($request['mobilenumber']);
        $user->user_type = $request['user_type'];
        $user->role_id = $request['user_type'];
        $user->user_visibility = $request['user_visibility'];
        $user->permission_users = (empty($request['remove_permission']) ? "" : json_encode($request['remove_permission']));
        if( $request['is_verified'] == 1 ){
            $user->email_verified_at = date('Y-m-d H:i:s');
        }

        //Files
        $contracts = array();
        if( !empty($user->contracts) ){
            $contracts = json_decode($user->contracts, true);
        }
        if($request->hasFile('contracts')){
            foreach ($request->contracts as $key => $contract) {
                $originalname = time() . $contract->getClientOriginalName();
                $path = $contract->storeAs('uploads/buyers/contracts', $originalname);
                array_push($contracts, $originalname);
            }
            $user->contracts = json_encode($contracts);
        }
        $documents = array();
        if( !empty($user->documents) ){
            $documents = json_decode($user->documents, true);
        }
        if($request->hasFile('documents')){
            foreach ($request->documents as $key => $document) {
                $originalname = time() . $document->getClientOriginalName();
                $path = $document->storeAs('uploads/buyers/documents', $originalname);
                array_push($documents, $originalname);
            }
            $user->documents = json_encode($documents);
        }

        $user->profit_percentage = $request['profit_percentage'];
        $user->user_auto_pay_status = $request['user_auto_pay_status'];
        $user->user_auto_pay_amount = $request['user_auto_pay_amount'];

        $user->save();

        AccessLog::create([
            'user_id' => Auth::user()->id,
            'user_name' => Auth::user()->username,
            'section_id' => $id,
            'section_name' => ucwords($request['firstname'] . ' ' .$request['lastname']),
            'user_role' => Auth::user()->role_id,
            'section'   => 'Buyers',
            'action'    => 'Updated',
            'ip_address' => request()->ip(),
            'location' => json_encode(\Location::get(request()->ip())),
            'request_method' => json_encode($request->all())
        ]);

        return redirect()->back();
    }

    public function updateUserPassword(Request $request, $id){
        $this->validate($request, [
            'password'=> 'required|string|min:8|confirmed'
        ]);

        DB::table('users')
            ->where('id', $id)
            ->update(['password' => Hash::make($request['password'])]);

        $username = DB::table('users')->where('id', $id)->first('username');

        AccessLog::create([
            'user_id' => Auth::user()->id,
            'user_name' => Auth::user()->username,
            'section_id' => $id,
            'section_name' => $username->username,
            'user_role' => Auth::user()->role_id,
            'section'   => 'Buyers',
            'action'    => 'Updated',
            'ip_address' => request()->ip(),
            'location' => json_encode(\Location::get(request()->ip())),
            'request_method' => json_encode($request->all())
        ]);

        return redirect()->back();
    }

    public function join_as_apro_list(Request $request){
        $col_name = null;
        $query = null;
        $sort_search = null;
        $sort_type = null;

        $prospects = new JoinAsaPro;
        if ($request->type != null){
            $var = explode(",", $request->type);
            $col_name = $var[0];
            $query = $var[1];
            $prospects = $prospects->orderBy($col_name, $query);
            $sort_type = $request->type;
        }
        if ($request->search != null){
            $sort_search = $request->search;
            $prospects = $prospects->where(function ($query) use($sort_search) {
                $query->where('full_name', 'like', '%'.$sort_search.'%');
                $query->OrWhere('business_name', 'like', '%'.$sort_search.'%');
            });
            $sort_search = $request->search;
        }

//        $prospects = $prospects->orderBy('created_at', 'desc')->paginate(15);
        $prospects = $prospects->orderBy('created_at', 'desc')->get();

        return view('Admin.Prospects.JoinAsAPro.index', compact('prospects', 'col_name', 'query', 'sort_search', 'sort_type'));
    }

    public function join_as_apro_export(Request $request){
        $prospects = JoinAsaPro::orderBy('created_at', 'desc')->get();

        AccessLog::create([
            'user_id' => Auth::user()->id,
            'user_name' => Auth::user()->username,
            'section_id' => 1,
            'section_name' => "Export Contractors",
            'user_role' => Auth::user()->role_id,
            'section'   => 'ProspectUsers',
            'action'    => 'Export',
            'ip_address' => request()->ip(),
            'location' => json_encode(\Location::get(request()->ip())),
            'request_method' => json_encode($request->all())
        ]);

        return (new FastExcel($prospects))->download('Contractors.csv', function ($lead) {
            return [
                'ID' => $lead->id,
                'Full Name' => $lead->full_name,
                'Business Name' => $lead->business_name,
                'Phone Number' => " " . $lead->phone_number . " ",
                'Email' => $lead->email,
                'Services' => $lead->services,
                'Address' => $lead->lead_address,
                'City' => $lead->city,
                'ZIPCode' => " " . $lead->zip_code . " ",
                'Resource' => $lead->resource,
                'IP Address' => $lead->ip_address,
                'TS' => $lead->google_ts,
                'C' => $lead->google_c,
                'K' => $lead->google_k,
                'G' => $lead->google_g,
                'Note' => $lead->note,
                'Created Date' => date('m/d/Y H:i:s', strtotime($lead->created_at)),
            ];
        });
    }

    public function buyers_export(Request $request){
        $date = date('Y-m-d');

        $users = User::join('zip_codes', 'zip_codes.zip_code_id', '=', 'users.zip_code_id')
            ->join('cities', 'cities.city_id', '=', 'zip_codes.city_id')
            ->leftjoin('states', 'states.state_id', '=', 'zip_codes.state_id')
            ->leftJoin('total_amounts', 'total_amounts.user_id', '=', 'users.id')
            ->leftJoin('users AS sales_users', 'sales_users.id', '=', 'users.sales_id')
            ->leftJoin('users AS sdr_users', 'sdr_users.id', '=', 'users.sdr_id')
            ->leftJoin('users AS acc_manager_users', 'acc_manager_users.id', '=', 'users.acc_manger_id')
//            ->leftJoin('campaigns_leads_users', 'campaigns_leads_users.user_id', '=', 'users.id')
            ->where('users.user_visibility', '<>', 4)
            ->whereIn('users.role_id', ['3', '4', '5', '6','7'])
            ->orderBy('users.created_at', 'desc')
            ->groupBy('users.id')
            ->get([
                'users.id', 'users.username', 'users.email', 'users.user_business_name', 'users.user_phone_number',
                'users.user_mobile_number', 'cities.city_name', 'states.state_code', 'users.created_at', 'total_amounts.total_amounts_value',
                'sales_users.username AS sales_username', 'sdr_users.username AS sdr_username', 'acc_manager_users.username AS acc_manager_username',
//                DB::raw("COUNT(campaigns_leads_users.lead_id) AS total_lead"),
//                DB::raw("(SELECT COUNT(campaigns_leads_users2.lead_id) FROM campaigns_leads_users As campaigns_leads_users2
//                                WHERE campaigns_leads_users2.user_id = users.id
//                                AND campaigns_leads_users2.date = '". $date ."' ) AS daily_lead"),
                DB::raw('(CASE WHEN users.role_id = 4 THEN "Aggregator" WHEN users.role_id = 5 THEN "Seller" WHEN users.role_id = 6 THEN "Enterprise" WHEN users.role_id = 7 THEN "RevShare Seller" ELSE "Buyer" END) AS user_type'),
                DB::raw('(CASE WHEN users.user_visibility = 1 THEN "Active" WHEN users.user_visibility = 2 THEN "Not Active" ELSE "Closed" END) AS users_status_visibility'),
//                DB::raw("(SELECT SUM(transactions.transactions_value) FROM transactions
//                                WHERE transactions.user_id = users.id
//                                AND transactions.transaction_status = 1
//                                AND transactions.accept = 1
//                                AND transactions.transactions_comments IN ('Credit Accumulation', 'Auto Credit Accumulation', 'eCheck', 'PayPal', 'ACH Credit', 'Add Credit')
//                                GROUP BY transactions.user_id) AS total_bid"),
//                DB::raw("(SELECT SUM(campaigns_leads_users.campaigns_leads_users_bid) FROM campaigns_leads_users
//                                WHERE campaigns_leads_users.user_id = users.id
//                                AND campaigns_leads_users.is_returned = 0
//                                GROUP BY campaigns_leads_users.user_id) AS total_spend"),
//                DB::raw("(SELECT campaigns_leads_users2.date FROM campaigns_leads_users AS campaigns_leads_users2
//                                WHERE campaigns_leads_users2.user_id = users.id
//                                AND campaigns_leads_users2.is_returned = 0
//                                ORDER BY campaigns_leads_users2.date DESC LIMIT 1) AS lead_date"),
                DB::raw("(SELECT GROUP_CONCAT(DISTINCT service__campaigns.service_campaign_name) as service_campaign_name FROM campaigns
                                JOIN users AS user1 ON campaigns.user_id = user1.id
                                JOIN service__campaigns ON service__campaigns.service_campaign_id=campaigns.service_campaign_id
                                WHERE campaigns.campaign_visibility = 1
                                AND campaigns.is_seller = 0
                                AND user1.user_visibility = 1
                                AND campaigns.user_id = users.id) AS service_campaign_name"),
                DB::raw("(SELECT COUNT(DISTINCT campaigns.campaign_id) as numberOfCamp FROM campaigns
                                JOIN users AS user2 ON campaigns.user_id = user2.id
                                WHERE campaigns.campaign_visibility = 1
                                AND campaigns.is_seller = 0
                                AND user2.user_visibility = 1
                                AND campaigns.user_id = users.id) AS numberOfCamp"),
            ]);

        $transactions_comments = ['Credit Accumulation', 'Auto Credit Accumulation', 'eCheck', 'PayPal', 'ACH Credit', 'Add Credit'];

        $daily_lead_arr = DB::table('campaigns_leads_users')
            ->where("date", $date)
            ->groupBy('user_id')
            ->selectRaw('COUNT(lead_id) as daily_lead, user_id as user_id_key')
            ->pluck('daily_lead', "user_id_key")->toarray();

        $total_lead_arr = DB::table('campaigns_leads_users')
            ->groupBy('user_id')
            ->selectRaw('COUNT(lead_id) as total_lead, user_id as user_id_key')
            ->pluck('total_lead', "user_id_key")->toarray();

        $last_trx_arr = DB::table('campaigns_leads_users')
            ->where("is_returned", 0)
            ->orderBy("date", "desc")
            ->selectRaw('user_id, MAX(date) as last_date')
            ->groupBy('user_id')
            ->pluck('last_date', "user_id")->toarray();

        $total_spend_arr = DB::table('campaigns_leads_users')
            ->where("is_returned", 0)
            ->groupBy('user_id')
            ->selectRaw('SUM(campaigns_leads_users_bid) as total_spend, user_id as user_id_key')
            ->pluck('total_spend', "user_id_key")->toarray();

        $list_of_return_amount = DB::table('transactions')
            ->where("accept", 1)
            ->where('transactions_comments', 'like', '%Return Leads Amount%')
            ->whereNotNull("transactionauthid")
            ->groupBy('user_id')
            ->selectRaw('SUM(transactions_value) as total_bid, user_id as user_id_key')
            ->pluck('total_bid', "user_id_key")->toarray();

        $total_bid_arr = DB::table('transactions')
            ->where("transaction_status", 1)
            ->where("accept", 1)
            ->whereIn("transactions_comments", $transactions_comments)
            ->groupBy('user_id')
            ->selectRaw('SUM(transactions_value) as total_bid, user_id as user_id_key')
            ->pluck('total_bid', "user_id_key")->toarray();

        AccessLog::create([
            'user_id' => Auth::user()->id,
            'user_name' => Auth::user()->username,
            'section_id' => 1,
            'section_name' => "Export Buyers",
            'user_role' => Auth::user()->role_id,
            'section'   => 'Buyers',
            'action'    => 'Export',
            'ip_address' => request()->ip(),
            'location' => json_encode(\Location::get(request()->ip())),
            'request_method' => json_encode($request->all())
        ]);

        $data_array[0] = array(
            'ID', 'Name', 'Email', 'Business Name', 'Phone', 'Mobile', 'City', 'State', 'Type', 'Status',
            'Created At', 'Current Balance', 'Total Spent', 'Total Funded', 'Last Transaction', 'Account Manager Claimer',
            'Sales Claimer', 'SDR Claimer', 'Number Of Campaigns', 'Services', 'Daily Lead', 'Total Lead'
        );

        if (!empty($users)) {
            $count = 1;
            foreach ($users as $item) {
                $data_array[$count][0] = $item->id;
                $data_array[$count][1] = $item->username;
                $data_array[$count][2] = $item->email;
                $data_array[$count][3] = $item->user_business_name;
                $data_array[$count][4] = " " . $item->user_phone_number . " ";
                $data_array[$count][5] = " " . $item->user_mobile_number . " ";
                $data_array[$count][6] = $item->city_name;
                $data_array[$count][7] = $item->state_code;
                $data_array[$count][8] = $item->user_type;
                $data_array[$count][9] = $item->users_status_visibility;
                $data_array[$count][10] = " " . $item->created_at . " ";
                $data_array[$count][11] = (!empty($item->total_amounts_value) ? $item->total_amounts_value : 0);
                $data_array[$count][12] = number_format((!empty($total_spend_arr[$item->id]) ? (!empty($list_of_return_amount[$item->id]) ? $total_spend_arr[$item->id] - $list_of_return_amount[$item->id] : $total_spend_arr[$item->id]) : 0), 2, '.', ',');
                $data_array[$count][13] = (!empty($total_bid_arr[$item->id]) ? $total_bid_arr[$item->id] : 0);
                $data_array[$count][14] = (!empty($last_trx_arr[$item->id]) ? " " . $last_trx_arr[$item->id] . " " : "");
                $data_array[$count][15] = $item->acc_manager_username;
                $data_array[$count][16] = $item->sales_username;
                $data_array[$count][17] = $item->sdr_username;
                $data_array[$count][18] = $item->numberOfCamp;
                $data_array[$count][19] = $item->service_campaign_name;
                $data_array[$count][20] = (!empty($daily_lead_arr[$item->id]) ? $daily_lead_arr[$item->id] : 0);
                $data_array[$count][21] = (!empty($total_lead_arr[$item->id]) ? $total_lead_arr[$item->id] : 0);
                $count += 1;
            }
        }

        header("Content-type: application/vnd.ms-excel");
        header('Content-Type: application/force-download');
        header("Content-disposition: attachment; filename=buyers.csv");
        header('Content-Encoding: UTF-8');
        header('Content-type: text/csv; charset=UTF-8');
        header("Pragma: no-cache");
        header("Expires: 0");
        header("Cache-Control: ");

        $fp = fopen('php://output', 'w');
        echo "\xEF\xBB\xBF";

        foreach( $data_array as $key=>$item ){
            fputcsv($fp, $item);
        }

//        return (new FastExcel($users))->download('buyers.csv', function ($user) {
//            return [
//                'ID' => $user->id,
//                'Name' => $user->username,
//                'Email' => $user->email,
//                'Business Name' => $user->user_business_name,
//                'Phone' => $user->user_phone_number,
//                'Mobile' => $user->user_mobile_number,
//                'City' => $user->city_name,
//                'State' => $user->state_code,
//                'Type' => $user->user_type,
//                'Status' => $user->users_status_visibility,
//                'CreatedAt' => " " . $user->created_at . " ",
//                'Current Balance' => $user->total_amounts_value,
//                'Total Spent' => $user->total_spend,
//                'Total Funded' => $user->total_bid,
//                'Last Transaction' => " " . $user->lead_date . " ",
//                'Account Manager Claimer' => $user->acc_manager_username,
//                'Sales Claimer' => $user->sales_username,
//                'SDR Claimer' => $user->sdr_username,
//                'Number Of Campaigns' => $user->numberOfCamp,
//                'Services' => $user->service_campaign_name,
//                'Daily Lead' => $user->daily_lead,
//                'Total Lead' => $user->total_lead,
//            ];
//        });
    }

    public function listOfOldBuyers(Request $request){
        $col_name = null;
        $query = null;
        $sort_search = null;
        $sort_type = null;

        $users = DB::table('users')
            ->leftJoin('payments', function ($join) {
                $join->on('payments.user_id', '=', 'users.id')
                    ->where('payments.payment_primary', '=', '1');
            })
            ->leftJoin('total_amounts', 'total_amounts.user_id', '=', 'users.id')
            ->join('zip_codes', 'zip_codes.zip_code_id', '=', 'users.zip_code_id')
            ->join('zip_codes_lists', 'zip_codes_lists.zip_code_list_id', '=', 'zip_codes.zip_code')
            ->join('cities', 'cities.city_id', '=', 'zip_codes.city_id')
            ->leftJoin('users AS sales_users', 'sales_users.id', '=', 'users.sales_id')
            ->leftJoin('users AS sdr_users', 'sdr_users.id', '=', 'users.sdr_id')
            ->leftJoin('users AS acc_manager_users', 'acc_manager_users.id', '=', 'users.acc_manger_id')
            ->whereIn('users.role_id', ['3', '4', '5', '6','7'])
            ->whereIn('users.user_visibility', ['2', '3']);

        if ($request->type != null){
            $var = explode(",", $request->type);
            $col_name = $var[0];
            $query = $var[1];
            $users = $users->orderBy($col_name, $query);
            $sort_type = $request->type;
        }
        if ($request->search != null){
            $sort_search = $request->search;
            $users = $users->where(function ($query) use($sort_search) {
                $query->where('users.username', 'like', '%'.$sort_search.'%');
                $query->OrWhere('users.user_business_name', 'like', '%'.$sort_search.'%');
            });
            $sort_search = $request->search;
        }

        $users->orderBy('users.created_at', 'DESC')
            ->select([
                'users.username', 'users.email', 'users.user_business_name', 'users.user_phone_number',
                'users.user_mobile_number', 'users.created_at', 'users.user_visibility', 'users.id', 'users.role_id',
                'users.user_type',
                'cities.city_name', 'zip_codes_lists.zip_code_list',
                'sales_users.username AS sales_username', 'sdr_users.username AS sdr_username', 'acc_manager_users.username AS acc_manager_username',
                DB::raw('(CASE WHEN users.role_id = 4 THEN "Aggregator" WHEN users.role_id = 5 THEN "Seller" WHEN users.role_id = 6 THEN "Enterprise" WHEN users.role_id = 7 THEN "RevShare Seller" ELSE "Buyer" END) AS user_type'),
                DB::raw('(CASE WHEN users.user_visibility = 2 THEN "Not Active" ELSE "Closed" END) AS users_status_visibility'),
            ]);

        $users = $users->get();


        return view('Admin.Buyers.ListOfOldBuyers', compact('users', 'col_name', 'query', 'sort_search', 'sort_type'));
    }

    public function Rev_Share($user_id){
        $campaigns = DB::table('campaigns')
            ->where('campaign_visibility', 1)
            ->where('user_id', $user_id)
            ->where('is_seller', 0)
            ->orderBy('created_at', 'DESC')
            ->get(['campaign_id','campaign_name']);

        return view('Admin.Buyers.editRefShare', compact('campaigns','user_id'));
    }

    public function Rev_Share_Save(Request $request){
        $this->validate($request, [
            'price' => 'required',
        ]);

        $campaign_id = $request->campaign_id;
        $price = $request->price;
        $start_date = $request->start_date;
        $end_date = $request->end_date;
        $user_id = $request->user_id;

        //get total amounts for buyer
        $Current_Balance = DB::table('total_amounts')
            ->where('user_id', $user_id)
            ->first(['total_amounts_value', 'total_amounts_id']);

        //return total and sum lead
        $campaign_leads = DB::table('campaigns_leads_users')
            ->where('campaign_id', $campaign_id)
            ->where('is_returned', 0)
            ->where('date','>=', $start_date)
            ->where('date','<=',  $end_date)
            ->selectRaw('SUM(campaigns_leads_users_bid) as sum_bid, COUNT(campaigns_leads_users_id) as total_lead')->first();

        if (!empty($campaign_leads->total_lead)) {
            $leads_cost = $price / $campaign_leads->total_lead;
            $leads_cost_trn = $price - $campaign_leads->sum_bid;
            $New_Current_Balance = $Current_Balance->total_amounts_value - $leads_cost_trn;

            //update bid for leads
            DB::table('campaigns_leads_users')
                ->where('campaign_id', $campaign_id)
                ->where('is_returned', 0)
                ->where('date','>=', $start_date)
                ->where('date','<=',  $end_date)
                ->update(['campaigns_leads_users_bid' => $leads_cost]);

            //update current balance
            DB::table('total_amounts')
                ->where('total_amounts_id', $Current_Balance->total_amounts_id)
                ->update(['total_amounts_value' => $New_Current_Balance]);

            $message = "Leads update successfully";
        } else {
            $message = "Please make sure that this campaign recently bought leads";
        }

        return redirect()->back()->with('message', $message);
    }

}
