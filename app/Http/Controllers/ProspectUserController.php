<?php

namespace App\Http\Controllers;

use App\AccessLog;
use App\ProspectTransactions;
use App\ProspectUsers;
use App\Service_Campaign;
use App\State;
use App\User;
use App\Zip_code;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\Prospects;
use mysql_xdevapi\Exception;

class ProspectUserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function __construct()
    {
        $this->middleware(['auth', 'AdminMiddleware']);
    }

    public function index(Request $request)
    {
        $states = State::All();
        $services = Service_Campaign::All();

        return view('Admin.Prospects.index', compact('states', 'services'));
    }

    public function search(Request $request){
        $service_id = $request->service_id;
        $state_id = $request->state_id;
        $claimer_prospect = $request->claimer_prospect;
        $start_date = $request->start_date . ' 00:00:00';
        $end_date = $request->end_date . ' 23:59:59';

        $prospects = ProspectUsers::join('zip_codes', 'zip_codes.zip_code_id', '=', 'prospect_users.zip_code_id')
            ->leftjoin('states', 'states.state_id', '=', 'zip_codes.state_id')
            ->where('user_visibility', '<>', 4);

        if (!empty($service_id)) {
            $prospects->whereIn('prospect_users.service', $service_id);
        }

        if (!empty($state_id)) {
            $prospects->whereIn('zip_codes.state_id', $service_id);
        }

        if (!empty($claimer_prospect)) {
            $prospects->where(function ($query) use($claimer_prospect) {
                $query->where('prospect_users.sdr_claimer', 'like', '%' . $claimer_prospect . '%');
                $query->OrWhere('prospect_users.sales_claimer', 'like', '%' . $claimer_prospect . '%');
            });
        }

        if (!empty($start_date) && !empty($end_date)) {
            $prospects->whereBetween('prospect_users.created_at', [$start_date, $end_date]);
        }

        $prospects = $prospects->orderBy('prospect_users.created_at', 'desc')
            ->get([
                'prospect_users.*', 'states.state_code'
            ]);

        $permission_users = array();
        if( !empty(Auth::user()->permission_users) ){
            $permission_users = json_decode(Auth::user()->permission_users, true);
        }

        $dataJason = '<table id="datatable4" class="table table-striped table-bordered" cellspacing="0" width="100%">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Business Name</th>
                                    <th>Contact Name</th>
                                    <th>Contact</th>
                                    <th>State</th>
                                    <th>Service</th>
                                    <th>Status</th>
                                    <th>Sales Executive</th>
                                    <th>Created At</th>
                                    <th>Statistics</th>';
        if( empty($permission_users) || in_array('14-2', $permission_users) || in_array('14-3', $permission_users)
            || in_array('14-4', $permission_users) || in_array('14-5', $permission_users)|| in_array('14-6', $permission_users)){
            $dataJason .= '<th>Action</th>';
        }
        $dataJason .= '</tr>
                       </thead>
                       <tbody>';

        if (!empty($prospects)) {
            foreach ($prospects as $val) {
                $dataJason .= '<tr>';

                $dataJason .= '<td>' . $val->id . '</td>';
                $dataJason .= '<td>' . $val->user_business_name . '</td>';
                $dataJason .= '<td>' . $val->username . '</td>';
                $dataJason .= '<td><b>Email Address:</b> ' . $val->email . '<br/><b>Tel No:</b> '. $val->user_phone_number .'</td>';
                $dataJason .= '<td>' . $val->state_code . '</td>';
                $dataJason .= '<td>' . $val->service . '</td>';

                if( $val->user_visibility == 1 ){
                    $user_visibility = "Active";
                } else if( $val->user_visibility == 2 ){
                    $user_visibility = "InActive";
                } else if( $val->user_visibility == 3 ){
                    $user_visibility = "Closed";
                } else {
                    $user_visibility = "Active";
                }
                $dataJason .= '<td>' . $user_visibility . '</td>';

                $dataJason .= '<td><b>SDR:</b> ' . (!empty($val->sdr_claimer) ? $val->sdr_claimer : "---") . '<br/><b>Sales:</b> ' . (!empty($val->sales_claimer) ? $val->sales_claimer : "---") . '</td>';

                $dataJason .= '<td>' . date('Y/m/d h:i A', strtotime($val->created_at)) . '</td>';

                $numberofcall = ProspectTransactions::where('prospect_id', $val->id)->where('type', 'Call')->count();
                $numberofemail = ProspectTransactions::where('prospect_id', $val->id)->where('type', 'Email')->count();
                $numberofmeeting = ProspectTransactions::where('prospect_id', $val->id)->where('type', 'Meeting')->count();

                $dataJason .= "<td><b>Call#:</b> $numberofcall<br/><b>Email#:</b> $numberofemail <br/><b>Meeting#:</b> $numberofmeeting</td>";

                $dataJason .= '<td>';

                if($val->user_visibility != 4 ){
                    if( empty($permission_users) || in_array('14-2', $permission_users) ){
                        $dataJason .= '<a href="'.route('Prospects.edit', $val->id) .'" class="on-default edit-row" data-toggle="tooltip" data-placement="top" title="" data-original-title="Edit" data-trigger="hover" data-animation="false">
                                                        <i class="fa fa-pencil"></i>
                                                    </a>';
                    }

                    if( empty($permission_users) || in_array('14-3', $permission_users) ){
                        if( $val->user_visibility == 1 ){
                            $dataJason .= '<form style="display: inline-block" action="' . route('Prospects.destroy', $val->id) . '" method="post" id="DeleteForm' . $val->id . '">
                                        ' . csrf_field() . method_field('DELETE') . '
                                        </form>
                                        <span style="cursor: pointer;" class="on-default remove-row" data-toggle="tooltip" data-placement="top" title="" data-original-title="Delete" data-trigger="hover" data-animation="false"
                                            onclick="return confirmMsgForDelete(\'' . $val->id . '\');">
                                            <i class="fa fa-trash-o"></i>
                                        </span>';
                        }
                    }

                    if( empty($permission_users) || in_array('14-4', $permission_users) ) {
                        $dataJason .= '<a href="' . route('Prospects.transaction', $val->id) . '" class="on-default edit-row" data-toggle="tooltip" data-placement="top" title="" data-original-title="List Of Transactions" data-trigger="hover" data-animation="false">
                                                            <i class="fa fa-list"></i>
                                                        </a>';
                    }
                    if( empty($permission_users) || in_array('14-5', $permission_users) ) {
                        $dataJason .= '<span style="cursor: pointer;color: #36c736;" class="on-default edit-row" data-toggle="tooltip" data-placement="top" title="" data-original-title="Add Transactions" data-trigger="hover" data-animation="false">
                                                             <i class="mdi mdi-plus-circle-outline" data-toggle="modal" data-target="#con-close-modal"
                                                                onclick="return document.getElementById(\'prospect_id_menu\').value =' . $val->id . '"></i>
                                                        </span>';
                    }

                    if( empty($permission_users) || in_array('14-6', $permission_users) ) {
                        $dataJason .= '<a href="' . route('Prospects.convert', $val->id) . '" class="on-default edit-row" data-toggle="tooltip" data-placement="top" title="" data-original-title="Change to a buyer" data-trigger="hover" data-animation="false"
                                                            onclick="return confirm(\'Are you sure you want to convert this user to a buyer?\')">
                                                            <i class="fa fa-share"></i>
                                                        </a>';
                    }
                }

                $dataJason .= '</td>';

            }
        }

        $dataJason .= '  </tbody>
                            </table>';

        return $dataJason;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $states = State::All();
        $services = Service_Campaign::All();
        return view('Admin.Prospects.create', compact('states', 'services'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'firstname' => ['required', 'string', 'max:255'],
            'lastname' => ['required', 'string', 'max:255'],
            'service' => ['required', 'string', 'max:255'],
            'businessname' => ['required', 'string', 'max:255'],
            'mobilenumber' => ['required', 'string', 'max:255'],
            'state' => ['required', 'string', 'max:255'],
        ]);

        //Save ZipCode
        $zip_code = new Zip_code();
        $zip_code->city_id = $request['city'];
        $zip_code->zip_code = $request['zipcode'];
        $zip_code->street_name = $request['streetname'];
        $zip_code->state_id = $request['state'];

        $zip_code->save();
        $zip_code_id = DB::getPdo()->lastInsertId();

        $prospect = new ProspectUsers();

        $prospect->user_first_name = $request['firstname'];
        $prospect->user_last_name = $request['lastname'];
        $prospect->username = ucwords($request['firstname'] . " " . $request['lastname']);
        $prospect->email = $request['email'];
        $prospect->service = $request['service'];
        $prospect->user_owner = $request['owner'];
        $prospect->zip_code_id = $zip_code_id;
        $prospect->user_business_name = $request['businessname'];
        $prospect->user_phone_number = $request['mobilenumber'];
        $prospect->user_mobile_number = $request['mobilenumber'];
        $prospect->user_type = (!empty($request['user_type']) ?  $request['user_type'] : 3);
        $prospect->sdr_claimer = $request['sdr_claimer'];
        $prospect->sales_claimer = $request['sales_claimer'];

        $prospect->save();
        $prospect_id = DB::getPdo()->lastInsertId();

        AccessLog::create([
            'user_id' => Auth::user()->id,
            'user_name' => Auth::user()->username,
            'section_id' => $prospect_id,
            'section_name' => ucwords($request['firstname'] . " " . $request['lastname']),
            'user_role' => Auth::user()->role_id,
            'section'   => 'ProspectUsers',
            'action'    => 'Created',
            'ip_address' => request()->ip(),
            'location' => json_encode(\Location::get(request()->ip())),
            'request_method' => json_encode($request->all())
        ]);

        \Session::put('success', 'Prospects User has been Added Successfully!');

          return redirect()->route('Prospects.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $states = State::All();
        $services = Service_Campaign::All();

        $prospect = ProspectUsers::find($id);

        $zip_code_id = $prospect->zip_code_id;
        $zip_code_data = Zip_code::where('zip_code_id', $zip_code_id)->first();

        $zip_code = $zip_code_data->zip_code;
        $street = $zip_code_data->street_name;
        $city_id = $zip_code_data->city_id;
        $state_id = $zip_code_data->state_id;

        $listOfIds = array(
            'state_id'      => $state_id,
            'city_id'       => $city_id,
            'zip_code'      => $zip_code,
            'street'        => $street
        );

        return view('Admin.Prospects.edit', compact('states', 'services', 'prospect', 'listOfIds'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'firstname' => 'required|string',
            'lastname' => 'required|string',
            'service' => 'required|string',
            'businessname' => 'required|string',
            'mobilenumber' => 'required',
            'state' => 'required',
            'user_type' => 'required',
            'user_visibility' => 'required'
        ]);

        Zip_code::where('zip_code_id', $request['zip_code_id'] )
            ->update([
                'zip_code' => $request['zipcode'],
                'street_name' => $request['streetname'],
                'city_id' => $request['city'],
                'state_id' => $request['state'],
            ]);

        $prospect = ProspectUsers::find($id);

        $prospect->user_first_name = $request['firstname'];
        $prospect->user_last_name = $request['lastname'];
        $prospect->username =ucwords($request['firstname'] . ' ' .$request['lastname']);
        $prospect->email = $request['email'];
        $prospect->user_owner = $request['owner'];
        $prospect->service = $request['service'];
        $prospect->user_business_name = $request['businessname'];
        $prospect->user_phone_number = $request['mobilenumber'];
        $prospect->user_mobile_number = $request['mobilenumber'];
        $prospect->user_type = $request['user_type'];
        $prospect->sdr_claimer = $request['sdr_claimer'];
        $prospect->sales_claimer = $request['sales_claimer'];
        $prospect->user_visibility = $request['user_visibility'];

        $prospect->save();

        AccessLog::create([
            'user_id' => Auth::user()->id,
            'user_name' => Auth::user()->username,
            'section_id' => $id,
            'section_name' => ucwords($request['firstname'] . ' ' .$request['lastname']),
            'user_role' => Auth::user()->role_id,
            'section'   => 'ProspectUsers',
            'action'    => 'Updated',
            'ip_address' => request()->ip(),
            'location' => json_encode(\Location::get(request()->ip())),
            'request_method' => json_encode($request->all())
        ]);

        \Session::put('success', 'Prospects User has been Updated Successfully!');

        return redirect()->route('Prospects.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        ProspectUsers::where('id', $id)->update(['user_visibility'=>2]);
        $username = ProspectUsers::where('id', $id)->first(['username']);

        AccessLog::create([
            'user_id' => Auth::user()->id,
            'user_name' => Auth::user()->username,
            'section_id' => $id,
            'section_name' => $username->username,
            'user_role' => Auth::user()->role_id,
            'section'   => 'ProspectUsers',
            'action'    => 'Deleted',
            'ip_address' => request()->ip(),
            'location' => json_encode(\Location::get(request()->ip())),
            'request_method' => ""
        ]);

        \Session::put('success', 'Prospects User has been Deleted Successfully!');

        return redirect()->route('Prospects.index');
    }

    public function convert($id){
        $prospect = ProspectUsers::find($id);
        $prospect_zipcode = Zip_code::where('zip_code_id', $prospect->zip_code_id )->first();

        $zip_code_id = "";
        try {
            //Save ZipCode
            $zip_code = new Zip_code();
            $zip_code->city_id = $prospect_zipcode->city_id;
            $zip_code->zip_code = $prospect_zipcode->zip_code;
            $zip_code->street_name = $prospect_zipcode->street_name;

            $zip_code->save();
            $zip_code_id = DB::getPdo()->lastInsertId();

            $user = new User();

            $user->user_first_name = $prospect->user_first_name;
            $user->user_last_name = $prospect->user_last_name;
            $user->username = $prospect->username;
            $user->email = $prospect->email;
            $user->user_owner = $prospect->user_owner;
            $user->zip_code_id = $zip_code_id;
            $user->user_business_name = $prospect->user_business_name;
            $user->user_phone_number = $prospect->user_phone_number;
            $user->user_mobile_number = $prospect->user_mobile_number;
            $user->user_type = $prospect->user_type;
            $user->role_id = $prospect->user_type;
            $user->num_of_login = 1;
            $user->email_verified_at = date('Y-m-d H:i:s');
            $user->password = Hash::make("123456789");

            $user->save();

            $prospect->user_visibility = 4;

            $prospect->save();

            AccessLog::create([
                'user_id' => Auth::user()->id,
                'user_name' => Auth::user()->username,
                'section_id' => $id,
                'section_name' => $prospect->username,
                'user_role' => Auth::user()->role_id,
                'section'   => 'ProspectUsers',
                'action'    => 'Convert',
                'ip_address' => request()->ip(),
                'location' => json_encode(\Location::get(request()->ip())),
                'request_method' => ""
            ]);

            \Session::put('success', 'Prospects User has been Converted Successfully!');
        } catch (\Illuminate\Database\QueryException $ex){
            Zip_code::where('zip_code_id', $zip_code_id)->delete();
            \Session::put('error', 'Missing required parameters');
        }

        return redirect()->route('Prospects.index');
    }

    public function transaction(Request $request, $id){
        $col_name = null;
        $query = null;
        $sort_search = null;
        $sort_type = null;

        $prospect_transactions = ProspectTransactions::join('prospect_users', 'prospect_users.id', '=', 'prospect_transactions.prospect_id')
            ->join('users', 'users.id', '=', 'prospect_transactions.user_id');
        if ($request->type != null){
            $var = explode(",", $request->type);
            $col_name = $var[0];
            $query = $var[1];
            $prospect_transactions = $prospect_transactions->orderBy($col_name, $query);
            $sort_type = $request->type;
        }
        if ($request->search != null){
            $prospect_transactions = $prospect_transactions->where('prospect_transactions.type', $request->search);
            $sort_search = $request->search;
        }

        $prospect_transactions = $prospect_transactions->where('prospect_id', $id)
            ->orderBy('prospect_transactions.created_at', 'desc')
            ->select([
                'prospect_transactions.*',
                'prospect_users.username AS prospect_username',
                'prospect_users.user_business_name AS prospect_user_business_name',
                'prospect_users.sdr_claimer',  'prospect_users.sales_claimer',
                'users.username AS admin_username'
            ]);

//        $prospect_transactions = $prospect_transactions->paginate(15);
        $prospect_transactions = $prospect_transactions->get();

        return view('Admin.Prospects.Transaction.index', compact('prospect_transactions', 'id', 'col_name', 'query', 'sort_search', 'sort_type'));
    }

    public function transaction_store(Request $request){
        $prospect_transaction = new ProspectTransactions();

        $prospect_transaction->type = $request->type;
        $prospect_transaction->user_id = Auth::user()->id;
        $prospect_transaction->prospect_id = $request->prospect_id;
        $prospect_transaction->note = $request->note;

        $prospect_transaction->save();
        $prospect_transaction_id = DB::getPdo()->lastInsertId();

        AccessLog::create([
            'user_id' => Auth::user()->id,
            'user_name' => Auth::user()->username,
            'section_id' => $prospect_transaction_id,
            'section_name' => "Transactions",
            'user_role' => Auth::user()->role_id,
            'section'   => 'ProspectUsers',
            'action'    => 'Add Transactions',
            'ip_address' => request()->ip(),
            'location' => json_encode(\Location::get(request()->ip())),
            'request_method' => json_encode($request->all())
        ]);

        \Session::put('success', 'Prospects Transactions has been added Successfully!');

        return redirect()->route('Prospects.index');
    }

    public function prospects_export(Request $request){
        AccessLog::create([
            'user_id' => Auth::user()->id,
            'user_name' => Auth::user()->username,
            'section_id' => 1,
            'section_name' => "Export Prospects",
            'user_role' => Auth::user()->role_id,
            'section'   => 'ProspectUsers',
            'action'    => 'Export',
            'ip_address' => request()->ip(),
            'location' => json_encode(\Location::get(request()->ip())),
            'request_method' => json_encode($request->all())
        ]);

        return Excel::download(new Prospects, 'Prospects.xlsx');
    }
}
